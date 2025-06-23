<?php
    session_start();
    include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="css/guest.css">
</head>
<body>
    <div class="video-container">
        <video autoplay loop muted playsinline>
            <source src="media/dragonvid2.mp4" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>
    </div>
    
    <nav>
        <a href="index.html"><img src="media/logo.jpg" alt="Logo"></a>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="signup.php">Sign up</a></li>
        </ul>
    </nav>

    <div class="login-container">
        <h2>Login</h2>
        <form action="<?php $_SERVER["PHP_SELF"] ?>" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="form-link">
            <p>Don't have an account? <a href="signup.php"><u>Sign up here</u></a></p>
        </div>
    </div>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $password = $_POST["password"] ?? ''; 

    // Check if user is an admin
    $sql_check = "SELECT * FROM admins WHERE username='$username'";
    $result = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($username == $row['username'] && $password == $row['password']) {
            $_SESSION["role"] = 'admin';
            echo '<script>window.location.href="admin_home.php"</script>';
            echo '<script>alert("Login successful! Redirecting admin site...")</script>';
            exit;
        } else {
            echo '<script>alert("Incorrect admin credentials")</script>';
            exit;
        }
    }

    // If not admin, check in users
    $sql_check = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result); 
        if ($username == $row["username"]) {
            if (password_verify($password, $row["password"])) {
                $_SESSION["userID"] = $row['id'];
                $_SESSION["role"] = 'user';
                echo '<script>window.location.href="user_home.php"</script>';
                echo '<script>alert("Login successful! Redirecting user page...")</script>';
                exit;
            } else {
                echo '<script>alert("Incorrect password. Please try again")</script>';
            }
        } else {
            echo '<script>alert("Incorrect username. Please try again")</script>';
        }
    } else {
        echo '<script>alert("Account does not exist.")</script>';
    }
}
?>
