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
    header("Location: view_parents.php");
    exit();
}

// Fetch parent data
$parent_sql = "SELECT * FROM parents WHERE id = $id";
$parent_result = mysqli_query($conn, $parent_sql);
if (mysqli_num_rows($parent_result) != 1) {
    header("Location: view_parents.php");
    exit();
}
$parent = mysqli_fetch_assoc($parent_result);

// Fetch currently linked students
$linked_sql = "SELECT student_id FROM parent_students WHERE parent_id = $id";
$linked_result = mysqli_query($conn, $linked_sql);
$linked_students = [];
while ($row = mysqli_fetch_assoc($linked_result)) {
    $linked_students[] = $row['student_id'];
}

// Fetch all students for dropdown
$students_sql = "SELECT id, name, class, section FROM students ORDER BY class, name";
$students_result = mysqli_query($conn, $students_sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parent_id = mysqli_real_escape_string($conn, $_POST['parent_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $selected_students = isset($_POST['students']) ? $_POST['students'] : [];

    if (empty($parent_id) || empty($name)) {
        $error = "Parent ID and Name are required.";
    } else {
        mysqli_begin_transaction($conn);
        $success = true;

        // Update parent
        if (!empty($password)) {
            $update_sql = "UPDATE parents SET parent_id='$parent_id', name='$name', email='$email', phone='$phone', address='$address', password='$password' WHERE id=$id";
        } else {
            $update_sql = "UPDATE parents SET parent_id='$parent_id', name='$name', email='$email', phone='$phone', address='$address' WHERE id=$id";
        }
        if (mysqli_query($conn, $update_sql)) {
            // Remove old links
            $delete_links = "DELETE FROM parent_students WHERE parent_id = $id";
            if (mysqli_query($conn, $delete_links)) {
                // Insert new links
                if (!empty($selected_students)) {
                    foreach ($selected_students as $student_id) {
                        $link_sql = "INSERT INTO parent_students (parent_id, student_id) VALUES ($id, $student_id)";
                        if (!mysqli_query($conn, $link_sql)) {
                            $success = false;
                            $error = "Failed to link student.";
                            break;
                        }
                    }
                }
            } else {
                $success = false;
                $error = "Failed to update student links.";
            }
        } else {
            $success = false;
            $error = "Error updating parent: " . mysqli_error($conn);
        }

        if ($success) {
            mysqli_commit($conn);
            $message = "Parent updated successfully!";
            // Refresh parent data
            $parent_result = mysqli_query($conn, "SELECT * FROM parents WHERE id = $id");
            $parent = mysqli_fetch_assoc($parent_result);
            $linked_students = $selected_students; // for form pre-selection
        } else {
            mysqli_rollback($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Parent</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Edit Parent</h1>
        <p><a href="view_parents.php">← Back to Parent List</a></p>
        
        <?php if ($message != ''): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Parent ID:</label>
            <input type="text" name="parent_id" id="parent_id" value="<?php echo $parent['parent_id']; ?>" required>
            
            <label>Full Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $parent['name']; ?>" required>
            
            <label>Email:</label>
            <input type="email" name="email" id="email" value="<?php echo $parent['email']; ?>">
            
            <label>Phone:</label>
            <input type="text" name="phone" id="phone" value="<?php echo $parent['phone']; ?>">
            
            <label>Address:</label>
            <textarea name="address"id="address" ><?php echo $parent['address']; ?></textarea>
            
            <label>New Password (leave blank to keep current):</label>
            <input type="text" name="password" id="password" placeholder="Enter new password if changing">
            
            <label>Select Children (hold Ctrl/Cmd to select multiple):</label>
            <select name="students[]" multiple size="8">
                <?php 
                mysqli_data_seek($students_result, 0); // reset pointer
                while ($student = mysqli_fetch_assoc($students_result)): 
                    $selected = in_array($student['id'], $linked_students) ? 'selected' : '';
                ?>
                    <option value="<?php echo $student['id']; ?>" <?php echo $selected; ?>>
                        <?php echo $student['name']; ?> (Class <?php echo $student['class']; ?>-<?php echo $student['section']; ?>)
                    </option>
                <?php endwhile; ?>
            </select>
            <small>Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</small>
            
            <input type="submit" value="Update Parent">
        </form>
    </div>
</body>
</html>