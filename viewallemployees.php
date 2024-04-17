
<?php
include_once 'header.php';
include_once 'connection.php';

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $company = $_SESSION["company"];
    $sql = "SELECT employees_name, employees_email, employees_uid FROM employee WHERE employees_company = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$company]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
</head>

<body>
    <h2>Employee List</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Employee ID</th>
        </tr>
        <?php foreach ($result as $row) : ?>
            <tr>
                <td><?= $row['employees_name'] ?></td>
                <td><?= $row['employees_email'] ?></td>
                <td><?= $row['employees_uid'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>

<?php
$stmt->closeCursor();
$dbh = null;

?>