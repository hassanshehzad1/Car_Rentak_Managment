<?php
session_start();
include('../php/conn.php');



//  Store and trim values
$car_id = isset($_GET['car_id']) ? trim($_GET['car_id']) : null;
$customer_id = isset($_GET['customer_id']) ? trim($_GET['customer_id']) : null;

//  Check values
if (!$car_id || !$customer_id) {
    die(" Invalid Request! Car or Customer ID missing.");
}



//  Fetch Car Details
$stmt = $conn->prepare("SELECT * FROM car WHERE CarID = ?");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$car = $stmt->get_result()->fetch_assoc();
$stmt->close();

//  Fetch Customer Details
$stmt = $conn->prepare("SELECT * FROM Customer WHERE CustomerID = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();
$stmt->close();

//  Final Validation
if (!$car || !$customer) {
    die(" Car or Customer not found!");
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Car</title>
    <link rel="shortcut icon" href="/Home/logo.png" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="book.css">
</head>

<body>

    <!-- Navbar -->
    <header class="custom_container">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <div class="logo">
                    <img src="../car rental images/Home/logo.png" alt="Car Rental Logo">
                </div>
                <div class="center">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" href="../Home/index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="../Vehicals/Vehicals.php">Vehicles</a></li>
                        <li class="nav-item"><a class="nav-link" href="../Details/details.php">Details</a></li>
                        <li class="nav-item"><a class="nav-link" href="../about/about.php">About us</a></li>
                        <li class="nav-item"><a class="nav-link" href="../Contact Us/Contact Us/Contact.php">Contact us</a></li>
                    </ul>
                </div>
                <div><i class="fa-solid fa-user">+</i></div>
            </div>
        </nav>
    </header>
    <!-- Navbar End -->

    <div class="container py-4 my-4 mx-auto d-flex flex-column">
        <div class="header">
            <div class="row r1">
                <div class="col-md-9 abc">
                    <h1><?php echo $car['name']; ?></h1>
                </div>
                <div class="col-md-3 text-right pqr">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    <i class="fa fa-star"></i><i class="fa fa-star"></i>
                </div>
                <p class="text-right para">Based on 250 Reviews</p>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="container-body mt-4">
            <div class="row r3">
                <div class="col-md-5 p-0 klo">
                    <form action="../php/booking.php" method="post">
                        <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                        <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
                        <input type="hidden" name="action" value="create"> <!-- Ensure action is set -->

                        <!-- Rental Start Date -->
                        <label for="start_date">Rental Start Date:</label>
                        <input type="date" id="start_date" name="rental_start_date" class="form-control" required>

                        <!-- Rental End Date -->
                        <label for="end_date">Rental End Date:</label>
                        <input type="date" id="end_date" name="rental_end_date" class="form-control" required>

                        <!-- Price per day -->
                        <label>Price per day:</label>
                        <p><?php echo $car['price']; ?> USD</p>

                        <br>

                        <!-- Submit Button -->
                        <button type="submit" name="bookingDetails" class="btn btn-primary">Book a Ride</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Booking Form End -->

        <!-- Car Details Section -->
        <div class="car-details mt-5">
            <h2>Car Details</h2>
            <p><strong>Model:</strong> <?php echo $car['Model']; ?></p>
            <p><strong>Color:</strong> <?php echo $car['Color']; ?></p>
            <p><strong>Type:</strong> <?php echo $car['type']; ?></p>
            <p><strong>Year:</strong> <?php echo $car['YearOfManufacture']; ?></p>
            <p><strong>Mileage:</strong> <?php echo $car['Mileage']; ?> km</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="custom_container footer_section mt-5">
        <div class="row">
            <div class="col">
                <div class="box d-flex flex-column justify-content-evenly gap-10">
                    <h2><i class="fa-solid fa-car"></i> Car Rental</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo doloremque repudiandae deleniti mollitia libero!</p>
                    <div class="icons d-flex justify-content-evenly">
                        <i class="fa-brands fa-facebook fs-1"></i>
                        <i class="fa-brands fa-instagram fs-1"></i>
                        <i class="fa-brands fa-twitter fs-1"></i>
                        <i class="fa-brands fa-youtube fs-1"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <h3>Useful Links</h3>
                <ul>
                    <li>About Us</li>
                    <li>Contact Us</li>
                    <li>Gallery</li>
                    <li>Blog</li>
                    <li>F.A.Q</li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p class="text-center fs-5 mt-3">@Copyright Car Rental 2024</p>
        </div>
    </footer>
    <!-- Footer End -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
