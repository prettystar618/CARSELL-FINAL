<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'car_sales_db';

// Connect to the database
$conn = new mysqli($host, $user, $pass, $dbname);
if($conn->connect_error) die("Connection failed");
?>