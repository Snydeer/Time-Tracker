
<?php
    include_once 'header.php';
?>

<html>
    <body>
        <h1> Settings</h1>
        <p> User Profile:</p>
        <p>Company: <?php echo $_SESSION['company']; ?></p>
        <p><?php echo $_SESSION['company']; ?></p>
        <div>
            <button type="submit" name="redirect">View Time Tracking</button>
        </div>
        
        
            
        

        

    </body>
</html>


<?php
include_once 'footer.php';
?>

