<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Document</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="app/css/style.css">
</head>
<body>
    

        <div class="sidenav">
            <div class="image-container">
                <img src="https://upload.wikimedia.org/wikipedia/en/9/92/Bowser_Stock_Art_2021.png" alt="bowser">
            </div>

            <button class="sideBut" onclick="navigateToHome()">
                <span>Home</span>
            </button>

            <?php
                if (isset($_SESSION["useruid"])) {
                    echo '
                        <button class="sideBut" onclick="navigateToDashboard()">
                            <span>Dashboard</span>
                        </button>
                    ';
                    echo '
                        <button class="sideBut" onclick="navigateToLogout()">
                            <span>Logout</span>
                        </button>
                    ';
                } elseif (isset($_SESSION["employeeuid"])) {
                    echo '
                        <button class="sideBut" onclick="navigateToDashboard()">
                            <span>Dashboard</span>
                        </button>
                    ';
                } else {
                    echo '
                        <button class="sideBut" onclick="navigateToLogin()">
                            <span>Login</span>
                        </button>
                    ';
                }
            ?>
        </div>

        <script src="app/js/script.js"></script>
</body>
</html>

