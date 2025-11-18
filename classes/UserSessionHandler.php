<?php
class UserSessionHandler
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function logged_in()
    {
        return isset($_SESSION['user_id']);
    }

    public function confirm_logged_in()
    {
        if (!$this->logged_in()) {
            $redirect = new Redirector("login.php");
        }
    }

    // Get user id
    public function get_user_id()
    {
        return $this->logged_in() ? $_SESSION['user_id'] : null;
    }
}

