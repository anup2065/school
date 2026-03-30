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

// Get parent details
$parent_sql = "SELECT * FROM parents WHERE id = $parent_id";
$parent_res = mysqli_query($conn, $parent_sql);
$parent = mysqli_fetch_assoc($parent_res);

// Get linked children
$children_sql = "SELECT s.* FROM students s
                 JOIN parent_students ps ON s.id = ps.student_id
                 WHERE ps.parent_id = $parent_id
                 ORDER BY s.class, s.name";
$children_res = mysqli_query($conn, $children_sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Parent Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($parent['name']); ?>!</p>

        <div class="navbar">
            <a href="dashboard.php">Home</a>
            <a href="change_password.php">Change Password</a>
            <a href="logout.php">Logout</a>
        </div>

        <h2>Your Children</h2>
        <?php if (mysqli_num_rows($children_res) > 0): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
                <?php while ($child = mysqli_fetch_assoc($children_res)): ?>
                <div style="background: #f0f0f0; padding: 20px; text-align: center; border-radius: 5px;">
                    <h3><?php echo $child['name']; ?></h3>
                    <p><strong>Class:</strong> <?php echo $child['class'] . ' ' . $child['section']; ?><br>
                    <strong>Roll No:</strong> <?php echo $child['roll_no']; ?></p>
                    <p><a href="view_child.php?student_id=<?php echo $child['id']; ?>" style="background: #007bff; color: white; padding: 10px; text-decoration: none; border-radius: 5px; display: inline-block;">View Details</a></p>
                </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No children linked to your account.</p>
        <?php endif; ?>
    </div>
</body>
</html>