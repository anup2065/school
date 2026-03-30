<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$parent_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($parent_id == 0) {
    header("Location: view_parents.php");
    exit();
}

// Get parent name
$parent_sql = "SELECT name FROM parents WHERE id = $parent_id";
$parent_result = mysqli_query($conn, $parent_sql);
$parent = mysqli_fetch_assoc($parent_result);

// Get linked students
$students_sql = "SELECT s.* FROM students s
                 JOIN parent_students ps ON s.id = ps.student_id
                 WHERE ps.parent_id = $parent_id";
$students_result = mysqli_query($conn, $students_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Children of <?php echo $parent['name']; ?></title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Children of <?php echo $parent['name']; ?></h1>
        <p><a href="view_parents.php">← Back to Parent List</a></p>
        
        <?php if (mysqli_num_rows($students_result) > 0): ?>
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Roll No</th>
                </tr>
                <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                <tr>
                    <td><?php echo $student['student_id']; ?></td>
                    <td><?php echo $student['name']; ?></td>
                    <td><?php echo $student['class']; ?></td>
                    <td><?php echo $student['section']; ?></td>
                    <td><?php echo $student['roll_no']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No children linked to this parent.</p>
        <?php endif; ?>
    </div>
</body>
</html>