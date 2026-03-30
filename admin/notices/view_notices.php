<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$sql = "SELECT * FROM notices ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Notices</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Notice List</h1>
        <p><a href="add_notice.php">➕ Add New Notice</a> | <a href="../dashboard.php">Back to Dashboard</a></p>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Target</th>
                    <th>Class</th>
                    <th>Posted On</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo substr($row['content'], 0, 50); ?>...</td>
                    <td><?php echo $row['target_audience']; ?></td>
                    <td><?php echo $row['class'] ?? '-'; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="edit_notice.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                        <a href="delete_notice.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No notices found. <a href="add_notice.php">Add one now</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>