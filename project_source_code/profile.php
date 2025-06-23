<?php
include('user.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $userID = $_POST['userID'];
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $avatar_index = intval($_POST['avatar_index']);

  $sql = "UPDATE users SET name='$name', avatar_index='$avatar_index' WHERE id='$userID'";
  if (mysqli_query($conn, $sql)) {
      header("Location: profile.php"); // Replace with actual profile page filename
      exit();
  } else {
      echo "Error updating record: " . mysqli_error($conn);
  }
}
$userID=$_SESSION["userID"];
$sql_query="SELECT * FROM users where id='$userID'";
$result=mysqli_query($conn,$sql_query);
$row=mysqli_fetch_assoc($result);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $userID = $_POST['userID'];
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $avatar_index = intval($_POST['avatar_index']);

  $sql = "UPDATE users SET name='$name', avatar_index='$avatar_index' WHERE id='$userID'";
  if (mysqli_query($conn, $sql)) {
      header("Location: profile.php"); // Replace with actual profile page filename
      exit();
  } else {
      echo "Error updating record: " . mysqli_error($conn);
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Profile</title>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/profile.css">
</head>
<body>

  <!-- Background video -->
  <video src="media/homepage_vid.mp4" autoplay muted loop playsinline></video>
  <div class="overlay"></div>

  <!-- Navbar -->
  <nav class="navbar">
    <a href="user_home.php"><img src="media/logo.jpg" alt="Logo" aria-label="Website Logo"></a>
    <h1>Dungeon Knowlegde</h1>
    <div style="display: flex; align-items: center; gap: 10px; padding-right: 50px;">
      <div class="menu-toggle" id="menuToggle" aria-label="Toggle Menu">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
      </div>
      <ul class="menu" id="menu">
        <li><a href="user_home.php">Home</a></li>
        <li><a href="history.php">History</a></li>
        <li><a href="logout.php">Log out</a></li>
      </ul>
    </div>
  </nav>

  <!-- User Info Section -->
  <div class="user-info" style="text-align: center;">

  <h2>Profile</h2>

<!-- Avatar display -->
<div id="avatarDisplay">
  <img src="media/avatar<?php echo $row['avatar_index']; ?>.jpeg" alt="Profile Picture" class="profile-pic">
</div>

<!-- Editable name -->
<h3 id="nameDisplay">Name: <?php echo $row['name']?> </h3>
<h3>Username: <?php echo $row['username']?> </h3>

<!-- Edit button -->
<button onclick="enableEdit()" id="editBtn">Edit</button>

<!-- Editable form (hidden by default) -->
<form method="POST" action="profile.php" id="editForm" style="display:none; margin-top: 15px;">
  <input type="hidden" name="userID" value="<?php echo $userID; ?>">
  
  <!-- Avatar selection -->
  <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 10px;">
    <?php for ($i = 1; $i <= 7; $i++): ?>
      <label>
        <input type="radio" name="avatar_index" value="<?php echo $i; ?>" style="display:none;" <?php if($row['avatar_index'] == $i) echo "checked"; ?>>
        <img src="media/avatar<?php echo $i; ?>.jpeg" style="width: 60px; height: 60px; border-radius: 50%; border: 2px solid #fff; cursor: pointer;" onclick="selectAvatar(this)">
      </label>
    <?php endfor; ?>
  </div>

  <!-- Name edit -->
  <input type="text" name="name" value="<?php echo $row['name']?>" style="padding: 8px; font-size: 16px; width: 70%;"><br><br>

  <!-- Save and Cancel buttons -->
  <button type="submit" class="save-btn">Save</button>
  <button type="button" onclick="cancelEdit()">Cancel</button>
</form>


  <!-- Script to toggle dropdown -->
  <script>
      function enableEdit() {
      document.getElementById("editForm").style.display = "block";
      document.getElementById("editBtn").style.display = "none";
      document.getElementById("nameDisplay").style.display = "none";
    }

    function cancelEdit() {
      document.getElementById("editForm").style.display = "none";
      document.getElementById("editBtn").style.display = "inline-block";
      document.getElementById("nameDisplay").style.display = "block";
    }
    function selectAvatar(imgElement) {
      const radios = document.querySelectorAll('input[name="avatar_index"]');
      radios.forEach(r => r.checked = false);

      const images = document.querySelectorAll('label img');
      images.forEach(img => img.classList.remove("selected-avatar"));

      const parent = imgElement.closest("label").querySelector("input");
      parent.checked = true;
      imgElement.classList.add("selected-avatar");
    }

    const menuToggle = document.getElementById("menuToggle");
    const menu = document.getElementById("menu");

    menuToggle.addEventListener("click", function(event) {
      menu.classList.toggle("active");
      event.stopPropagation();
    });

    document.addEventListener("click", function(event) {
      if (!menu.contains(event.target) && !menuToggle.contains(event.target)) {
        menu.classList.remove("active");
      }
    });
  </script>
</body>
</html>
