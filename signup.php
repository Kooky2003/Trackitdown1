<?php
require('connection.php');

if (isset($_POST['signup'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $retypepassword = $_POST['retypepassword'];

  if ($password !== $retypepassword) {
    echo '<script>alert("Password and Retyped password do not match");</script>';
  } else {
    $verify_query = "SELECT * FROM users WHERE username = '$username'";
    $res = mysqli_query($con, $verify_query);

    if (mysqli_num_rows($res) == 0) {
      $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
      $result = mysqli_query($con, $insert_query);

      if ($result) {
        $table_name = preg_replace('/[^a-zA-Z0-9_]/', '_', $username);
        $create_table = "CREATE TABLE `$table_name` (
          taskname VARCHAR(100),
          taskdeadline DATE,
          timedeadline TIME
        )";
        mysqli_query($con, $create_table);

        echo '<script>alert("User is added");
        window.location.href="login.php";
        </script>';
      } else {
        echo '<script>alert("Error occurred while adding user");</script>';
      }
    } else {
      echo '<script>alert("User already has an account");
      window.location.href="login.php";
      </script>';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
    <link rel="stylesheet" href="styleall.css">
</head>
<body>
    
  <div class="signup">
    <div class="sign-upContainer">
      <div class="signupbox">
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
          <div class="retypepassword">
            <p>Retype Password:</p>
          </div>
          <div class="box3">
            <input id="retypepassword" type="password" name="retypepassword" required>
          </div>
  
          <div class="txt">
            <p class="login">Already Have an Account?
              <a href="login.php">LogIn.</a>
            </p>
          </div>
  
          <button name="signup" class="button CTA">SignUp</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
