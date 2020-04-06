<?php

$servername = "localhost:3306";
$database = "mainproject";
$username = "projectuser";
$password = "projectuser353";

// Create connection

$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";

mysqli_close($conn);
?>