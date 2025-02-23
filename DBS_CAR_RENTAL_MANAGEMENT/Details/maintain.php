<?php
include('../php/conn.php');

$car_id = isset($_GET['car_id']) ? $_GET['car_id'] : null; //  `car_id` URL se lo

if (!$car_id) {
    echo "❌ Car ID Missing!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintain Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center">Car Maintenance</h2>

        <!--  ADD MAINTENANCE FORM -->
        <form action="../php/Maintanace.php" method="post">
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="car_id" value="<?php echo $car_id; ?>"> <!--  Car ID from URL -->

            <div class="mb-3">
                <label class="form-label">Description:</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Cost:</label>
                <input type="number" name="cost" class="form-control" min="1" required>
            </div>
            <button type="submit" name="maintenanceDetails" class="btn btn-success">Add Maintenance</button>
        </form>

        <hr>

        <!--  UPDATE MAINTENANCE FORM -->
        <form action="../php/Maintanace.php" method="post">
            <input type="hidden" name="action" value="update">

            <div class="mb-3">
                <label class="form-label">Maintenance ID:</label>
                <input type="number" name="maintenance_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">New Description:</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">New Cost:</label>
                <input type="number" name="cost" class="form-control" min="1" required>
            </div>
            <button type="submit" class="btn btn-warning">Update Maintenance</button>
        </form>

        <hr>

        <!--  DELETE MAINTENANCE FORM -->
        <form action="../php/Maintanace.php" method="post">
            <input type="hidden" name="action" value="delete">
            <div class="mb-3">
                <label class="form-label">Maintenance ID:</label>
                <input type="number" name="maintenance_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-danger">Delete Maintenance</button>
        </form>

        <hr>

        <!--  READ MAINTENANCE BUTTON -->
        <form action="../php/Maintanace.php" method="post">
            <input type="hidden" name="action" value="read">
            <button type="submit" class="btn btn-primary">Show All Maintenance Records</button>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>