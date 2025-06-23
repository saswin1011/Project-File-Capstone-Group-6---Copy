<?php
header('Content-Type: application/json');
session_start(); // to access user_id if stored in session

$conn = new mysqli("localhost", "root", "", "dungeon_knowledge");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

$user_id = $_SESSION['userID']; // Change this to match your login/session logic
$quiz_id = $_POST['quiz_id'] ?? 0;
$score = $_POST['score'] ?? '';

if (!$quiz_id || $score === '') {
    echo json_encode(["success" => false, "message" => "Missing quiz_id or score"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO results (user_id, quiz_id, score) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $quiz_id, $score);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to save result"]);
}
?>
