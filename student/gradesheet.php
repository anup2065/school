<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}
include '../config.php';

// always use the logged‑in student id; do not trust or require a GET value
$student_id = $_SESSION['student_id'];
$exam_name = isset($_GET['exam']) ? mysqli_real_escape_string($conn, $_GET['exam']) : '';

if (empty($exam_name)) {
    header("Location: class_result.php");
    exit();
}

// Get student details
$student_sql = "SELECT * FROM students WHERE id = $student_id";
$student_res = mysqli_query($conn, $student_sql);
if (mysqli_num_rows($student_res) == 0) {
    header("Location: class_result.php");
    exit();
}
$student = mysqli_fetch_assoc($student_res);

// Get results for this student and exam
$results_sql = "SELECT * FROM results WHERE student_id = $student_id AND exam_name = '$exam_name'";
$results_res = mysqli_query($conn, $results_sql);

$subjects = [];
$total_obtained = 0;
$total_max = 0;
while ($r = mysqli_fetch_assoc($results_res)) {
    $subjects[] = $r;
    $total_obtained += $r['marks'];
    $total_max += $r['total_marks'];
}
$percentage = ($total_max > 0) ? ($total_obtained / $total_max) * 100 : 0;

// GPA function (same as before)
function calculateGPA($percentage) {
    if ($percentage >= 90) return 4.0;
    if ($percentage >= 80) return 3.6;
    if ($percentage >= 70) return 3.2;
    if ($percentage >= 60) return 2.8;
    if ($percentage >= 50) return 2.4;
    if ($percentage >= 40) return 2.0;
    if ($percentage >= 33) return 1.6;
    return 0.0;
}
$gpa = calculateGPA($percentage);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Result</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .result-box { max-width: 600px; margin: auto; border: 1px solid #ccc; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        .details table { width: 100%; }
        .marks-table { width: 100%; border-collapse: collapse; }
        .marks-table th, .marks-table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .total-row { font-weight: bold; background: #f0f0f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="result-box">
            <div class="header">
                <h2>Student Result Sheet</h2>
                <h3><?php echo $exam_name; ?></h3>
            </div>
            <div class="details">
                <table>
                    <tr><td><strong>Name:</strong></td><td><?php echo $student['name']; ?></td></tr>
                    <tr><td><strong>Student ID:</strong></td><td><?php echo $student['student_id']; ?></td></tr>
                    <tr><td><strong>Class:</strong></td><td><?php echo $student['class'] . ' ' . $student['section']; ?></td></tr>
                    <tr><td><strong>Roll No:</strong></td><td><?php echo $student['roll_no']; ?></td></tr>
                </table>
            </div>
            <h4>Marks Obtained</h4>
            <table class="marks-table">
                <tr>
                    <th>Subject</th>
                    <th>Full Marks</th>
                    <th>Obtained Marks</th>
                </tr>
                <?php foreach ($subjects as $sub): ?>
                <tr>
                    <td><?php echo $sub['subject']; ?></td>
                    <td><?php echo $sub['total_marks']; ?></td>
                    <td><?php echo $sub['marks']; ?></td>
                    
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td><strong>Total</strong></td>
                    <td><strong><?php echo $total_max; ?></strong></td>
                    <td><strong><?php echo $total_obtained; ?></strong></td>
                    
                </tr>
                <tr>
                    <td colspan="3"><strong>Percentage: <?php echo number_format($percentage, 2); ?>%</strong></td>
                </tr>
                <tr>
                    <td colspan="3"><strong>GPA: <?php echo number_format($gpa, 2); ?></strong></td>
                </tr>
            </table>
                    <a href="results.php">← Back to Results</a>
        </div>
    </div>
</body>
</html>