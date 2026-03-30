<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h1>Welcome to School Management System</h1>
            <p>Your one-stop solution for school administration.</p>
        </div>
        <hr>