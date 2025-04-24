<?php
// Database connection variables
$servername = "localhost";  // or your database server
$username = "root";         // your database username (default is 'root')
$password = "";             // your database password (default is an empty string for 'root')
$dbname = "student management system";     // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
