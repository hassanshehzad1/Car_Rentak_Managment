<?php
// Include database connection
include('../php/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            addCarDetails($conn);
            break;
        case 'read':
            readCarDetails($conn);
            break;
        case 'delete':
            deleteCarDetails($conn);
            break;
        case 'update':
            updateCarDetails($conn);
            break;
        default:
            echo "Invalid Action!";
            break;
    }
} else {
    echo "Invalid Request";
    exit();
}

//  Function to Add Car
function addCarDetails($conn)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['carDetails'])) {
        $model = trim($_POST['model'] ?? "");
        $brand = trim($_POST['brand'] ?? "");
        $license = trim($_POST['license'] ?? "");
        $year = trim($_POST['year'] ?? "");
        $color = trim($_POST['color'] ?? "");
        $price = trim($_POST['price'] ?? "");
        $mileage = trim($_POST['mileage'] ?? "");
        $name = trim($_POST['name'] ?? "");
        $availabilityStatus = trim($_POST['availabilityStatus'] ?? "");
        $carType = trim($_POST['type'] ?? "");

        // Check for required fields
        if (empty($model) || empty($license) || empty($year) || empty($color) || empty($price) || $price < 0 || empty($mileage) || $mileage < 0 || empty($name)) {
            echo "⚠️ Required fields are missing!";
            return;
        }

        //  Check if Car Already Exists
        $sqlCheck = "SELECT * FROM car WHERE LicenseNumber = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $license);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();

        if ($result->num_rows > 0) {
            echo "❌ Car already exists with this License Number!";
            return;
        }

        //  Upload Image
        $targetDir = "Images/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imageName = preg_replace("/\s+/", "_", basename($_FILES["carImage"]["name"]));
        $targetFilePath = $targetDir . $imageName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($fileType, $allowedTypes)) {
            echo "❌ Invalid file type!";
            return;
        }

        if (!move_uploaded_file($_FILES["carImage"]["tmp_name"], $targetFilePath)) {
            echo "❌ Image upload failed.";
            return;
        }

        //  Insert Data
        $sqlInsert = "INSERT INTO car (Model, Brand, LicenseNumber, YearOfManufacture, Color, Mileage, AvailabilityStatus, Name, Price, Type, ImageUrl) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("sssssdssdss", $model, $brand, $license, $year, $color, $mileage, $availabilityStatus, $name, $price, $carType, $targetFilePath);

        if ($stmtInsert->execute()) {
            echo " Car added successfully!";
            
            header("Location: ../car details/carDetails.php");

        } else {
            echo "❌ Error: " . $conn->error;
        }
    }
}

//  Function to Read Car Details
function readCarDetails($conn)
{
    $result = $conn->query("SELECT * FROM car");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo json_encode($row);
        }
    } else {
        echo "❌ No Cars Found!";
    }
}

//  Function to Delete Car

function deleteCarDetails($conn)
{
    // Start a transaction to ensure data integrity

    $carID = $_POST['id']; // Car ID jo form se aa rahi hai
    $conn->begin_transaction();


    try {
        // Pehle related bookings delete karein
        $deleteBookings = $conn->prepare("DELETE FROM rentalbooking WHERE CarID = ?");
        $deleteBookings->bind_param("i", $carID);
        $deleteBookings->execute();

        // Ab car delete karein
        $deleteCar = $conn->prepare("DELETE FROM car WHERE CarID = ?");
        $deleteCar->bind_param("i", $carID);
        $deleteCar->execute();

        // Transaction commit karein
        $conn->commit();

        echo "<script>alert('Car deleted successfully!'); window.location.href='../car details/car_list.php';</script>";
    } catch (Exception $e) {
        // Error aane par rollback kar dein
        $conn->rollback();
        echo "<script>alert('Error: Unable to delete car.'); window.history.back();</script>";
    }
}

if (isset($_GET['car_id'])) {
    $carID = $_GET['car_id'];
    deleteCarDetails($conn, $carID);
}



//  Function to Update Car
function updateCarDetails($conn)
{
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo "❌ Car ID is required!";
        return;
    }

    $id = trim($_POST['id']);
    $model = trim($_POST['model'] ?? "");
    $brand = trim($_POST['brand'] ?? "");
    $license = trim($_POST['license'] ?? "");
    $year = trim($_POST['year'] ?? "");
    $color = trim($_POST['color'] ?? "");
    $price = trim($_POST['price'] ?? "");
    $mileage = trim($_POST['mileage'] ?? "");
    $availabilityStatus = trim($_POST['availability'] ?? ""); // FIXED: Matching form field
    $carType = trim($_POST['type'] ?? "");

    //  Fix: Removed 'name' field validation since it's not in the form
    if (empty($model) || empty($brand) || empty($license) || empty($year) || empty($color) || empty($price) || $price < 0 || empty($mileage) || $mileage < 0) {
        echo "❌ Required fields are missing!";
        return;
    }

    //  Fix: Corrected field names in SQL query
    $sqlUpdate = "UPDATE car SET Model=?, Brand=?, LicenseNumber=?, YearOfManufacture=?, Color=?, Mileage=?, AvailabilityStatus=?, Price=?, Type=? WHERE CarID=?";
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param("sssssdsssi", $model, $brand, $license, $year, $color, $mileage, $availabilityStatus, $price, $carType, $id);

    if ($stmt->execute()) {
        echo " Car updated successfully!";
    } else {
        echo "❌ Error updating car: " . $conn->error;
    }
}
