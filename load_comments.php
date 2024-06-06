<?php
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
$servername = "sql109.infinityfree.com";
$username = "if0_36548773";
$password = "mEvo5LBUfd";
$dbname = "if0_36548773_gaprecruitment";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select comment, user_type, and created_at from the comments table
$sql = "SELECT comment, user_type, created_at FROM comments ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="comments-list">';
    while($row = $result->fetch_assoc()) {
        // Ensure that we are not passing null to htmlspecialchars by checking if the keys exist
        $comment = isset($row['comment']) ? htmlspecialchars($row['comment']) : '';
        $userType = isset($row['user_type']) ? htmlspecialchars($row['user_type']) : 'Anonymous';
        $createdAt = isset($row['created_at']) ? $row['created_at'] : 'Unknown date';

        echo "<p>{$comment}</p>";
        echo "<p>Posted by: {$userType} on {$createdAt}</p>";
    }
    echo '</div>';
} else {
    echo "No comments yet.";
}
$conn->close();

