<?php
session_start();
include('conn.php');
$email = strtolower(trim($_POST['email']));
$password = trim($_POST['Password']); 
echo  $email .$password;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['Password']);  // User Input Password

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM Employee WHERE LOWER(Email) = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $storedPassword = preg_replace('/[\x00-\x1F\x7F]/', '', trim($row['Password']));



            // Compare Passwords
            if ($password === $storedPassword) {
                $_SESSION['employee_id'] = $row['EmployeeID'];
                $_SESSION['employee_firstname'] = $row['FirstName'];
                $_SESSION['employee_lastname'] = $row['LastName'];
                $_SESSION['role'] = $row['Role'];

                header("Location: ../Home/index.php");
                exit();
            } else {
                echo "<script>alert('Invalid password!'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('No employee found with this email!'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please enter email and password!'); window.history.back();</script>";
    }
}
?>
