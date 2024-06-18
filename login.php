
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="stylelogin.css">

  </head>
  <body>
    <div class="container">
      <div class="col1">
        <div class="formInformation">
          <div class="box">
            <img src="./logo.png" alt="logo" height="38px" width="auto" />
          </div>
          <form action=" " method="POST">
            <div class="welcomeInfo">
              <h1>WELCOME BACK!</h1>
              <p>
                Simplify your workflow and boost your productivity <br />
                with <span>Track It Down</span> App. Get started for free
              </p>
            </div>
            <div class="info">
              <div class="username">
                <p>Username:</p>
              </div>
              <div class="box1">
                <input id="username" type="username" name="username" required />
              </div>
              <div class="password">
                <p>Password:</p>
              </div>
              <div class="box2">
                <input id="password" type="password" name="password" required />
              </div>

              <p id="forgetPassword">
                <a href="">Forgot Password</a>
              </p>
              <button class="button CTA" name ="login">Log In</button>
              <p id="signup">
                Don't have an account?<a href="signup.php">
                  Create an account</a
                >
              </p>
            </div>
          </form>
        </div>
      </div>

        </div>
      </div>
    </div>
  </body>
</html>


<?php

require('connection.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE username = '$username' AND password='$password' ";
    $result = mysqli_query($con, $query);


    if ($result->num_rows != 0) {
        header("Location: homepage.php?username=".urlencode($username));
        exit();
    }  
        else {
          echo('<Script>alert("User doesnot exist");</Script>');
            header("Location:./login.php");
            exit();



        }
}
$con->close();

?>


