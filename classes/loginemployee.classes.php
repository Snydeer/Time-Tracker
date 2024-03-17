<?php

class LoginEmployee extends Dbh {

    protected function getUser($uid, $pwd) {
        $stmt = $this->connect()->prepare('SELECT employees_pwd FROM employee WHERE employees_uid = ? OR employees_email = ?;');

        if (!$stmt->execute(array($uid, $pwd))) {
            $stmt = null;
            header("location: ../loginemployee.php?error=stmtfailed");
            exit();
        }

        if($stmt->rowCount() == 0) {
            $stmt = null;
            header("location: ../loginemployee.php?error=usernotfound1");
            exit();
        }

        $pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($pwd, $pwdHashed[0]["employees_pwd"]);

        if($checkPwd == false) {
            $stmt = null;
            header("location: ../loginemployee.php?error=usernotfound");
            exit();
        } elseif($checkPwd == true) {
            $stmt = $this->connect()->prepare('SELECT * FROM employee WHERE employees_uid = ? OR employees_email = ? AND employees_pwd = ?;');

            if (!$stmt->execute(array($uid, $uid, $pwd))) {
                $stmt = null;
                header("location: ../loginemployee.php?error=stmtfailed");
                exit();
            }

            if($stmt->rowCount() == 0) {
                $stmt = null;
                header("location: ../loginemployee.php?error=usernotfound2");
                exit();
            }

            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            session_start();
            $_SESSION["employeeid"] = $user[0]["employees_id"];
            $_SESSION["employeeuid"] = $user[0]["employees_uid"];
            $_SESSION["employeename"] = $user[0]["employees_name"];

            $stmt = null;
        }

        
    }

    

}