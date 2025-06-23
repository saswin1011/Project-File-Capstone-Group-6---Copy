<?php
include('user.php');
$userID=$_SESSION["userID"];
$sql_query="SELECT DISTINCT(subject_id) FROM quizzes WHERE id IN (SELECT DISTINCT(quiz_id) FROM results WHERE user_id='$userID')";
$result_subjectID=mysqli_query($conn,$sql_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quiz History</title>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/search-history.css">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
     <a href="user_home.php"><img src="media/logo.jpg" alt="Logo" aria-label="Website Logo"></a>
    <h1>Dungeon Knowlegde</h1>
    <div style="display: flex; align-items: center; gap: 10px;">
      <div class="menu-toggle" id="menuToggle" aria-label="Toggle Menu">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
      </div>

      <ul class="menu" id="menu">
        <li><a href="user_home.php">Home</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="logout.php">Log out</a></li>
      </ul>
    </div>
  </nav>

  <!-- Header -->
  <header>
    <h1>Subject History</h1>
  </header>
  <?php 
  if(mysqli_num_rows($result_subjectID) > 0){
    while($row_subjectID=mysqli_fetch_assoc($result_subjectID)){
      $subjectID = $row_subjectID['subject_id'];
      $sql_query = "SELECT * FROM subjects WHERE id='$subjectID'";
      $result_subject = mysqli_query($conn,$sql_query);
      while($row_subject=mysqli_fetch_assoc($result_subject)){
        $subject_name = $row_subject['subject_name'];
        $sql_query ="SELECT COUNT(*) AS total FROM quizzes WHERE subject_id='$subjectID'";
        $result_total = mysqli_query($conn,$sql_query);
        $row_total = mysqli_fetch_assoc($result_total);
        $total_quiz = (int)$row_total['total'];
        $sql_check = "SELECT COUNT(DISTINCT quiz_id) AS total FROM results WHERE user_id = $userID AND quiz_id IN ( SELECT id FROM quizzes WHERE subject_id = '$subjectID' )";
        $result_completed = mysqli_query($conn,$sql_check);
        $row_completed = mysqli_fetch_assoc($result_completed);
        $total_completed =(int)$row_completed['total'];
        $percentage = ($total_completed/$total_quiz) * 100;
        echo"
        <div class='history-container' data-subject='$subjectID'>
          <div class='subject'>
            <div class='subject-title'>$subject_name</div>
            <div class='progress-bar'>
              <div class='progress-fill' style='width: $percentage%;'>$percentage%</div>
            </div>
        </div></div>";
      }
    }
  }else{
    echo"<h2>No history of subjects </h2>";
  }
  ?>

  <!-- Dropdown Toggle Script -->
  <script>
    const menuToggle = document.getElementById("menuToggle");
    const menu = document.getElementById("menu");
    const cards = document.querySelectorAll('.history-container');
    menuToggle.addEventListener("click", function (event) {
      menu.classList.toggle("active");
      event.stopPropagation();
    });

    document.addEventListener("click", function (event) {
      if (!menu.contains(event.target) && !menuToggle.contains(event.target)) {
        menu.classList.remove("active");
      }
    });

    cards.forEach(card => {
            card.addEventListener('click', () => {
                const subjectId = card.dataset.subject;
                window.location.href = `subject.php?subject_id=${encodeURIComponent(subjectId)}`;
            });
        });
  </script>

</body>
</html>
