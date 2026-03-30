<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}
include '../config.php';

// Fetch all notices (public and class-specific)
// For simplicity, we'll show all notices; later you can filter by class if needed.
$sql = "SELECT * FROM notices ORDER BY created_at DESC";
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
                    <small>Posted on: <?php echo $row['created_at']; ?></small><br>
                    <small>Target: <?php echo $row['target_audience']; ?> <?php echo ($row['target_audience']=='class') ? ' - Class '.$row['class'] : ''; ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No notices available.</p>
        <?php endif; ?>
    </div>
</body>
</html>