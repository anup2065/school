<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}
include '../config.php';

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current = mysqli_real_escape_string($conn, $_POST['current']);
    $new = mysqli_real_escape_string($conn, $_POST['new']);
    $confirm = mysqli_real_escape_string($conn, $_POST['confirm']);

    $student_id = $_SESSION['student_id'];
    $sql = "SELECT password FROM students WHERE id = $student_id";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);

    if ($current != $row['password']) {
        $error = "Current password is incorrect.";
    } elseif ($new != $confirm) {
        $error = "New passwords do not match.";
    } elseif (strlen($new) < 4) {
        $error = "Password must be at least 4 characters.";
    } else {
        $update = "UPDATE students SET password = '$new' WHERE id = $student_id";
        if (mysqli_query($conn, $update)) {
            $message = "Password changed successfully.";
        } else {
            $error = "Error updating password.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Change Password</h1>
        <p><a href="dashboard.php">← Back to Dashboard</a></p>
        <?php if ($message): ?><div class="success"><?php echo $message; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <label>Current Password:</label>
            <input type="password" name="current" id="current" required>
            <label>New Password:</label>
            <input type="password" name="new" id="new" required>
            <label>Confirm New Password:</label>
            <input type="password" name="confirm" id="confirm" required>
            <input type="submit" value="Change Password">
        </form>
    </div>
</body>
</html>