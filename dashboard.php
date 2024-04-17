<?php
include_once 'header.php';
include_once 'connection.php';

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

$current_wage = null;
$total_hours_past_7_days = null;
$recent_announcement = null;

if (isset($_SESSION["useruid"])) {
    $dashboard_title = "Manager Dashboard";

    $sql_announcement = "SELECT announcement_text FROM announcements ORDER BY id DESC LIMIT 1";
    $stmt_announcement = $dbh->query($sql_announcement);
    $recent_announcement = $stmt_announcement->fetchColumn();
} elseif (isset($_SESSION["employeeuid"])) {
    $employee_uid = $_SESSION["employeeuid"];
    $sql_wage = "SELECT pay_amount FROM payroll WHERE employees_uid = ?";
    $stmt_wage = $dbh->prepare($sql_wage);
    $stmt_wage->execute([$employee_uid]);
    $current_wage = $stmt_wage->fetchColumn();

    $sql_total_hours = "SELECT FLOOR(SUM(elapsed_time / 3600)) AS total_hours
                        FROM time_tracking
                        WHERE username = ? AND end_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    $stmt_total_hours = $dbh->prepare($sql_total_hours);
    $stmt_total_hours->execute([$employee_uid]);
    $total_hours_past_7_days = $stmt_total_hours->fetchColumn();
    $total_hours_past_7_days = $total_hours_past_7_days !== null ? $total_hours_past_7_days : 0;

    $sql_announcement = "SELECT announcement_text FROM announcements ORDER BY id DESC LIMIT 1";
    $stmt_announcement = $dbh->query($sql_announcement);
    $recent_announcement = $stmt_announcement->fetchColumn();

    $dashboard_title = "Employee Dashboard";
}
//var_dump($total_hours_past_7_days); testing for null

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $dashboard_title; ?></title>
    <style>
        .dashboard-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #dddddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container h1,
        .dashboard-container h2,
        .dashboard-container p {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }

        .dashboard-container h1 {
            font-size: 24px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="dashboard-container">
        <h1><?php echo $dashboard_title; ?></h1>
        <div class="box">
            <?php if (isset($_SESSION["useruid"])) : ?>
                <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
                <h2>Recent Announcement</h2>
                <p><?php echo $recent_announcement; ?></p>
            <?php elseif (isset($_SESSION["employeeuid"])) : ?>
                <h2>Welcome, <?php echo $_SESSION['employeename']; ?>!</h2>
                <h2>Current Wage</h2>
                <p>Current Wage: $<?php echo $current_wage; ?> per hour</p>
                <h2>Total Hours in Past 7 days</h2>
                <p>Total Hours in the Past 7 Days: <?php echo $total_hours_past_7_days; ?> hours</p>
                <h2>Recent Announcement</h2>
                <p><?php echo $recent_announcement; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php include_once 'footer.php'; ?>

</body>

</html>