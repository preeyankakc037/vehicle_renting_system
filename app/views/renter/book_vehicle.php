<form method="post" action="../../controllers/BookingController.php">
<input type="hidden" name="vehicle_id" value="<?= $_GET['vid'] ?>">
<input type="date" name="pickup">
<input type="date" name="drop">
<input type="number" name="days">
<input type="number" name="price">
<button name="book">Confirm</button>
</form>
