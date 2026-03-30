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
    // Because of ON DELETE CASCADE, parent_students rows will be removed automatically
    $sql = "DELETE FROM parents WHERE id = $id";
    mysqli_query($conn, $sql);
}
header("Location: view_parents.php");
exit();
?>