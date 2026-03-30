<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    header("Location: view_homework.php");
    exit();
}

// Ensure ownership
$check_sql = "SELECT * FROM homework WHERE id = $id AND teacher_id = {$_SESSION['teacher_id']}";
$check_res = mysqli_query($conn, $check_sql);
if (mysqli_num_rows($check_res) == 0) {
    header("Location: view_homework.php");
    exit();
}
$hw = mysqli_fetch_assoc($check_res);

// Fetch subjects
$subjects_sql = "SELECT id, subject_name FROM subjects";
$subjects_res = mysqli_query($conn, $subjects_sql);

$message = $error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class = mysqli_real_escape_string($conn, $_POST['class']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);

    if (empty($class) || empty($section) || empty($subject_id) || empty($title) || empty($due_date)) {
        $error = "All fields are required.";
    } else {
        $update = "UPDATE homework SET class='$class', section='$section', subject_id=$subject_id, title='$title', description='$description', due_date='$due_date' WHERE id=$id";
        if (mysqli_query($conn, $update)) {
            $message = "Homework updated.";
            // refresh
            $check_res = mysqli_query($conn, "SELECT * FROM homework WHERE id=$id");
            $hw = mysqli_fetch_assoc($check_res);
        } else {
            $error = "Error updating.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Homework</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Edit Homework</h1>
        <p><a href="view_homework.php">← Back</a></p>
        <?php if ($message): ?><div class="success"><?php echo $message; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <label>Class:</label>
            <input type="text" name="class" id="class" value="<?php echo $hw['class']; ?>" required>

            <label>Section:</label>
            <input type="text" name="section" id="section" value="<?php echo $hw['section']; ?>" required>

            <label>Subject:</label>
            <select name="subject_id" required>
                <?php while ($sub = mysqli_fetch_assoc($subjects_res)): ?>
                    <option value="<?php echo $sub['id']; ?>" <?php if($sub['id'] == $hw['subject_id']) echo 'selected'; ?>><?php echo $sub['subject_name']; ?></option>
                <?php endwhile; ?>
            </select>

            <label>Title:</label>
            <input type="text" name="title" id="title" value="<?php echo $hw['title']; ?>" required>

            <label>Description:</label>
            <textarea name="description" id="description" rows="5"><?php echo $hw['description']; ?></textarea>

            <label>Due Date:</label>
            <input type="date" name="due_date" id="due_date" value="<?php echo $hw['due_date']; ?>" required>

            <input type="submit" value="Update Homework">
        </form>
    </div>
</body>
</html>