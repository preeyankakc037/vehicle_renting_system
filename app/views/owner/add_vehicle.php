<?php include __DIR__ . '/../layouts/header.php'; ?>

<h2>Add Vehicle</h2>

<form method="POST"
      action="index.php?action=addVehicle"
      enctype="multipart/form-data">

    <input type="text" name="vehicle_name" placeholder="Vehicle Name" required>

    <select name="category" required>
        <option value="">Select Category</option>
        <option value="car">Car</option>
        <option value="bike">Bike</option>
        <option value="scooty">Scooty</option>
    </select>

    <input type="text" name="vehicle_type" placeholder="Model / Brand" required>

    <input type="number" name="price_per_day" placeholder="Price Per Day" required>

    <input type="text" name="location" placeholder="Pickup Location" required>

    <textarea name="description" placeholder="Vehicle Description"></textarea>

    <label>Vehicle Image</label>
    <input type="file" name="image" accept="image/*" required>

    <button type="submit">Save Vehicle</button>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
