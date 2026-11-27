function navigateToLogin() {
    window.location.href = "loginpicker.php";
}

function navigateToViewEmployees() {
    window.location.href = "viewallemployees.php";
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

//Dark Mode Functions

// let body = document.body; // Get the body element directly

// function toggleDarkMode() {
//     console.log("Dark mode button clicked!"); // Add this line
//     body.classList.toggle('dark');
//     body.classList.toggle('light');

//     // Store the current state in localStorage
//     let isDarkMode = body.classList.contains('dark');
//     localStorage.setItem('dark', isDarkMode);
// }

// // Check localStorage for the dark mode preference when the page loads
// window.onload = function() {
//     let isDarkMode = localStorage.getItem('dark') === 'true';

//     // Set the body class based on the stored preference
//     if (isDarkMode) {
//         body.classList.add('dark');
//     } else {
//         body.classList.add('light');
//     }
// };

function darkmode() {
    const wasDarkmode = localStorage.getItem('darkmode') === 'true';
    localStorage.setItem('darkmode', !wasDarkmode);
    const element = document.body;
    element.classList.toggle('dark', !wasDarkmode);
}

// function onload() {
//     document.body.classList.toggle('dark', localStorage.getItem('darkmode') === 'true');
// }

// function applyDarkMode() {
//     const wasDarkmode = localStorage.getItem('darkmode') === 'true';
//     const element = document.body;
//     element.classList.toggle('dark', wasDarkmode);
// }

// Call applyDarkMode() when the page loads
window.onload = function() {
    darkmode();
};

