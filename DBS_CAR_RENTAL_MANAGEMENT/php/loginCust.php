<?php
session_start();
include('../php/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);

    // Check if redirection is needed
    $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : null;
    $car_id = isset($_POST['car_id']) ? $_POST['car_id'] : null;

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT CustomerID, Name, password FROM Customer WHERE LOWER(Email) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['password'];

            // **Direct comparison (No hashing)*
                // Store session
                $_SESSION['customer_id'] = $row['CustomerID'];
                $_SESSION['name'] = $row['Name'];

                // Redirect after login
                if (!empty($redirect) && $redirect == "payment" && !empty($car_id)) {
                    header("Location: ../payment/payment.php?car_id=$car_id&customer_id=" . $_SESSION['customer_id']);
                    exit();
                } else {
                    // Check if `car_id` exists in session, agar user booking se login aaya hai
                    if (isset($_SESSION['car_id'])) {
                        header("Location: ../BookDetails/book.php?car_id=" . $_SESSION['car_id'] . "&customer_id=" . $_SESSION['customer_id']);
                        exit();
                    } else {
                        header("Location: ../BookDetails/book.php");
                        exit();
                    }
                }
            } else {
                echo "<script>alert('Invalid password!'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('No customer found with this email!'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please enter email and password!'); window.history.back();</script>";
    }

?>
