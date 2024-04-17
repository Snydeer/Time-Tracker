<?php
    include_once 'header.php';
    include_once 'connection.php';
?>

<?php


try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

function getTimeTracksForUser($dbh, $employeeuid) {
    $stmt = $dbh->prepare("SELECT * FROM time_tracking WHERE username = ?");
    $stmt->execute([$employeeuid]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function formatElapsedTime($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;
    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}

if (!isset($_SESSION['employeeuid'])) {
    header("Location: loginpicker.php");
    exit();
}

$timeTracks = getTimeTracksForUser($dbh, $_SESSION['employeeuid']);
?>

<?php
function calculateHours($elapsed_time) {
    $hours = floor($elapsed_time / 3600);
    return $hours;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Time Tracks</title>
</head>
<body>
<h1>Timesheet</h1>
    <div class="timesheet-box">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Elapsed Time</th>
                    <th>Hours</th> <!-- New column for hours -->
                </tr>
            </thead>
            <tbody>
                <!-- Timesheet entries from database -->
                <?php foreach ($timeTracks as $track) : ?>
                    <tr>
                        <td><?php echo date('Y-m-d', strtotime($track['start_time'])); ?></td>
                        <td><?php echo date('g:i A', strtotime($track['start_time'])); ?></td>
                        <td><?php echo date('g:i A', strtotime($track['end_time'])); ?></td>
                        <td><?php echo formatElapsedTime($track['elapsed_time']); ?></td>
                        <td><?php echo calculateHours($track['elapsed_time']); ?></td> <!-- Call function to calculate hours -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="app/js/script.js"></script>
</body>
</html>


<?php
    include_once 'footer.php';
?>