<?php
require 'connection.php';

$encodedUsername = isset($_GET['username']) ? $_GET['username'] : '';
$decodedUsername = urldecode($encodedUsername);

$encodedTaskname = isset($_GET['taskname']) ? $_GET['taskname'] : '';
$decodedTaskname = urldecode($encodedTaskname);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pomodoro.css">
    <title>Pomodoro Timer</title>
    <style>
        .section {
            display: flex;
            justify-content: center;
            margin: 0px;
            height: 60px;
            width: 100%;
            background-color: var(--color-buttonbg);
            color: white;
            overflow: none;
            align-items: center;
            gap: 50px;
        }
        .section a {
            display: block;
            background-color: var(--color-buttonbg);
            color: var(--color-text);
            padding: 16px 44px;
            font-size: large;
            text-decoration: none;
        }
        .section a:active {
            background-color: var(--color-buttonbg);
            color: var(--color-text);
        }
        .section a:hover:not(.active) {
            background-color: var(--color-buttonbg);
            color: #3b6653;
            border-radius: 4px;
        }
        .links {
            display: flex;
        }
        .logo {
            width: 200px;
            height: 50px;
            display: flex;
            align-items: right;
        }
        .logout {
            display: flex;
            justify-content: left;
            width: 25px;
            height: 25px;
        }
        .logout button {
            display: flex;
            width: 50px;
            height: 50px;
            border: none;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="section">
    <div class="logo">
        <img src="logo.png" alt="logo">
    </div>
    <div class="links">
        <a href="homepage.php?username=<?php echo urlencode($decodedUsername); ?>" class="home">Home</a>
        <a href="pomodoro.php?username=<?php echo urlencode($decodedUsername); ?>&taskname=<?php echo urlencode($decodedTaskname);?>" class="pomodoro">Pomodoro</a>
        <a href="calendar.php?username=<?php echo urlencode($decodedUsername); ?>" class="calendar">Calendar</a>
        <a href="update.php?username=<?php echo urlencode($decodedUsername); ?>" class="update">Update</a>
    </div>
    <div class="logout">
        <img src="logout.png" onclick="logout()">
    </div>
</div>

<h1>Pomodoro Timer</h1>
<h2>Taskname: <?php echo htmlspecialchars($decodedTaskname, ENT_QUOTES, 'UTF-8'); ?></h2>
<div id="container">
    <p id="work" class="label">Work:</p>
    <p id="break" class="label">Break:</p>
    <div id="work-timer" class="timer">
        <p id="w_minutes">25</p><p class="semicolon">:</p><p id="w_seconds">00</p>
    </div>
    <div id="break-timer" class="timer">
        <p id="b_minutes">05</p><p class="semicolon">:</p><p id="b_seconds">00</p>
    </div>
    <button id="start" class="btn">Start</button>
    <button id="stop" class="btn">Pause</button>
    <button id="reset" class="btn">End</button>
</div>

<script>
var start = document.getElementById('start');
var stop = document.getElementById('stop');
var reset = document.getElementById('reset');

var wm = document.getElementById('w_minutes');
var ws = document.getElementById('w_seconds');

var bm = document.getElementById('b_minutes');
var bs = document.getElementById('b_seconds');

var startTimer;
var startTime, endTime;

start.addEventListener('click', function(){
    startTime = new Date();
    if (startTimer === undefined) {
        startTimer = setInterval(timer, 1000);
    } else {
        alert("Timer is already running");
    }
});

reset.addEventListener('click', function(){
    endTime = new Date();
    clearInterval(startTimer);
    startTimer = undefined;
    wm.innerText = 25;
    ws.innerText = "00";
    bm.innerText = 5;
    bs.innerText = "00";
    sendTimeToDatabase(startTime, endTime);
});

stop.addEventListener('click', function(){
    stopInterval();
    startTimer = undefined;
});

function timer(){
    if (ws.innerText != 0) {
        ws.innerText--;
    } else if (wm.innerText != 0 && ws.innerText == 0) {
        ws.innerText = 59;
        wm.innerText--;
    }

    if (wm.innerText == 0 && ws.innerText == 0) {
        if (bs.innerText != 0) {
            bs.innerText--;
        } else if (bm.innerText != 0 && bs.innerText == 0) {
            bs.innerText = 59;
            bm.innerText--;
        }
    }

    if (wm.innerText == 0 && ws.innerText == 0 && bm.innerText == 0 && bs.innerText == 0) {
        wm.innerText = 25;
        ws.innerText = "00";
        bm.innerText = 5;
        bs.innerText = "00";
    }
}

function stopInterval(){
    clearInterval(startTimer);
}

function logout(){
    window.location.href="login.php";
    alert("Logged Out");
}

function sendTimeToDatabase(startTime, endTime) {
    var difference = endTime - startTime;
    var timetaken = Math.floor(difference / 1000);
    var usernamedata = "<?php echo htmlspecialchars($decodedUsername, ENT_QUOTES, 'UTF-8'); ?>";
    var tasknamedata = "<?php echo htmlspecialchars($decodedTaskname, ENT_QUOTES, 'UTF-8'); ?>";
    var pomodoroUrl = `pomodoro.php?username=${encodeURIComponent(usernamedata)}&taskname=${encodeURIComponent(tasknamedata)}`;

    console.log('Sending data to:', pomodoroUrl);
    console.log('Timetaken:', timetaken);

    fetch(pomodoroUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${encodeURIComponent(usernamedata)}&taskname=${encodeURIComponent(tasknamedata)}&difference=${timetaken}`,
    })
    .then(res => res.json())
    .then(data => {
        console.log('Response from server:', data);
    })
    .catch(err => {
        console.log('Error:', err);
    });
}
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $difference = intval($_POST['difference']);
    $taskname = isset($_POST['taskname']) ? $_POST['taskname'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';

    error_log("Received data - Username: $username, Taskname: $taskname, Difference: $difference");

    if ($difference != 0) {
        $timequery = "INSERT INTO pomodoro (timetaken, taskname, username) VALUES ($difference, '$taskname', '$username')";
        $res = mysqli_query($con, $timequery);

        if (!$res) {
            error_log("Database error: " . mysqli_error($con));
            echo json_encode(['error' => mysqli_error($con)]);
        } else {
            echo json_encode(['success' => 'Data inserted successfully']);
        }
    } else {
        error_log("Difference is 0, no data inserted");
        echo json_encode(['error' => 'Difference is 0, no data inserted']);
    }
}
?>

</body>
</html>
