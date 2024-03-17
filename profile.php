<?php
    include_once 'header.php';
?>
<?php
    if (isset($_SESSION["useruid"])) {
        $current = $_SESSION["username"];
    } else {
        $current = $_SESSION["employeename"];
    }

?>

    <section class="Picker">
        <h2>Welcome, <?php echo $current; ?>!</h2>
        <?php
                    if (isset($_SESSION["useruid"])) {
                        echo "<li><a href = 'changepassword.php'>Change Password</a></li>";
                    } elseif (isset($_SESSION["employeeuid"])) {
                        echo "<li><a href = 'changepassword.php'>Change Password</a></li>";
                    } 

                ?>
     </section>

<?php
    include_once 'footer.php';
?>