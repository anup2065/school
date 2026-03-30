<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$teacher_id = $_SESSION['teacher_id'];

$sql = "SELECT h.*, s.subject_name 
        FROM homework h 
        JOIN subjects s ON h.subject_id = s.id 
        WHERE h.teacher_id = $teacher_id 
        ORDER BY h.due_date DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Homework</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>My Homework Posts</h1>
        <p><a href="post_homework.php">➕ Post New Homework</a> | <a href="../dashboard.php">Dashboard</a></p>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Subject</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['class']; ?></td>
                    <td><?php echo $row['section']; ?></td>
                    <td><?php echo $row['subject_name']; ?></td>
                    <td><?php echo $row['due_date']; ?></td>
                    <td>
                        <a href="submissions.php?homework_id=<?php echo $row['id']; ?>">View Submissions</a> |
                        <a href="edit_homework.php?id=<?php echo $row['id']; ?>">Edit</a> |
                        <a href="delete_homework.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No homework posted yet. <a href="post_homework.php">Post one now</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>