<?php
    include_once 'header.php';
?>
    <html>
    <body>
        <h1> Main Dashboard</h1>
        <div class="buttons">

        <?php
                if (isset($_SESSION["useruid"])) {
                    echo '
                        <button class="main" onclick="navigateToLogin()">
                            <span>Login</span>
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
                    echo '
                        <button class="main" onclick="navigateToTimeSheet()">
                            <span>Idk</span>
                        </button>
                    ';
                }
            ?>

            <button class="main" onclick="navigateToTimeSheet()">
                <span>Timesheet</span>
            </button>
        
            <button class="main" onclick="navigateToSchedule()">
                <span>Schedule</span>
            </button>
        
            <button class="main" onclick="navigateToAnnouncement()">
                <span>Announcements</span>
              </button>

            <button class="main" onclick="navigateToPayPeriod()">
                <span>Pay Period</span>
            </button>
            
            <button class="main" onclick="navigateToSettings()">
                <span>Settings</span>
            </button>
            
        

        

    </body>
</html>

<?php
    include_once 'footer.php';
?>