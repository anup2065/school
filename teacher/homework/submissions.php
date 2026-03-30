<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$homework_id = isset($_GET['homework_id']) ? intval($_GET['homework_id']) : 0;
if ($homework_id == 0) {
    header("Location: view_homework.php");
    exit();
}

// Ensure this homework belongs to the logged-in teacher
$check_sql = "SELECT * FROM homework WHERE id = $homework_id AND teacher_id = {$_SESSION['teacher_id']}";
$check_res = mysqli_query($conn, $check_sql);
if (mysqli_num_rows($check_res) == 0) {
    header("Location: view_homework.php");
    exit();
}
$homework = mysqli_fetch_assoc($check_res);

// Handle grading submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_grades'])) {
    $marks = $_POST['marks'];      // associative array submission_id => mark
    $feedback = $_POST['feedback']; // associative array submission_id => feedback

    foreach ($marks as $sid => $mark) {
        // If mark is empty string, set to NULL; otherwise use the numeric value
        $mark_value = ($mark !== '') ? floatval($mark) : 'NULL';
        $fb = mysqli_real_escape_string($conn, $feedback[$sid]);
        $update = "UPDATE homework_submissions SET marks=$mark_value, feedback='$fb' WHERE id=$sid";
        mysqli_query($conn, $update);
    }
    $message = "Grades saved.";
}

// Fetch submissions for this homework
$submissions_sql = "SELECT hs.*, s.name, s.roll_no 
                    FROM homework_submissions hs 
                    JOIN students s ON hs.student_id = s.id 
                    WHERE hs.homework_id = $homework_id 
                    ORDER BY s.roll_no";
$submissions_res = mysqli_query($conn, $submissions_sql);

// Also get list of all students in that class-section for those who haven't submitted
$all_students_sql = "SELECT id, name, roll_no FROM students WHERE class='{$homework['class']}' AND section='{$homework['section']}' ORDER BY roll_no";
$all_students_res = mysqli_query($conn, $all_students_sql);
$submitted_students = [];
while ($sub = mysqli_fetch_assoc($submissions_res)) {
    $submitted_students[$sub['student_id']] = $sub;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submissions</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Submissions for: <?php echo $homework['title']; ?></h1>
        <p><a href="view_homework.php">← Back to Homework List</a></p>

        <?php if (isset($message)): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <table>
                <tr>
                    <th>Roll No</th>
                    <th>Student Name</th>
                    <th>Submission</th>
                    <th>Submitted At</th>
                    <th>Marks</th>
                    <th>Feedback</th>
                </tr>
                <?php 
                mysqli_data_seek($all_students_res, 0);
                while ($student = mysqli_fetch_assoc($all_students_res)): 
                    $sub = isset($submitted_students[$student['id']]) ? $submitted_students[$student['id']] : null;
                ?>
                <tr>
                    <td><?php echo $student['roll_no']; ?></td>
                    <td><?php echo $student['name']; ?></td>
                    <td>
                        <?php if ($sub): ?>
                            <?php echo nl2br($sub['submission_text']); ?>
                            <?php if ($sub['file_path']): ?>
                                <br><a href="../../<?php echo $sub['file_path']; ?>" target="_blank">View File</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <em>Not submitted</em>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $sub ? $sub['submitted_at'] : '-'; ?></td>
                    <td>
                        <?php if ($sub): ?>
                            <input type="number" name="marks[<?php echo $sub['id']; ?>]" value="<?php echo $sub['marks']; ?>" step="0.01">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>   
                    <td>
                        <?php if ($sub): ?>
                            <input type="text" name="feedback[<?php echo $sub['id']; ?>]" value="<?php echo htmlspecialchars($sub['feedback']); ?>">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
            <input type="submit" name="save_grades" value="Save Grades">
        </form>
    </div>
</body>
</html>