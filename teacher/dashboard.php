<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}
include '../config.php';

$teacher_id = $_SESSION['teacher_id'];

// Get teacher details
$teacher_sql = "SELECT * FROM teachers WHERE id = $teacher_id";
$teacher_res = mysqli_query($conn, $teacher_sql);
$teacher = mysqli_fetch_assoc($teacher_res);

// Get assigned classes
$classes_sql = "SELECT * FROM teacher_classes WHERE teacher_id = $teacher_id";
$classes_res = mysqli_query($conn, $classes_sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Teacher Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($teacher['name']); ?>!</p>

        <div class="navbar">
            <a href="dashboard.php">Home</a>
            <a href="students.php">My Students</a>
            <a href="enter_marks.php">Enter Marks</a>
            <a href="class_result.php">Class Results</a>
              <a href="homework/view_homework.php">Homework</a> 
            <a href="notices.php">Notices</a>
            <a href="fees.php">Fee Status</a>
            <a href="change_password.php">Change Password</a>
            <a href="logout.php">Logout</a>
        </div>

        <h2>Your Profile</h2>
        <table>
            <tr><th>Name</th><td><?php echo $teacher['name']; ?></td></tr>
            <tr><th>Email</th><td><?php echo $teacher['email']; ?></td></tr>
            <tr><th>Phone</th><td><?php echo $teacher['phone']; ?></td></tr>
            <tr><th>Qualification</th><td><?php echo $teacher['qualification']; ?></td></tr>
            <tr><th>Subject</th><td><?php echo $teacher['subject']; ?></td></tr>
        </table>

        <h2>Assigned Classes</h2>
        <?php if (mysqli_num_rows($classes_res) > 0): ?>
        <table>
            <tr><th>Class</th><th>Section</th><th>Subject</th></tr>
            <?php while ($row = mysqli_fetch_assoc($classes_res)): ?>
            <tr>
                <td><?php echo $row['class']; ?></td>
                <td><?php echo $row['section']; ?></td>
                <td><?php echo $row['subject']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
        <p>No classes assigned yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>