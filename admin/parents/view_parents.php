<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$sql = "SELECT * FROM parents ORDER BY name";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Parents</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Parent List</h1>
        <p><a href="add_parent.php">➕ Add New Parent</a> | <a href="../dashboard.php">Back to Dashboard</a></p>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Parent ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['parent_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td>
                        <a href="edit_parent.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                        <a href="delete_parent.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a> |
                        <a href="view_linked_students.php?id=<?php echo $row['id']; ?>">View Children</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No parents found. <a href="add_parent.php">Add one now</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>