<?php
    include_once 'header.php';
?>

    <section class="create-form">
            <h2>Please Enter your Employee's information</h2>
            <p>Please Note that this will register your employee to your current company as well as create a temporary password using their information</p>
            <div class = "signup-container">
        <form action="includes/create.inc.php" method="post">
            <input class = "signup" type="text" name="name" placeholder="Full name...">
            <input class = "signup" type="text" name="email" placeholder="Email...">
            <input class = "signup" type="text" name="company" value="<?php echo $_SESSION["company"]; ?>" readonly>
            <button class = "signup" type="submit" name="submit">Create</button>
        </form>
        </div>
        <div class = "error-signup">
        <?php
        if(isset($_GET["error"])) {
             if ($_GET["error"] == "emptyinput") {
                 echo "<p class ='error-message'>Fill in all fields!</p>";
             } else if ($_GET["error"] == "invaliduid") {
                 echo "<p class ='error-message'>Choose a proper username!</p>";
             } else if ($_GET["error"] == "invalidemail") {
                 echo "<p class ='error-message'>Choose a proper email!</p>";
             } else if ($_GET["error"] == "stmtfailed") {
                 echo "<p class ='error-message'>Something went wrong, try again!</p>";
                } else if ($_GET["error"] == "none") {
                    echo "<p class ='error-message'>You have created the employee's account!</p>";
                } 
            } 
        ?> 
        </div>

    </section> 

<?php
    include_once 'footer.php';
?>