<?php
/**
 * ImageController.php
 * Handles secure image retrieval and serving from the database.
 */
/**
 * Image Asset Controller
 * Responsible for the dynamic serving of vehicle assets, supporting both filesystem paths and database BLOB storage.
 */

class ImageController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function serve()
    {
        // Normalize the execution environment by clearing all active output buffers to prevent image corruption.
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $vehicle_id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $slot = isset($_GET['slot']) ? (int) $_GET['slot'] : 1;

        if (!$vehicle_id) {
            $this->serveDefault();
            return;
        }

        $column = 'image_path';
        if ($slot == 2)
            $column = 'image_path_2';
        if ($slot == 3)
            $column = 'image_path_3';

        $stmt = $this->db->prepare("SELECT $column FROM vehicles WHERE vehicle_id = ?");
        if (!$stmt) {
            $this->serveDefault();
            return;
        }

        $stmt->bind_param("i", $vehicle_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $imageData = $row[$column];
            if (!empty($imageData)) {
                // Heuristic check: Determine if the retrieved data represents a filesystem path or raw binary.
                if (is_string($imageData) && !preg_match('~[^\x20-\x7E\t\r\n]~', $imageData)) {
                    // Construct and validate potential absolute paths within the application's secure directory structure.
                    $imageData = ltrim(str_replace(['\\', '/'], '/', $imageData), '/');
                    $possiblePaths = [
                        PUBLIC_PATH . '/assets/' . $imageData,
                        PUBLIC_PATH . '/' . $imageData,
                        BASE_PATH . '/' . $imageData,
                        $imageData
                    ];

                    foreach ($possiblePaths as $fullPath) {
                        if (file_exists($fullPath) && !is_dir($fullPath)) {
                            $mime = mime_content_type($fullPath) ?: 'image/jpeg';
                            header("Content-Type: $mime");
                            header("Content-Length: " . filesize($fullPath));
                            readfile($fullPath);
                            exit;
                        }
                    }
                }

                // Execute binary processing for Large Object (BLOB) data detected in storage.
                if (is_string($imageData) && strlen($imageData) > 500) {
                    // Utilize the Fileinfo extension to perform dynamic MIME-type detection.
                    $mime = 'image/jpeg';
                    if (class_exists('finfo')) {
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $detectedMime = $finfo->buffer($imageData);
                        if ($detectedMime)
                            $mime = $detectedMime;
                    }

                    header("Content-Type: $mime");
                    header("Content-Length: " . strlen($imageData));
                    header("Cache-Control: public, max-age=86400");

                    echo $imageData;
                    exit;
                }
            }
        }

        $this->serveDefault();
    }

    private function serveDefault()
    {
        $defaultPath = BASE_PATH . '/public/assets/images/default-vehicle.png';
        if (file_exists($defaultPath)) {
            header("Content-Type: image/png");
            header("Content-Length: " . filesize($defaultPath));
            readfile($defaultPath);
        } else {
            header("HTTP/1.0 404 Not Found");
        }
        exit;
    }
}
