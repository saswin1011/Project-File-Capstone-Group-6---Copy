<?php
include('user.php');
$sql_fetch="SELECT * FROM subjects";
$result=mysqli_query($conn,$sql_fetch);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Quiz Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/user_home.css">
</head>
<body>

    <!-- Top Section -->
    <div class="top-section">
        <!-- Video Background -->
        <video src="media/uservid.mp4" autoplay loop muted></video>

        <!-- Navbar -->
        <nav class="navbar">
            <!-- Left Side: Logo -->
            <a href="user_home.php"><img src="media/logo.jpg" alt="Logo" aria-label="Website Logo"></a>
            <h1>Dungeon Knowlegde</h1>
            <!-- Right Side: Menu Toggle + Dropdown -->
            <div style="display: flex; align-items: center; gap: 10px; ">
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

        <!-- Search -->
        <form class="search-container" action="search.php" method="GET">
            <input type="text" class="search-box" name="query" placeholder="Search for subjects..." aria-label="Search for quizzes">
            <button class="search-btn" aria-label="Search button">Search</button>
        </form>

    </div>

    <!-- Quiz Section -->
    <div class="subject-section">
    <?php
    if (mysqli_num_rows($result) > 0) {
        echo "<h2 class='subject-title'>Choose a Subject</h2>";
        echo "<div class='subject-grid'>";
        while ($row = mysqli_fetch_assoc($result)) {
            $subjectName = htmlspecialchars($row['subject_name']);
            $description = htmlspecialchars($row['description']);
            $subject_id = htmlspecialchars($row['id']);
            echo "
            <div class='subject-card' data-subject='$subject_id'>
                <h3>$subjectName</h3>
                <p>$description</p>
            </div>";
        }
        echo "</div>";
    } else {
        echo '<h2 class="subject-title">There are no subjects available yet</h2>';
    }
    ?>

    </div>

    <script>
        const menuToggle = document.getElementById("menuToggle");
        const menu = document.getElementById("menu");
        const cards = document.querySelectorAll('.subject-card');
    
        // Toggle menu visibility
        menuToggle.addEventListener("click", function(event) {
            menu.classList.toggle("active");
            event.stopPropagation();
        });
    
        // Close menu when clicking outside
        document.addEventListener("click", function(event) {
            if (!menu.contains(event.target) && !menuToggle.contains(event.target)) {
                menu.classList.remove("active");
            }
        });
    
        // Navigate to subject.html on card click

        cards.forEach(card => {
            card.addEventListener('click', () => {
                const subjectId = card.dataset.subject;
                window.location.href = `subject.php?subject_id=${encodeURIComponent(subjectId)}`;
            });
        });

    </script>
    
</body>
</html>
