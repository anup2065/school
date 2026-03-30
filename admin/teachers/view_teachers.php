<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$sql = "SELECT * FROM teachers ORDER BY name";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Teachers</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Teacher List</h1>
        <p><a href="add_teacher.php">➕ Add New Teacher</a> | <a href="assign_class.php">📌 Assign Class to Teacher</a> | <a href="../dashboard.php">Back to Dashboard</a></p>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Teacher ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['teacher_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['subject']; ?></td>
                    <td>
                        <a href="edit_teacher.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                        <a href="delete_teacher.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a> |
                        <a href="assign_class.php?teacher_id=<?php echo $row['id']; ?>">Assign Class</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No teachers found. <a href="add_teacher.php">Add one now</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>