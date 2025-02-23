<?php
include('../php/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            addMaintenance($conn);
            break;
        case 'read':
            readMaintenance($conn);
            break;
        case 'update':
            updateMaintenance($conn);
            break;
        case 'delete':
            deleteMaintenance($conn);
            break;
    }
}

//  CREATE MAINTENANCE RECORD
function addMaintenance($conn)
{
    if (isset($_POST['maintenanceDetails'])) {
        echo"Hello";
        $car_id = $_POST['car_id'];
        $description = $_POST['description'];
        $cost = $_POST['cost'];
        $date = date('Y-m-d'); // 📅 Auto-generated current date

        if (empty($car_id) || empty($description) || empty($cost)) {
            echo "All fields are required.";
            return;
        }

        if ($cost < 0) {
            echo "Cost always greater than 0.";
            return;
        }

        $sql = "INSERT INTO Maintenance (Date, Description, Cost, CarID) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $date, $description, $cost, $car_id);

        if ($stmt->execute()) {
            echo "Maintenance Record Added!";
        } else {
            echo " Error: " . $conn->error;
        }
    }
}

//  READ MAINTENANCE RECORDS
function readMaintenance($conn)
{
    $sql = "SELECT * FROM Maintenance";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row . "\n";
        }
    } else {
        echo "No Maintenance Records Found!";
    }
}

//  UPDATE MAINTENANCE RECORD
function updateMaintenance($conn)
{
    if (isset($_POST['maintenance_id'])) {
        $maintenance_id = $_POST['maintenance_id'];
        $description = $_POST['description'];
        $cost = $_POST['cost'];

        if (empty($maintenance_id) || empty($description) || empty($cost)) {
            echo "All fields are required.";
            return;
        }

        $sql = "UPDATE Maintenance SET Description=?, Cost=? WHERE Maintenance_ID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdi", $description, $cost, $maintenance_id);

        if ($stmt->execute()) {
            echo " Maintenance Record Updated!";
        } else {
            echo " Error: " . $conn->error;
        }
    }
}

//  DELETE MAINTENANCE RECORD
function deleteMaintenance($conn)
{
    if (isset($_POST['maintenance_id'])) {
        $maintenance_id = $_POST['maintenance_id'];

        $sql = "DELETE FROM Maintenance WHERE Maintenance_ID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $maintenance_id);

        if ($stmt->execute()) {
            echo " Maintenance Record Deleted!";
        } else {
            echo " Error: " . $conn->error;
        }
    }
}
