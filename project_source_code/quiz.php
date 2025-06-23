<?php
include('user.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$quizId = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 1;
$subjectId = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 1;

$conn = new mysqli("localhost", "root", "", "dungeon_knowledge");
if ($conn->connect_error) {
    die("Connection failed");
}

$limitResult = $conn->query("SELECT wrong_answer_limit FROM quizzes WHERE id = $quizId");
$wrongLimit = 3; // fallback default
if ($limitResult && $row = $limitResult->fetch_assoc()) {
    $wrongLimit = intval($row['wrong_answer_limit']);
}

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
    if (!isset($questionsMap[$qid])) {
        $questionsMap[$qid] = [
            'text' => $row['question_text'],
            'options' => [],
            'correct' => null,
            'explanation' => $row['note'] ?? ''
        ];
    }

    $questionsMap[$qid]['options'][] = $row['answer_text'];
    if ($row['is_correct']) {
        $questionsMap[$qid]['correct'] = count($questionsMap[$qid]['options']) - 1;
    }
}

$questionsJson = json_encode(array_values($questionsMap));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Quiz Arena</title>
  <style>
  body {
    margin: 0;
    overflow: hidden;
    background: black;
  }
  canvas {
    display: block;
  }

  /* 🔊 Glowing animation for active music button */
  .music-on {
    animation: pulse 1s infinite;
    border: 2px solid gold;
  }

  @keyframes pulse {
    0% { box-shadow: 0 0 0px gold; }
    50% { box-shadow: 0 0 15px gold; }
    100% { box-shadow: 0 0 0px gold; }
  }
</style>

</head>
<!-- 🧾 Explanation Popup -->
<div id="explanationPopup" style="
  display: none;
  position: absolute;
  top: 25%;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(0, 0, 0, 0.85);
  color: white;
  padding: 20px 30px;
  border: 2px solid gold;
  border-radius: 15px;
  z-index: 9999;
  width: 400px;
  font-family: Georgia, serif;
  text-align: center;
">
  <h3 style="color: orange;">🔥 Wrong Answer!</h3>
  <p id="explanationText"></p>
  <p id="livesLeftText" style="margin-top: 10px; font-weight: bold;"></p>
  <button onclick="closeExplanation()" style="
    margin-top: 20px;
    padding: 8px 20px;
    background: gold;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
  ">Close</button>
</div>

<body>
  <canvas id="gameCanvas"></canvas>

  <!-- 🕹 INSTRUCTIONS PANEL -->
  <div id="instructionsPanel" style="
    position: absolute;
    top: 20px;
    right: 20px;
    width: 250px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    font-family: Georgia, serif;
    font-size: 14px;
    padding: 15px;
    border-radius: 10px;
    z-index: 10;
  ">
    <h3 style="margin-top: 0; color: gold;">🕹 Instructions</h3>
    <ul style="padding-left: 20px;">
      <li>← ↑ → ↓ to move</li>
      <li><strong>Q</strong> near bookshelf to start question</li>
      <li><strong>E</strong> to interact with door</li>
      <li>🔥 Wrong answers = dragon attack</li>
      <li><strong>ESC</strong> to pause</li>
    </ul>
  </div>

  <!-- ⏸ PAUSE OVERLAY -->
<div id="pauseOverlay" style="
  display: none;
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.85);
  color: white;
  font-family: 'Georgia', serif;
  font-size: 22px;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  z-index: 9999;
  text-align: center;
">
  <div style="
    background: rgba(40, 20, 0, 0.9);
    border: 3px solid gold;
    border-radius: 20px;
    padding: 30px 50px;
    box-shadow: 0 0 20px gold;
  ">
    <h2 style="margin: 0 0 20px 0; color: gold; font-size: 32px;">⏸ Game Paused</h2>
    <button onclick="resumeGame()" style="
      margin: 10px;
      padding: 10px 25px;
      background: gold;
      color: black;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
    ">▶ Resume</button>
    <button onclick="window.location.href = 'subject.php?subject_id=' + window.subjectId" style="
      margin: 10px;
      padding: 10px 25px;
      background: crimson;
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
    ">🏰 Back to Menu</button>
  </div>
</div>


  <!-- 📜 DATA FROM PHP TO JS -->
  <script>
    const questions = <?php echo $questionsJson; ?>;
    const quizId = <?php echo $quizId; ?>;
    const subjectId = <?php echo $subjectId; ?>;
    const wrongAnswerLimit = <?php echo $wrongLimit; ?>;

    window.quizId = quizId;
    window.subjectId = subjectId;
    window.wrongAnswerLimit = wrongAnswerLimit;
    function toggleMusic() {
  const music = document.getElementById("bgMusic");
  const btn = document.getElementById("musicToggleBtn");

  if (music.paused || music.muted) {
    music.muted = false;
    music.play();
    btn.classList.add("music-on");
  } else {
    music.pause();
    btn.classList.remove("music-on");
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const music = document.getElementById("bgMusic");
  const btn = document.getElementById("musicToggleBtn");
  music.volume = 0.3;

  // Optional: turn on class if music autoplays after muted
  if (!music.muted && !music.paused) {
    btn.classList.add("music-on");
  }
});


  </script>

  <!-- 🎮 MAIN GAME SCRIPT -->
  <script src="game.js"></script>

<!-- 🔊 Background Music -->
<audio id="bgMusic" src="assets/background.mp3" loop autoplay muted></audio>

<!-- 🔘 Music Toggle Button -->
<button id="musicToggleBtn" onclick="toggleMusic()" style="
  position: absolute; bottom: 20px; right: 20px;
  z-index: 9999; padding: 10px 15px;
  background: rgba(0,0,0,0.7); color: white;
  border: none; border-radius: 8px; font-size: 16px;">
  🎵 Toggle Music
</button>


</body>
</html>

