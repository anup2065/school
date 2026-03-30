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

// Fetch distinct classes from students for targeting specific class
$classes_sql = "SELECT DISTINCT class FROM students ORDER BY class";
$classes_result = mysqli_query($conn, $classes_sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $target_audience = mysqli_real_escape_string($conn, $_POST['target_audience']);
    $class = ($target_audience == 'class') ? mysqli_real_escape_string($conn, $_POST['class']) : null;
    $posted_by = $_SESSION['admin_id'] ?? 1; // fallback to 1 if not set

    if (empty($title) || empty($content)) {
        $error = "Title and content are required.";
    } else {
        $sql = "INSERT INTO notices (title, content, posted_by, target_audience, class) 
                VALUES ('$title', '$content', $posted_by, '$target_audience', " . ($class ? "'$class'" : "NULL") . ")";
        if (mysqli_query($conn, $sql)) {
            $message = "Notice added successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Notice</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Add New Notice</h1>
        <p><a href="view_notices.php">← Back to Notice List</a></p>
        
        <?php if ($message != ''): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Title:</label>
            <input type="text" name="title" id="title" required>
            
            <label>Content:</label>
            <textarea name="content" id="content" rows="6" required></textarea>
            
            <label>Target Audience:</label>
            <input type="radio" name="target_audience" id="target_audience" value="all" checked onclick="toggleClassField()"> All
            <input type="radio" name="target_audience" id="target_audience" value="class" onclick="toggleClassField()"> Specific Class
            
            <div id="class_field" style="display:none;">
                <label>Select Class:</label>
                <select name="class"id="class" >
                    <option value="">-- Select Class --</option>
                    <?php while ($row = mysqli_fetch_assoc($classes_result)): ?>
                        <option value="<?php echo $row['class']; ?>"><?php echo $row['class']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <input type="submit" value="Add Notice">
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