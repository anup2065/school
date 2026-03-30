<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    header("Location: view_fees.php");
    exit();
}

// Update status to paid and set paid_date to today
$paid_date = date('Y-m-d');
$sql = "UPDATE fees SET status='paid', paid_date='$paid_date' WHERE id=$id";
mysqli_query($conn, $sql);

header("Location: view_fees.php");
exit();
?>