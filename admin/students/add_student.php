<?php
// Start session and check admin login
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data (basic validation)
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $class = mysqli_real_escape_string($conn, $_POST['class']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $roll_no = mysqli_real_escape_string($conn, $_POST['roll_no']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // plain text for now

    // Check required fields
    if (empty($student_id) || empty($name) || empty($class) || empty($password)) {
        $error = "Student ID, Name, Class and Password are required.";
    } else {
        // Insert into database
        $sql = "INSERT INTO students (student_id, name, email, phone, class, section, roll_no, address, password) 
                VALUES ('$student_id', '$name', '$email', '$phone', '$class', '$section', '$roll_no', '$address', '$password')";
        
        if (mysqli_query($conn, $sql)) {
            $message = "Student added successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Add New Student</h1>
        <p><a href="view_students.php">← Back to Student List</a></p>
        
        <?php if ($message != ''): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Student ID (unique):</label>
            <input type="text" name="student_id" id="student_id" required>
            
            <label>Full Name:</label>
            <input type="text" name="name" id="name" required>
            
            <label>Email:</label>
            <input type="email" name="email"id="email" >
            
            <label>Phone:</label>
            <input type="text" name="phone"id="phone" >
            
            <label>Class:</label>
            <input type="text" name="class" id="class" placeholder="e.g. 10" required>
            
            <label>Section:</label>
            <input type="text" name="section" id="section" placeholder="e.g. A">
            
            <label>Roll Number:</label>
            <input type="number" name="roll_no"id="roll_no" >
            
            <label>Address:</label>
            <textarea name="address"id="address" ></textarea>
            
            <label>Password (default):</label>
            <input type="text" name="password" id="password" value="student123" required>
                        
            <input type="submit" value="Add Student">
        </form>
    </div>
</body>
</html>