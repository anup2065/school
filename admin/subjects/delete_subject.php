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
if ($id > 0) {
    $sql = "DELETE FROM subjects WHERE id = $id";
    mysqli_query($conn, $sql); // no error handling for simplicity
}
header("Location: view_subjects.php");
exit();
?>