
<?php
include_once 'header.php';
include_once 'connection.php';

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

if (isset($_SESSION['useruid']) && isset($_SESSION['company'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
        $employee_uid = $_POST["employees_uid"];
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];

        $stmt = $dbh->prepare("SELECT pay_amount FROM payroll WHERE employees_uid = ?");
        $stmt->execute([$employee_uid]);
        $pay_amount = $stmt->fetchColumn();

        $stmt = $dbh->prepare("SELECT elapsed_time FROM time_tracking WHERE username = ? AND start_time >= ? AND end_time <= ?");
        $stmt->execute([$employee_uid, $start_date, $end_date]);
        $time_records = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $stmt = $dbh->prepare("SELECT employees_name FROM employee WHERE employees_uid = ?");
        $stmt->execute([$employee_uid]);
        $name = $stmt->fetchColumn();

        $total_elapsed_time_hours = floor(array_sum($time_records) / 3600);
        $total_pay = $total_elapsed_time_hours * $pay_amount;

        echo "<h2>Employee Pay Report</h2>";
        echo "<p>Employee Username: $name</p>";
        echo "<p>Employee Username: $employee_uid</p>";
        echo "<p>Start Date: " . date("F j, Y", strtotime($start_date)) . "</p>";
        echo "<p>End Date: " . date("F j, Y", strtotime($end_date)) . "</p>";
        echo "<p>Employee Pay Rate: $$pay_amount per hour</p>";
        echo "<p>Total Hours: $total_elapsed_time_hours hours tracked</p>";
        echo "<p>Total Pay: $$total_pay</p>";
    }

    $stmt = $dbh->prepare("SELECT * FROM employee WHERE employees_company = ?");
    $stmt->execute([$_SESSION['company']]);
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: login.php");
    exit();
}

$dbh = null;
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="employees_uid">Select Employee:</label>
    <select id="employees_uid" name="employees_uid">
        <?php foreach ($employees as $employee): ?>
            <option value="<?= $employee['employees_uid'] ?>"><?= $employee['employees_name'] ?></option>
        <?php endforeach; ?>
    </select>
    
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date">

    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date">

    <button type="submit" name="submit">View Pay</button>
</form>

<?php
include_once 'footer.php';
?>
