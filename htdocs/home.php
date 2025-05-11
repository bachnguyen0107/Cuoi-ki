<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

// Get user info
$stmt = $conn->prepare("SELECT id, email FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$stmt->bind_result($user_id, $email);
$stmt->fetch();
$stmt->close();

$username = $_SESSION['username'];

// Handle note operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $note_id = $_POST['note_id'] ?? null;
    
    if (!empty($title)) {
        if ($note_id) {
            // Update existing note
            $stmt = $conn->prepare("UPDATE notes SET title = ?, content = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ssii", $title, $content, $note_id, $user_id);
        } else {
            // Create new note
            $stmt = $conn->prepare("INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $title, $content);
        }
        $stmt->execute();
        
        if (!$note_id) {
            $note_id = $conn->insert_id;
        }
        echo "note_id=$note_id";
        exit();
    }
}

// Get all notes for the user
$notes = $conn->prepare("SELECT id, title, content, updated_at FROM notes WHERE user_id = ? ORDER BY updated_at DESC");
$notes->bind_param("i", $user_id);
$notes->execute();
$notes_result = $notes->get_result();
$all_notes = $notes_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
include 'home.html';
?>