<?php
// Database Credentials
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "car_rental_managment";

// Create Connection
$conn = new mysqli($servername, $username, $password);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Database If Not Exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {

} else {
    die("Error Creating Database: " . $conn->error);
}

// Now Select Database
$conn->select_db($dbname);

// Set Character Encoding
$conn->set_charset("utf8");
// Database connection include


   
?>
