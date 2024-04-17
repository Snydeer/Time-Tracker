<?php

session_start();

?>

<!DOCTYPE html>
<html>


<head>
    <title>Homepage</title>
    <link rel="stylesheet" href="app/css/home.css">
          <meta charset="UTF-8">
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body onload="onload()">
    <header class="remove-margin flex-container" id="nav-divider">
        <!--have the logo be all the way to the side -->
        <div class="image-container">
            <img src="BigLogoV1.png" alt="Logo" width="100em">
        </div>
        <!-- use flex for buttons on the side -->
        <div id="nav-bttn" class="flex-container right">
            <button id ="darkmode" onclick = "darkmode()">dark mode</button>
            <a href=""><button class="txt-bttn header-text">about</button></a>
            <a href="dashboard.php"><button class="txt-bttn header-text">dashboard</button></a>
            <?php

            if (isset($_SESSION['useruid']) || isset($_SESSION['employeeuid'])) {
                echo '<a href="includes/logout.inc.php"><button class="login-bttn">log-out</button></a>';
            } else {
                echo '<a href="loginpicker.php"><button class="login-bttn">log-in</button></a>';
            }
            ?>
            <!--<h1 class = "line"></h1>-->
        </div>


        </header>

        <main>
            <div id = "about" >
                <div class ="flex-container">
                    <div class = "left">
                        <h1 class = "title">Agile Company Time Tracker</h1>
                        <h2 class = "sub">This is a site that does cool things</h2>
                        <h3 class = "sub2">Includes Features</h3>
                        <h3>
                            <ul>
                                <li>schedule</li>
                                <li>clock in</li>
                                <li>pay period info</li>
                                <li>announcement messages from employer</li>
                            </ul>
                        </h3>
                    </div>
                    <div class = "right">
                        <img id  = "main-image" src = "resources/actt.png" ></img>
                    </div>
                </div>
            </div>
        </main>
        <script src = "app/js/script.js"></script>
    </body>


<!--</html>-->

<?php
include_once 'footer.php';
?>