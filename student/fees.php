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

$sql = "SELECT * FROM fees WHERE student_id = $student_id ORDER BY due_date DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fee Status</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>My Fee Records</h1>
        <p><a href="dashboard.php">← Back to Dashboard</a></p>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Paid Date</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>Rs <?php echo $row['amount']; ?></td>
                    <td><?php echo $row['due_date']; ?></td>
                    <td><?php echo $row['paid_date'] ?? '-'; ?></td>
                    <td>
                        <?php if ($row['status'] == 'paid'): ?>
                            <span style="color:green;">Paid</span>
                        <?php else: ?>
                            <span style="color:red;">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['remarks'] ?? '-'; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No fee records found.</p>
        <?php endif; ?>
    </div>
</body>
</html>