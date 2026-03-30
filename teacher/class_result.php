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

// Get teacher's assigned classes (for dropdown)
$classes_sql = "SELECT DISTINCT class, section FROM teacher_classes WHERE teacher_id = $teacher_id ORDER BY class, section";
$classes_res = mysqli_query($conn, $classes_sql);

// Function to calculate GPA from percentage
function calculateGPA($percentage) {
    if ($percentage >= 90) return 4.0;
    if ($percentage >= 80) return 3.6;
    if ($percentage >= 70) return 3.2;
    if ($percentage >= 60) return 2.8;
    if ($percentage >= 50) return 2.4;
    if ($percentage >= 40) return 2.0;
    if ($percentage >= 33) return 1.6;
    return 0.0;
}

// Handle form submission for showing result
$show_result = false;
$class = $section = $exam_name = '';
$students_data = [];
$ranked_data = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show_result'])) {
    $class = mysqli_real_escape_string($conn, $_POST['class']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $exam_name = mysqli_real_escape_string($conn, $_POST['exam_name']);

    // Get all students in that class-section
    $students_sql = "SELECT id, name, roll_no FROM students WHERE class='$class' AND section='$section' ORDER BY roll_no";
    $students_res = mysqli_query($conn, $students_sql);

    // For each student, get all results for this exam
    while ($student = mysqli_fetch_assoc($students_res)) {
        $student_id = $student['id'];
        $results_sql = "SELECT subject, marks, total_marks FROM results WHERE student_id=$student_id AND exam_name='$exam_name'";
        $results_res = mysqli_query($conn, $results_sql);

        $subjects = [];
        $total_obtained = 0;
        $total_max = 0;
        while ($r = mysqli_fetch_assoc($results_res)) {
            $subjects[] = [
                'subject' => $r['subject'],
                'marks' => $r['marks'],
                'total' => $r['total_marks']
            ];
            $total_obtained += $r['marks'];
            $total_max += $r['total_marks'];
        }

        if (count($subjects) > 0) {
            $percentage = ($total_max > 0) ? ($total_obtained / $total_max) * 100 : 0;
            $gpa = calculateGPA($percentage);
            $students_data[] = [
                'id' => $student_id,
                'name' => $student['name'],
                'roll' => $student['roll_no'],
                'subjects' => $subjects,
                'total_obtained' => $total_obtained,
                'total_max' => $total_max,
                'percentage' => $percentage,
                'gpa' => $gpa
            ];
        }
    }

    // Sort by percentage descending for ranking
    usort($students_data, function($a, $b) {
        return $b['percentage'] <=> $a['percentage'];
    });

    // Assign ranks
    $rank = 1;
    foreach ($students_data as &$s) {
        $s['rank'] = $rank++;
    }

    // Re-sort by roll no for display (optional, but we'll keep rank order)
    $ranked_data = $students_data;
    $show_result = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Class Result</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .result-table { border-collapse: collapse; width: 100%; }
        .result-table th, .result-table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .result-table th { background-color: #007bff; color: white; }
        .rank-1 { background-color: gold; }
        .rank-2 { background-color: silver; }
        .rank-3 { background-color: #cd7f32; }
    </style>
    <script src="/schoolms/js/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Class Result</h1>
        <p><a href="dashboard.php">← Back to Dashboard</a></p>

        <!-- Selection Form -->
        <form method="POST" style="margin-bottom:20px; padding:15px; background:#f9f9f9;">
            <label>Select Class:</label>
            <select name="class" id="class" required>
                <option value="">-- Choose --</option>
                <?php 
                mysqli_data_seek($classes_res, 0);
                while ($c = mysqli_fetch_assoc($classes_res)): 
                ?>
                <option value="<?php echo $c['class']; ?>" <?php if($class == $c['class']) echo 'selected'; ?>><?php echo $c['class']; ?></option>
                <?php endwhile; ?>
            </select>

            <label>Section:</label>
            <input type="text" name="section" id="section" value="<?php echo $section; ?>" required>

            <label>Exam Name:</label>
            <input type="text" name="exam_name" id="exam_name" value="<?php echo $exam_name; ?>" required>

            <input type="submit" name="show_result" value="Show Result">
        </form>

        <?php if ($show_result): ?>
            <h2>Result for Class <?php echo "$class $section - $exam_name"; ?></h2>

            <?php if (empty($ranked_data)): ?>
                <p class="error">No results found for this exam/class.</p>
            <?php else: ?>
                <table class="result-table">
                    <tr>
                        <th>Rank</th>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <?php 
                        // Get unique subjects from first student to create columns
                        $subj_list = array_map(function($s) { return $s['subject']; }, $ranked_data[0]['subjects']); 
                        foreach ($subj_list as $subj): ?>
                        <th><?php echo $subj; ?></th>
                        <?php endforeach; ?>
                        <th>Total</th>
                        <th>%</th>
                        <th>GPA</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($ranked_data as $s): ?>
                    <tr class="<?php 
                        if ($s['rank'] == 1) echo 'rank-1';
                        elseif ($s['rank'] == 2) echo 'rank-2';
                        elseif ($s['rank'] == 3) echo 'rank-3';
                    ?>">
                        <td><?php echo $s['rank']; ?></td>
                        <td><?php echo $s['roll']; ?></td>
                        <td><?php echo $s['name']; ?></td>
                        <?php 
                        // Create a map of subject -> marks for this student
                        $marks_map = [];
                        foreach ($s['subjects'] as $sub) {
                            $marks_map[$sub['subject']] = $sub['marks'] . '/' . $sub['total'];
                        }
                        foreach ($subj_list as $subj): 
                        ?>
                        <td><?php echo isset($marks_map[$subj]) ? $marks_map[$subj] : '-'; ?></td>
                        <?php endforeach; ?>
                        <td><?php echo $s['total_obtained'] . '/' . $s['total_max']; ?></td>
                        <td><?php echo number_format($s['percentage'], 2); ?>%</td>
                        <td><?php echo number_format($s['gpa'], 2); ?></td>
                        <td><a href="student_result.php?student_id=<?php echo $s['id']; ?>&exam=<?php echo urlencode($exam_name); ?>">View</a></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <p><button onclick="window.print()">Print Result</button></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>