<?php
class RegisterNewAdmin
{
    public $message;
    public function __construct($fname, $lname, $username, $password)
    {
        // perform validations on the form data
        $db = new DatabaseCon();
        $fname = trim($fname);
        $lname = trim($lname);
        $user = trim($username);
        $pass = trim($password);
        $iterations = ['cost' => 11];
        $hashed_password = password_hash($pass, PASSWORD_BCRYPT, $iterations);
        $query = $db->databaseCon->prepare("INSERT INTO `admins` (first_name, last_name, username, admin_password) VALUES ('{$fname}', '{$lname}', '{$user}', '{$hashed_password}')");

        if ($query->execute()) {
            $this->message = "User Created.";
        } else {
            $this->message = "User could not be created.";
        }
        $db->DatabaseClose();
    }
}
