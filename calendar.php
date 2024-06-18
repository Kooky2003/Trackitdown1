<?php
$encodedUsername = isset($_GET['username']) ? $_GET['username'] : '';
$decodedUsername = urldecode($encodedUsername);

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Calendar</title>
  <link rel="stylesheet" href="calendarstyle.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet"
href="calendar.css">  <script src="calendarscript.js" defer></script>
</head>

<body>
  <div class="section">
    <div class="logo">
      <img src="logo.png" alt="logo" height=40px>

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


  </div>
  <div class="calendar">
  <div class="wrapper">
      <p class="current-date"></p>
      <ul class="weeks">
        <li>Sun</li>
        <li>Mon</li>
        <li>Tue</li>
        <li>Wed</li>
        <li>Thu</li>
        <li>Fri</li>
        <li>Sat</li>
      </ul>
      <ul class="days"></ul>
    </div>
  </div>

</body>

</html>