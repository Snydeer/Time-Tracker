<?php


if (isset($_POST["submit"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $username = generateUsername($name);
    $pwd = $username;
    $company = $_POST["company"];

    include "../classes/dbh.classes.php";
    include "../classes/create.classes.php";
    include "../classes/create-contr.classes.php";
    $create = new CreateContr($name, $email, $username, $pwd, $company);


    $create->createUser();

    header("location: ../dashboard.php?error=none");
}

function generateUsername($name)
{

    $name = strtolower(str_replace(' ', '', $name));

    $randomNumber = rand(1000, 9999);

    $username = $name . $randomNumber;

    return $username;
}
