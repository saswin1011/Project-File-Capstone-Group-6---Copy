<?php
include('user.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "dungeon_knowledge");
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}

$quizId = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

// Get all questions + answers for that quiz
$stmt = $conn->prepare("
    SELECT q.id AS question_id, q.question_text, q.note, 
           a.answer_text, a.is_correct
    FROM questions q
    JOIN answers a ON q.id = a.question_id
    WHERE q.quiz_id = ?
    ORDER BY q.id, a.id
");
$stmt->bind_param("i", $quizId);
$stmt->execute();
$result = $stmt->get_result();

$questionsMap = [];

while ($row = $result->fetch_assoc()) {
    $qid = $row['question_id'];

    // If we haven't seen this question before, initialize it
    if (!isset($questionsMap[$qid])) {
        $questionsMap[$qid] = [
            'text' => $row['question_text'],
            'options' => [],
            'correct' => null,
            'explanation' => $row['note'] ?? ''
        ];
    }

    // Add this answer option
    $questionsMap[$qid]['options'][] = $row['answer_text'];

    // If it's the correct one, store its index
    if ($row['is_correct']) {
        $questionsMap[$qid]['correct'] = count($questionsMap[$qid]['options']) - 1;
    }
}

// Reset keys to make it a clean JS array
echo json_encode(array_values($questionsMap));
