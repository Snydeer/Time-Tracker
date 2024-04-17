<?php
session_start();
include_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $pay_amounts = $_POST["pay_amount"];
    $employees_uids = $_POST["employees_uid"];

    //include_once 'connection.php';


    try {
        $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Error: Could not connect. " . $e->getMessage());
    }

    for ($i = 0; $i < count($employees_uids); $i++) {
        $employees_uid = $employees_uids[$i];
        $pay_amount = $pay_amounts[$i];

        $stmt = $dbh->prepare("UPDATE payroll SET pay_amount = :pay_amount WHERE employees_uid = :employees_uid");

        $stmt->bindParam(':pay_amount', $pay_amount);
        $stmt->bindParam(':employees_uid', $employees_uid);
        $stmt->execute();
    }

    $dbh = null;

    header("Location: profile.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}

?>

