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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_name = mysqli_real_escape_string($conn, $_POST['subject_name']);

    if (empty($subject_name)) {
        $error = "Subject name is required.";
    } else {
        $sql = "INSERT INTO subjects (subject_name) VALUES ('$subject_name')";
        if (mysqli_query($conn, $sql)) {
            $message = "Subject added successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Subject</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Add New Subject</h1>
        <p><a href="view_subjects.php">← Back to Subject List</a></p>
        
        <?php if ($message != ''): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Subject Name:</label>
            <input type="text" name="subject_name" required>
            <input type="submit" value="Add Subject">
        </form>
    </div>
</body>
</html>