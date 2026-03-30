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

// Get student's class and section
$student_sql = "SELECT class, section FROM students WHERE id = $student_id";
$student_res = mysqli_query($conn, $student_sql);
$student = mysqli_fetch_assoc($student_res);
$class = $student['class'];
$section = $student['section'];

// Fetch homework for this class-section
$homework_sql = "SELECT h.*, s.subject_name 
                 FROM homework h
                 JOIN subjects s ON h.subject_id = s.id
                 WHERE h.class = '$class' AND h.section = '$section'
                 ORDER BY h.due_date DESC";
$homework_res = mysqli_query($conn, $homework_sql);

// For each homework, check if student has submitted
$submissions_sql = "SELECT homework_id FROM homework_submissions WHERE student_id = $student_id";
$submissions_res = mysqli_query($conn, $submissions_sql);
$submitted = [];
while ($sub = mysqli_fetch_assoc($submissions_res)) {
    $submitted[] = $sub['homework_id'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Homework</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>My Homework</h1>
        <p><a href="../dashboard.php">← Back to Dashboard</a> | <a href="my_submissions.php">My Submissions</a></p>

        <?php if (mysqli_num_rows($homework_res) == 0): ?>
            <p>No homework assigned.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php while ($hw = mysqli_fetch_assoc($homework_res)): 
                    $status = in_array($hw['id'], $submitted) ? 'Submitted' : 'Pending';
                ?>
                <tr>
                    <td><?php echo $hw['title']; ?></td>
                    <td><?php echo $hw['subject_name']; ?></td>
                    <td><?php echo $hw['due_date']; ?></td>
                    <td><?php echo $status; ?></td>
                    <td>
                        <?php if ($status == 'Pending'): ?>
                            <a href="submit_homework.php?id=<?php echo $hw['id']; ?>">Submit</a>
                        <?php else: ?>
                            <a href="view_submission.php?hw_id=<?php echo $hw['id']; ?>">View Submission</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>