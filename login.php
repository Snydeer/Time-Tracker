<?php
    include_once 'header.php';
?>

    <section class="signup-form">
        <h1>Log In for Company Manager</h1>
        <div class = "login-container">
        <form action="includes/login.inc.php" method="post">
            <input class ="login" input type="text" name="uid" placeholder="Username...">
            <input class ="login" input type="password" name="pwd" placeholder="Password...">
            <button class ="login" button type="submit" name="submit">Log In</button>
        </form>
        </div>
        <div class = "error-login">
        <?php
        if(isset($_GET["error"])) {
             if ($_GET["error"] == "emptyinput") {
                echo "<p class='error-message'>Fill in all fields!</p>";
             } else if ($_GET["error"] == "wronglogin") {
                echo "<p class='error-message'>Incorrect Login Information!</p>";
             } else if ($_GET["error"] == "usernotfound") {
                echo "<p class='error-message'>Cannot find user!</p>";
             } 
            } 
        ?> 
        
        </div>
     </section>

<?php
    include_once 'footer.php';
?>