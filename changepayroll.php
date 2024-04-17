<?php
    session_start();
   include_once 'connection.php';

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

if (isset($_SESSION['useruid']) && isset($_SESSION['company'])) {
    $stmt = $dbh->prepare("SELECT e.*, p.pay_amount 
                      FROM employee e 
                      JOIN payroll p ON e.employees_uid = p.employees_uid 
                      WHERE e.employees_company = ?");
    $stmt->execute([$_SESSION['company']]);
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
    <div style="text-align: center;">
    <form action="update_pay.php" method="post">
    <link rel="stylesheet" href="app/css/pay.css">

        <table>
            <tr>
                <th>Employee Name</th>
                <th>Pay Amount</th>
            </tr>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?= $employee['employees_name'] ?></td>
                    <td>
                        <input type="text" name="pay_amount[]" value="<?= $employee['pay_amount'] ?>">
                        <input type="hidden" name="employees_uid[]" value="<?= $employee['employees_uid'] ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button type="submit" name="submit">Update Pay</button>
    </form>
    </div>

<?php
} else {
    header("Location: login.php");
    exit();
}

$dbh = null;
?>

