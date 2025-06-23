<?php
include('admin.php');

if (isset($_POST['delete_subject'])) {
    $subject_id = $_POST['subject_id'];
    $sql_query="SELECT id FROM quizzes where subject_id='$subject_id'";
    $results=mysqli_query($conn, $sql_query);
    while($row=mysqli_fetch_assoc($results)){
        $quiz_id=$row['id'];
        $delete_query = "DELETE FROM results WHERE quiz_id = '$quiz_id'";
        mysqli_query($conn,$delete_query);
        $sql_query="SELECT id FROM questions where quiz_id = '$quiz_id'";
        $result=mysqli_query($conn,$sql_query);
        while($question_row=mysqli_fetch_assoc($result)){
            $question_id=$question_row['id'];
            $sql_delete="DELETE FROM answers WHERE question_id = '$question_id'";
            mysqli_query($conn,$sql_delete);
            $sql_delete="DELETE FROM questions WHERE id = '$question_id'";
            mysqli_query($conn,$sql_delete);

        }
        $delete_query = "DELETE FROM quizzes WHERE id = '$quiz_id'";
        mysqli_query($conn,$delete_query);
    }
    $delete_query = "DELETE FROM subjects WHERE id = '$subject_id'";
    mysqli_query($conn, $delete_query);
    header('Location: admin_home.php');
    exit;
}
if (!isset($_GET['query'])) {
    header("Location: index.html");
    exit();
}

$search = mysqli_real_escape_string($conn, $_GET['query']);
$sql_search = "SELECT * FROM subjects WHERE subject_name LIKE '%$search%' OR description LIKE '%$search%'";
$result = mysqli_query($conn, $sql_search);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/admin-home-search.css">
</head>
<body>
    <nav class="navbar">
        <a href="admin_home.php"><img src="media/logo.jpg" alt="Logo" aria-label="Website Logo"></a>
        <h1>Dungeon Knowledge - Admin Site</h1>
        <li><a href="logout.php">Log out</a></li>
    </nav>
    <header>
    <h1>Results for "<?php echo htmlspecialchars($search); ?>"</h1>
    </header>
    <form class="search-container" action="search_admin.php" method="GET">
        <input type="text" class="search-box" name="query" placeholder="Search for subjects..." aria-label="Search for quizzes">
        <button class="search-btn" aria-label="Search button">Search</button>
    </form>
    <?php
    if (mysqli_num_rows($result) > 0) {
      echo "<div class='quiz-grid'>";
      while ($row = mysqli_fetch_assoc($result)) {
          $subjectName = htmlspecialchars($row['subject_name']);
          $description = htmlspecialchars($row['description']);
          $subject_id = htmlspecialchars($row['id']);
          echo "
          <div class='quiz-card' data-subject='$subject_id'>
              <h3>$subjectName</h3>
              <p>$description</p>
              <form method='POST' onsubmit='return confirmDelete();'>
                  <input type='hidden' name='subject_id' value='$subject_id'>
                  <button type='submit' name='delete_subject' class='delete-btn'>Delete</button>
              </form>
          </div>";
      }
      echo "</div>";
    } else {
      echo '<h2 class="quiz-title">There are no subjects available yet</h2>';
    }
    ?>
    <script>
        const cards = document.querySelectorAll('.quiz-card');
        cards.forEach(card => {
            card.addEventListener('click', (e) => {
            if (e.target.tagName.toLowerCase() !== 'button') {
                const subjectId = card.dataset.subject;
                window.location.href = `edit_subject.php?subject_id=${encodeURIComponent(subjectId)}`;
            }
            });
        });
        function confirmDelete() {
            return confirm('Are you sure you want to delete this subject? This action cannot be undone.');
        }
    </script>
</body>
</html>