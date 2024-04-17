<?php
include_once 'header.php';
?>

<section class="signup-form">
    <h2>Sign Up</h2>
    <p>Please Note that this is only for company managers or business owners.</p>
    <div class="signup-container">
        <form action="includes/signup.inc.php" method="post">
            <input class="signup" type="text" name="name" placeholder="Full name...">
            <input class="signup" type="text" name="email" placeholder="Email...">
            <input class="signup" type="text" name="uid" placeholder="Username...">
            <input class="signup" type="password" name="pwd" placeholder="Password...">
            <input class="signup" type="password" name="pwdrepeat" placeholder="Repeat Password...">
            <input class="signup" type="text" name="companyname" placeholder="Company name...">
            <button class="signup" type="submit" name="submit">Sign Up</button>
        </form>
    </div>
    <div class="error-signup">
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo "<p class ='error-message'>Fill in all fields!</p>";
            } else if ($_GET["error"] == "invaliduid") {
                echo "<p class ='error-message'>Choose a proper username!</p>";
            } else if ($_GET["error"] == "invalidemail") {
                echo "<p class ='error-message'>Choose a proper email!</p>";
            } else if ($_GET["error"] == "passwordsdontmatch") {
                echo "<p class ='error-message'>Passwords don't match!</p>";
            } else if ($_GET["error"] == "stmtfailed") {
                echo "<p class ='error-message'>Something went wrong, try again!</p>";
            } else if ($_GET["error"] == "usernametaken") {
                echo "<p class ='error-message'>Username already taken!</p>";
            } else if ($_GET["error"] == "none") {
                echo "<p class ='error-message'>You have signed up!</p>";
            } else if ($_GET["error"] == "dupecompanyaccount") {
                echo "<p class ='error-message'>Your company already has an account!</p>";
            }
        }
        ?>

    </div>
</section>

<?php
include_once 'footer.php';
?>