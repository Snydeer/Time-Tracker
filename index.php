
<?php
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
    <link rel = "stylesheet" href = "app/css/home.css">
</head>

<body>
    <header class = "remove-margin flex-container" id = "nav-divider">
        <!--have the logo be all the way to the side -->
        <div id = "logo" class = "left">
            <h1 id = "logo-txt">AC</h1>
        </div>
        <!-- use flex for buttons on the side -->
        <div id = "nav-bttn" class = "flex-container right">
           <a href = ""><button class ="txt-bttn">about</button></a>
           <a href = "dashboard.php"><button class = "txt-bttn">dashboard</button></a>
           <a href = "loginpicker.php"><button class ="login-bttn">log-in</button></a>
            <h1 class = "line"></h1>
        </div>

    </header>

   
    <main>
        <div id = "about" >
            <div class ="flex-container">
                <div class = "left">
                    <h1>Agile Company
                        Time Tracker
                    </h1>
                    <h2>This is a site that does cool things</h2>
                    <h3>Includes Features</h3>
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
                    <h1>This will be filled with different things</h1>
                </div>
            </div>
           

        </div>
    </main>
    
</body>




</html>

<?php
    include_once 'footer.php';
?>