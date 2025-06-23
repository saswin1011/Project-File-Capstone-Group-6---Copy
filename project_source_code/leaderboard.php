<?php 
include('admin.php');
$subject_id = $_GET['subject_id'] ?? ''; 
$quiz_id = $_GET['quiz_id'] ?? ''; 
$sql_query= "SELECT user_id, MAX(score) AS highest_score, COUNT(user_id) AS attempt_count FROM results WHERE quiz_id = '$quiz_id' GROUP BY user_id ORDER BY highest_score DESC, attempt_count ASC";
$result_leaderboard = mysqli_query($conn,$sql_query);
$sql_query="SELECT quiz_name from quizzes where id='$quiz_id'";
$result_quizname= mysqli_query($conn,$sql_query);
$row_quizname= mysqli_fetch_assoc($result_quizname);
$quizname = $row_quizname['quiz_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - <?php echo $quizname; ?></title>
    <link rel="stylesheet" href="css/leaderboard.css">


</head>
<body>
    <nav class="navbar">
        <a href="admin_home.php"><img src="media/logo.jpg" alt="Logo" aria-label="Website Logo"></a>
        <h1>Dungeon Knowledge - Admin Site</h1>
        <li><a href="logout.php">Log out</a></li>
    </nav>
    <div class='title'>
        <a href="edit_subject.php?subject_id=<?php echo $subject_id; ?>">
        <button class='back-btn'>⬅Back</button>
        </a>
        <h1>Leaderboard of <?php echo $quizname; ?></h1>
    </div>
    <div class='leaderboard'>
        <div class="leaderboard-header">
            <span>Pos.</span>
            <span>Name</span>
            <span>Score</span>
            <span>Attempts</span>
        </div>
        <?php
        $count=0;
        if(mysqli_num_rows($result_leaderboard) > 0){
            while($row_leaderboard = mysqli_fetch_assoc($result_leaderboard)){
                $count++;
                $user_id=$row_leaderboard['user_id'];
                $attempts=$row_leaderboard['attempt_count'];
                $score=$row_leaderboard['highest_score'];
                $sql_query="SELECT name from users where id='$user_id'";
                $result= mysqli_query($conn,$sql_query);
                $row= mysqli_fetch_assoc($result);
                $name=$row['name'];
                echo "<div class='leaderboard-row'>
                <span>$count.</span> <span>$name</span> <span>$score</span> <span>$attempts</span>
                </div>";
            }
        } else {
            echo "<div>No attempts on this quiz yet</div>";
        }
        ?>
    </div>
</body>
</html>
