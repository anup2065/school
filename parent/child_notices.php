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

$check_sql = "SELECT * FROM parent_students WHERE parent_id = $parent_id AND student_id = $student_id";
$check_res = mysqli_query($conn, $check_sql);
if (mysqli_num_rows($check_res) == 0) {
    header("Location: dashboard.php");
    exit();
}

// Get student's class
$student_sql = "SELECT name, class FROM students WHERE id = $student_id";
$student_res = mysqli_query($conn, $student_sql);
$student = mysqli_fetch_assoc($student_res);
$class = $student['class'];

// Fetch notices for 'all' or this class
$notices_sql = "SELECT * FROM notices WHERE target_audience = 'all' OR (target_audience = 'class' AND class = '$class') ORDER BY created_at DESC";
$notices_res = mysqli_query($conn, $notices_sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notices - <?php echo $student['name']; ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Notices for <?php echo $student['name']; ?></h1>
        <p><a href="view_child.php?student_id=<?php echo $student_id; ?>">← Back to Child Dashboard</a></p>

        <?php if (mysqli_num_rows($notices_res) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($notices_res)): ?>
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