<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}
include '../config.php';

$student_id = $_SESSION['student_id'];

// Get student details
$student_sql = "SELECT * FROM students WHERE id = $student_id";
$student_res = mysqli_query($conn, $student_sql);
$student = mysqli_fetch_assoc($student_res);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Student Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($student['name']); ?>!</p>

        <div class="navbar">
            <a href="dashboard.php">Home</a>
            <a href="results.php">My Results</a>
            <a href="fees.php">Fee Status</a>
            <a href="notices.php">Notices</a>
            <a href="homework/view_homework.php">Homework</a>
            <a href="change_password.php">Change Password</a>
            <a href="logout.php">Logout</a>
        </div>

        <h2>Your Profile</h2>
        <table>
            <tr><th>Student ID</th><td><?php echo $student['student_id']; ?></td></tr>
            <tr><th>Name</th><td><?php echo $student['name']; ?></td></tr>
            <tr><th>Class</th><td><?php echo $student['class'] . ' ' . $student['section']; ?></td></tr>
            <tr><th>Roll No</th><td><?php echo $student['roll_no']; ?></td></tr>
            <tr><th>Email</th><td><?php echo $student['email']; ?></td></tr>
            <tr><th>Phone</th><td><?php echo $student['phone']; ?></td></tr>
            <tr><th>Address</th><td><?php echo $student['address']; ?></td></tr>
        </table>
    </div>
</body>
</html>