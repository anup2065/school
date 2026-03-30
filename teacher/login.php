<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($teacher_id) || empty($password)) {
        $error = "Please fill in both fields.";
    } else {
        $sql = "SELECT * FROM teachers WHERE teacher_id = '$teacher_id'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
            $teacher = mysqli_fetch_assoc($result);
            // Compare plain text password (you can later use password_verify if you hash)
            if ($password == $teacher['password']) {
                $_SESSION['teacher_id'] = $teacher['id'];
                $_SESSION['teacher_name'] = $teacher['name'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Teacher ID not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Teacher Login</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>Teacher ID:</label>
            <input type="text" name="teacher_id" id="teacher_id" required>
            <label>Password:</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="Login">
        </form>
        <p><a href="../index.php">Back to Home</a></p>
    </div>
</body>
</html>