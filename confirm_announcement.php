<?php
include_once 'header.php';
?>

<h2>Confirm Announcement</h2>

<section class="confirm-container">
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $announcement_text = htmlspecialchars($_POST["announcement_text"]);
        $expiration_date = $_POST["expiration_date"];

        echo "<div class='announcement'>";
        echo "<p>Announcement Text: $announcement_text</p>";
        echo "<p>Expiration Date: $expiration_date</p>";
        echo "</div>";
        echo "<div class='but-cont'>"; 
        echo "<form method='post' action='processannouncement.php'>";
        echo "<input type='hidden' name='announcement_text' value='$announcement_text'>";
        echo "<input type='hidden' name='expiration_date' value='$expiration_date'>";
        echo "<button type='submit' name='confirm'>Confirm</button>";
        echo "</form>";
        echo "<form method='get' action='createannouncement.php'>";
        echo "<button type='submit'>Cancel</button>";
        echo "</form>";
        echo "</div>"; 
    } else {
        header("Location: index.php");
        exit();
    }
    ?>
</section>

<?php
include_once 'footer.php';
?>

