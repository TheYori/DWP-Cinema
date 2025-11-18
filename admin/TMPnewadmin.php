<?php
// THIS FILE SHOULD BE DELETED AS POSSIBLE

spl_autoload_register(function ($class)
{include "../classes/".$class.".php";});
$session = new AdminSessionHandler();
// START FORM PROCESSING
if (isset($_POST['submit'])) { // Form has been submitted.
    $newUser = new RegisterNewAdmin($_POST['fname'], $_POST['lname'], $_POST['user'],$_POST['pass']);
    $msg = $newUser->message;
}
$redirect = new Redirector("login.php");
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html"/>
</head>
<?php
if (!empty($msg)) {echo "<p>" . $msg . "</p>";}
?>
<h2>Create New User</h2>
<form action="" method="post">
    First name:
    <input type="text" name="fname" maxlength="50"/>
    Last name:
    <input type="text" name="lname" maxlength="50"/>
    Username:
    <input type="text" name="user" maxlength="30"/>
    Password:
    <input type="password" name="pass" maxlength="30"/>
    <input type="submit" name="submit" value="Create"/>
</form>
</body>
</html>
