<?php
// Include database connection
include('../php/conn.php');

// Handle different actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            addEmployee($conn);
            break;

        case 'read':
            readEmployees($conn);
            break;

        case 'update':
            updateEmployee($conn);
            break;

        case 'delete':
            deleteEmployee($conn);
            break;

        default:
            echo "Invalid action";
            break;
    }
} else {
    echo "Invalid request";
    exit();
}

//  Function to create an employee
function addEmployee($conn)
{
    if (isset($_POST['employeeDetails']) && $_POST['employeeDetails'] == "1") {
        $firstName = trim($_POST['first_name']);
        $middleName = trim($_POST['middle_name']);
        $lastName = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']); // No Hashing
        $city = trim($_POST['city']);
        $role = trim($_POST['role']);
        $contactNumber1 = trim($_POST['contact_number_1']);
        $contactNumber2 = isset($_POST['contact_number_2']) ? trim($_POST['contact_number_2']) : null;

        if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($role)) {
            echo "Required fields are missing!";
            return;
        }

        try {
            $conn->begin_transaction();

            // Insert Employee Details (No Password Hashing)
            $sqlInsertEmployee = "INSERT INTO employee (FirstName, MiddleName, LastName, Role, Email, Password, City) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmtEmployee = $conn->prepare($sqlInsertEmployee);
            $stmtEmployee->bind_param("sssssss", $firstName, $middleName, $lastName, $role, $email, $password, $city);
            
            if (!$stmtEmployee->execute()) {
                throw new Exception("Error inserting employee: " . $stmtEmployee->error);
            }

            $employeeID = $stmtEmployee->insert_id;
            $stmtEmployee->close();

            $conn->commit();
            echo "Employee created successfully!";

            
            header("Location: ../Registered/login.php");
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    }
}

//  Function to read employees
function readEmployees($conn)
{
    $sql = "SELECT e.EmployeeID, e.FirstName, e.LastName, e.Email, e.City, e.Role, 
                   GROUP_CONCAT(ep.PhoneNumber SEPARATOR ', ') AS PhoneNumbers, 
                   es.DayOfWeek, es.StartTime, es.EndTime
            FROM employee e
            LEFT JOIN employeephone ep ON e.EmployeeID = ep.EmployeeID
            LEFT JOIN employeeschedule es ON e.EmployeeID = es.EmployeeID
            GROUP BY e.EmployeeID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo json_encode($row) . "\n";
        }
    } else {
        echo "No employees found.";
    }
}

//  Function to update an employee
function updateEmployee($conn)
{
    echo "Hello<br>";

    $employeeID = isset($_POST['employee_id']) ? $_POST['employee_id'] : "";

    if (empty($employeeID)) {
        echo "Employee ID is required";
        return;
    }

    //  Har field ko `isset()` check karke assign karein
    $firstName = isset($_POST['first_name']) ? trim($_POST['first_name']) : "";
    $middleName = isset($_POST['middle_name']) ? trim($_POST['middle_name']) : "";
    $lastName = isset($_POST['last_name']) ? trim($_POST['last_name']) : "";
    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $city = isset($_POST['city']) ? trim($_POST['city']) : "";
    $role = isset($_POST['role']) ? trim($_POST['role']) : "";
    $contactNumber1 = isset($_POST['contact_number_1']) ? trim($_POST['contact_number_1']) : "";
    $contactNumber2 = isset($_POST['contact_number_2']) ? trim($_POST['contact_number_2']) : "";
    $dayOfWeek = isset($_POST['day_of_week']) ? trim($_POST['day_of_week']) : null;
    $startTime = isset($_POST['start_time']) ? trim($_POST['start_time']) : null;
    $endTime = isset($_POST['end_time']) ? trim($_POST['end_time']) : null;

    try {
        $conn->begin_transaction();

        //  Update Employee Table
        $sqlUpdateEmployee = "UPDATE employee SET FirstName = ?, MiddleName = ?, LastName = ?, Email = ?, City = ?, Role = ? WHERE EmployeeID = ?";
        $stmtEmployee = $conn->prepare($sqlUpdateEmployee);
        $stmtEmployee->bind_param("ssssssi", $firstName, $middleName, $lastName, $email, $city, $role, $employeeID);
        $stmtEmployee->execute();
        $stmtEmployee->close();

        //  Delete old phone numbers and insert new ones
        $conn->query("DELETE FROM employeephone WHERE EmployeeID = $employeeID");

        if (!empty($contactNumber1)) {
            $sqlInsertPhone = "INSERT INTO employeephone (EmployeeID, PhoneNumber) VALUES (?, ?)";
            $stmtPhone = $conn->prepare($sqlInsertPhone);
            $stmtPhone->bind_param("is", $employeeID, $contactNumber1);
            $stmtPhone->execute();
            $stmtPhone->close();
        }

        if (!empty($contactNumber2)) {
            $stmtPhone = $conn->prepare($sqlInsertPhone);
            $stmtPhone->bind_param("is", $employeeID, $contactNumber2);
            $stmtPhone->execute();
            $stmtPhone->close();
        }

        $conn->commit();
        echo "Employee updated successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

function deleteEmployee($conn)
{
    if (!isset($_POST['employee_id']) || empty($_POST['employee_id'])) {
        echo "Employee ID is required!";
        return;
    }

    $employeeID = $_POST['employee_id'];

    try {
        //  Transaction start
        $conn->begin_transaction();

        //  Step 1: Pehle related data delete karein
        $conn->query("DELETE FROM EmployeePhone WHERE EmployeeID = $employeeID");
        $conn->query("DELETE FROM EmployeeSchedule WHERE EmployeeID = $employeeID");

        //  Step 2: Employee record delete karein
        $sqlDeleteEmployee = "DELETE FROM Employee WHERE EmployeeID = ?";
        $stmt = $conn->prepare($sqlDeleteEmployee);
        $stmt->bind_param("i", $employeeID);
        $stmt->execute();
        $stmt->close();

        //  Step 3: Transaction commit
        $conn->commit();

        echo "Employee deleted successfully!";
    } catch (Exception $e) {
        //  Agar koi error aaye toh rollback karein
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
