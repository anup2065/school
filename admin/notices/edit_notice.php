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
    header("Location: view_notices.php");
    exit();
}

// Fetch notice
$sql = "SELECT * FROM notices WHERE id = $id";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) != 1) {
    header("Location: view_notices.php");
    exit();
}
$notice = mysqli_fetch_assoc($result);

// Fetch classes for dropdown
$classes_sql = "SELECT DISTINCT class FROM students ORDER BY class";
$classes_result = mysqli_query($conn, $classes_sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $target_audience = mysqli_real_escape_string($conn, $_POST['target_audience']);
    $class = ($target_audience == 'class') ? mysqli_real_escape_string($conn, $_POST['class']) : null;

    if (empty($title) || empty($content)) {
        $error = "Title and content are required.";
    } else {
        $update_sql = "UPDATE notices SET title='$title', content='$content', target_audience='$target_audience', class=" . ($class ? "'$class'" : "NULL") . " WHERE id=$id";
        if (mysqli_query($conn, $update_sql)) {
            $message = "Notice updated successfully!";
            // refresh notice
            $result = mysqli_query($conn, "SELECT * FROM notices WHERE id = $id");
            $notice = mysqli_fetch_assoc($result);
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Notice</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Edit Notice</h1>
        <p><a href="view_notices.php">← Back to Notice List</a></p>
        
        <?php if ($message != ''): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Title:</label>
            <input type="text" name="title" id="title" value="<?php echo $notice['title']; ?>" required>
            
            <label>Content:</label>
            <textarea name="content" id="content" rows="6" required><?php echo $notice['content']; ?></textarea>
            
            <label>Target Audience:</label>
            <input type="radio" name="target_audience" id="target_audience" value="all" <?php echo ($notice['target_audience']=='all')?'checked':''; ?> onclick="toggleClassField()"> All
            <input type="radio" name="target_audience" id="target_audience" value="class" <?php echo ($notice['target_audience']=='class')?'checked':''; ?> onclick="toggleClassField()"> Specific Class
            
            <div id="class_field" style="display: <?php echo ($notice['target_audience']=='class')?'block':'none'; ?>;">
                <label>Select Class:</label>
                <select name="class"id="class" >
                    <option value="">-- Select Class --</option>
                    <?php 
                    mysqli_data_seek($classes_result, 0);
                    while ($row = mysqli_fetch_assoc($classes_result)): 
                        $selected = ($row['class'] == $notice['class']) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $row['class']; ?>" <?php echo $selected; ?>><?php echo $row['class']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <input type="submit" value="Update Notice">
        </form>
    </div>
    
    <script>
    function toggleClassField() {
        var classField = document.getElementById('class_field');
        var radios = document.getElementsByName('target_audience');
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked && radios[i].value == 'class') {
                classField.style.display = 'block';
                return;
            }
        }
        classField.style.display = 'none';
    }
    </script>
</body>
</html>