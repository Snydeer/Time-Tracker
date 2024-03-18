<?php
    include_once 'header.php';
?>

<?php

$host = 'localhost';
$dbname = 'OOPSWE';
$username = 'root';
$password = '';

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

$stmt = $dbh->prepare("SELECT * FROM employee WHERE employees_company = ?");
$stmt->execute([$_SESSION['company']]);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["select_employee"])) {
    $selected_employee_id = $_POST["employee_id"];

   // var_dump($selected_employee_id); was used to test to make sure the names were correct (debugging)

    $stmt = $dbh->prepare("SELECT * FROM time_tracking WHERE username = ?");
    $stmt->execute([$selected_employee_id]);
    $time_tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Manager</title>
</head>
<body>
    <h1>Time Manager</h1>
    <p>Welcome, Manager of <?php echo $_SESSION['company']; ?>!</p>
    
    <!-- Employee Selection Form -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="employee_id">Select an Employee:</label>
    <select name="employee_id" id="employee_id">
        <?php foreach ($employees as $employee) : ?>
            <option value="<?php echo $employee['employees_uid']; ?>"><?php echo $employee['employees_name']; ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="select_employee">View Time Tracking</button>
    </form>

    <!-- Display Time Tracking Records -->
    <?php if (isset($time_tracks)) : ?>
    <h2>Time Tracking Records for <?php echo $selected_employee_id; ?></h2>
    <table>
        <thead>
            <tr>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Elapsed Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($time_tracks as $track) : ?>
                <tr>
                    <td><?php echo date('F j, Y H:i:s', strtotime($track['start_time'])); ?></td>
                    <td><?php echo date('F j, Y H:i:s', strtotime($track['end_time'])); ?></td>
                    <td><?php echo formatElapsedTime($track['elapsed_time']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php

function formatElapsedTime($elapsed_time) {
    $hours = floor($elapsed_time / 3600);
    $minutes = floor(($elapsed_time % 3600) / 60);
    $seconds = $elapsed_time % 60;
    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}
?>

<?php
    include_once 'footer.php';
?>