<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parent_id = mysqli_real_escape_string($conn, $_POST['parent_id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($parent_id) || empty($password)) {
        $error = "Please fill in both fields.";
    } else {
        $sql = "SELECT * FROM parents WHERE parent_id = '$parent_id'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
            $parent = mysqli_fetch_assoc($result);
            // Compare plain text password (you can later use password_verify if you hash)
            if ($password == $parent['password']) {
                $_SESSION['parent_id'] = $parent['id'];
                $_SESSION['parent_name'] = $parent['name'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Parent ID not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Parent Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Parent Login</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>Parent ID:</label>
            <input type="text" name="parent_id" id="parent_id" required>
            <label>Password:</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="Login">
        </form>
        <p><a href="../index.php">Back to Home</a></p>
    </div>
</body>
</html>