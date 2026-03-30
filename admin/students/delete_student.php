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
    // Optional: check if student exists before deleting
    $sql = "DELETE FROM students WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Student deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting student: " . mysqli_error($conn);
    }
}
header("Location: view_students.php");
exit();
?>