<?php
// Database Credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_rental_management";

// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set Character Encoding
$conn->set_charset("utf8");

// Table creation queries array
$tables = [
    // Customer Tables
    "CREATE TABLE customer (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Age INT CHECK (Age >= 18), 
    Email VARCHAR(150) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL
) ENGINE=InnoDB",

    "CREATE TABLE IF NOT EXISTS CustomerPhone (
        PhoneID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT NOT NULL,
    PhoneNumber VARCHAR(20) NOT NULL,
    PhoneNumber2 VARCHAR(20),

    -- Foreign Key linking CustomerID to customer table
    CONSTRAINT fk_customer_phone FOREIGN KEY (CustomerID) REFERENCES customer(CustomerID) ON DELETE CASCADE
    ) ENGINE=InnoDB",

    "CREATE TABLE customeraddress (
    AddressID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT NOT NULL,
    City VARCHAR(100) NOT NULL,
    Country VARCHAR(100) NOT NULL,
    PhoneNumber2 VARCHAR(20),

    -- Foreign Key linking CustomerID to customer table
    CONSTRAINT fk_customer FOREIGN KEY (CustomerID) REFERENCES customer(CustomerID) ON DELETE CASCADE
)ENGINE=InnoDB",

    // Car Table
    "CREATE TABLE car (
    CarID INT AUTO_INCREMENT PRIMARY KEY,
    Model VARCHAR(100) NOT NULL,
    Brand VARCHAR(100) NOT NULL,
    LicenseNumber VARCHAR(50) UNIQUE NOT NULL,
    YearOfManufacture YEAR NOT NULL,
    Color VARCHAR(50) NOT NULL,
    Mileage INT NOT NULL,
    AvailabilityStatus ENUM('Available', 'Booked', 'Maintenance') DEFAULT 'Available',
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    type VARCHAR(50) NOT NULL,
    imageUrl VARCHAR(255) NOT NULL
) ENGINE=InnoDB",

    // Location Tables
    "CREATE TABLE IF NOT EXISTS Locations (
        LocationID INT AUTO_INCREMENT PRIMARY KEY,
        City VARCHAR(50) NOT NULL,
        Street VARCHAR(100) NOT NULL,
        Province VARCHAR(50) NOT NULL,
        ContactNumber VARCHAR(15) NOT NULL
    ) ENGINE=InnoDB",

    "CREATE TABLE IF NOT EXISTS LocationHours (
        HoursID INT AUTO_INCREMENT PRIMARY KEY,
        LocationID INT NOT NULL,
        DayOfWeek ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
        OpeningTime TIME NOT NULL,
        ClosingTime TIME NOT NULL,
        FOREIGN KEY (LocationID) REFERENCES Locations(LocationID) ON DELETE CASCADE
    ) ENGINE=InnoDB",

    // Employee Tables
    "CREATE TABLE IF NOT EXISTS Employee (
          EmployeeID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(100) NOT NULL,
    MiddleName VARCHAR(100) NULL,
    LastName VARCHAR(100) NOT NULL,
    Role ENUM('Admin', 'Manager', 'Staff') NOT NULL,
    Email VARCHAR(150) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB",

    "CREATE TABLE IF NOT EXISTS EmployeePhone (
     PhoneID INT AUTO_INCREMENT PRIMARY KEY,
    EmployeeID INT NOT NULL,
    PhoneNumber VARCHAR(20) NOT NULL,

    -- Foreign Key linking EmployeeID to employee table
    CONSTRAINT fk_employee_phone FOREIGN KEY (EmployeeID) REFERENCES employee(EmployeeID) ON DELETE CASCADE
    ) ENGINE=InnoDB",

    "CREATE TABLE IF NOT EXISTS EmployeeSchedule (
    ScheduleID INT AUTO_INCREMENT PRIMARY KEY,
    EmployeeID INT NOT NULL,
    DayOfWeek ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    StartTime TIME NOT NULL,
    EndTime TIME NOT NULL,

    -- Foreign Key linking EmployeeID to employee table
    CONSTRAINT fk_employee_schedule FOREIGN KEY (EmployeeID) REFERENCES employee(EmployeeID) ON DELETE CASCADE
    ) ENGINE=InnoDB",

    // Rental Booking Table
    "CREATE TABLE IF NOT EXISTS RentalBooking (
        BookingID INT AUTO_INCREMENT PRIMARY KEY,
        RentalStartDate DATE NOT NULL,
        RentalEndDate DATE NOT NULL,
        Total DECIMAL(10,2) NOT NULL,
        EmployeeID INT NOT NULL,
        CustomerID INT NOT NULL,
        CarID INT NOT NULL,
        FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID),
        FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
        FOREIGN KEY (CarID) REFERENCES Car(CarID),
        CHECK (RentalEndDate > RentalStartDate)
    ) ENGINE=InnoDB",

    // Payment Table
    "CREATE TABLE IF NOT EXISTS Payment (
        PaymentID INT AUTO_INCREMENT PRIMARY KEY,
        PaymentMethod ENUM('Credit Card', 'Debit Card', 'Cash', 'Online Transfer') NOT NULL,
        AmountPaid DECIMAL(10,2) NOT NULL,
        Date DATETIME DEFAULT CURRENT_TIMESTAMP,
        TransactionID VARCHAR(50) UNIQUE NOT NULL,
        BookingID INT NOT NULL,
        FOREIGN KEY (BookingID) REFERENCES RentalBooking(BookingID) ON DELETE CASCADE
    ) ENGINE=InnoDB",

    // Feedback Table
    "CREATE TABLE IF NOT EXISTS Feedback (
        FeedbackID INT AUTO_INCREMENT PRIMARY KEY,
        BookingID INT NOT NULL,
        Ratings INT NOT NULL CHECK (Ratings BETWEEN 1 AND 5),
        Comments TEXT,
        FOREIGN KEY (BookingID) REFERENCES RentalBooking(BookingID) ON DELETE CASCADE
    ) ENGINE=InnoDB",

    // Maintenance Table
    "CREATE TABLE IF NOT EXISTS Maintenance (
        MaintenanceID INT AUTO_INCREMENT PRIMARY KEY,
        Date DATE NOT NULL,
        Description TEXT NOT NULL,
        Cost DECIMAL(10,2) NOT NULL,
        CarID INT NOT NULL,
        FOREIGN KEY (CarID) REFERENCES Car(CarID) ON DELETE CASCADE
    ) ENGINE=InnoDB"
];

// Execute Each Query
foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully!<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Close Connection
$conn->close();
