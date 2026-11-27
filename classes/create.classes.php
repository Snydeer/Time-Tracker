<?php

class Create extends Dbh
{

    protected function setEmployee($name, $email, $username, $pwd, $company)
    {
        $stmt = $this->connect()->prepare('INSERT INTO employee (employees_name, employees_email, employees_uid, employees_pwd, employees_company) VALUES (?, ?, ?, ?, ?);');

        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        if (!$stmt->execute(array($name, $email, $username, $hashedPwd, $company))) {
            $stmt = null;
            header("location: ../create.php?error=stmtfailed");
            exit();
        }

        $stmt = null;
    }
}
