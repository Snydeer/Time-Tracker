<?php
include_once 'header.php';
include_once 'connection.php';
date_default_timezone_set('America/New_York');
?>

<script>
    //sets timezone to whatever location the user is accessing from
    var timezoneOffsetMinutes = new Date().getTimezoneOffset();

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'set_timezone.php?timezone_offset=' + timezoneOffsetMinutes, true);
    xhr.send();
</script>

<?php

$display = true;

//connection to sql


try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

$mostRecentTrackingDetails = getMostRecentTimeTrackingDetails($dbh, $_SESSION['employeeuid']);

//begin time tracking function

function startTimeTracking($dbh, $username)
{
    $stmt = $dbh->prepare("INSERT INTO time_tracking (start_time, username) VALUES (NOW(), ?)");
    $stmt->execute([$username]);
    $_SESSION['tracking_id'] = $dbh->lastInsertId();
    $_SESSION['start_time'] = date('Y-m-d H:i:s');
    //var_dump($_SESSION['start_time']); (debugging)
}

//stopping

function stopTimeTracking($dbh)
{
    if (isset($_SESSION['tracking_id'])) {
        $display = true;
        $tracking_id = $_SESSION['tracking_id'];
        $original_start_time = $_SESSION['start_time'];
        $stmt = $dbh->prepare("UPDATE time_tracking SET end_time = NOW(), start_time = ?, elapsed_time = TIMESTAMPDIFF(SECOND, ?, NOW()) WHERE id = ?");
        $stmt->execute([$original_start_time, $original_start_time, $tracking_id]);

        unset($_SESSION['tracking_id']);
        unset($_SESSION['start_time']);
        $mostRecentTrackingDetails = getMostRecentTimeTrackingDetails($dbh, $_SESSION['employeeuid']);
    }
}

//getting the current details to send to database

function getTimeTrackingDetails($dbh, $tracking_id)
{
    $stmt = $dbh->prepare("SELECT start_time, end_time, elapsed_time FROM time_tracking WHERE id = ?");
    $stmt->execute([$tracking_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//if clicked start button, use employeeuid to start adding data to database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["start"])) {
        $display = false;
        if (!isset($_SESSION['tracking_id'])) {
            startTimeTracking($dbh, $_SESSION['employeeuid']);
        }
    } elseif (isset($_POST["stop"])) {
        stopTimeTracking($dbh);
        $mostRecentTrackingDetails = getMostRecentTimeTrackingDetails($dbh, $_SESSION['employeeuid']);
    } elseif (isset($_POST["stopafter"])) {
        stopAfterTimeTracking($dbh);
        $mostRecentTrackingDetails = getMostRecentTimeTrackingDetails($dbh, $_SESSION['employeeuid']);
    }
}

function stopAfterTimeTracking($dbh)
{
    // Retrieve the most recent tracking details
    $mostRecentTrackingDetails = getMostRecentTimeTrackingDetails($dbh, $_SESSION['employeeuid']);

    if ($mostRecentTrackingDetails && isset($mostRecentTrackingDetails['id'])) {
        $tracking_id = $mostRecentTrackingDetails['id'];
        $before = $mostRecentTrackingDetails['start_time'];
        // Update end time to NOW() and calculate elapsed time
        $stmt = $dbh->prepare("UPDATE time_tracking SET start_time = ?, end_time = NOW(), elapsed_time = TIMESTAMPDIFF(SECOND, ?, NOW()) WHERE id = ?");
        $stmt->execute([$before, $before, $tracking_id]);
    }
}

//gets the most recent amount of work time

function getMostRecentTimeTrackingDetails($dbh, $username)
{
    $stmt = $dbh->prepare("SELECT id, start_time, end_time, elapsed_time FROM time_tracking WHERE username = ? ORDER BY start_time DESC LIMIT 1");
    $stmt->execute([$username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//html




?>

<!DOCTYPE html>
<html lang="en">

<div style="text-align: center;">
    <!--var_dump($mostRecentTrackingDetails); -->
</div>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Tracker</title>
</head>



<body>
    <h1>Time Tracker</h1>

    <div class="timesheetbox">


        <?php if (isset($_SESSION['tracking_id'])) : ?>
            <div class="title">Currently Tracking</div>
            <?php $trackingDetails = getTimeTrackingDetails($dbh, $_SESSION['tracking_id']); ?>
            <p>Start Time: <?php echo $trackingDetails['start_time']; ?></p>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <button class="timeSheetBut" type="submit" name="stop">
                    <span>Clock out</span>
                </button>
            </form>
        <?php elseif ($mostRecentTrackingDetails['elapsed_time'] === null) : ?>
            <div class="title">Currently Tracking</div>
            <p>Start Time: <?php echo $mostRecentTrackingDetails['start_time']; ?></p>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <button class="timeSheetBut" type="submit" name="stopafter">
                    <span>Clock out</span>
                </button>
            </form>

        <?php else : ?>
            <div class="title">Time Not Tracked</div>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <button class="timeSheetBut" type="submit" name="start">
                    <span>Clock in</span>
                </button>
            </form>
        <?php endif; ?>

    </div>


    <?php if (isset($_SESSION['tracking_id'])) : ?>
        <?php $trackingDetails = getTimeTrackingDetails($dbh, $_SESSION['tracking_id']); ?>
        <p>Start Time: <?php echo $trackingDetails['start_time']; ?></p>
        <div class="center-container">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <button type="submit" name="stop">Clock Out</button>
            </form>
            <!-- <p>nope</p> used to help test -->
        </div>
    <?php elseif ($mostRecentTrackingDetails['elapsed_time'] === null) : ?>
        <p>Start Time: <?php echo $mostRecentTrackingDetails['start_time']; ?></p>
        <div class="center-container">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <button type="submit" name="stopafter">Clock Out</button>
            </form>
            <!--<p>yup</p> dw about this-->
        </div>

    <?php else : ?>
        <p>Time Not Tracked</p>
        <div class="center-container">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <button type="submit" name="start">Clock In</button>
            </form>
        </div>
    <?php endif; ?>
</body>


<?php

//use the most recent tracked from employee and displays

$mostRecentTrackingDetails = getMostRecentTimeTrackingDetails($dbh, $_SESSION['employeeuid']);

if ($mostRecentTrackingDetails && !$mostRecentTrackingDetails['elapsed_time'] && !isset($_SESSION['tracking_id'])) {
    $lastTrackingMessage = "Your last tracking session was not completed.";
} else {
    $lastTrackingMessage = "";
}

if ($mostRecentTrackingDetails) {

    $startTime = date("F j, Y, g:i a", strtotime($mostRecentTrackingDetails['start_time']));

    $endTime = date("F j, Y, g:i a", strtotime($mostRecentTrackingDetails['end_time']));

    $elapsedTime = $mostRecentTrackingDetails['elapsed_time'];
    $hours = floor($elapsedTime / 3600);
    $minutes = floor(($elapsedTime / 60) % 60);
    $seconds = $elapsedTime % 60;

    echo "<h2>Most Recent Time Tracking Details</h2>";
    echo "<p>Start Time: " . $startTime . "</p>";
    if ($display) {
        echo "<p>End Time: " . $endTime . "</p>";
    } else {
        echo "<p>Currently tracking</p?>";
    }
    echo "<p>Elapsed Time: " . $hours . " hours, " . $minutes . " minutes, " . $seconds . " seconds</p>";
} else {
    echo "<p>No time tracking details available.</p>"; //if you have none previously
}

?>
<?php echo "<p>$lastTrackingMessage</p>"; ?>

<?php
include_once 'footer.php';
?>