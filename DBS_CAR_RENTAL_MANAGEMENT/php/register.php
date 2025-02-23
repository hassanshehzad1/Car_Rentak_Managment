<?php
//  Show All Errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

//  Include Database Connection
include('../php/conn.php');
session_start();

//  Handle Different Actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            addCustomer($conn);
            break;
        case 'read':
            readCustomers($conn);
            break;
        case 'update':
            updateCustomer($conn);
            break;
        case 'delete':
            deleteCustomer($conn);
            break;
        default:
            echo "Invalid action";
            break;
    }
} else {
    echo "Invalid request";
    exit();
}

function addCustomer($conn)
{
    if (isset($_POST['customerDetails']) && $_POST['customerDetails'] == "1") {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $age = trim($_POST['age']);
        $password = trim($_POST['password']); 
        $city = trim($_POST['city']);
        $country = trim($_POST['country']);

        // Get phone numbers separately
        $phone1 = isset($_POST['phone1']) ? trim($_POST['phone1']) : "";
        $phone2 = isset($_POST['phone2']) ? trim($_POST['phone2']) : "";

        // Required Fields Validation
        if (empty($name) || empty($email) || empty($password) || empty($phone1) || empty($age) || empty($city) || empty($country)) {
            echo " Required fields are missing!";
            return;
        }

        if ($age < 18) {
            echo " Error: Customer age must be 18 or older!";
            return;
        }

        try {
            $conn->begin_transaction();

            // Check if Email Already Exists
            $checkEmail = $conn->prepare("SELECT * FROM Customer WHERE Email = ?");
            $checkEmail->bind_param("s", $email);
            $checkEmail->execute();
            $result = $checkEmail->get_result();
            if ($result->num_rows > 0) {
                throw new Exception(" Error: Email already registered!");
            }

            // Insert Customer Details
            $sqlInsertCustomer = "INSERT INTO Customer (Name, Age, Email, password) VALUES (?, ?, ?, ?)";
            $stmtCustomer = $conn->prepare($sqlInsertCustomer);
            $stmtCustomer->bind_param("siss", $name, $age, $email, $password);

            if (!$stmtCustomer->execute()) {
                throw new Exception(" Error inserting customer: " . $stmtCustomer->error);
            }
            $customerID = $stmtCustomer->insert_id;
            $stmtCustomer->close();

            // Insert Customer Address
            $sqlInsertAddress = "INSERT INTO CustomerAddress (CustomerID, City, Country) VALUES (?, ?, ?)";
            $stmtAddress = $conn->prepare($sqlInsertAddress);
            $stmtAddress->bind_param("iss", $customerID, $city, $country);
            $stmtAddress->execute();
            $stmtAddress->close();

            // Insert Customer Phone Numbers
            $sqlInsertPhone = "INSERT INTO CustomerPhone (CustomerID, PhoneNumber, PhoneNumber2) VALUES (?, ?, ?)";
            $stmtPhone = $conn->prepare($sqlInsertPhone);
            $stmtPhone->bind_param("iss", $customerID, $phone1, $phone2);

            if (!$stmtPhone->execute()) {
                throw new Exception(" Error inserting phone numbers: " . $stmtPhone->error);
            }
            $stmtPhone->close();

            $conn->commit();

            // Redirect to Contact Page
            header("Location: ../Contact Us/Contact Us/loginCus.php");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            echo " Error: " . $e->getMessage();
        }
    }
}



//  FUNCTION: Read All Customers
function readCustomers($conn)
{
    $sql = "SELECT c.CustomerID, c.Name, c.Age, ca.City, ca.Country, 
                   cp.PhoneNumber, cp.PhoneNumber2
            FROM Customer c
            LEFT JOIN CustomerAddress ca ON c.CustomerID = ca.CustomerID
            LEFT JOIN CustomerPhone cp ON c.CustomerID = cp.CustomerID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo json_encode($row) . "\n";
        }
    } else {
        echo "No customers found.";
    }
}

//  FUNCTION: Update a Customer
function updateCustomer($conn)
{
    if (isset($_POST['customer_id'])) {
        $customerID = trim($_POST['customer_id']);
        $name = trim($_POST['name']);
        $age = trim($_POST['age']);
        $city = trim($_POST['city']);
        $country = trim($_POST['country']);
        $phoneNumbers = isset($_POST['phone_numbers']) ? $_POST['phone_numbers'] : [];

        if (empty($customerID) || empty($name) || empty($age) || empty($city) || empty($country) || empty($phoneNumbers[0])) {
            echo " Required fields are missing!";
            return;
        }

        if ($age < 18) {
            echo " Error: Customer age must be 18 or older!";
            return;
        }

        try {
            $conn->begin_transaction();

            //  Update Customer Table
            $sqlUpdateCustomer = "UPDATE Customer SET Name = ?, Age = ? WHERE CustomerID = ?";
            $stmtCustomer = $conn->prepare($sqlUpdateCustomer);
            $stmtCustomer->bind_param("sii", $name, $age, $customerID);
            $stmtCustomer->execute();
            $stmtCustomer->close();

            //  Update Customer Address
            $sqlUpdateAddress = "UPDATE CustomerAddress SET City = ?, Country = ? WHERE CustomerID = ?";
            $stmtAddress = $conn->prepare($sqlUpdateAddress);
            $stmtAddress->bind_param("ssi", $city, $country, $customerID);
            $stmtAddress->execute();
            $stmtAddress->close();

            //  Update Phone Numbers
            $sqlUpdatePhone = "UPDATE CustomerPhone SET PhoneNumber = ?, PhoneNumber2 = ? WHERE CustomerID = ?";
            $stmtPhone = $conn->prepare($sqlUpdatePhone);
            $stmtPhone->bind_param("ssi", $phoneNumbers[0], $phoneNumbers[1], $customerID);
            $stmtPhone->execute();
            $stmtPhone->close();

            $conn->commit();
            echo " Customer updated successfully!";
        } catch (Exception $e) {
            $conn->rollback();
            echo " Error: " . $e->getMessage();
        }
    }
}

//  FUNCTION: Delete a Customer
function deleteCustomer($conn)
{
    if (isset($_POST['customer_id'])) {
        $customerID = trim($_POST['customer_id']);

        if (empty($customerID)) {
            echo " Customer ID is required!";
            return;
        }

        try {
            $conn->begin_transaction();

            //  Delete Customer
            $sqlDeleteCustomer = "DELETE FROM Customer WHERE CustomerID = ?";
            $stmtCustomer = $conn->prepare($sqlDeleteCustomer);
            $stmtCustomer->bind_param("i", $customerID);
            $stmtCustomer->execute();
            $stmtCustomer->close();

            $conn->commit();
            echo " Customer deleted successfully!";
        } catch (Exception $e) {
            $conn->rollback();
            echo " Error: " . $e->getMessage();
        }
    }
}
?>
