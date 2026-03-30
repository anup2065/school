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
$homework_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($homework_id == 0) {
    header("Location: view_homework.php");
    exit();
}

// Check if already submitted
$check_sql = "SELECT id FROM homework_submissions WHERE homework_id = $homework_id AND student_id = $student_id";
$check_res = mysqli_query($conn, $check_sql);
if (mysqli_num_rows($check_res) > 0) {
    header("Location: view_homework.php?msg=already_submitted");
    exit();
}

// Get homework details
$hw_sql = "SELECT h.*, s.subject_name FROM homework h JOIN subjects s ON h.subject_id = s.id WHERE h.id = $homework_id";
$hw_res = mysqli_query($conn, $hw_sql);
if (mysqli_num_rows($hw_res) == 0) {
    header("Location: view_homework.php");
    exit();
}
$hw = mysqli_fetch_assoc($hw_res);

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submission_text = mysqli_real_escape_string($conn, $_POST['submission_text']);
    // File upload handling (optional)
    $file_path = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = "../../uploads/"; // create this folder
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES['file']['name']);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $file_path = 'uploads/' . $file_name;
        } else {
            $error = "File upload failed.";
        }
    }

    if (empty($submission_text) && empty($file_path)) {
        $error = "Please provide either text or a file.";
    } else {
        $insert = "INSERT INTO homework_submissions (homework_id, student_id, submission_text, file_path) 
                   VALUES ($homework_id, $student_id, '$submission_text', '$file_path')";
        if (mysqli_query($conn, $insert)) {
            $message = "Homework submitted successfully.";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Homework</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Submit Homework: <?php echo $hw['title']; ?></h1>
        <p><strong>Subject:</strong> <?php echo $hw['subject_name']; ?><br>
        <strong>Due Date:</strong> <?php echo $hw['due_date']; ?></p>
        <p><strong>Description:</strong> <?php echo nl2br($hw['description']); ?></p>
        <p><a href="view_homework.php">← Back to Homework List</a></p>

        <?php if ($message): ?><div class="success"><?php echo $message; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Your Answer / Text:</label>
            <textarea name="submission_text" rows="6"></textarea>

            <label>Upload File (optional):</label>
            <input type="file" name="file">

            <input type="submit" value="Submit Homework">
        </form>
    </div>
</body>
</html>