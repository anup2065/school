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

$check_sql = "SELECT * FROM parent_students WHERE parent_id = $parent_id AND student_id = $student_id";
$check_res = mysqli_query($conn, $check_sql);
if (mysqli_num_rows($check_res) == 0) {
    header("Location: dashboard.php");
    exit();
}

$student_sql = "SELECT name FROM students WHERE id = $student_id";
$student_res = mysqli_query($conn, $student_sql);
$student = mysqli_fetch_assoc($student_res);

$fees_sql = "SELECT * FROM fees WHERE student_id = $student_id ORDER BY due_date DESC";
$fees_res = mysqli_query($conn, $fees_sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fee Status - <?php echo $student['name']; ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Fee Records for <?php echo $student['name']; ?></h1>
        <p><a href="view_child.php?student_id=<?php echo $student_id; ?>">← Back to Child Dashboard</a></p>

        <?php if (mysqli_num_rows($fees_res) > 0): ?>
            <table>
                <tr>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Paid Date</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($fees_res)): ?>
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