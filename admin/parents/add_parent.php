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

// Fetch all students for the multi-select dropdown
$students_sql = "SELECT id, name, class, section FROM students ORDER BY class, name";
$students_result = mysqli_query($conn, $students_sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parent_id = mysqli_real_escape_string($conn, $_POST['parent_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // plain text for now
    $selected_students = isset($_POST['students']) ? $_POST['students'] : [];

    if (empty($parent_id) || empty($name) || empty($password)) {
        $error = "Parent ID, Name and Password are required.";
    } else {
        // Start transaction
        mysqli_begin_transaction($conn);
        $success = true;

        // Insert parent
        $sql = "INSERT INTO parents (parent_id, name, email, phone, address, password) 
                VALUES ('$parent_id', '$name', '$email', '$phone', '$address', '$password')";
        if (mysqli_query($conn, $sql)) {
            $new_parent_id = mysqli_insert_id($conn);
            // Insert links to students
            if (!empty($selected_students)) {
                foreach ($selected_students as $student_id) {
                    $link_sql = "INSERT INTO parent_students (parent_id, student_id) VALUES ($new_parent_id, $student_id)";
                    if (!mysqli_query($conn, $link_sql)) {
                        $success = false;
                        $error = "Failed to link student.";
                        break;
                    }
                }
            }
        } else {
            $success = false;
            $error = "Error inserting parent: " . mysqli_error($conn);
        }

        if ($success) {
            mysqli_commit($conn);
            $message = "Parent added successfully!";
        } else {
            mysqli_rollback($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Parent</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Add New Parent</h1>
        <p><a href="view_parents.php">← Back to Parent List</a></p>
        
        <?php if ($message != ''): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Parent ID (unique):</label>
            <input type="text" name="parent_id" id="parent_id" required>
            
            <label>Full Name:</label>
            <input type="text" name="name" id="name" required>
            
            <label>Email:</label>
            <input type="email" name="email"id="email" >
            
            <label>Phone:</label>
            <input type="text" name="phone"id="phone" >
            
            <label>Address:</label>
            <textarea name="address"id="address" ></textarea>
            
            <label>Password (default):</label>
            <input type="text" name="password" id="password" value="parent123" required>
            
            <label>Select Children (hold Ctrl/Cmd to select multiple):</label>
            <select name="students[]" multiple size="8">
                <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                    <option value="<?php echo $student['id']; ?>">
                        <?php echo $student['name']; ?> (Class <?php echo $student['class']; ?>-<?php echo $student['section']; ?>)
                    </option>
                <?php endwhile; ?>
            </select>
            
            
            <input type="submit" value="Add Parent">
        </form>
    </div>
</body>
</html>