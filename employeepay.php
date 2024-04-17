<?php
include_once 'connection.php';
include_once 'header.php';
?>

<?php


try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];

    $stmt = $dbh->prepare("SELECT elapsed_time FROM time_tracking WHERE username = ? AND start_time >= ? AND end_time <= ?");
    $stmt->execute([$_SESSION['employeeuid'], $start_date, $end_date]);
    $time_records = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $total_elapsed_time_hours = floor(array_sum($time_records) / 3600);

    $stmt = $dbh->prepare("SELECT employees_name FROM employee WHERE employees_uid = ?");
    $stmt->execute([$_SESSION['employeeuid']]);
    $employee_name = $stmt->fetchColumn();

    $stmt = $dbh->prepare("SELECT pay_amount FROM payroll WHERE employees_uid = ?");
    $stmt->execute([$_SESSION['employeeuid']]);
    $pay_amount = $stmt->fetchColumn();

    $total_pay = $total_elapsed_time_hours * $pay_amount;

    echo "<h2>Employee Pay Report</h2>";
    echo "<p>Employee: $employee_name</p>";
    echo "<p>Start Date: " . date("F j, Y", strtotime($start_date)) . "</p>";
    echo "<p>End Date: " . date("F j, Y", strtotime($end_date)) . "</p>";
    echo "<p>Pay Rate: $$pay_amount per hour</p>";
    echo "<p>Total Hours: $total_elapsed_time_hours hours tracked</p>";
    echo "<p>Total Pay: $$total_pay</p>";
}
$dbh = null;
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="form-payroll-employee">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date">

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date">

        <button type="submit" name="submit">View Pay</button>
    </div>
</form>


<?php
include_once 'footer.php';
?>