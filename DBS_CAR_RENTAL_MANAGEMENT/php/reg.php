<!-- Registering user -->

<?php

// <!-- If not exists -->
if (!(isset($_POST["signup"]))) {
    echo "Error: Internal Server Error";
} else {


    // If Exists


    $fname = trim($_POST["fname"]);
    $lname = trim($_POST["lname"]);

    $contact  = trim($_POST["contact"]);
    $address = trim($_POST["address"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $startDate = trim($_POST["start_date"]);
    $endDate = trim($_POST["end_date"]);


    //  Handle starting and ending date
    // Converting do objects
    $startDateTime = new DateTime($startDate);
    $endDateTime = new DateTime($endDate);


    if ($startDateTime > $endDateTime) {
        echo "Error: Start time cannot be later than end time ";
    } else {
        echo "Full Name: " . $fname  . " " . $lname . "<br/>Email: " . $email . "<br/>Password: " . $password . "<br/>Contact no" . $contact . "<br/>Address" . $address . "<br/>Starting Date: " . $startDate . "<br/>EndDate: " . $endDate;
    }
}




?>