<?php // dont use
include_once 'header.php';
?>

<?php
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["start"])) {
        startTimeTracking();
    } elseif (isset($_POST["stop"])) {
        $time_data = stopTimeTracking();
    }
}
?>

<h1>Time Tracker</h1>
<?php if (isset($time_data)) : ?>
    <p>Start Time: <?php echo $time_data['start_time']; ?></p>
    <?php if (isset($_SESSION['start_time'])) : ?>
        <p>Currently Tracking</p>
    <?php else : ?>
        <p>Stop Time: <?php echo $time_data['end_time']; ?></p>
    <?php endif; ?>
    <p>Elapsed Time: <?php echo $time_data['elapsed_time']; ?> seconds</p>
<?php endif; ?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <?php if (!isset($_SESSION['start_time'])) : ?>
        <button type="submit" name="start">Clock In</button>
    <?php else : ?>
        <button type="submit" name="stop">Clock Out</button>
    <?php endif; ?>
</form>

<?php
include_once 'footer.php';
?>