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

// Get student's class
$class_sql = "SELECT class FROM students WHERE id = $student_id";
$class_res = mysqli_query($conn, $class_sql);
$class_row = mysqli_fetch_assoc($class_res);
$class = $class_row['class'];

// Fetch notices: target_audience = 'all' OR (target_audience='class' AND class = student's class)
$sql = "SELECT * FROM notices WHERE target_audience = 'all' OR (target_audience = 'class' AND class = '$class') ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notices</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Notices</h1>
        <p><a href="dashboard.php">← Back to Dashboard</a></p>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px; border-radius:5px;">
                    <h3><?php echo $row['title']; ?></h3>
                    <p><?php echo nl2br($row['content']); ?></p>
                    <small>Posted on: <?php echo $row['created_at']; ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No notices available.</p>
        <?php endif; ?>
    </div>
</body>
</html>