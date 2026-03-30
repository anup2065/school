<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$student_id = $_SESSION['student_id'];

$sql = "SELECT hs.*, h.title, h.due_date, s.subject_name 
        FROM homework_submissions hs
        JOIN homework h ON hs.homework_id = h.id
        JOIN subjects s ON h.subject_id = s.id
        WHERE hs.student_id = $student_id
        ORDER BY hs.submitted_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Submissions</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>My Homework Submissions</h1>
        <p><a href="view_homework.php">← Back to Homework List</a> | <a href="../dashboard.php">Dashboard</a></p>

        <?php if (mysqli_num_rows($result) == 0): ?>
            <p>You have not submitted any homework yet.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Homework</th>
                    <th>Subject</th>
                    <th>Due Date</th>
                    <th>Submitted On</th>
                    <th>Marks</th>
                    <th>Feedback</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['subject_name']; ?></td>
                    <td><?php echo $row['due_date']; ?></td>
                    <td><?php echo $row['submitted_at']; ?></td>
                    <td><?php echo $row['marks'] ?? '-'; ?></td>
                    <td><?php echo $row['feedback'] ?? '-'; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>