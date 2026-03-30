<?php
include 'config.php';
include 'includes/public_header.php';

// Fetch public notices
$sql = "SELECT * FROM notices WHERE target_audience = 'all' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<h2>Public Notices</h2>

<?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div style="border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:5px;">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
            <small>Posted on: <?php echo $row['created_at']; ?></small>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No public notices available.</p>
<?php endif; ?>

<p><a href="index.php">← Back to Home</a></p>

<?php include 'includes/public_footer.php'; ?>