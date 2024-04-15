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
                <img src="BigLogoV1.png" alt="Logo">
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
                        <button class="sideBut" onclick="navigateToProfile()">
                            <span>Profile</span>
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
                    echo '
                        <button class="sideBut" onclick="navigateToProfile()">
                            <span>Profile</span>
                        </button>
                    ';
                    echo '
                        <button class="sideBut" onclick="navigateToLogout()">
                            <span>Logout</span>
                        </button>
                    ';
                } else {
                    echo '
                        <button class="sideBut" onclick="navigateToSignUp()">
                            <span>Sign Up</span>
                        </button>
                    ';
                    echo '
                        <button class="sideBut" onclick="navigateToLogin()">
                            <span>Login</span>
                        </button>
                    ';
                }
            ?>
        </div>
        <header>
        <div class="buttons">

        <?php
                if (isset($_SESSION["useruid"])) {
                    echo '
                        <button class="main" onclick="navigateToCreateEmployee()">
                            <span>Create Employee Account</span>
                        </button>
                    ';
                    echo '
                        <button class="main" onclick="navigateToEmployeeTimesheet()">
                            <span>Employee Timesheet</span>
                        </button>
                    ';
                    echo '
                        <button class="main" onclick="navigateToAnnouncement()">
                            <span>Create Announcements</span>
                        </button>
                    ';
                    echo '
                        <button class="main" onclick="navigateToViewAnnouncement()">
                            <span>View Announcements</span>
                        </button>
                    ';
                    echo '
                        <button class="main" onclick="navigateToPayPeriod()">
                            <span>Pay Period</span>
                        </button>
                    ';
                    echo '
                        <button class="main" onclick="navigateToSettings()">
                            <span>Settings</span>
                        </button>
                    ';
                } elseif (isset($_SESSION["employeeuid"])) {
                    echo '
                        <button class="main" onclick="navigateToTimePunch()">
                            <span>Time Punch</span>
                         </button>
                    ';
                    echo '
                        <button class="main" onclick="navigateToTimeSheet()">
                            <span>Time Sheet</span>
                         </button>
                    ';
                } else {
                    // header("location: ../index.php");
                }
            ?>
        
            



            
            
            <button class="main" onclick="navigateToHome()">
                <span>Home</span>
            </button>
            
        

        

    </header>
        

        <script src="app/js/script.js"></script>
</body>
</html>

