<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$message = '';
$error = '';

// Fetch all students for dropdown
$students_sql = "SELECT id, name, student_id, class, section FROM students ORDER BY class, name";
$students_result = mysqli_query($conn, $students_sql);

// Fetch all subjects from subjects table
$subjects_sql = "SELECT id, subject_name FROM subjects ORDER BY subject_name";
$subjects_result = mysqli_query($conn, $subjects_sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $exam_name = mysqli_real_escape_string($conn, $_POST['exam_name']);
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);
    $marks = mysqli_real_escape_string($conn, $_POST['marks']);
    $total_marks = mysqli_real_escape_string($conn, $_POST['total_marks']);

    // Get student's class (from students table) to store in results
    $class_sql = "SELECT class FROM students WHERE id = $student_id";
    $class_res = mysqli_query($conn, $class_sql);
    $class_row = mysqli_fetch_assoc($class_res);
    $class = $class_row['class'];

    // Get subject name from subjects table
    $subj_sql = "SELECT subject_name FROM subjects WHERE id = $subject_id";
    $subj_res = mysqli_query($conn, $subj_sql);
    $subj_row = mysqli_fetch_assoc($subj_res);
    $subject_name = $subj_row['subject_name'];

    if (empty($student_id) || empty($exam_name) || empty($subject_id) || empty($marks) || empty($total_marks)) {
        $error = "All fields are required.";
    } else {
        // Check if result already exists for this student, exam, subject
        $check_sql = "SELECT * FROM results WHERE student_id = $student_id AND exam_name = '$exam_name' AND subject = '$subject_name'";
        $check_result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Result already exists for this student in this exam and subject.";
        } else {
            $sql = "INSERT INTO results (student_id, class, exam_name, subject, marks, total_marks, entered_by) 
                    VALUES ($student_id, '$class', '$exam_name', '$subject_name', $marks, $total_marks, {$_SESSION['admin_id']})";
            if (mysqli_query($conn, $sql)) {
                $message = "Result added successfully!";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Result</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Add New Result</h1>
        <p><a href="view_results.php">← Back to Results List</a></p>
        
        <?php if ($message != ''): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Select Student:</label>
            <select name="student_id" id="student_id" required>
                <option value="">-- Select Student --</option>
                <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                    <option value="<?php echo $student['id']; ?>">
                        <?php echo $student['name']; ?> (<?php echo $student['student_id']; ?>) - Class <?php echo $student['class']; ?> <?php echo $student['section']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <label>Exam Name:</label>
            <input type="text" name="exam_name" id="exam_name" placeholder="e.g. First Term, Final Exam" required>
            
            <label>Select Subject:</label>
            <select name="subject_id" required>
                <option value="">-- Select Subject --</option>
                <?php while ($subject = mysqli_fetch_assoc($subjects_result)): ?>
                    <option value="<?php echo $subject['id']; ?>"><?php echo $subject['subject_name']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Marks Obtained:</label>
            <input type="number" name="marks" id="marks" required>
            
            <label>Total Marks:</label>
            <input type="number" name="total_marks" id="total_marks" required>
            
            <input type="submit" value="Add Result">
        </form>
    </div>
</body>
</html>