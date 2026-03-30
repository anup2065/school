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

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    header("Location: view_teachers.php");
    exit();
}

$sql = "SELECT * FROM teachers WHERE id = $id";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) != 1) {
    header("Location: view_teachers.php");
    exit();
}
$teacher = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($teacher_id) || empty($name)) {
        $error = "Teacher ID and Name are required.";
    } else {
        if (!empty($password)) {
            $update_sql = "UPDATE teachers SET teacher_id='$teacher_id', name='$name', email='$email', phone='$phone', qualification='$qualification', subject='$subject', address='$address', password='$password' WHERE id=$id";
        } else {
            $update_sql = "UPDATE teachers SET teacher_id='$teacher_id', name='$name', email='$email', phone='$phone', qualification='$qualification', subject='$subject', address='$address' WHERE id=$id";
        }
        if (mysqli_query($conn, $update_sql)) {
            $message = "Teacher updated successfully!";
            // refresh data
            $result = mysqli_query($conn, "SELECT * FROM teachers WHERE id = $id");
            $teacher = mysqli_fetch_assoc($result);
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Teacher</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Edit Teacher</h1>
        <p><a href="view_teachers.php">← Back to Teacher List</a></p>
        
        <?php if ($message != ''): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Teacher ID:</label>
            <input type="text" name="teacher_id" id="teacher_id" value="<?php echo $teacher['teacher_id']; ?>" required>
            
            <label>Full Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $teacher['name']; ?>" required>
            
            <label>Email:</label>
            <input type="email" name="email" id="email" value="<?php echo $teacher['email']; ?>">
            
            <label>Phone:</label>
            <input type="text" name="phone" id="phone" value="<?php echo $teacher['phone']; ?>">
            
            <label>Qualification:</label>
            <input type="text" name="qualification" id="qualification" value="<?php echo $teacher['qualification']; ?>">
            
            <label>Subject Specialization:</label>
            <input type="text" name="subject" id="subject" value="<?php echo $teacher['subject']; ?>">
            
            <label>Address:</label>
            <textarea name="address"id="address" ><?php echo $teacher['address']; ?></textarea>
            
            <label>New Password (leave blank to keep current):</label>
            <input type="text" name="password" id="password" placeholder="Enter new password if changing">
            
            <input type="submit" value="Update Teacher">
        </form>
    </div>
</body>
</html>