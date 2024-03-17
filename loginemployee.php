<?php
    include_once 'header.php';
?>

    <section class="signup-form">
        <h2>Log In for Employees</h2>
        <form action="includes/loginemployee.inc.php" method="post">
            <input type="text" name="uid" placeholder="Username/Email...">
            <input type="password" name="pwd" placeholder="Password...">
            <button type="submit" name="submit">Log In</button>
        </form>
        <?php
        if(isset($_GET["error"])) {
             if ($_GET["error"] == "emptyinput") {
                 echo "<p>Fill in all fields!</p>";
             } else if ($_GET["error"] == "wronglogin") {
                 echo "<p>Incorrect Login Information!</p>";
             } 
            } 
        ?> 
     </section>

<?php
    include_once 'footer.php';
?>