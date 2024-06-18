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
        <img src="logo.png" alt="logo" height="38px" width="auto" >
</div>
        <form action=" " method="post">
          <div class="info">
            <div class="username">

              <p>Username:</p>
            </div>
            <div class="box1">
              <input id="username" type="username" name="username" required>
            </div>

            <div class="password">
              <p>Old Password:</p>
            </div>

            <div class="box2">
              <input id="password" type="password" name="oldpassword" required>
            </div>
            <div class="newpassword">

              <p>New Password:</p>
            </div>

            <div class="box3">
              <input id="password" type="password" name="newpassword" required>
            </div>

            <div class="delete">
              <p>Do you want to delete your account?</p>
              <a href="delete.php?username=<?php echo urlencode($decodedUsername); ?>" class="delete">Delete</a>
            </div>

            <button name="update" class="update CTA">Update</button>

        </form>

        <?php
        require('connection.php');

        if (isset($_POST['update'])) {
          $username = $_POST['username'];
          $oldpassword = $_POST['oldpassword'];
          $newpassword = $_POST['newpassword'];


          if ($oldpassword == $newpassword) {
            echo"<script>alert('Same password cannot be used');</script>";
          } 
            else {
              $query = "SELECT * FROM users WHERE username = '$username' AND password = '$oldpassword'";
              $res = mysqli_query($con, $query);            
            
              if ($res->num_rows > 0) {
                $updatequery = "UPDATE users SET password = '$newpassword' WHERE username ='$username'";
                mysqli_query($con, $updatequery);
    
                echo '<script>alert("Password is updated"); window.location.href="login.php";</script>';
              } else {
                echo "<script>alert('User not found or old password incorrect');</script>";
              }
          }


        }

        ?>

</body>

</html>