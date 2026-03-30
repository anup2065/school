<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$sql = "SELECT * FROM subjects ORDER BY subject_name";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Subjects</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Subject List</h1>
        <p><a href="add_subject.php">➕ Add New Subject</a> | <a href="../dashboard.php">Back to Dashboard</a></p>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Subject Name</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['subject_name']; ?></td>
                    <td>
                        <a href="edit_subject.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                        <a href="delete_subject.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No subjects found. <a href="add_subject.php">Add one now</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>