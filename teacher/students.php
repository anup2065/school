<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}
include '../config.php';

$teacher_id = $_SESSION['teacher_id'];

// Get assigned classes
$classes_sql = "SELECT class, section, subject FROM teacher_classes WHERE teacher_id = $teacher_id";
$classes_res = mysqli_query($conn, $classes_sql);

// Build array of class-section pairs
$class_sections = [];
while ($row = mysqli_fetch_assoc($classes_res)) {
    $class_sections[] = $row;
}

// If no classes, show message
if (empty($class_sections)) {
    $no_classes = true;
} else {
    // Build a WHERE clause to get students from any of these classes
    $conditions = [];
    foreach ($class_sections as $cs) {
        $conditions[] = "(class = '{$cs['class']}' AND section = '{$cs['section']}')";
    }
    $where = implode(" OR ", $conditions);
    $students_sql = "SELECT * FROM students WHERE $where ORDER BY class, section, roll_no";
    $students_res = mysqli_query($conn, $students_sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Students</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Students in My Classes</h1>
        <p><a href="dashboard.php">← Back to Dashboard</a></p>

        <?php if (isset($no_classes)): ?>
            <p class="error">You have no assigned classes.</p>
        <?php elseif (mysqli_num_rows($students_res) == 0): ?>
            <p>No students found in your classes.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Roll No</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
                <?php while ($student = mysqli_fetch_assoc($students_res)): ?>
                <tr>
                    <td><?php echo $student['student_id']; ?></td>
                    <td><?php echo $student['name']; ?></td>
                    <td><?php echo $student['class']; ?></td>
                    <td><?php echo $student['section']; ?></td>
                    <td><?php echo $student['roll_no']; ?></td>
                    <td><?php echo $student['email']; ?></td>
                    <td><?php echo $student['phone']; ?></td>
                    <td>
                        <a href="enter_marks.php?student_id=<?php echo $student['id']; ?>">Enter Marks</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>