<?php
include('user.php');
$userID = $_SESSION['userID'];
$subjectId = $_GET['subject_id'] ?? '';
$sql_fetch="SELECT * FROM subjects WHERE id='$subjectId'";
$result=mysqli_query($conn,$sql_fetch);
$row=mysqli_fetch_assoc($result);
$subject_name=$row['subject_name'];
$description=$row['description'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Subject Page</title>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/user_subject.css">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
    <a href="user_home.php"><img src="media/logo.jpg" alt="Logo" aria-label="Website Logo"></a>
    <h1>Dungeon Knowledge</h1>
    <div style="display: flex; align-items: center; gap: 10px;">
      <div class="menu-toggle" id="menuToggle">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
      </div>

      <ul class="menu" id="menu">
        <li><a href="profile.php">Profile</a></li>
        <li><a href="history.php">History</a></li>
        <li><a href="logout.php">Log out</a></li>
      </ul>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container">
    <div class="subject-title"><?php echo $subject_name ;?></div>
    <div class="subject-description">
      <?php echo $description ;?>
    </div>

    
      <?php
      $sql_fetch="SELECT * FROM quizzes WHERE subject_id='$subjectId' ORDER BY quiz_name ASC";
      $result=mysqli_query($conn,$sql_fetch);
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $quiz_name = htmlspecialchars($row['quiz_name']);
            $quiz_id = htmlspecialchars($row['id']);
            $subject_id = htmlspecialchars($row['subject_id']);
            $quiz_desc = htmlspecialchars($row['description']);
            $sql_score = "SELECT MAX(score) AS max_score FROM results WHERE quiz_id='$quiz_id' AND user_id='$userID'";
            $score_list = mysqli_query($conn, $sql_score);
            $score = mysqli_fetch_assoc($score_list);
            $highest_score = $score['max_score'] ?? 'Not attempted';
    
            echo "
            <div class='quiz-section'>
                <div class='quiz-details'>
                    <h3>$quiz_name</h3>
                    <p>$quiz_desc</p>
                    <div><h3>Highest Score: $highest_score</h3></div>
                    <a href='avatar.php?quiz_id=$quiz_id&subject_id=$subject_id'><button class='start-button'>Start Quiz</button></a>
                </div>
            </div>
            ";
        }
    } else {
        echo '<h1>This Subject has no quizzes yet</h1>';
    }
    ?>
  </div>

  <script>
    const menuToggle = document.getElementById("menuToggle");
    const menu = document.getElementById("menu");

    menuToggle.addEventListener("click", function (e) {
      menu.classList.toggle("active");
      e.stopPropagation();
    });

    document.addEventListener("click", function (e) {
      if (!menu.contains(e.target) && !menuToggle.contains(e.target)) {
        menu.classList.remove("active");
      }
    });
  </script>

</body>
</html>
