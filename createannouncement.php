<?php
include_once 'header.php';
?>

<div class="new-container">
    <h2>Create Announcement</h2>

    <form method="post" action="confirm_announcement.php">
        <label for="announcement_text">Announcement Text:</label><br>
        <textarea id="announcement_text" name="announcement_text" rows="4" cols="50"></textarea><br><br>

        <label for="expiration_date">Expiration Date:</label><br>
        <input type="date" id="expiration_date" name="expiration_date"><br><br>

        <input type="submit" name="submit" value="Confirm Announcement">
    </form>
</div>

<?php
include_once 'footer.php';

?>