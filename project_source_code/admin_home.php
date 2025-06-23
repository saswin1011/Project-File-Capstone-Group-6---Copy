<?php
include('admin.php');

// Handle delete request
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

// Handle add new subject request
if (isset($_POST['add_subject'])) {
    $subject_name = mysqli_real_escape_string($conn, $_POST['subject_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if (!empty($subject_name)) {
        $insert_query = "INSERT INTO subjects (subject_name, description) VALUES ('$subject_name', '$description')";
        mysqli_query($conn, $insert_query);
        header('Location: admin_home.php');
        exit;
    }
}

$sql_query = "SELECT * FROM subjects";
$result = mysqli_query($conn, $sql_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Subjects</title>
  <link rel="stylesheet" href="css/admin-home-search.css">
</head>

<body>
  <nav class="navbar">
    <a href="admin_home.php"><img src="media/logo.jpg" alt="Logo" aria-label="Website Logo"></a>
    <h1>Dungeon Knowledge - Admin Site</h1>
    <li><a href="logout.php">Log out</a></li>
  </nav>
  <h2 class='quiz-title'>Subjects Created:</h2>
    <form class="search-container" action="search_admin.php" method="GET">
        <input type="text" class="search-box" name="query" placeholder="Search for subjects..." aria-label="Search for quizzes">
        <button class="search-btn" aria-label="Search button">Search</button>
    </form>
    <div style="text-align: center; margin: 30px;">
    <button onclick="toggleAddForm()" class="add-btn" id="addSubjectToggleBtn">
        ➕ Add New Subject
    </button>
    </div>

    <!-- Add New Subject Form (initially hidden) -->
    <div id="addSubjectForm" class="add-form" style="display: none;">
    <h2>Add New Subject</h2>
    <form method="POST" style="margin-bottom: 10px;">
        <input type="text" name="subject_name" placeholder="Subject Name" required>
        <textarea name="description" placeholder="Description (optional)" rows="4"></textarea>
        <button type="submit" name="add_subject">➕ Add Subject</button>
    </form>
    <button onclick="toggleAddForm()" style="width: 100%; padding: 12px; background: #dc3545; color: white; font-size: 16px; border: none; border-radius: 8px; cursor: pointer;">
        ❌ Cancel
    </button>
    </div>

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
        function toggleAddForm() {
            const form = document.getElementById('addSubjectForm');
            const toggleBtn = document.getElementById('addSubjectToggleBtn');
            if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
            toggleBtn.style.display = 'none';
            form.scrollIntoView({ behavior: 'smooth' });
            } else {
            form.style.display = 'none';
            toggleBtn.style.display = 'inline-block';
            }
        }

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
