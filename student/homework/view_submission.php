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
$homework_id = isset($_GET['hw_id']) ? intval($_GET['hw_id']) : 0;

if ($homework_id == 0) {
    header("Location: view_homework.php");
    exit();
}

// Fetch submission details
$sql = "SELECT hs.*, h.title, h.due_date, s.subject_name 
        FROM homework_submissions hs
        JOIN homework h ON hs.homework_id = h.id
        JOIN subjects s ON h.subject_id = s.id
        WHERE hs.student_id = $student_id AND hs.homework_id = $homework_id";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    header("Location: view_homework.php");
    exit();
}
$sub = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submission Details</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Submission for: <?php echo $sub['title']; ?></h1>
        <p><a href="view_homework.php">← Back to Homework List</a></p>

        <table>
            <tr><th>Subject</th><td><?php echo $sub['subject_name']; ?></td></tr>
            <tr><th>Due Date</th><td><?php echo $sub['due_date']; ?></td></tr>
            <tr><th>Submitted On</th><td><?php echo $sub['submitted_at']; ?></td></tr>
            <tr><th>Your Submission</th><td><?php echo nl2br($sub['submission_text']); ?></td></tr>
            <?php if ($sub['file_path']): ?>
            <tr><th>Attached File</th><td><a href="../../<?php echo $sub['file_path']; ?>" target="_blank">Download</a></td></tr>
            <?php endif; ?>
            <tr><th>Marks</th><td><?php echo $sub['marks'] ?? 'Not graded yet'; ?></td></tr>
            <tr><th>Feedback</th><td><?php echo $sub['feedback'] ?? 'No feedback yet'; ?></td></tr>
        </table>
    </div>
</body>
</html>