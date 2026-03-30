<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['parent_id'])) {
    header("Location: login.php");
    exit();
}
include '../config.php';

$parent_id = $_SESSION['parent_id'];
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;

// Verify parent-student link
$check_sql = "SELECT * FROM parent_students WHERE parent_id = $parent_id AND student_id = $student_id";
$check_res = mysqli_query($conn, $check_sql);
if (mysqli_num_rows($check_res) == 0) {
    header("Location: dashboard.php");
    exit();
}

// Get student name
$student_sql = "SELECT name FROM students WHERE id = $student_id";
$student_res = mysqli_query($conn, $student_sql);
$student = mysqli_fetch_assoc($student_res);

// Get results
$results_sql = "SELECT * FROM results WHERE student_id = $student_id ORDER BY exam_name, subject";
$results_res = mysqli_query($conn, $results_sql);

$results_by_exam = [];
while ($row = mysqli_fetch_assoc($results_res)) {
    $results_by_exam[$row['exam_name']][] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Results - <?php echo $student['name']; ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Results for <?php echo $student['name']; ?></h1>
        <p><a href="view_child.php?student_id=<?php echo $student_id; ?>">← Back to Child Dashboard</a></p>

        <?php if (empty($results_by_exam)): ?>
            <p>No results found.</p>
        <?php else: ?>
            <?php foreach ($results_by_exam as $exam => $subjects): ?>
                <h2><?php echo $exam; ?></h2>
                <table>
                    <tr>
                        <th>Subject</th>
                        <th>Marks Obtained</th>
                        <th>Total Marks</th>
                        <th>Percentage</th>
                    </tr>
                    <?php 
                    $total_obtained = 0;
                    $total_max = 0;
                    foreach ($subjects as $s): 
                        $total_obtained += $s['marks'];
                        $total_max += $s['total_marks'];
                    ?>
                    <tr>
                        <td><?php echo $s['subject']; ?></td>
                        <td><?php echo $s['marks']; ?></td>
                        <td><?php echo $s['total_marks']; ?></td>
                        <td><?php echo number_format(($s['marks']/$s['total_marks'])*100, 2); ?>%</td>
                    </tr>
                    <?php endforeach; ?>
                    <tr style="font-weight:bold; background:#f0f0f0;">
                        <td>Total</td>
                        <td><?php echo $total_obtained; ?></td>
                        <td><?php echo $total_max; ?></td>
                        <td><?php echo number_format(($total_obtained/$total_max)*100, 2); ?>%</td>
                    </tr>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>