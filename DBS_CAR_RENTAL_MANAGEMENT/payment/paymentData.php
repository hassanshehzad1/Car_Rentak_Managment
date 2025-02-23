<?php
include('../php/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            addPayment($conn);
            break;
        case 'read':
            readPayment($conn);
            break;
        case 'update':
            updatePayment($conn);
            break;
        case 'delete':
            deletePayment($conn);
            break;
        default:
            echo " Invalid action!";
            break;
    }
} else {
    echo " Invalid Request!";
    exit();
}

//  **CREATE PAYMENT**
function addPayment($conn)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['paymentDetails'])) {
        echo "Peyment ";

        $method = trim($_POST['payment_method']);
        $bookingID = trim($_POST['booking_id']);
        $carID = trim($_POST['car_id']);
        $amount = trim($_POST['amount']);

        $transactionID = uniqid("TRX_", true);
        $sqlMax = "SELECT MAX(TransactionID) AS maxID FROM Payment";
        $resultMax = $conn->query($sqlMax);
        $rowMax = $resultMax->fetch_assoc();
        $transactionID = $rowMax['maxID'] + 1; //  Next Incremented ID
        $currentDate = date('Y-m-d H:i:s'); //  Current Timestamp

        //  **Validation**
        if (empty($method) || empty($bookingID) || empty($carID) || empty($amount)) {
            die(" Error: All fields are required.");
        }

        //  **Check if Booking Exists**
        $sqlCheck = "SELECT CustomerID FROM RentalBooking WHERE BookingID = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("i", $bookingID);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        if ($resultCheck->num_rows === 0) {
            die(" Error: Invalid Booking ID.");
        }
        $row = $resultCheck->fetch_assoc();
        $customerID = $row['CustomerID']; //  Fetch Customer ID
        $stmtCheck->close();

        //  **Insert Payment into Database**
        $sql = "INSERT INTO Payment (PaymentMethod, AmountPaid, Date, TransactionID, BookingID) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsii", $method, $amount, $currentDate, $transactionID, $bookingID);

        if ($stmt->execute()) {
            echo " Payment Successful! Redirecting...";
            header("Location: ../Feedback/feedback.php?customer_id=$customerID&booking_id=$bookingID");
            
            exit();
        } else {
            die(" Error: Payment failed. " . $conn->error);
        }
    }
}


//  **READ PAYMENT**
function readPayment($conn)
{
    $query = "SELECT p.*, r.RentalStartDate, r.RentalEndDate, r.Total, c.Name 
              FROM Payment p 
              JOIN RentalBooking r ON p.BookingID = r.BookingID
              JOIN Customer c ON c.CustomerID = p.CustomerID";

    $result = $conn->query($query);
    $payments = [];

    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }

    echo json_encode($payments);
}

//  **UPDATE PAYMENT**
function updatePayment($conn)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['paymentDetails'])) {
        $paymentID = trim($_POST['payment_id']);
        $method = trim($_POST['payment_method']);
        $amount = trim($_POST['amount']);
        $transactionID = trim($_POST['transaction_id']);
        $bookingID = trim($_POST['booking_id']);
        $currentDate = date('Y-m-d H:i:s'); //  Generate current timestamp

        if (empty($paymentID) || empty($method) || empty($amount) || empty($transactionID) || empty($bookingID)) {
            echo " Error: All fields are required.";
            return;
        }

        //  **Check if Booking ID exists**
        $bookingCheck = "SELECT * FROM RentalBooking WHERE BookingID = ?";
        $stmtCheck = $conn->prepare($bookingCheck);
        $stmtCheck->bind_param("i", $bookingID);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();

        if ($result->num_rows == 0) {
            echo " Error! Booking does not exist.";
            return;
        }

        //  **Check if transaction ID is unique (excluding current payment ID)**
        $transactionCheck = "SELECT * FROM Payment WHERE TransactionID = ? AND PaymentID != ?";
        $stmtTransaction = $conn->prepare($transactionCheck);
        $stmtTransaction->bind_param("si", $transactionID, $paymentID);
        $stmtTransaction->execute();
        $resultTransaction = $stmtTransaction->get_result();

        if ($resultTransaction->num_rows > 0) {
            echo " Error: Transaction already exists.";
            return;
        }

        //  **Update Payment**
        $sql = "UPDATE Payment SET PaymentMethod = ?, AmountPaid = ?, Date = ?, TransactionID = ?, BookingID = ? WHERE PaymentID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsiii", $method, $amount, $currentDate, $transactionID, $bookingID, $paymentID);

        if ($stmt->execute()) {
            echo " Payment Updated Successfully!";
        } else {
            echo " Error! " . $conn->error;
        }
    }
}

//  **DELETE PAYMENT**
function deletePayment($conn)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_id'])) {
        $paymentID = trim($_POST['payment_id']);

        if (empty($paymentID)) {
            echo " Error: Payment ID Required.";
            return;
        }

        $sql = "DELETE FROM Payment WHERE PaymentID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $paymentID);

        if ($stmt->execute()) {
            echo " Payment Deleted Successfully!";
        } else {
            echo " Error in deleting payment: " . $conn->error;
        }
    }
}
