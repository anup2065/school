<?php
// Start session (with check to avoid double start)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - School Management System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <p style="color: blue; font-weight:bold; font-style: italic;">Welcome, <?php echo $_SESSION['admin_username'] ?? 'Admin'; ?>!</p>
        
        <!-- Navigation Bar -->
        <div class="navbar">
            <a href="dashboard.php">Home</a>
            <a href="students/view_students.php">Students</a>
            <a href="teachers/view_teachers.php">Teachers</a>
            <a href="parents/view_parents.php">Parents</a>
            <a href="subjects/view_subjects.php">Subjects</a>
            <a href="notices/view_notices.php">Notices</a>
            <a href="results/view_results.php">Results</a>
            <a href="fees/view_fees.php">Fees</a>
            <a href="logout.php">Logout</a>
        </div>
        
        <h2>Quick Links</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
            <!-- Students Card -->
            <div style="background: #f0f0f0; padding: 20px; text-align: center; border-radius: 5px;">
                <h3>👥 Students</h3>
                <p><a href="students/add_student.php">➕ Add New Student</a></p>
                <p><a href="students/view_students.php">📋 View All Students</a></p>
            </div>
            
            <!-- Teachers Card -->
            <div style="background: #f0f0f0; padding: 20px; text-align: center; border-radius: 5px;">
                <h3>👩‍🏫 Teachers</h3>
                <p><a href="teachers/add_teacher.php">➕ Add New Teacher</a></p>
                <p><a href="teachers/view_teachers.php">📋 View All Teachers</a></p>
                <p><a href="teachers/assign_class.php">📌 Assign Class to Teacher</a></p>
            </div>
            
            <!-- NEW: Parents Card -->
            <div style="background: #f0f0f0; padding: 20px; text-align: center; border-radius: 5px;">
                <h3>👪 Parents</h3>
                <p><a href="parents/add_parent.php">➕ Add New Parent</a></p>
                <p><a href="parents/view_parents.php">📋 View All Parents</a></p>
                <p><a href="parents/view_parents.php">🔗 Link to Students</a></p>
            </div>
            
            <!-- NEW: Subjects Card -->
            <div style="background: #f0f0f0; padding: 20px; text-align: center; border-radius: 5px;">
                <h3>📚 Subjects</h3>
                <p><a href="subjects/add_subject.php">➕ Add New Subject</a></p>
                <p><a href="subjects/view_subjects.php">📋 View All Subjects</a></p>
            </div>
            
            <!-- Notices Card -->
            <div style="background: #f0f0f0; padding: 20px; text-align: center; border-radius: 5px;">
                <h3>📢 Notices</h3>
                <p><a href="notices/add_notice.php">➕ Add New Notice</a></p>
                <p><a href="notices/view_notices.php">📋 View All Notices</a></p>
            </div>
            
            <!-- Results Card -->
            <div style="background: #f0f0f0; padding: 20px; text-align: center; border-radius: 5px;">
                <h3>📊 Results</h3>
                <p><a href="results/add_result.php">➕ Add New Result</a></p>
                <p><a href="results/view_results.php">📋 View All Results</a></p>
            </div>
            
            <!-- Fees Card -->
            <div style="background: #f0f0f0; padding: 20px; text-align: center; border-radius: 5px;">
                <h3>💰 Fees</h3>
                <p><a href="fees/add_fee.php">➕ Add Fee Record</a></p>
                <p><a href="fees/view_fees.php">📋 View All Fees</a></p>
                <p><a href="fees/fee_reports.php">📈 Fee Reports</a></p>
            </div>
        </div>
    </div>
</body>
</html>