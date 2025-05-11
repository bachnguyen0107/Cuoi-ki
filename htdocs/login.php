<?php
include 'db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password_input = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $stmt->bind_result($password_hash);
    if ($stmt->fetch() && password_verify($password_input, $password_hash)) {
        $_SESSION['username'] = $username;
        header("Location: home.php");
    } else {
        echo "Invalid credentials. <a href='login.html'>Try again</a>";
    }

    $stmt->close();
    $conn->close();
}
?>