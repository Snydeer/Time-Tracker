<?php
include_once 'header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    $announcement_text = htmlspecialchars($_POST["announcement_text"]);
    $expiration_date = $_POST["expiration_date"];
    $current_date = date("Y-m-d");

    $host = 'localhost';
    $dbname = 'OOPSWE';
    $username = 'root';
    $password = '';

    try {
        $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $dbh->prepare("INSERT INTO announcements (announcement_text, date_posted, expiration_date) VALUES (?, ?, ?)");
        $stmt->execute([$announcement_text, $current_date, $expiration_date]);

        header("Location: dashboard.php");
        exit;
    } catch (PDOException $e) {
        die("Error: Could not connect. " . $e->getMessage());
    }
} else {
    header("Location: createannouncement.php");
    exit();
}

include_once 'footer.php';
?>