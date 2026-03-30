<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

// Filtering options
$class_filter = isset($_GET['class']) ? $_GET['class'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$student_filter = isset($_GET['student_id']) ? $_GET['student_id'] : '';

// Build query with filters
$sql = "SELECT f.*, s.name as student_name, s.student_id as student_code, s.class, s.section 
        FROM fees f 
        JOIN students s ON f.student_id = s.id 
        WHERE 1=1";
if (!empty($class_filter)) {
    $class_filter = mysqli_real_escape_string($conn, $class_filter);
    $sql .= " AND s.class = '$class_filter'";
}
if (!empty($status_filter)) {
    $status_filter = mysqli_real_escape_string($conn, $status_filter);
    $sql .= " AND f.status = '$status_filter'";
}
if (!empty($student_filter)) {
    $student_filter = mysqli_real_escape_string($conn, $student_filter);
    $sql .= " AND f.student_id = '$student_filter'";
}
$sql .= " ORDER BY f.due_date DESC, s.class, s.name";
$result = mysqli_query($conn, $sql);

// Fetch distinct classes for filter dropdown
$classes_sql = "SELECT DISTINCT class FROM students ORDER BY class";
$classes_result = mysqli_query($conn, $classes_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Fees</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Fee Records</h1>
        <p><a href="add_fee.php">➕ Add New Fee Record</a> | <a href="fee_reports.php">📈 Fee Reports</a> | <a href="../dashboard.php">Back to Dashboard</a></p>
        
        <!-- Filter Form -->
        <form method="GET" action="" style="margin-bottom:20px; padding:15px; background:#f9f9f9; border-radius:5px;">
            <label>Filter by Class:</label>
            <select name="class"id="class" >
                <option value="">All Classes</option>
                <?php while ($c = mysqli_fetch_assoc($classes_result)): ?>
                    <option value="<?php echo $c['class']; ?>" <?php if($class_filter == $c['class']) echo 'selected'; ?>><?php echo $c['class']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Filter by Status:</label>
            <select name="status">
                <option value="">All</option>
                <option value="paid" <?php if($status_filter == 'paid') echo 'selected'; ?>>Paid</option>
                <option value="pending" <?php if($status_filter == 'pending') echo 'selected'; ?>>Pending</option>
            </select>
            
            <input type="submit" value="Filter">
        </form>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Paid Date</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['student_name']; ?> (<?php echo $row['student_code']; ?>)</td>
                    <td><?php echo $row['class']; ?>-<?php echo $row['section']; ?></td>
                    <td>Rs <?php echo $row['amount']; ?></td>
                    <td><?php echo $row['due_date']; ?></td>
                    <td><?php echo $row['paid_date'] ?? '-'; ?></td>
                    <td>
                        <?php if ($row['status'] == 'paid'): ?>
                            <span style="color:green; font-weight:bold;">Paid</span>
                        <?php else: ?>
                            <span style="color:red; font-weight:bold;">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['remarks'] ?? '-'; ?></td>
                    <td>
                        <?php if ($row['status'] == 'pending'): ?>
                            <a href="update_fee.php?id=<?php echo $row['id']; ?>">Mark as Paid</a> | 
                        <?php endif; ?>
                        <a href="delete_fee.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No fee records found. <a href="add_fee.php">Add one now</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>