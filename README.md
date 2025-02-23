# Car_Rentak_Managment

<p>A PHP & MySQL-based Car Rental Management System that allows users to rent cars, manage bookings,  and process payments efficiently.</p>
<h1> Features</h1>
<ul>
 <li> User Registration & Login (Customer & Admin)</li>
<li> Car Listing with Availability Status</li>
  <li>Booking System (Start & End Date Selection)</li>
  <li> Price Calculation Based on Rental Duration</li>
<li> Payment Processing (Manual / Online)</li>
<li>Admin Panel (Manage Cars, Users, & Bookings)</li>
<li> Booking Status Update (Car Marked as Rented)</li>
<li> Car Return & Availability Update</li>
  <li>Admin add vehicles and branches</li>
</ul>

<h1> Tech Stack
</h1>
<ul>
 <li>Frontend: HTML, CSS, JavaScript, Bootstrap</li>
<li>Backend: PHP </li>
<li>Database: MySQL || SQL</li>
<li></li>
</ul>


<h1>Installation & Setup</h1>
<h2>Clone the Repository</h2>

git clone https://github.com/your-username/Car-Rental-Management.git
cd Car-Rental-Management

<h2>Database Configuration</h2>

<li>Open phpMyAdmin and create a database: car_rental_db</li>
<li>Import the SQL file from the database/ folder.</li>


<h2>Configure Database Connection
</h2>

$host = "localhost";
$user = "root"; // Change if necessary
$password = ""; // Change if necessary
$database = "car_rental_db";
$conn = new mysqli($host, $user, $password, $database);


<h2> Start Local Server</h2>

php -S localhost:8000

<h1>How to Use?</h1>
<ul>
 <li>Register/Login as a customer</li>
<li> Browse available cars</li>
<li> Select rental start & end date</li>
<li> Confirm booking & proceed to payment</li>
<li>Admin manages bookings from the dashboard</li>
<li>Admin add vehicles and branches</li>

</ul>


<h1>Troubleshooting</h1>
<ul>
 <li>Register/Login as a customer</li>
<li> Browse available cars</li>
<li> Select rental start & end date</li>
<li> Confirm booking & proceed to payment</li>
<li>Admin manages bookings from the dashboard</li>

</ul>
