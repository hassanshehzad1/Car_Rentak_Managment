<?php
session_start();
include('../php/conn.php');

//  Agar user login nahi hai toh login page par bhejo
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../Registered/login.php");
    exit();
}

$employeeID = $_SESSION['employee_id'];

//  Employee ki sari details fetch karo
$sql = "SELECT * FROM Employee WHERE EmployeeID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

//  Employee Phone Numbers Fetch
$sqlPhones = "SELECT PhoneNumber FROM EmployeePhone WHERE EmployeeID = ?";
$stmtPhones = $conn->prepare($sqlPhones);
$stmtPhones->bind_param("i", $employeeID);
$stmtPhones->execute();
$phoneResult = $stmtPhones->get_result();
$phones = [];
while ($row = $phoneResult->fetch_assoc()) {
    $phones[] = $row['PhoneNumber'];
}

//  Employee Schedule Fetch
$sqlSchedule = "SELECT * FROM EmployeeSchedule WHERE EmployeeID = ?";
$stmtSchedule = $conn->prepare($sqlSchedule);
$stmtSchedule->bind_param("i", $employeeID);
$stmtSchedule->execute();
$scheduleResult = $stmtSchedule->get_result();
$schedules = $scheduleResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Employee Profile</title>
    <link rel="stylesheet" href="Prof.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!--  jQuery for AJAX -->
</head>

<body>

    <header class="custom_container">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <div class="logo">
                    <img src="../car rental images/Home/logo.png" alt="Logo" />
                </div>

                <div class="center">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="../Vehicals/Vehicals.php">Vehicles</a></li>
                            <li class="nav-item"><a class="nav-link" href="../Details/details.php">Details</a></li>
                            <li class="nav-item"><a class="nav-link" href="../about/about.php">About us</a></li>
                            <li class="nav-item"><a class="nav-link" href="../Contact Us/Contact.php">Contact us</a></li>

                            <!--  Sirf "Manager" ke liye "Add Vehicles" & "Branches" dikhaye -->
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Manager') : ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="../carDetails/carDetails.php">Add Vehicles</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../branch/branch.php">Branches</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <!--  Profile Dropdown -->
                <div class="dropdown">
                    <a class="reg dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-user"></i>
                        <?php
                        if (isset($_SESSION['employee_firstname']) && isset($_SESSION['employee_lastname'])) {
                            echo $_SESSION['employee_firstname'] . " " . $_SESSION['employee_lastname'];
                        }
                        ?>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (isset($_SESSION['role'])) : ?>
                            <li><a class="dropdown-item" href="../Profile/Profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="../Registered/logout.php">Logout</a></li>
                        <?php else : ?>
                            <li><a class="dropdown-item" href="../Registered/login.php">Login</a></li>
                            <li><a class="dropdown-item" href="../Registered/Registered.php">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>
        </nav>
    </header>

    <div class="container mt-5">
        <h2 class="text-center">Employee Profile</h2>
        <div class="row">
            <!--  Profile Image & Info -->
            <div class="col-md-3">
                <div class="text-center">
                    <img class="rounded-circle mt-3" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
                    <h5 id="displayName"><?php echo $employee['FirstName'] . " " . $employee['MiddleName'] . " " . $employee['LastName']; ?></h5>
                    <p class="text-muted" id="displayEmail"><?php echo $employee['email']; ?></p>
                    <p class="text-muted"><?php echo $employee['Role']; ?></p>
                </div>
            </div>

            <!--  Update Profile Form -->
            <div class="col-md-5">
                <!-- Update Profile Form -->
                <form action="../php/empoySignup.php" method="POST">
                    <div class="p-3 py-5">
                        <h4 class="text-right">Profile Settings</h4>
                        <input type="hidden" name="action" value="update">
                        <!-- Employee ID (Hidden Field) -->
                        <input type="hidden" name="employee_id" value="<?php echo $employeeID; ?>">

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="labels">First Name</label>
                                <input type="text" class="form-control" name="first_name" value="<?php echo $employee['FirstName']  ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Middle Name</label>
                                <input type="text" class="form-control" name="last_name" value="<?php echo $employee['MiddleName']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Last Name</label>
                                <input type="text" class="form-control" name="last_name" value="<?php echo $employee['LastName']; ?>">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="labels">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $employee['email']  ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="labels">City</label>
                                <input type="text" class="form-control" name="city" value="<?php echo $employee['city']; ?>">
                            </div>
                        </div>

                        <!-- Phone Numbers -->
                        <div class="mt-3">
                            <label class="labels">Phone Numbers</label>
                            <?php foreach ($phones as $index => $phoneNumber) : ?>
                                <input type="text" class="form-control mt-2" name="phone_numbers[]" value="<?php echo htmlspecialchars($phoneNumber); ?>">
                            <?php endforeach; ?>

                        </div>

                        <!-- Work Schedule -->
                        <div class="mt-3">
                            <h4 class="text-right">Work Schedule</h4>
                            <?php foreach ($schedules as $schedule) : ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label><?php echo htmlspecialchars($schedule['DayOfWeek']); ?></label>
                                        <input type="hidden" name="schedule_days[]" value="<?php echo htmlspecialchars($schedule['DayOfWeek']); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="time" class="form-control" name="start_times[]" value="<?php echo htmlspecialchars($schedule['StartTime']); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="time" class="form-control" name="end_times[]" value="<?php echo htmlspecialchars($schedule['EndTime']); ?>">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-5 text-center">
                            <button class="btn btn-primary" type="submit">Update Profile</button>
                        </div>
                    </div>
                </form>
                <form action="../php/empoySignup.php" method="POST">
                    <input type="hidden" name="employee_id" value="<?php echo $employeeID; ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this employee?');"> Delete Employee</button>
                </form>

            </div>
        </div>
    </div>

    <!--  JavaScript (Live Update Without Page Refresh) -->
    <script>
        $(document).ready(function() {
            $("#updateProfileForm").submit(function(event) {
                event.preventDefault(); //  Form submit hone se rokna

                $.ajax({
                    url: "update_profile.php", //  Backend PHP file call karega
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response); //  Update ka response dikhayega
                        $("#displayName").text($("input[name='first_name']").val() + " " + $("input[name='last_name']").val());
                        $("#displayEmail").text($("input[name='email']").val());
                    },
                    error: function() {
                        alert(" Update Failed!");
                    }
                });
            });
        });
    </script>

</body>

</html>