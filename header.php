<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <nav>
        <div class="wrapper">
            
            <ul>
                <li><a href = "index.php">Home</a></li>
                <li><a href = "index.php">Discover</a></li>
                <li><a href = "index.php">About Us</a></li>
                <?php
                    if (isset($_SESSION["useruid"])) {
                        echo "<p>Hello there " . $_SESSION["useruid"] . "</p>";
                        echo "<li><a href = 'profile.php'>Profile page</a></li>"; //not developed yet but will be soon
                        echo "<li><a href = 'create.php'>Create Account for Employees</a></li>";
                        echo "<li><a href = 'includes/logout.inc.php'>Log out</a></li>";
                    } else {
                        echo "<li><a href = 'signup.php'>Sign Up</a></li>";
                        echo "<li><a href = 'login.php'>Log in</a></li>";
                    }

                ?>
            </ul>
        </div>
    </nav>

<div class="wrapper">