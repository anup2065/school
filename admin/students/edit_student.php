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

// Get student ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    header("Location: view_students.php");
    exit();
}

// Fetch current data
$sql = "SELECT * FROM students WHERE id = $id";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) != 1) {
    header("Location: view_students.php");
    exit();
}
$student = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $class = mysqli_real_escape_string($conn, $_POST['class']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $roll_no = mysqli_real_escape_string($conn, $_POST['roll_no']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // may be empty

    if (empty($student_id) || empty($name) || empty($class)) {
        $error = "Student ID, Name and Class are required.";
    } else {
        // Build update query (include password only if provided)
        if (!empty($password)) {
            $update_sql = "UPDATE students SET student_id='$student_id', name='$name', email='$email', phone='$phone', class='$class', section='$section', roll_no='$roll_no', address='$address', password='$password' WHERE id=$id";
        } else {
            $update_sql = "UPDATE students SET student_id='$student_id', name='$name', email='$email', phone='$phone', class='$class', section='$section', roll_no='$roll_no', address='$address' WHERE id=$id";
        }
        
        if (mysqli_query($conn, $update_sql)) {
            $message = "Student updated successfully!";
            // Refresh data
            $result = mysqli_query($conn, "SELECT * FROM students WHERE id = $id");
            $student = mysqli_fetch_assoc($result);
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Edit Student</h1>
        <p><a href="view_students.php">← Back to Student List</a></p>
        
        <?php if ($message != ''): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Student ID:</label>
            <input type="text" name="student_id" id="student_id" value="<?php echo $student['student_id']; ?>" required>
            
            <label>Full Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $student['name']; ?>" required>
            
            <label>Email:</label>
            <input type="email" name="email" id="email" value="<?php echo $student['email']; ?>">
            
            <label>Phone:</label>
            <input type="text" name="phone" id="phone" value="<?php echo $student['phone']; ?>">
            
            <label>Class:</label>
            <input type="text" name="class" id="class" value="<?php echo $student['class']; ?>" required>
            
            <label>Section:</label>
            <input type="text" name="section" id="section" value="<?php echo $student['section']; ?>">
            
            <label>Roll Number:</label>
            <input type="number" name="roll_no" id="roll_no" value="<?php echo $student['roll_no']; ?>">
            
            <label>Address:</label>
            <textarea name="address"id="address" ><?php echo $student['address']; ?></textarea>
            
            <label>New Password (leave blank to keep current):</label>
            <input type="text" name="password" id="password" placeholder="Enter new password if you want to change">
            
            <input type="submit" value="Update Student">
        </form>
    </div>
</body>
</html>