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
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;

$check_sql = "SELECT * FROM parent_students WHERE parent_id = $parent_id AND student_id = $student_id";
$check_res = mysqli_query($conn, $check_sql);
if (mysqli_num_rows($check_res) == 0) {
    header("Location: dashboard.php");
    exit();
}

// Get student's class and section
$student_sql = "SELECT name, class, section FROM students WHERE id = $student_id";
$student_res = mysqli_query($conn, $student_sql);
$student = mysqli_fetch_assoc($student_res);
$class = $student['class'];
$section = $student['section'];

// Get homework for that class-section
$homework_sql = "SELECT h.*, s.subject_name 
                 FROM homework h
                 JOIN subjects s ON h.subject_id = s.id
                 WHERE h.class = '$class' AND h.section = '$section'
                 ORDER BY h.due_date DESC";
$homework_res = mysqli_query($conn, $homework_sql);

// Get submissions for this student
$submissions_sql = "SELECT homework_id, marks, feedback, submission_text, file_path, submitted_at 
                    FROM homework_submissions WHERE student_id = $student_id";
$submissions_res = mysqli_query($conn, $submissions_sql);
$submissions = [];
while ($sub = mysqli_fetch_assoc($submissions_res)) {
    $submissions[$sub['homework_id']] = $sub;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Homework - <?php echo $student['name']; ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Homework for <?php echo $student['name']; ?></h1>
        <p><a href="view_child.php?student_id=<?php echo $student_id; ?>">← Back to Child Dashboard</a></p>

        <?php if (mysqli_num_rows($homework_res) == 0): ?>
            <p>No homework assigned.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Marks</th>
                    <th>Feedback</th>
                    <th>Submitted On</th>
                </tr>
                <?php while ($hw = mysqli_fetch_assoc($homework_res)): 
                    $sub = isset($submissions[$hw['id']]) ? $submissions[$hw['id']] : null;
                    $status = $sub ? 'Submitted' : 'Pending';
                ?>
                <tr>
                    <td><?php echo $hw['title']; ?></td>
                    <td><?php echo $hw['subject_name']; ?></td>
                    <td><?php echo $hw['due_date']; ?></td>
                    <td><?php echo $status; ?></td>
                    <td><?php echo $sub ? $sub['marks'] : '-'; ?></td>
                    <td><?php echo $sub ? $sub['feedback'] : '-'; ?></td>
                    <td><?php echo $sub ? $sub['submitted_at'] : '-'; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>