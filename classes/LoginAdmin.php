<?php
class LoginAdmin
{
    public $message;
    public function __construct($username, $password)
    {
        $db = new DatabaseCon();
        $username = trim($username);
        $pass = trim($password);
        $query = $db->databaseCon->prepare("SELECT admin_id, username, admin_password FROM Admins WHERE username = '{$username}' LIMIT 1");
        if($query->execute())
        {
            $found_user = $query->fetchAll();
            if (count($found_user)==1){
                if(password_verify($pass, $found_user[0]['admin_password']))
                {
                    $_SESSION['admin_id'] = $found_user[0]['admin_id'];
                    $_SESSION['username'] = $found_user[0]['username'];
                    $redirect = new Redirector("company.php");
                }
                else
                {
                    // username/password combo was not found in the database
                    $this->message = "Username/password combination incorrect.<br />
					Please make sure your caps lock key is off and try again.";
                }
            }
            else
            {
                $this->message = "No such Username in the database.<br />
				Please make sure your caps lock key is off and try again.";
            }
        }
    }
}