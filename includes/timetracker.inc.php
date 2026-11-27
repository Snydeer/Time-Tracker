<?php

if (isset($_POST["clockin"])) {
    $username = $_SESSION["employeeuid"];

    include "../classes/dbh.classes.php";
    $login = new LoginContr($uid, $pwd);


    $login->loginUser();

    header("location: ../index.php?error=none");
}

function startTimeTracking()
{
    $_SESSION['start_time'] = time();
}

function stopTimeTracking()
{
    if (isset($_SESSION['start_time'])) {
        $start_time = $_SESSION['start_time'];
        $end_time = time();
        $elapsed_time = $end_time - $start_time;
        unset($_SESSION['start_time']);
        return array(
            'start_time' => date('Y-m-d H:i:s', $start_time),
            'end_time' => date('Y-m-d H:i:s', $end_time),
            'elapsed_time' => $elapsed_time
        );
    }
    return false;
}

// Check if the user wants to start or stop tracking time
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["start"])) {
        startTimeTracking();
    } elseif (isset($_POST["stop"])) {
        $time_data = stopTimeTracking();
    }
}
