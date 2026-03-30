<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

// Fetch all students
$sql = "SELECT * FROM students ORDER BY class, section, roll_no";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Student List</h1>
        <p><a href="add_student.php">➕ Add New Student</a> | <a href="../dashboard.php">Back to Dashboard</a></p>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Roll</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['student_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['class']; ?></td>
                    <td><?php echo $row['section']; ?></td>
                    <td><?php echo $row['roll_no']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td>
                        <a href="edit_student.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                        <a href="delete_student.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No students found. <a href="add_student.php">Add one now</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>