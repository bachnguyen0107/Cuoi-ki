<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

// Get user info including email
$stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$stmt->bind_result($email);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Home</title>
</head>
<body>
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
  <p>Email: <?php echo htmlspecialchars($email); ?></p>
  <p>You are logged in.</p>
  <p><a href="profile.php">View My Profile</a></p>
  <a href="logout.php">Logout</a>
</body>
</html>