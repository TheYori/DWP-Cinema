<?php
class AdminSessionHandler
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function logged_in() {
        return isset($_SESSION['admin_id']);
    }

    public function confirm_logged_in() {
        if (!$this->logged_in()) {
            $redirect = new Redirector("login.php");
        }
    }
}
