<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$teacher_id = $_SESSION['teacher_id'];
$message = '';
$error = '';

// Get teacher's assigned classes/subjects
$assignments_sql = "SELECT DISTINCT class, section, subject FROM teacher_classes WHERE teacher_id = $teacher_id ORDER BY class, section";
$assignments_res = mysqli_query($conn, $assignments_sql);

// Fetch subjects for dropdown (optional, if we want to link to subjects table)
$subjects_sql = "SELECT id, subject_name FROM subjects ORDER BY subject_name";
$subjects_res = mysqli_query($conn, $subjects_sql);

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
        $sql = "INSERT INTO homework (teacher_id, class, section, subject_id, title, description, due_date) 
                VALUES ($teacher_id, '$class', '$section', $subject_id, '$title', '$description', '$due_date')";
        if (mysqli_query($conn, $sql)) {
            $message = "Homework posted successfully.";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Post Homework</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Post Homework</h1>
        <p><a href="view_homework.php">← View All Homework</a> | <a href="../dashboard.php">Dashboard</a></p>

        <?php if ($message): ?><div class="success"><?php echo $message; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>

        <form method="POST">
            <label>Class:</label>
            <select name="class" id="class" required>
                <option value="">-- Select --</option>
                <?php 
                mysqli_data_seek($assignments_res, 0);
                $seen = [];
                while ($a = mysqli_fetch_assoc($assignments_res)) {
                    if (!in_array($a['class'], $seen)) {
                        echo "<option value='{$a['class']}'>{$a['class']}</option>";
                        $seen[] = $a['class'];
                    }
                }
                ?>
            </select>

            <label>Section:</label>
            <input type="text" name="section" id="section" required>

            <label>Subject:</label>
            <select name="subject_id" required>
                <option value="">-- Select Subject --</option>
                <?php while ($sub = mysqli_fetch_assoc($subjects_res)): ?>
                    <option value="<?php echo $sub['id']; ?>"><?php echo $sub['subject_name']; ?></option>
                <?php endwhile; ?>
            </select>

            <label>Title:</label>
            <input type="text" name="title" id="title" required>

            <label>Description:</label>
            <textarea name="description" id="description" rows="5"></textarea>

            <label>Due Date:</label>
            <input type="date" name="due_date" id="due_date" required>

            <input type="submit" value="Post Homework">
        </form>
    </div>
</body>
</html>