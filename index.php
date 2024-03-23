<?php
    include_once 'header.php';
?>
    <html>
    <body>
        <h1>Welcome to Agile Company</h1>
        <h2>Write an about us or smth here</h2>
        <div class="buttons">

            

            <button class="main" onclick="navigateToTimePunch()">
                <span>Time Punch</span>
            </button>

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