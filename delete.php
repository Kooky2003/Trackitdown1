<?php

$encodedUsername = isset($_GET['username']) ? $_GET['username'] : '';
$decodedUsername = urldecode($encodedUsername);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
    <link rel="stylesheet" href="update.css">
    <script>
        function logout() {
            window.location.href = "login.php";
            alert("Logged Out");
        }
    </script>
</head>

<body>

    <div class="section">
        <div class="logo">
            <img src="logo.png" alt="logo">
        </div>

        <div class="links">
            <a href="homepage.php?username=<?php echo urlencode($decodedUsername); ?>" class="home">Home</a>
            <a href="pomodoro.php?username=<?php echo urlencode($decodedUsername); ?>" class="pomodoro">Pomodoro</a>
            <a href="calendar.php?username=<?php echo urlencode($decodedUsername); ?>" class="calendar">Calendar</a>
            <a href="update.php?username=<?php echo urlencode($decodedUsername); ?>" class="update">Update</a>
        </div>
        <div class="logout">
            <img src="logout.png" onclick="logout()">
        </div>
    </div>

    <div class="update">
        <div class="updateContainer">
            <div class="box">
                <img src="logo.png" alt="logo" height="38px" width="auto">
            </div>
            <form action="" method="post">
                <div class="info">
                    <div class="username">
                        <p>Username:</p>
                    </div>
                    <div class="box1">
                        <input id="username" type="text" name="username" required>
                    </div>

                    <div class="password">
                        <p>Password:</p>
                    </div>

                    <div class="box2">
                        <input id="password" type="password" name="password" required>
                    </div>

                    <div class="newpassword">
                        <button name="delete" class="delete CTA">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
    require('connection.php');

    if (isset($_POST['delete'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if($decodedUsername=$username)
        {
                    $query = "SELECT * FROM users WHERE username = '$username' AND password='$password' ";
                    $result = mysqli_query($con, $query);
            
                    if ($result->num_rows != 0) {
                        $deletetable = "DROP TABLE $username";
                        $deletedata = "DELETE FROM users WHERE username ='$username'";
            
                        $result1 = mysqli_query($con, $deletetable);
                        $result2 = mysqli_query($con, $deletedata);
                        header("Location: ./login.php");

                    } else {

                        error_log("User does not exist.");
                        echo '<script>alert("User does not exist.");</script>';
                        header("Location: ./login.php");
                        exit();
                    }
                }

        }


    ?>

</body>

</html>
