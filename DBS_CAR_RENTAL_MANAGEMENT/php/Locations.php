<?php
include('../php/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            addLocation($conn);
            break;
        case 'read':
            readLocations($conn);
            break;
        case 'update':
            updateLocation($conn);
            break;
        case 'delete':
            deleteLocation($conn);
            break;
    }
}

//  CREATE LOCATION & LOCATION HOURS
function addLocation($conn)
{
    if (isset($_POST['locationDetails'])) {
        $city = trim($_POST['city']);
        $street = trim($_POST['street']);
        $province = trim($_POST['province']);
        $contact_number = trim($_POST['contact_number']);

        if (empty($city) || empty($street) || empty($province) || empty($contact_number)) {
            die(" All fields are required.");
        }

        //  Insert Location Data
        $sql = "INSERT INTO Locations (City, Street, Province, ContactNumber) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $city, $street, $province, $contact_number);

        if ($stmt->execute()) {
            $locationID = $stmt->insert_id;

            //  Insert Default Location Hours (Monday - Sunday, 09:00 AM - 06:00 PM)
            $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $openingTime = '09:00:00';
            $closingTime = '18:00:00';

            foreach ($daysOfWeek as $day) {
                $sqlHours = "INSERT INTO LocationHours (LocationID, DayOfWeek, OpeningTime, ClosingTime) VALUES (?, ?, ?, ?)";
                $stmtHours = $conn->prepare($sqlHours);
                $stmtHours->bind_param("isss", $locationID, $day, $openingTime, $closingTime);
                $stmtHours->execute();
            }

            echo " Location Added Successfully!";
        } else {
            die(" Error: " . $conn->error);
        }
    }
}

//  READ LOCATIONS WITH LOCATION HOURS
function readLocations($conn)
{
    $sql = "SELECT L.LocationID, L.City, L.Street, L.Province, L.ContactNumber, 
                   H.DayOfWeek, H.OpeningTime, H.ClosingTime 
            FROM Locations L
            LEFT JOIN LocationHours H ON L.LocationID = H.LocationID
            ORDER BY L.LocationID, FIELD(H.DayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $locations = [];

        while ($row = $result->fetch_assoc()) {
            $locations[$row['LocationID']]['City'] = $row['City'];
            $locations[$row['LocationID']]['Street'] = $row['Street'];
            $locations[$row['LocationID']]['Province'] = $row['Province'];
            $locations[$row['LocationID']]['ContactNumber'] = $row['ContactNumber'];
            $locations[$row['LocationID']]['Hours'][] = [
                'Day' => $row['DayOfWeek'],
                'OpeningTime' => $row['OpeningTime'],
                'ClosingTime' => $row['ClosingTime']
            ];
        }

        echo json_encode($locations, JSON_PRETTY_PRINT);
    } else {
        echo " No Locations Found!";
    }
}

//  UPDATE LOCATION & LOCATION HOURS
function updateLocation($conn)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['location_id'])) {
        $location_id = $_POST['location_id'];

        //  Pehle database se existing location data le lo
        $sqlFetch = "SELECT City, Street, Province, ContactNumber FROM Locations WHERE LocationID=?";
        $stmtFetch = $conn->prepare($sqlFetch);
        $stmtFetch->bind_param("i", $location_id);
        $stmtFetch->execute();
        $result = $stmtFetch->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            //  Ab form se data lo, agar available ho to overwrite karo
            $city = isset($_POST['city']) ? trim($_POST['city']) : $row['City'];
            $street = isset($_POST['street']) ? trim($_POST['street']) : $row['Street'];
            $province = isset($_POST['province']) ? trim($_POST['province']) : $row['Province'];
            $contact_number = isset($_POST['contact_number']) ? trim($_POST['contact_number']) : $row['ContactNumber'];

            //  Update Query
            $sqlUpdate = "UPDATE Locations SET City=?, Street=?, Province=?, ContactNumber=? WHERE LocationID=?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ssssi", $city, $street, $province, $contact_number, $location_id);

            if ($stmtUpdate->execute()) {
                echo " Location Updated Successfully!";
            } else {
                die(" Error: " . $conn->error);
            }
        } else {
            die(" Error: Location not found!");
        }
    } else {
        die(" Error: Invalid Request!");
    }
}



//  DELETE LOCATION (Cascades Location Hours)
function deleteLocation($conn)
{
    if (isset($_POST['location_id'])) {
        $location_id = $_POST['location_id'];

        $sql = "DELETE FROM Locations WHERE LocationID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $location_id);

        if ($stmt->execute()) {
            echo " Location & Hours Deleted Successfully!";
        } else {
            die(" Error: " . $conn->error);
        }
    }
}
