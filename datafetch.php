<?php
require 'connection.php';

$combinedQuery = "SELECT taskname, timetaken FROM pomodoro";
$result = mysqli_query($con, $combinedQuery);

if ($result) {
    $combinedData = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($combinedData);
} else {
    echo json_encode(['error' => mysqli_error($con)]);
}

mysqli_close($con);
?>
