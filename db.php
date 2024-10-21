<?php
$servername = "localhost"; // Change this to your MySQL server hostname
$username = "id21982426_serviz"; // Change this to your MySQL username
$password = "@123Serviz"; // Change this to your MySQL password
$dbname = "id21982426_serviz"; // Change this to the name of your MySQL database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>