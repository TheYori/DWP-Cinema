<?php
class LoginUser
{
    public $message;
    public function __construct($email, $password)
    {
        $db = new DatabaseCon();
        $username = trim($email);
        $pass = trim($password);
        $query = $db->databaseCon->prepare("SELECT user_id, email, user_password FROM Users WHERE email = '{$email}' LIMIT 1");
        if($query->execute())
        {
            $found_user = $query->fetchAll();
            if (count($found_user)==1)
            {
                if(password_verify($pass, $found_user[0]['user_password']))
                {
                    $_SESSION['user_id'] = $found_user[0]['user_id'];
                    $_SESSION['email'] = $found_user[0]['email'];
                    $redirect = new Redirector("profile.php");
                }
                else
                {
                    // username/password combo was not found in the database
                    $this->message = "Username and/or password was incorrect.<br />
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
