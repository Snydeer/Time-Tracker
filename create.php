<?php
    include_once 'header.php';
?>

    <section class="create-form">
            <h2>Please Enter your Employee's information</h2>
            <p>Please Note that this will register your employee to your current company as well as create a temporary password using their information</p>
        <form action="includes/create.inc.php" method="post">
            <input type="text" name="name" placeholder="Full name...">
            <input type="text" name="email" placeholder="Email...">
            <input type="text" name="company" value="<?php echo $_SESSION["company"]; ?>" readonly>
            <button type="submit" name="submit">Create</button>
        </form>
        <?php
        if(isset($_GET["error"])) {
             if ($_GET["error"] == "emptyinput") {
                 echo "<p>Fill in all fields!</p>";
             } else if ($_GET["error"] == "invaliduid") {
                 echo "<p>Choose a proper username!</p>";
             } else if ($_GET["error"] == "invalidemail") {
                 echo "<p>Choose a proper email!</p>";
             } else if ($_GET["error"] == "stmtfailed") {
                 echo "<p>Something went wrong, try again!</p>";
                } else if ($_GET["error"] == "none") {
                    echo "<p>You have created the employee's account!</p>";
                } 
            } 
        ?> 

    </section> 

<?php
    include_once 'footer.php';
?>
