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

// Fetch all teachers for dropdown
$teachers_sql = "SELECT id, name FROM teachers ORDER BY name";
$teachers_result = mysqli_query($conn, $teachers_sql);

// Fetch all subjects from subjects table
$subjects_sql = "SELECT id, subject_name FROM subjects ORDER BY subject_name";
$subjects_result = mysqli_query($conn, $subjects_sql);

// For simplicity, get distinct class and section from students (or you can maintain a separate classes table)
$classes_sql = "SELECT DISTINCT class FROM students ORDER BY class";
$classes_result = mysqli_query($conn, $classes_sql);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $class = mysqli_real_escape_string($conn, $_POST['class']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);

    // Get subject name from subjects table (optional, but we store subject_id only)
    $subj_sql = "SELECT subject_name FROM subjects WHERE id = $subject_id";
    $subj_res = mysqli_query($conn, $subj_sql);
    $subj_row = mysqli_fetch_assoc($subj_res);
    $subject_name = $subj_row['subject_name'];

    if (empty($teacher_id) || empty($class) || empty($section) || empty($subject_id)) {
        $error = "All fields are required.";
    } else {
        // Insert into teacher_classes
        $sql = "INSERT INTO teacher_classes (teacher_id, class, section, subject) 
                VALUES ('$teacher_id', '$class', '$section', '$subject_name')"; // storing subject name for simplicity; can store subject_id if you prefer
        if (mysqli_query($conn, $sql)) {
            $message = "Class assigned successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

// Optional: Fetch existing assignments to display below
$assignments_sql = "SELECT tc.*, t.name as teacher_name FROM teacher_classes tc 
                    JOIN teachers t ON tc.teacher_id = t.id 
                    ORDER BY tc.class, tc.section";
$assignments_result = mysqli_query($conn, $assignments_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Class to Teacher</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Assign Class to Teacher</h1>
        <p><a href="view_teachers.php">← Back to Teacher List</a></p>
        
        <?php if ($message != ''): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Select Teacher:</label>
            <select name="teacher_id" id="teacher_id" required>
                <option value="">-- Select Teacher --</option>
                <?php while ($teacher = mysqli_fetch_assoc($teachers_result)): ?>
                    <option value="<?php echo $teacher['id']; ?>"><?php echo $teacher['name']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Class:</label>
            <select name="class" id="class" required>
                <option value="">-- Select Class --</option>
                <?php while ($class = mysqli_fetch_assoc($classes_result)): ?>
                    <option value="<?php echo $class['class']; ?>"><?php echo $class['class']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Section:</label>
            <input type="text" name="section" id="section" placeholder="e.g. A" required>
            
            <label>Subject:</label>
            <select name="subject_id" required>
                <option value="">-- Select Subject --</option>
                <?php while ($subject = mysqli_fetch_assoc($subjects_result)): ?>
                    <option value="<?php echo $subject['id']; ?>"><?php echo $subject['subject_name']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <input type="submit" value="Assign">
        </form>
        
        <h2>Current Assignments</h2>
        <?php if (mysqli_num_rows($assignments_result) > 0): ?>
            <table>
                <tr>
                    <th>Teacher</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Subject</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($assignments_result)): ?>
                <tr>
                    <td><?php echo $row['teacher_name']; ?></td>
                    <td><?php echo $row['class']; ?></td>
                    <td><?php echo $row['section']; ?></td>
                    <td><?php echo $row['subject']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No assignments yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>