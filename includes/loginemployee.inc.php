<?php

if (isset($_POST["submit"])) {
    $uid = $_POST["uid"];
    $pwd = $_POST["pwd"];

    include "../classes/dbh.classes.php";
    include "../classes/loginemployee.classes.php";
    include "../classes/loginemployee-contr.classes.php";
    $login = new LoginEmployeeContr($uid, $pwd);


    $login->loginUser();

    header("location: ../dashboard.php?error=none");
}