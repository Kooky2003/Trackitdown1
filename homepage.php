<?php

$encodedUsername = isset($_GET['username']) ? $_GET['username'] : '';
$decodedUsername = urldecode($encodedUsername);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="homepage.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    <div class="section">
        <div class="logo">
            <img src="logo.png" alt="logo" height="40px">
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

    <!-- todolist -->
    <div class="main">
        <div class="container">
            <div class="todolist">
                <button class="btn" id="btn" onclick="openpopup()">Add Task</button><br>

                <ul id="listcontainer"></ul>

            </div>
            <!-- graph -->
            <div class="graph">
                <h2 class="chart-heading">Productivity Meter</h2>
                <div class="programming-stats">
                    <div class="chart-container">
                    <script src="script.js"></script>

                        <canvas class="my-chart">
                        </canvas>
                    </div>
                    <div class="details">
                        <ul></ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="popup" id="popup">
            <img src="logo.png" alt="logo">
            <h2>Add a task</h2>
            <div class="data">
                <form method="POST">
                    <input type="text" name="taskname" id="taskname" required>
                    <label for="taskdeadline">Task Deadline: (Date) </label>
                    <input type="date" name="taskdeadline" required>
                    <label for="taskdeadline">Task Deadline (Time): </label>
                    <input type="time" name="timedeadline" required>
                    <button type="submit" name="done" id="done" onclick="closepopup()">Done</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let popup = document.getElementById('popup');

        function openpopup() {
            popup.classList.add("open-popup");
        }

        function closepopup() {
            popup.classList.remove("open-popup");
        }

        function logout() {
            window.location.href = "login.php";
            alert("Logged Out");
        }
    </script>

    <?php
    require 'connection.php';

    if (isset($_POST['done'])) {
        $taskname = $_POST['taskname'];
        $timedeadline = $_POST['timedeadline'];
        $taskdeadline = $_POST['taskdeadline'];

        $insert_query = "INSERT INTO $decodedUsername (taskname, taskdeadline, timedeadline) VALUES ('$taskname', '$taskdeadline', '$timedeadline')";

        $output = mysqli_query($con, $insert_query);

        if (!$output) {
            echo "Error: " . $insert_query . "<br>" . mysqli_error($con);
        }
    }

    $query = "SELECT taskname FROM $decodedUsername";
    $task_query = mysqli_query($con, $query);

    if ($task_query->num_rows != 0) {
        while ($row = mysqli_fetch_assoc($task_query)) {
            foreach ($row as $columnName => $columnValue) {
                echo '<div class="container">
                          <div class="taskshow" onclick="taskdone(this)"></div>' . $columnValue . '
                          <div class="pomodoro"><a href="pomodoro.php?username=' . urlencode($decodedUsername) . '&taskname=' . urlencode($columnValue) . '"><img src="right.png" onclick="datapass(\'' . $columnValue . '\')"></a></div>
                      </div>';
            }
        }
    } else {
        echo "<script>alert('No data added');</script>";
    }
    ?>

    <script src="script.js"></script>
</body>
</html>
