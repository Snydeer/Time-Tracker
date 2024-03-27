<?php
include_once 'header.php';

$host = 'localhost';
$dbname = 'OOPSWE';
$username = 'root';
$password = '';

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $dbh->query("SELECT * FROM announcements");
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}
?>

<div class="announcement-container" style="overflow-y: auto; height: 400px;">
    <?php foreach ($announcements as $announcement) : ?>
        <div class="announcement">
            <p><?php echo $announcement['announcement_text']; ?></p>
            <p><strong>Date Posted:</strong> <?php echo date('F j, Y', strtotime($announcement['date_posted'])); ?></p>
        </div>
        <hr> 
    <?php endforeach; ?>
</div>

<?php
// Include footer
include_once 'footer.php';
?>
