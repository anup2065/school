<?php include 'includes/public_header.php'; ?>

<div style="text-align: center; margin: 40px 0;">
    <h2>Login as:</h2>
    <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
        <a href="admin/login.php" style="background: #007bff; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 18px;">Admin Login</a>
        <a href="teacher/login.php" style="background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 18px;">Teacher Login</a>
        <a href="student/login.php" style="background: #ffc107; color: black; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 18px;">Student Login</a>
        <a href="parent/login.php" style="background: #17a2b8; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 18px;">Parent Login</a>
    </div>
</div>

<div style="margin: 40px 0;">
    <h2>About the System</h2>
    <p>This web-based school management system helps schools manage students, teachers, parents, notices, results, fees, and homework efficiently. It provides separate dashboards for administrators, teachers, students, and parents with role-based access.</p>
</div>

<div style="margin: 40px 0; text-align: center;">
    <h3>View <a href="notices.php">Public Notices</a></h3>
</div>

<?php include 'includes/public_footer.php'; ?>