<?php
include_once 'header.php';
include_once 'connection.php';
date_default_timezone_set('America/New_York');
?>

<script>
    //sets timezone to whatever location the user is accessing from
    var timezoneOffsetMinutes = new Date().getTimezoneOffset();

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'set_timezone.php?timezone_offset=' + timezoneOffsetMinutes, true);
    xhr.send();
</script>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    $announcement_text = htmlspecialchars($_POST["announcement_text"]);
    $expiration_date = $_POST["expiration_date"];
    $current_date = date("Y-m-d");


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