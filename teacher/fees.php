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
$classes_sql = "SELECT DISTINCT class, section FROM teacher_classes WHERE teacher_id = $teacher_id";
$classes_res = mysqli_query($conn, $classes_sql);

// If classes exist, fetch students in those classes
$students_in_my_classes = [];
while ($row = mysqli_fetch_assoc($classes_res)) {
    $class = $row['class'];
    $section = $row['section'];
    $students_sql = "SELECT id FROM students WHERE class='$class' AND section='$section'";
    $students_res = mysqli_query($conn, $students_sql);
    while ($s = mysqli_fetch_assoc($students_res)) {
        $students_in_my_classes[] = $s['id'];
    }
}

// If no students, show empty
if (empty($students_in_my_classes)) {
    $fee_data = [];
} else {
    $student_ids = implode(',', $students_in_my_classes);
    $fee_sql = "SELECT f.*, s.name, s.student_id, s.class, s.section 
                FROM fees f 
                JOIN students s ON f.student_id = s.id 
                WHERE f.student_id IN ($student_ids)
                ORDER BY s.class, s.name, f.due_date DESC";
    $fee_res = mysqli_query($conn, $fee_sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fee Status</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Fee Status of My Students</h1>
        <p><a href="dashboard.php">← Back to Dashboard</a></p>

        <?php if (empty($students_in_my_classes)): ?>
            <p class="error">No students in your classes.</p>
        <?php elseif (mysqli_num_rows($fee_res) == 0): ?>
            <p>No fee records found for your students.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Paid Date</th>
                    <th>Status</th>
                </tr>
                <?php while ($fee = mysqli_fetch_assoc($fee_res)): ?>
                <tr>
                    <td><?php echo $fee['student_id']; ?></td>
                    <td><?php echo $fee['name']; ?></td>
                    <td><?php echo $fee['class']; ?></td>
                    <td><?php echo $fee['section']; ?></td>
                    <td>Rs <?php echo $fee['amount']; ?></td>
                    <td><?php echo $fee['due_date']; ?></td>
                    <td><?php echo $fee['paid_date'] ?? '-'; ?></td>
                    <td>
                        <?php if ($fee['status'] == 'paid'): ?>
                            <span style="color:green;">Paid</span>
                        <?php else: ?>
                            <span style="color:red;">Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>