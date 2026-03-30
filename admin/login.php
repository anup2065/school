<?php
// Start session
session_start();

// Include database connection
include '../config.php';

// Initialize error variable
$error = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Simple validation
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        // Query to check username
        $sql = "SELECT * FROM admin WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // For now, we'll use simple password (since admin table might not have hashed passwords yet)
            // Later we can add password_verify if we hash them
            if ($password == $row['password']) {
                // Set session
                $_SESSION['admin'] = true;
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_username'] = $row['username'];
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "Username not found";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - School Management System</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Admin Login</h1>
        
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Username:</label>
            <input type="text" name="username" required>
            
            <label>Password:</label>
            <input type="password" name="password" id="password" required>
            
            <input type="submit" value="Login">
        </form>
        
        <p><a href="../index.php">Back to Home</a></p>
    </div>
</body>
</html>