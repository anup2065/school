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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // plain text for now

    if (empty($teacher_id) || empty($name) || empty($password)) {
        $error = "Teacher ID, Name and Password are required.";
    } else {
        $sql = "INSERT INTO teachers (teacher_id, name, email, phone, qualification, subject, address, password) 
                VALUES ('$teacher_id', '$name', '$email', '$phone', '$qualification', '$subject', '$address', '$password')";
        if (mysqli_query($conn, $sql)) {
            $message = "Teacher added successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Teacher</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Add New Teacher</h1>
        <p><a href="view_teachers.php">← Back to Teacher List</a></p>
        
        <?php if ($message != ''): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Teacher ID (unique):</label>
            <input type="text" name="teacher_id" id="teacher_id" required>
            
            <label>Full Name:</label>
            <input type="text" name="name" id="name" required>
            
            <label>Email:</label>
            <input type="email" name="email"id="email" >
            
            <label>Phone:</label>
            <input type="text" name="phone"id="phone" >
            
            <label>Qualification:</label>
            <input type="text" name="qualification"id="qualification" >
            
            <label>Subject Specialization:</label>
            <input type="text" name="subject"id="subject" >
            
            <label>Address:</label>
            <textarea name="address"id="address" ></textarea>
            
            <label>Password (default):</label>
            <input type="text" name="password" id="password" value="teacher123" required>
            
            <input type="submit" value="Add Teacher">
        </form>
    </div>
</body>
</html>