function navigateToLogin() {
    window.location.href = "loginpicker.php";
}

function navigateToEmployeePay() {
    window.location.href = "employeepay.php";
}

function navigateToChangePay() {
    window.location.href = "changepayroll.php";
}

function navigateToPwChange() {
    window.location.href = "changepassword.php";
}

function navigateToViewAnnouncement() {
    window.location.href = "viewannouncements.php";
}

function navigateToEmployeeLogin() {
    window.location.href = "loginemployee.php";
}

function navigateToEmployeeTimesheet() {
    window.location.href = "timemanager.php";
}

function navigateToCreateEmployee() {
    window.location.href = "create.php";
}

function navigateToSignUp() {
    window.location.href = "signup.php";
}

function navigateToDashboard() {
    window.location.href = "dashboard.php";
}

function navigateToHome() {
    window.location.href = "index.php";
}

function navigateToProfile() {
    window.location.href = "profile.php";
}

function navigateToLogout() {
    window.location.href = "includes/logout.inc.php";
}

function navigateToManagerLogin() {
    window.location.href = "login.php";
}

function navigateToProfile() {
    window.location.href = "profile.php";
}

function navigateToTimePunch() {
    window.location.href = "timetracker.php";
}

function navigateToTimeSheet() {
    window.location.href = "timeview.php";
}

function navigateToSchedule() {
    window.location.href = "/Schedule.html";
}

function navigateToAnnouncement() {
    window.location.href = "createannouncement.php";
}

function navigateToPayPeriod() {
    window.location.href = "viewpayroll.php";
}

function navigateToSettings() {
    window.location.href = "settings.php";
}

function returnBut() {
    window.location.href = "/main.html";
}

//Functions

document.addEventListener('DOMContentLoaded', function() {
    // Get the dark mode toggle element
    const darkModeToggle = document.getElementById('dark-mode-toggle');

    // Add event listener to toggle dark mode
    darkModeToggle.addEventListener('change', function() {
        // Toggle dark mode class on the body element
        document.body.classList.toggle('dark-mode');
    });
});