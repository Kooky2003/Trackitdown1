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
    <style>
        /* Your CSS styles here */
        .chart-container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
    </style>
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
                        <canvas id="myChart"></canvas>
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

        async function fetchData() {
            try {
                const response = await fetch('http://localhost/Project/codes/datafetch.php');
                if (!response.ok) {
                    throw new Error('Failed to fetch data');
                }
                const data = await response.json();

                console.log(data);

                processData(data);
            } catch (error) {
                console.error('Error fetching data:', error.message);
            }
        }

        function processData(data) {
            if (!Array.isArray(data)) {
                console.error('Invalid data format');
                return;
            }
            const chartData = {
                labels: data.map(item => item.taskname),
                data: data.map(item => item.timetaken),
            };

            createDoughnutChart(chartData);
            populateUl(chartData);
        }

        function createDoughnutChart(chartData) {
            const myChart = document.getElementById("myChart");

            new Chart(myChart, {
                type: "doughnut",
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: "Time spent",
                            data: chartData.data,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                                'rgba(255, 159, 64, 0.6)'
                            ]
                        },
                    ],
                },
                options: {
                    borderWidth: 10,
                    borderRadius: 2,
                    hoverBorderWidth: 0,
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                    tooltips: {
                        enabled: false,
                    },
                },
            });
        }

        const ul = document.querySelector(".programming-stats .details ul");

        function populateUl(chartData) {
            if (!Array.isArray(chartData.labels) || !Array.isArray(chartData.data) || chartData.labels.length !== chartData.data.length) {
                console.error('Invalid chart data');
                return;
            }
            ul.innerHTML = ''; // Clear previous data
            chartData.labels.forEach((label, i) => {
                let li = document.createElement("li");
                li.innerHTML = `${label}: <span class='percentage'>${chartData.data[i]}sec</span>`;
                ul.appendChild(li);
            });
        }

        fetchData();
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
                          <div class="pomodoro"><a href="pomodoro.php?username=' . urlencode($decodedUsername) . '&taskname=' . urlencode($columnValue) . '
