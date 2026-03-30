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

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    header("Location: view_results.php");
    exit();
}

// Fetch result
$sql = "SELECT * FROM results WHERE id = $id";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) != 1) {
    header("Location: view_results.php");
    exit();
}
$res = mysqli_fetch_assoc($result);

// Fetch all students for dropdown
$students_sql = "SELECT id, name, student_id, class, section FROM students ORDER BY class, name";
$students_result = mysqli_query($conn, $students_sql);

// Fetch all subjects
$subjects_sql = "SELECT id, subject_name FROM subjects ORDER BY subject_name";
$subjects_result = mysqli_query($conn, $subjects_sql);

// For pre-selecting subject, we need to match subject name to subject id (since results stores subject name)
// So we'll store the subject id in a variable if found.
$current_subject_id = null;
$subj_find = mysqli_query($conn, "SELECT id FROM subjects WHERE subject_name = '{$res['subject']}'");
if (mysqli_num_rows($subj_find) > 0) {
    $subj_row = mysqli_fetch_assoc($subj_find);
    $current_subject_id = $subj_row['id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $exam_name = mysqli_real_escape_string($conn, $_POST['exam_name']);
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);
    $marks = mysqli_real_escape_string($conn, $_POST['marks']);
    $total_marks = mysqli_real_escape_string($conn, $_POST['total_marks']);

    // Get student's class
    $class_sql = "SELECT class FROM students WHERE id = $student_id";
    $class_res = mysqli_query($conn, $class_sql);
    $class_row = mysqli_fetch_assoc($class_res);
    $class = $class_row['class'];

    // Get subject name
    $subj_sql = "SELECT subject_name FROM subjects WHERE id = $subject_id";
    $subj_res = mysqli_query($conn, $subj_sql);
    $subj_row = mysqli_fetch_assoc($subj_res);
    $subject_name = $subj_row['subject_name'];

    if (empty($student_id) || empty($exam_name) || empty($subject_id) || empty($marks) || empty($total_marks)) {
        $error = "All fields are required.";
    } else {
        // Check duplicate (excluding current record)
        $check_sql = "SELECT * FROM results WHERE student_id = $student_id AND exam_name = '$exam_name' AND subject = '$subject_name' AND id != $id";
        $check_result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Another result already exists for this student in this exam and subject.";
        } else {
            $update_sql = "UPDATE results SET student_id=$student_id, class='$class', exam_name='$exam_name', subject='$subject_name', marks=$marks, total_marks=$total_marks WHERE id=$id";
            if (mysqli_query($conn, $update_sql)) {
                $message = "Result updated successfully!";
                // Refresh data
                $result = mysqli_query($conn, "SELECT * FROM results WHERE id = $id");
                $res = mysqli_fetch_assoc($result);
                $current_subject_id = $subject_id; // update selected subject id
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
    <title>Edit Result</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Edit Result</h1>
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
                    <option value="<?php echo $student['id']; ?>" <?php if($student['id'] == $res['student_id']) echo 'selected'; ?>>
                        <?php echo $student['name']; ?> (<?php echo $student['student_id']; ?>) - Class <?php echo $student['class']; ?> <?php echo $student['section']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <label>Exam Name:</label>
            <input type="text" name="exam_name" id="exam_name" value="<?php echo $res['exam_name']; ?>" required>
            
            <label>Select Subject:</label>
            <select name="subject_id" required>
                <option value="">-- Select Subject --</option>
                <?php while ($subject = mysqli_fetch_assoc($subjects_result)): ?>
                    <option value="<?php echo $subject['id']; ?>" <?php if($subject['id'] == $current_subject_id) echo 'selected'; ?>>
                        <?php echo $subject['subject_name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <label>Marks Obtained:</label>
            <input type="number" name="marks" id="marks" value="<?php echo $res['marks']; ?>" required>
            
            <label>Total Marks:</label>
            <input type="number" name="total_marks" id="total_marks" value="<?php echo $res['total_marks']; ?>" required>
            
            <input type="submit" value="Update Result">
        </form>
    </div>
</body>
</html>