<?php
include('../php/conn.php');

//  Fetch All Cars
$cars = [];
$sql = "SELECT * FROM car";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cars[$row['CarID']] = $row;
    }
}

//  Handle Update Car Request
$editCar = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['car_id'];
    $model = $_POST['model'];
    $brand = $_POST['brand'];
    $license = $_POST['license'];
    $year = $_POST['year'];
    $color = $_POST['color'];
    $mileage = $_POST['mileage'];
    $availability = $_POST['availability'];
    $price = $_POST['price'];
    $type = $_POST['type'];

    //  Update Query
    $sql = "UPDATE car SET Model=?, Brand=?, LicenseNumber=?, YearOfManufacture=?, Color=?, Mileage=?, AvailabilityStatus=?, price=?, type=? WHERE CarID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssdsssi", $model, $brand, $license, $year, $color, $mileage, $availability, $price, $type, $id);
    $stmt->execute();

    //  Refresh Page to Show Updated Values
    header("Location: manage_cars.php");
    exit();
}

//  If edit button is clicked, fetch car details
if (isset($_GET['edit'])) {
    $carID = $_GET['edit'];
    $editCar = $cars[$carID] ?? null;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Cars - Car Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center">🚗 Manage Cars</h2>

        <!--  Show All Cars -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Car ID</th>
                    <th>Model</th>
                    <th>Brand</th>
                    <th>License No</th>
                    <th>Year</th>
                    <th>Color</th>
                    <th>Mileage</th>
                    <th>Availability</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cars)) : ?>
                    <?php foreach ($cars as $id => $car) : ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td><?= $car['Model'] ?></td>
                            <td><?= $car['Brand'] ?></td>
                            <td><?= $car['LicenseNumber'] ?></td>
                            <td><?= $car['YearOfManufacture'] ?></td>
                            <td><?= $car['Color'] ?></td>
                            <td><?= $car['Mileage'] ?> km</td>
                            <td><?= $car['AvailabilityStatus'] ?></td>
                            <td><?= $car['price'] ?></td>
                            <td><?= $car['type'] ?></td>
                            <td>
                                <!--  Update Button -->
                                <a href="?edit=<?= $id ?>" class="btn btn-warning btn-sm">Edit</a>

                                <!--  DELETE FORM -->
                                <form action="../car details/car.php" method="post" class="d-inline">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="11" class="text-center">❌ No Cars Found!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!--  Edit Car Form (Appears Below Table) -->
        <?php if ($editCar) : ?>
            <h4 class="mt-5">✏️ Edit Car</h4>
            <form action="../car details/car.php" method="post">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= $editCar['CarID'] ?>">

                <div class="mb-3">
                    <label class="form-label">Model:</label>
                    <input type="text" name="model" class="form-control" value="<?= $editCar['Model'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Brand:</label>
                    <input type="text" name="brand" class="form-control" value="<?= $editCar['Brand'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">License No:</label>
                    <input type="text" name="license" class="form-control" value="<?= $editCar['LicenseNumber'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Year:</label>
                    <input type="number" name="year" class="form-control" value="<?= $editCar['YearOfManufacture'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Color:</label>
                    <input type="text" name="color" class="form-control" value="<?= $editCar['Color'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mileage:</label>
                    <input type="text" name="mileage" class="form-control" value="<?= $editCar['Mileage'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Availability:</label>
                    <input type="text" name="availability" class="form-control" value="<?= $editCar['AvailabilityStatus'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price:</label>
                    <input type="text" name="price" class="form-control" value="<?= $editCar['price'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Type:</label>
                    <input type="text" name="type" class="form-control" value="<?= $editCar['type'] ?>" required>
                </div>

                <button type="submit" name="" class="btn btn-success">Update Car</button>
            </form>
        <?php endif; ?>

        <!--  Add New Car Form -->
        <h4 class="mt-5">➕ Add New Car</h4>
        <form method="POST" enctype="multipart/form-data" action="./car.php">
            <!-- Car Model -->
            <div class="mb-3">
                <label for="model" class="form-label">Car Model</label>
                <input type="text" class="form-control" id="model" name="model" required>
            </div>
            <div class="mb-3">
                <input type="hidden" class="form-control" name="action" value="create">
            </div>

            <!-- Brand -->
            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" class="form-control" id="brand" name="brand" required>
            </div>

            <!-- License Number -->
            <div class="mb-3">
                <label for="license" class="form-label">License Number</label>
                <input type="text" class="form-control" id="license" name="license" required>
            </div>
            <!-- License Number -->
            <div class="mb-3">
                <label for="Price" class="form-label">Price</label>
                <input type="text" class="form-control" id="license" name="price" required>
            </div>
            <!-- License Number -->
            <div class="mb-3">
                <label for="Price" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <!-- Year of Manufacture -->
            <div class="mb-3">
                <label for="year" class="form-label">Year of Manufacture</label>
                <input type="number" class="form-control" id="year" name="year" min="1900" max="2025" required>
            </div>


            <!-- Color -->
            <div class="mb-3">
                <label for="color" class="form-label">Car Color</label>
                <input type="text" class="form-control" id="color" name="color" required>
            </div>

            <!-- Mileage -->
            <div class="mb-3">
                <label for="mileage" class="form-label">Mileage (in km)</label>
                <input type="number" class="form-control" id="mileage" name="mileage" step="0.01" required>
            </div>

            <!-- Availability Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Availability Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="Available">Available</option>
                    <option value="Booked">Rented</option>
                    <option value="Maintenance">Maintenance</option>
                </select>
            </div>
            <!-- Availability Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Vehicles Type</label>
                <select class="form-select" id="status" name="type">
                    <option value="SUV">SUV</option>
                    <option value="Sports">Sports</option>
                    <option value="Sedan">Sedan</option>
                    <option value="Bike">Bike</option>
                    <option value="Bus">Bus</option>
                    <option value="All Vehicles">All Vehicles</option>
                </select>
            </div>

            <!-- Upload Car Image -->
            <div class="mb-3">
                <label for="carImage" class="form-label">Upload Car Image</label>
                <input type="file" class="form-control" id="carImage" name="carImage" accept="image/*" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100" name="carDetails">Submit</button>

        </form>
    </div>

</body>

</html>