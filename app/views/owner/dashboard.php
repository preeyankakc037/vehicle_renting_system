<?php
$page_title = "My Vehicles - Vehicle Rental System";
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="container my-5">
    <h2>My Vehicles</h2>

    <?php if(!empty($vehicles)): ?>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Price/Day</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($vehicles as $v): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($v['vehicle_name']); ?></td>
                        <td><?php echo htmlspecialchars($v['vehicle_type']); ?></td>
                        <td>$<?php echo htmlspecialchars($v['price_per_day']); ?></td>
                        <td><?php echo ucfirst($v['availability_status']); ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=edit&id=<?php echo $v['vehicle_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=delete&id=<?php echo $v['vehicle_id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have not listed any vehicles yet.</p>
        <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=add" class="btn btn-primary mt-2">List a Vehicle</a>
    <?php endif; ?>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
