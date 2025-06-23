<?php
include('user.php');

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
    <title>Search Results</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/search-history.css">
</head>
<body>
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
                <li><a href="profile.php">Profile</a></li>
                <li><a href="history.php">History</a></li>
                <li><a href="logout.php">Log out</a></li>
            </ul>
        </div>
    </nav>
    <header>
    <h1>Results for "<?php echo htmlspecialchars($search); ?>"</h1>
    </header>
    <form class="search-container" action="search.php" method="GET">
        <input type="text" class="search-box" name="query" placeholder="Search for quizzes..." aria-label="Search for quizzes">
        <button class="search-btn" aria-label="Search button">Search</button>
    </form>
    <div class="quiz-section">
        <?php
        if (mysqli_num_rows($result) > 0) {
            echo "<div class='quiz-grid'>";
            while ($row = mysqli_fetch_assoc($result)) {
                $subjectName = htmlspecialchars($row['subject_name']);
                $description = htmlspecialchars($row['description']);
                $subject_id = htmlspecialchars($row['id']);
                echo "<div class='quiz-card' data-subject='$subject_id'>
                        <h3>$subjectName</h3>
                        <p>$description</p>
                      </div>";
            }
            echo "</div>";
        } else {
            echo "<p>No subjects matched your search.</p>";
        }
        ?>
    </div>

    <script>
        const menuToggle = document.getElementById("menuToggle");
        const menu = document.getElementById("menu");
        const cards = document.querySelectorAll('.quiz-card');

        menuToggle.addEventListener("click", function(event) {
            menu.classList.toggle("active");
            event.stopPropagation();
        });

        document.addEventListener("click", function(event) {
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
