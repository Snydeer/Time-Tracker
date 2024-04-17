
<?php
include_once 'header.php';
include_once 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
</head>
<body>

<?php
try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $dbh->query("SELECT * FROM announcements");
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($announcements) === 0) {
        echo '<div class="announcement-container">';
        echo '<p>No Announcements</p>';
        echo '</div>';
    } else {
        echo '<div class="announcement-container" style="overflow-y: auto; height: 400px;">';
        foreach ($announcements as $announcement) {
            echo '<div class="announcement">';
            echo '<p>' . $announcement['announcement_text'] . '</p>';
            echo '<p><strong>Date Posted:</strong> ' . date('F j, Y', strtotime($announcement['date_posted'])) . '</p>';
            echo '</div>';
            echo '<hr>';
        }
        echo '</div>';
    }
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

include_once 'footer.php';
?>

</body>
</html>