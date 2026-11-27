<?php

class SignupContr extends Signup
{

    private $name;
    private $email;
    private $uid;
    private $pwd;
    private $pwdrepeat;
    private $companyname;

    public function __construct($name, $email, $uid, $pwd, $pwdrepeat, $companyname)
    {
        $this->name = $name;
        $this->email = $email;
        $this->uid = $uid;
        $this->pwd = $pwd;
        $this->pwdrepeat = $pwdrepeat;
        $this->companyname = $companyname;
    }

    public function signupUser()
    {
        if ($this->emptyInput() == false) {
            header("location: ../signup.php?error=emptyinput");
            exit();
        }
        if ($this->invalidUid() == false) {
            header("location: ../signup.php?error=invaliduid");
            exit();
        }
        if ($this->invalidEmail() == false) {
            header("location: ../signup.php?error=invalidemail");
            exit();
        }
        if ($this->pwdMatch() == false) {
            header("location: ../signup.php?error=passwordsdontmatch");
            exit();
        }
        if ($this->uidTakenCheck() == false) {
            header("location: ../signup.php?error=usernametaken");
            exit();
        }

        $this->setUser($this->name, $this->email, $this->uid, $this->pwd, $this->companyname);
    }

    private function emptyInput()
    {
        $result = false;
        if (empty($this->name) || empty($this->email) || empty($this->uid) || empty($this->pwd) || empty($this->pwdrepeat) || empty($this->companyname)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function invalidUid()
    {
        $result = false;
        if (!preg_match("/^[a-zA-Z0-9]*$/", $this->uid)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function invalidEmail()
    {
        $result = false;
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function pwdMatch()
    {
        $result = false;
        if ($this->pwd !== $this->pwdrepeat) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function uidTakenCheck()
    {
        $result = false;
        if (!$this->checkUser($this->uid, $this->email)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}
