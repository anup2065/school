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
    header("Location: view_subjects.php");
    exit();
}

$sql = "SELECT * FROM subjects WHERE id = $id";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) != 1) {
    header("Location: view_subjects.php");
    exit();
}
$subject = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_name = mysqli_real_escape_string($conn, $_POST['subject_name']);
    if (empty($subject_name)) {
        $error = "Subject name is required.";
    } else {
        $update_sql = "UPDATE subjects SET subject_name='$subject_name' WHERE id=$id";
        if (mysqli_query($conn, $update_sql)) {
            $message = "Subject updated successfully!";
            $subject['subject_name'] = $subject_name; // refresh display
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Subject</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Edit Subject</h1>
        <p><a href="view_subjects.php">← Back to Subject List</a></p>
        
        <?php if ($message != ''): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Subject Name:</label>
            <input type="text" name="subject_name" value="<?php echo $subject['subject_name']; ?>" required>
            <input type="submit" value="Update Subject">
        </form>
    </div>
</body>
</html>