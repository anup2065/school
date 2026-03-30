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
$student_filter = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$exam_filter = isset($_GET['exam_name']) ? $_GET['exam_name'] : '';

// Build query with filters
$sql = "SELECT r.*, s.name as student_name, s.student_id as student_code 
        FROM results r 
        JOIN students s ON r.student_id = s.id 
        WHERE 1=1";
if (!empty($class_filter)) {
    $class_filter = mysqli_real_escape_string($conn, $class_filter);
    $sql .= " AND r.class = '$class_filter'";
}
if (!empty($student_filter)) {
    $student_filter = mysqli_real_escape_string($conn, $student_filter);
    $sql .= " AND r.student_id = '$student_filter'";
}
if (!empty($exam_filter)) {
    $exam_filter = mysqli_real_escape_string($conn, $exam_filter);
    $sql .= " AND r.exam_name = '$exam_filter'";
}
$sql .= " ORDER BY r.class, s.name, r.exam_name";
$result = mysqli_query($conn, $sql);

// Fetch distinct classes and exam names for filter dropdowns
$classes_sql = "SELECT DISTINCT class FROM students ORDER BY class";
$classes_result = mysqli_query($conn, $classes_sql);
$exams_sql = "SELECT DISTINCT exam_name FROM results ORDER BY exam_name";
$exams_result = mysqli_query($conn, $exams_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Results</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Results List</h1>
        <p><a href="add_result.php">➕ Add New Result</a> | <a href="../dashboard.php">Back to Dashboard</a></p>
        
        <!-- Filter Form -->
        <form method="GET" action="" style="margin-bottom:20px; padding:15px; background:#f9f9f9; border-radius:5px;">
            <label>Filter by Class:</label>
            <select name="class"id="class" >
                <option value="">All Classes</option>
                <?php while ($c = mysqli_fetch_assoc($classes_result)): ?>
                    <option value="<?php echo $c['class']; ?>" <?php if($class_filter == $c['class']) echo 'selected'; ?>><?php echo $c['class']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Filter by Exam:</label>
            <select name="exam_name"id="exam_name" >
                <option value="">All Exams</option>
                <?php while ($e = mysqli_fetch_assoc($exams_result)): ?>
                    <option value="<?php echo $e['exam_name']; ?>" <?php if($exam_filter == $e['exam_name']) echo 'selected'; ?>><?php echo $e['exam_name']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <input type="submit" value="Filter">
        </form>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Exam</th>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Total</th>
                    <th>Percentage</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): 
                    $percentage = ($row['marks'] / $row['total_marks']) * 100;
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['student_name']; ?> (<?php echo $row['student_code']; ?>)</td>
                    <td><?php echo $row['class']; ?></td>
                    <td><?php echo $row['exam_name']; ?></td>
                    <td><?php echo $row['subject']; ?></td>
                    <td><?php echo $row['marks']; ?></td>
                    <td><?php echo $row['total_marks']; ?></td>
                    <td><?php echo number_format($percentage, 2); ?>%</td>
                    <td>
                        <a href="edit_result.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                        <a href="delete_result.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No results found. <a href="add_result.php">Add one now</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>