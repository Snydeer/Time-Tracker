function navigateToLogin() {
    window.location.href = "loginpicker.php";
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
    window.location.href = "/announcements.html";
}

function navigateToPayPeriod() {
    window.location.href = "/payPeriod.html";
}

function navigateToSettings() {
    window.location.href = "settings.php";
}

function returnBut() {
    window.location.href = "/main.html";
}



/**/

//get root
var r = document.querySelector(':root')

//create function for changing variable 

function toggleDarkMode(){
    var rs = getComputerStyle(r);


    //get temp value 
    var text = rs.getPropertyValue('--text1');
    var main = rs.getPropertyValue('--main1');
    var secondary = rs.getPropertyValue('--secondary1');
    var hover = rs.getPropertyValue('--hover1');

    //set primary to secondary
    r.computedStyleMap.setProperty('--text1', rs.getPropertyValue('--text2'));
    r.computedStyleMap.setProperty('--main1', rs.getPropertyValue('--main2'));
    r.computedStyleMap.setProperty('--secondary1', rs.getPropertyValue('--secondary2'));
    r.computedStyleMap.setProperty('--hover1',rs.getPropertyValue('--hover2'));


    //set secondary to temp
    r.computedStyleMap.setProperty('--text2',text);
    r.computedStyleMap.setProperty('--main2',main);
    r.computedStyleMap.setProperty('--secondary2',secondary);
    r.computedStyleMap.setProperty('--hover2', hover);


}