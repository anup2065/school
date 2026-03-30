<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$host="localhost";
$user="root";
$password="";
$db="school_db";
// Database connection
$conn = mysqli_connect($host, $user, $password, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

date_default_timezone_set("Asia/Kathmandu");
?>