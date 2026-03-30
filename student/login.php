<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($student_id) || empty($password)) {
        $error = "Please fill in both fields.";
    } else {
        $sql = "SELECT * FROM students WHERE student_id = '$student_id'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
            $student = mysqli_fetch_assoc($result);
            // Compare plain text password (you can later use password_verify if you hash)
            if ($password == $student['password']) {
                $_SESSION['student_id'] = $student['id'];
                $_SESSION['student_name'] = $student['name'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Student ID not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Student Login</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>Student ID:</label>
            <input type="text" name="student_id" id="student_id" required>
            <label>Password:</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="Login">
        </form>
        <p><a href="../index.php">Back to Home</a></p>
    </div>
</body>
</html>