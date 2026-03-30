<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
include '../../config.php';

$message = '';
$error = '';

// Fetch all students for dropdown
$students_sql = "SELECT id, name, student_id, class, section FROM students ORDER BY class, name";
$students_result = mysqli_query($conn, $students_sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    if (empty($student_id) || empty($amount) || empty($due_date)) {
        $error = "Student, Amount and Due Date are required.";
    } else {
        $sql = "INSERT INTO fees (student_id, amount, due_date, status, remarks) 
                VALUES ('$student_id', '$amount', '$due_date', 'pending', '$remarks')";
        if (mysqli_query($conn, $sql)) {
            $message = "Fee record added successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Fee Record</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Add New Fee Record</h1>
        <p><a href="view_fees.php">← Back to Fee List</a></p>
        
        <?php if ($message != ''): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Select Student:</label>
            <select name="student_id" id="student_id" required>
                <option value="">-- Select Student --</option>
                <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                    <option value="<?php echo $student['id']; ?>">
                        <?php echo $student['name']; ?> (<?php echo $student['student_id']; ?>) - Class <?php echo $student['class']; ?> <?php echo $student['section']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <label>Amount (Rs):</label>
            <input type="number" step="0.01" name="amount" id="amount" required>
            
            <label>Due Date:</label>
            <input type="date" name="due_date" id="due_date" required>
            
            <label>Remarks (optional):</label>
            <textarea name="remarks"id="remarks" ></textarea>
            
            <input type="submit" value="Add Fee Record">
        </form>
    </div>
</body>
</html>