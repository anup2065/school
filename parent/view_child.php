<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['parent_id'])) {
    header("Location: login.php");
    exit();
}
include '../config.php';

$parent_id = $_SESSION['parent_id'];
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;

// Verify that this student belongs to this parent
$check_sql = "SELECT * FROM parent_students WHERE parent_id = $parent_id AND student_id = $student_id";
$check_res = mysqli_query($conn, $check_sql);
if (mysqli_num_rows($check_res) == 0) {
    header("Location: dashboard.php");
    exit();
}

// Get student details
$student_sql = "SELECT * FROM students WHERE id = $student_id";
$student_res = mysqli_query($conn, $student_sql);
$student = mysqli_fetch_assoc($student_res);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $student['name']; ?> - Parent View</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $student['name']; ?>'s Dashboard</h1>
        <p><a href="dashboard.php">← Back to My Children</a></p>

        <div class="navbar" style="margin-bottom:20px;">
            <a href="view_child.php?student_id=<?php echo $student_id; ?>">Profile</a>
            <a href="child_results.php?student_id=<?php echo $student_id; ?>">Results</a>
            <a href="child_fees.php?student_id=<?php echo $student_id; ?>">Fees</a>
            <a href="child_notices.php?student_id=<?php echo $student_id; ?>">Notices</a>
            <a href="child_homework.php?student_id=<?php echo $student_id; ?>">Homework</a>
        </div>

        <h2>Profile</h2>
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