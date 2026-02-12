<?php
/**
 * User Messages
 * Inbox for inquiries submitted via the Contact Us form.
 */
$page_title = "Contact Messages - Admin Panel";
require APP_PATH . '/views/layouts/admin_navbar.php';
?>

<div class="container-fluid px-4 py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Contact Messages</h2>
            <p class="text-muted small mb-0">Review inquiries and feedback from users and renters.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo $_SESSION['success'];
            unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card card-modern border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Sender Info</th>
                            <th class="py-3">Subject</th>
                            <th class="py-3">Message Snippet</th>
                            <th class="py-3">Date</th>
                            <th class="text-end pe-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($messages)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted mb-2"><i class="fas fa-envelope-open fa-3x opacity-25"></i></div>
                                    <p class="text-muted">No messages found.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $modals = ''; ?>
                            <?php foreach ($messages as $msg):
                                $mid = $msg['id'] ?? $msg['message_id'] ?? 0;
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark small">
                                            <?php echo htmlspecialchars($msg['name']); ?>
                                        </div>
                                        <div class="text-muted admin-text-xs">
                                            <?php echo htmlspecialchars($msg['email']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fw-normal">
                                            <?php echo htmlspecialchars($msg['subject']); ?>
                                        </span>
                                    </td>
                                    <td class="small text-muted" style="max-width: 300px; overflow: hidden;">
                                        <div class="text-truncate" title="<?php echo htmlspecialchars($msg['message']); ?>">
                                            <?php echo htmlspecialchars(substr($msg['message'], 0, 50) . (strlen($msg['message']) > 50 ? '...' : '')); ?>
                                        </div>
                                    </td>
                                    <td class="text-muted small">
                                        <?php echo date('M d, Y h:i A', strtotime($msg['created_at'])); ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn-light btn-sm text-primary" data-bs-toggle="modal"
                                                data-bs-target="#msgModal<?php echo $mid; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <form action="<?php echo BASE_URL; ?>/index.php?page=admin&action=deleteMessage"
                                                method="POST" onsubmit="return confirm('Delete this message?');">
                                                <input type="hidden" name="message_id" value="<?php echo $mid; ?>">
                                                <button type="submit" class="btn btn-light btn-sm text-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                $modals .= '
                                <!-- Message Modal -->
                                <div class="modal fade" id="msgModal' . $mid . '" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg rounded-4 text-start">
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="modal-title fw-bold">Message Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body pt-3">
                                                <div class="mb-3">
                                                    <label class="small text-muted text-uppercase fw-bold">From</label>
                                                    <div class="fw-bold">' . htmlspecialchars($msg['name']) . '</div>
                                                    <div class="small text-primary">' . htmlspecialchars($msg['email']) . '</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="small text-muted text-uppercase fw-bold">Subject</label>
                                                    <div class="fw-bold">' . htmlspecialchars($msg['subject']) . '</div>
                                                </div>
                                                <div class="mb-0">
                                                    <label class="small text-muted text-uppercase fw-bold">Message</label>
                                                    <div class="p-3 bg-light rounded-3 small whitespace-pre-wrap mt-1">' . nl2br(htmlspecialchars($msg['message'])) . '</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 pt-0">
                                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                                <a href="mailto:' . $msg['email'] . '" class="btn btn-primary rounded-pill px-4">Reply via Email</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                                ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php echo $modals; ?>
</div>

<?php require APP_PATH . '/views/layouts/admin_footer.php'; ?>