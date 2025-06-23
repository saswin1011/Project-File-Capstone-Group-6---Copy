<?php
    session_start();
    include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
            <li><a href="login.php">Log in</a></li>
        </ul>
    </nav>
    

    <div class="container">
        <h2>Sign Up</h2>
        <form action="<?php $_SERVER["PHP_SELF"] ?>" method="POST">
            <!-- Name Input -->
            <div class="input-group">
                <input type="text" id="name" name="name" placeholder="Full Name" required>
            </div>

            <!-- Username Input -->
            <div class="input-group">
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>

            <!-- Password Input -->
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>

            <!-- Submit Button -->
            <button name="signup"type="submit">Sign Up</button>
        </form>

        <div class="form-link">
            <p>Already have an account? <a href="login.php"><u>Login here</u></a></p>
        </div>
    </div>

</body>
</html>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = filter_input(INPUT_POST,"name",FILTER_SANITIZE_SPECIAL_CHARS);
        $username = filter_input(INPUT_POST,"username",FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST,"password",FILTER_SANITIZE_SPECIAL_CHARS) ;
        if (empty($username) || empty($name) || empty($password)) {
            echo '<script>alert("All fields are required.");</script>';
        } else {
            $sql_check = "SELECT * FROM users WHERE username = '$username'";
            $result = mysqli_query($conn, $sql_check);
            $hash= password_hash($password, PASSWORD_DEFAULT);

            if (mysqli_num_rows($result) > 0) {
                echo '<script>alert("Sign Up Unsuccessful! This username has already been taken.");</script>';
            } else {
                $sql_check = "SELECT * FROM admins WHERE username = '$username'";
                $result = mysqli_query($conn, $sql_check);
                if (mysqli_num_rows($result) > 0) {
                    echo '<script>alert("Sign Up Unsuccessful! This username has already been taken.");</script>';
                } else{
                    $sql_insert = "INSERT INTO users (name, username, password, avatar_index) VALUES ('$name', '$username', '$hash','1')";
                    $insert=mysqli_query($conn, $sql_insert);
                    if ($insert) {
                        $sql_query="SELECT id FROM users WHERE username='$username'";
                        $result=mysqli_query($conn,$sql_query);
                        $row=mysqli_fetch_assoc($result);
                        $_SESSION["role"] = 'user';
                        $_SESSION["userID"] = $row['id'];
                        echo"<script>window.location.href='user_home.php'</script>";
                        echo '<script>alert("You are now logged in with the account you have just signed up with.");</script>';
                        exit();
                    } else {
                        echo '<script>alert("Sign Up Failed. Please try again later.");</script>';
                    }                    
                }

            }
        }
    }
?>