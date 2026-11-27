<?php

class CreateContr extends Create
{

    private $name;
    private $email;
    private $username;
    private $pwd;
    private $company;

    public function __construct($name, $email, $username, $pwd, $company)
    {
        $this->name = $name;
        $this->email = $email;
        $this->username = $username;
        $this->pwd = $pwd;
        $this->company = $company;
    }

    public function createUser()
    {
        if ($this->emptyInput() == false) {
            header("location: ../create.php?error=emptyinput");
            exit();
        }

        $this->setEmployee($this->name, $this->email, $this->username, $this->pwd, $this->company);
    }

    private function emptyInput()
    {
        $result = false;
        if (empty($this->name) || empty($this->email)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}
