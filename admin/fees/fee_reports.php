<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

// Summary by class
$class_summary_sql = "SELECT s.class, 
                             COUNT(DISTINCT s.id) as total_students,
                             SUM(CASE WHEN f.status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                             SUM(CASE WHEN f.status = 'paid' THEN 1 ELSE 0 END) as paid_count,
                             SUM(CASE WHEN f.status = 'pending' THEN f.amount ELSE 0 END) as pending_amount,
                             SUM(CASE WHEN f.status = 'paid' THEN f.amount ELSE 0 END) as paid_amount
                      FROM students s
                      LEFT JOIN fees f ON s.id = f.student_id
                      GROUP BY s.class
                      ORDER BY s.class";
$class_summary = mysqli_query($conn, $class_summary_sql);

// Overall summary
$overall_sql = "SELECT 
                    COUNT(DISTINCT student_id) as students_with_fees,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as total_pending,
                    SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as total_paid,
                    SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) as total_pending_amount,
                    SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) as total_collected
                FROM fees";
$overall_result = mysqli_query($conn, $overall_sql);
$overall = mysqli_fetch_assoc($overall_result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fee Reports</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Fee Reports</h1>
        <p><a href="view_fees.php">← Back to Fee List</a> | <a href="../dashboard.php">Dashboard</a></p>
        
        <h2>Overall Summary</h2>
        <table>
            <tr>
                <th>Total Pending Fees (Count)</th>
                <td><?php echo $overall['total_pending'] ?? 0; ?></td>
            </tr>
            <tr>
                <th>Total Paid Fees (Count)</th>
                <td><?php echo $overall['total_paid'] ?? 0; ?></td>
            </tr>
            <tr>
                <th>Total Pending Amount (Rs)</th>
                <td>Rs <?php echo number_format($overall['total_pending_amount'] ?? 0, 2); ?></td>
            </tr>
            <tr>
                <th>Total Collected Amount (Rs)</th>
                <td>Rs <?php echo number_format($overall['total_collected'] ?? 0, 2); ?></td>
            </tr>
        </table>
        
        <h2>Class-wise Fee Summary</h2>
        <table>
            <tr>
                <th>Class</th>
                <th>Total Students</th>
                <th>Pending Count</th>
                <th>Paid Count</th>
                <th>Pending Amount (Rs)</th>
                <th>Collected Amount (Rs)</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($class_summary)): ?>
            <tr>
                <td><?php echo $row['class']; ?></td>
                <td><?php echo $row['total_students']; ?></td>
                <td><?php echo $row['pending_count']; ?></td>
                <td><?php echo $row['paid_count']; ?></td>
                <td>Rs <?php echo number_format($row['pending_amount'], 2); ?></td>
                <td>Rs <?php echo number_format($row['paid_amount'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <h2>Top Defaulters (Students with highest pending fees)</h2>
        <?php
        $defaulters_sql = "SELECT s.id, s.name, s.student_id, s.class, s.section, SUM(f.amount) as total_pending
                           FROM students s
                           JOIN fees f ON s.id = f.student_id
                           WHERE f.status = 'pending'
                           GROUP BY s.id
                           ORDER BY total_pending DESC
                           LIMIT 10";
        $defaulters = mysqli_query($conn, $defaulters_sql);
        if (mysqli_num_rows($defaulters) > 0): ?>
        <table>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Class</th>
                <th>Section</th>
                <th>Total Pending (Rs)</th>
            </tr>
            <?php while ($d = mysqli_fetch_assoc($defaulters)): ?>
            <tr>
                <td><?php echo $d['student_id']; ?></td>
                <td><?php echo $d['name']; ?></td>
                <td><?php echo $d['class']; ?></td>
                <td><?php echo $d['section']; ?></td>
                <td>Rs <?php echo number_format($d['total_pending'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>No defaulters found.</p>
        <?php endif; ?>
    </div>
</body>
</html>