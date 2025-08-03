<?php
// includes/db.php

$host = "127.0.0.1";
$user = "root";
$password = "";
$dbname = "crowdfund_d";

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
