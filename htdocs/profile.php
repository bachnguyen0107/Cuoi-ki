<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Get user data
$stmt = $conn->prepare("SELECT username, email, avatar, bio, full_name FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <style>
        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>User Profile</h2>
        
        <!-- Avatar Display -->
        <img src="uploads/avatars/<?php echo htmlspecialchars($user['avatar']); ?>" 
             alt="Profile Avatar" class="avatar">
        
        <!-- Profile Info -->
        <h3><?php echo htmlspecialchars($user['full_name'] ?? 'Not set'); ?></h3>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        
        <!-- Bio -->
        <div class="bio">
            <h4>About Me:</h4>
            <p><?php echo htmlspecialchars($user['bio'] ?? 'No bio yet'); ?></p>
        </div>
        
        <!-- Navigation -->
        <a href="edit_profile.php">Edit Profile</a> |
        <a href="home.php">Back to Home</a>
    </div>
</body>
</html>