<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}
include '../config.php';

$teacher_id = $_SESSION['teacher_id'];
$message = '';
$error = '';

// Get teacher's assigned classes/subjects for dropdowns
$classes_sql = "SELECT DISTINCT class, section, subject FROM teacher_classes WHERE teacher_id = $teacher_id ORDER BY class, section";
$classes_res = mysqli_query($conn, $classes_sql);
$assignments = [];
while ($row = mysqli_fetch_assoc($classes_res)) {
    $assignments[] = $row;
}

// Step 1: Show selection form (if no step2)
if (!isset($_POST['load_students']) && !isset($_POST['save_marks'])) {
    // Display the selection form
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Enter Marks</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <div class="container">
            <h1>Enter Marks</h1>
            <p><a href="dashboard.php">← Back to Dashboard</a></p>
            <form method="POST">
                <label>Select Class & Section:</label>
                <select name="class_section" required onchange="updateSubjects(this)">
                    <option value="">-- Choose --</option>
                    <?php foreach ($assignments as $a): ?>
                        <option value="<?php echo $a['class'].'|'.$a['section'].'|'.$a['subject']; ?>">
                            Class <?php echo $a['class'].' '.$a['section'].' - '.$a['subject']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Exam Name:</label>
                <input type="text" name="exam_name" id="exam_name" required>

                <label>Maximum Marks:</label>
                <input type="number" name="total_marks" id="total_marks" required>

                <input type="submit" name="load_students" value="Load Students">
            </form>
        </div>
        <script>
        function updateSubjects(select) {
            // no need, subject is embedded in value
        }
        </script>
    </body>
    </html>
    <?php
    exit();
}

// Step 2: Load students for the selected class/subject
if (isset($_POST['load_students'])) {
    list($class, $section, $subject) = explode('|', $_POST['class_section']);
    $exam_name = mysqli_real_escape_string($conn, $_POST['exam_name']);
    $total_marks = mysqli_real_escape_string($conn, $_POST['total_marks']);

    // Store in session to pass to next step (or use hidden fields)
    $_SESSION['temp_class'] = $class;
    $_SESSION['temp_section'] = $section;
    $_SESSION['temp_subject'] = $subject;
    $_SESSION['temp_exam'] = $exam_name;
    $_SESSION['temp_total'] = $total_marks;

    // Fetch students of this class-section
    $students_sql = "SELECT id, name, roll_no FROM students WHERE class='$class' AND section='$section' ORDER BY roll_no";
    $students_res = mysqli_query($conn, $students_sql);

    // Also fetch existing marks for this exam/subject to pre-fill
    $marks_data = [];
    $marks_sql = "SELECT student_id, marks FROM results WHERE exam_name='$exam_name' AND subject='$subject'";
    $marks_res = mysqli_query($conn, $marks_sql);
    while ($m = mysqli_fetch_assoc($marks_res)) {
        $marks_data[$m['student_id']] = $m['marks'];
    }

    // Display marks entry form
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Enter Marks</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <div class="container">
            <h1>Enter Marks for <?php echo "$class $section - $subject ($exam_name)"; ?></h1>
            <p><a href="enter_marks.php">← Change Selection</a></p>
            <form method="POST">
                <table>
                    <tr>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Marks Obtained (out of <?php echo $total_marks; ?>)</th>
                    </tr>
                    <?php while ($student = mysqli_fetch_assoc($students_res)): 
                        $existing = isset($marks_data[$student['id']]) ? $marks_data[$student['id']] : '';
                    ?>
                    <tr>
                        <td><?php echo $student['roll_no']; ?></td>
                        <td><?php echo $student['name']; ?></td>
                        <td>
                            <input type="number" name="marks[<?php echo $student['id']; ?>]" value="<?php echo $existing; ?>" min="0" max="<?php echo $total_marks; ?>" step="0.01">
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
                <input type="submit" name="save_marks" value="Save Marks">
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Step 3: Save marks
if (isset($_POST['save_marks'])) {
    $class = $_SESSION['temp_class'];
    $section = $_SESSION['temp_section'];
    $subject = $_SESSION['temp_subject'];
    $exam_name = $_SESSION['temp_exam'];
    $total_marks = $_SESSION['temp_total'];

    $marks = $_POST['marks']; // array student_id => marks

    foreach ($marks as $student_id => $mark) {
        if ($mark !== '') {
            // Check if record exists
            $check = "SELECT id FROM results WHERE student_id=$student_id AND exam_name='$exam_name' AND subject='$subject'";
            $check_res = mysqli_query($conn, $check);
            if (mysqli_num_rows($check_res) > 0) {
                // Update
                $update = "UPDATE results SET marks='$mark', total_marks='$total_marks', entered_by=$teacher_id WHERE student_id=$student_id AND exam_name='$exam_name' AND subject='$subject'";
                mysqli_query($conn, $update);
            } else {
                // Insert
                $insert = "INSERT INTO results (student_id, class, exam_name, subject, marks, total_marks, entered_by) 
                           VALUES ($student_id, '$class', '$exam_name', '$subject', '$mark', '$total_marks', $teacher_id)";
                mysqli_query($conn, $insert);
            }
        }
    }

    $message = "Marks saved successfully!";
    // Clear session temp vars
    unset($_SESSION['temp_class'], $_SESSION['temp_section'], $_SESSION['temp_subject'], $_SESSION['temp_exam'], $_SESSION['temp_total']);

    // Show success and link back
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Marks Saved</title>
        <link rel="stylesheet" href="../css/style.css">
        <script src="/schoolms/js/script.js"></script>
</head>
    <body>
        <div class="container">
            <div class="success"><?php echo $message; ?></div>
            <p><a href="enter_marks.php">Enter more marks</a> | <a href="dashboard.php">Dashboard</a></p>
        </div>
    </body>
    </html>
    <?php
}
?>