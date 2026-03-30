<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    // Ensure ownership
    $check = "SELECT id FROM homework WHERE id=$id AND teacher_id={$_SESSION['teacher_id']}";
    $check_res = mysqli_query($conn, $check);
    if (mysqli_num_rows($check_res) == 1) {
        $delete = "DELETE FROM homework WHERE id=$id";
        mysqli_query($conn, $delete);
    }
}
header("Location: view_homework.php");
exit();
?>