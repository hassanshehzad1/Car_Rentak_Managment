<?php
session_start();
include('../php/conn.php');

//  Check if booking_id, customer_id, and car_id are set
if (!isset($_GET['booking_id']) || !isset($_GET['customer_id']) || !isset($_GET['car_id'])) {
  echo "❌ Invalid Request! Booking, Customer, or Car ID missing.";
  exit();
}

$bookingID = $_GET['booking_id'];
$customerID = $_GET['customer_id'];
$carID = $_GET['car_id'];

//  Fetch Total Amount from RentalBooking
$stmt = $conn->prepare("SELECT Total FROM RentalBooking WHERE BookingID = ?");
$stmt->bind_param("i", $bookingID);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking) {
  echo "❌ Booking not found!";
  exit();
}
$totalAmount = $booking['Total'];

//  Fetch Customer Details (Email)
$stmt = $conn->prepare("SELECT Email FROM Customer WHERE CustomerID = ?");
$stmt->bind_param("i", $customerID);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$customer) {
  echo "❌ Customer not found!";
  exit();
}
$customerEmail = $customer['Email'];

//  Fetch Customer Phone from Different Table (Assume Table Name: CustomerContact)
$stmt = $conn->prepare("SELECT PhoneNumber FROM customerphone WHERE CustomerID = ?");
$stmt->bind_param("i", $customerID);
$stmt->execute();
$contact = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$contact) {
  echo "❌ Phone number not found!";
  exit();
}
$customerPhone = $contact['PhoneNumber'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="shortcut icon" href="../car rental images/Home/logo.png" type="image/x-icon" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment - Car Rental</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="payment.css" />
</head>

<body>

  <header class="custom_container">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <div class="logo">
          <img src="../car rental images/Home/logo.png" alt="Logo" />
        </div>
        <div class="center">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link active" href="../Home/index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="../Vehicals/Vehicals.php">Vehicles</a></li>
            <li class="nav-item"><a class="nav-link" href="../Details/details.php">Details</a></li>
            <li class="nav-item"><a class="nav-link" href="../about/about.php">About us</a></li>
            <li class="nav-item"><a class="nav-link" href="../Contact Us/Contact.php">Contact us</a></li>
          </ul>
        </div>
        <div><a class="reg" href="../Registered/Registered.php"><i class="fa-solid fa-user">+</i></a></div>
      </div>
    </nav>
  </header>

  <!-- Payment Section -->
  <div class="custom_container d-lg-flex">
    <div class="box-2">
      <div class="box-inner-2">
        <div>
          <p class="fw-bold">Payment Details</p>
          <p class="dis mb-3">Complete your purchase by providing your payment details</p>
        </div>
        <form action="paymentData.php" method="post">
          <!--  Hidden Fields (Pass Booking, Customer & Car Info) -->
          <input type="hidden" name="action" value="create"> <!--  Define action -->
          <input type="hidden" name="booking_id" value="<?php echo $bookingID; ?>">
          <input type="hidden" name="customer_id" value="<?php echo $customerID; ?>">
          <input type="hidden" name="car_id" value="<?php echo $carID; ?>">
          <input type="hidden" name="amount" value="<?php echo $totalAmount; ?>">

          <!--  Customer Email -->
          <div class="mb-3">
            <p class="dis fw-bold mb-2">Email Address</p>
            <input class="form-control" type="email" value="<?php echo $customerEmail; ?>" readonly />
          </div>

          <!--  Customer Phone -->
          <div class="mb-3">
            <p class="dis fw-bold mb-2">Phone Number</p>
            <input class="form-control" type="text" value="<?php echo $customerPhone; ?>" readonly />
          </div>

          <!--  Show Total Amount -->
          <div class="d-flex flex-column dis">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <p class="fw-bold">Total Amount</p>
              <p class="fw-bold"><span class="fas fa-dollar-sign"></span><?php echo $totalAmount; ?></p>
            </div>
          </div>

          <!--  Payment Method Selection -->
          <label for="payment_method">Payment Method:</label>
          <select name="payment_method" class="form-control" required>
            <option value="Credit Card">Credit Card</option>
            <option value="Debit Card">Debit Card</option>
            <option value="Cash">Cash</option>
            <option value="Online Banking">Online Banking</option>
          </select>

          <!--  Submit Button -->
          <button type="submit" name="paymentDetails" class="btn btn-primary mt-2">
            Pay <span class="fas fa-dollar-sign px-1"></span><?php echo $totalAmount; ?>
          </button>
        </form>
      </div>
    </div>
  </div>


  <!-- Footer -->
  <footer class="custom_container footer_section mt-5">
    <div class="copyright">
      <p class="text-center fs-5 mt-3">@Copyright Car Rental 2024 - Design by Wala loog</p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>