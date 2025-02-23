<?php
include('../php/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            addBooking($conn);
            break;
        case 'read':
            readBooking($conn);
            break;
        case 'update':
            updateBooking($conn);
            break;
        case 'delete':
            deleteBooking($conn);
            break;
    }
} else {
    echo "Error! In booking ";
    exit();
}

//  CREATE RENTAL BOOKING
function addBooking($conn)
{
    if (isset($_POST['bookingDetails'])) {
        echo "Hello";

        // Corrected POST keys
        $startDate = trim($_POST['rental_start_date']); 
        $endDate = trim($_POST['rental_end_date']); 
        $customerID = trim($_POST['customer_id']); 
        $carID = trim($_POST['car_id']); 
        $employeeID = 1; // Default Employee ID (Update this if needed)

        // Validation
        if (empty($startDate) || empty($endDate) || empty($customerID) || empty($carID)) {
            echo " All fields are required";
            return;
        }

        // Fetch Car Price
        $stmt = $conn->prepare("SELECT price FROM Car WHERE CarID = ? AND AvailabilityStatus  = 'available'");
        $stmt->bind_param("i", $carID);
        $stmt->execute();
        $car = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$car) {
            echo " Car not available or not found!";
            return;
        }

        $pricePerDay = $car['price'];

        // Calculate Total Price
        $start_date = strtotime($startDate);
        $end_date = strtotime($endDate);
        $diff_days = ceil(abs($end_date - $start_date) / (60 * 60 * 24)); 
        $total = $diff_days * $pricePerDay;

        if ($diff_days <= 0) {
            echo " Invalid date selection!";
            return;
        }

        // Insert Booking into Database
        $sql = "INSERT INTO RentalBooking (RentalStartDate, RentalEndDate, Total, EmployeeID, CustomerID, CarID) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsii", $startDate, $endDate, $total, $employeeID, $customerID, $carID);

        if ($stmt->execute()) {
            // Get Last Inserted Booking ID
            $bookingID = $stmt->insert_id;
            echo " Car booked Successfully!";

            // Update Car Availability Status to 'rented'
            $updateCarStatus = $conn->prepare("UPDATE Car SET  AvailabilityStatus = 'BookedE CarID = ?");
            $updateCarStatus->bind_param("i", $carID);
            $updateCarStatus->execute();
            $updateCarStatus->close();

            // Redirect to Payment Page
            header("Location: ../payment/payment.php?booking_id=$bookingID&car_id=$carID&customer_id=$customerID");

            exit();
        } else {
            echo " Error! " . $conn->error;
        }
    }
}


//  READ RENTAL BOOKINGS
function readBooking($conn)
{
    $sql = "SELECT * FROM RentalBooking";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        echo json_encode($bookings);
    } else {
        echo " Error in reading bookings";
    }
}

//  UPDATE RENTAL BOOKING
function updateBooking($conn)
{
    if (isset($_POST['bookingDetails'])) {
        $bookingID = trim($_POST['booking_id']);
        $endDate = trim($_POST['rental_end_date']);

        if (empty($bookingID) || empty($endDate)) {
            echo " All fields are required";
            return;
        }

        //  Fetch Car Price & Calculate Total Again
        $stmt = $conn->prepare("SELECT CarID, RentalStartDate FROM RentalBooking WHERE BookingID = ?");
        $stmt->bind_param("i", $bookingID);
        $stmt->execute();
        $booking = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$booking) {
            echo " Booking not found!";
            return;
        }

        $carID = $booking['CarID'];
        $startDate = strtotime($booking['RentalStartDate']);

        $stmt = $conn->prepare("SELECT price FROM Car WHERE CarID = ?");
        $stmt->bind_param("i", $carID);
        $stmt->execute();
        $car = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$car) {
            echo " Car not found!";
            return;
        }

        $pricePerDay = $car['price'];

        //  Recalculate Total Price
        $endDate = strtotime($endDate);
        $diff_days = ceil(abs($endDate - $startDate) / (60 * 60 * 24)); 
        $total = $diff_days * $pricePerDay;

        $sql = "UPDATE RentalBooking SET RentalEndDate = ?, Total = ? WHERE BookingID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdi", $endDate, $total, $bookingID);

        if ($stmt->execute()) {
            echo " Booking updated Successfully";
        } else {
            echo " Error in updating Booking: " . $conn->error;
        }
    }
}

//  DELETE RENTAL BOOKING
function deleteBooking($conn)
{
    if (isset($_POST['booking_id'])) {
        $bookingID = trim($_POST['booking_id']);

        if (empty($bookingID)) {
            echo " Booking ID Required";
            return;
        }

        $sql = "DELETE FROM RentalBooking WHERE BookingID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bookingID);

        if ($stmt->execute()) {
            echo " Booking deleted Successfully";
        } else {
            echo " Error in deleting booking: " . $conn->error;
        }
    }
}
?>
