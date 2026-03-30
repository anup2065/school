<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}
include '../config.php';

$student_id = $_SESSION['student_id'];

$sql = "SELECT * FROM results WHERE student_id = $student_id ORDER BY exam_name, subject";
$result = mysqli_query($conn, $sql);

// Group by exam for better display
$results_by_exam = [];
while ($row = mysqli_fetch_assoc($result)) {
    $results_by_exam[$row['exam_name']][] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Results</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>My Results</h1>
        <p><a href="dashboard.php">← Back to Dashboard</a></p>

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
                <p><a href="gradesheet.php?exam=<?php echo urlencode($exam); ?>">
                View gradesheet for <?php echo htmlspecialchars($exam); ?>
            </a></p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>