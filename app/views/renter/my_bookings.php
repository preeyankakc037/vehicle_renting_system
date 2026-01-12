<h2>My Bookings</h2>
<?php while($b=mysqli_fetch_assoc($bookings)): ?>
    <p><?= $b['start_date'] ?> to <?= $b['end_date'] ?> (<?= $b['status'] ?>)</p>
<?php endwhile; ?>
