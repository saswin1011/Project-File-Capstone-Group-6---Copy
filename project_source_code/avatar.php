<?php include('user.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Choose Your Knight</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: url('assets/avatar_bg.png') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Georgia', serif;
      color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    h1 {
      font-size: 48px;
      color: gold;
      margin-bottom: 30px;
      text-shadow: 2px 2px 4px #000;
    }
    .avatars {
      display: flex;
      gap: 50px;
      justify-content: center;
      flex-wrap: wrap;
    }
    .avatar-card {
      background: rgba(0, 0, 0, 0.6);
      border-radius: 10px;
      padding: 15px;
      transition: transform 0.3s, border-color 0.3s;
      border: 3px solid transparent;
      cursor: pointer;
    }
    .avatar-card:hover {
      transform: scale(1.05);
      border-color: gold;
    }
    .avatar-card img {
      width: 120px;
      height: 120px;
      border-radius: 8px;
    }
    .avatar-label {
      margin-top: 10px;
      font-size: 18px;
      color: #fff;
    }
  </style>
</head>
<body>
  <h1>Choose Your Knight</h1>
  <div class="avatars">
    <div class="avatar-card" onclick="selectAvatar('knight1.png')">
      <img src="assets/knight1.png" alt="Knight 1">
      <div class="avatar-label">Knight A</div>
    </div>
    <div class="avatar-card" onclick="selectAvatar('knight2.png')">
      <img src="assets/knight2.png" alt="Knight 2">
      <div class="avatar-label">Knight B</div>
    </div>
    <div class="avatar-card" onclick="selectAvatar('knight3.png')">
      <img src="assets/knight3.png" alt="Knight 3">
      <div class="avatar-label">Knight C</div>
    </div>
  </div>

  <script>
    const params = new URLSearchParams(window.location.search);
    const quizId = params.get("quiz_id") || 1;
    const subjectId = params.get("subject_id") || 1;

    function selectAvatar(filename) {
      localStorage.setItem("knightAvatar", filename);
      window.location.href = `quiz.php?quiz_id=${quizId}&subject_id=${subjectId}`;
    }
  </script>
</body>
</html>
