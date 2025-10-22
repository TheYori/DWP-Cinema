<?php
spl_autoload_register(function ($class)
{include "classes/".$class.".php";});
$session = new AdminSessionHandler();

// START FORM PROCESSING
if (isset($_POST['submit'])) {
    $newUser = new RegisterNewUser($_POST['fname'], $_POST['lname'], $_POST['phone'], $_POST['birth_date'], $_POST['email'], $_POST['street'], $_POST['postal_code'], $_POST['city'], $_POST['pass']);
    $msg = $newUser->message;

    if ($msg === "User registered successfully.") {
        $redirect = new Redirector("login.php");
        exit;
    }
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Register New User</title>
</head>
<body>
<?php
if (!empty($msg)) {
    echo "<p><strong>" . htmlspecialchars($msg) . "</strong></p>";
}
?>
<h2>Create New User</h2>
<form action="" method="post">
    <label>First name:</label><br>
    <input type="text" name="fname" maxlength="50" required><br><br>

    <label>Last name:</label><br>
    <input type="text" name="lname" maxlength="50" required><br><br>

    <label>Phone number:</label><br>
    <input type="text" name="phone" maxlength="20" required><br><br>

    <label>Birth date:</label><br>
    <input type="date" name="birth_date" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" maxlength="100" required><br><br>

    <label>Street address:</label><br>
    <input type="text" name="street" maxlength="100" required><br><br>

    <label>Postal code:</label><br>
    <input type="number" name="postal_code" maxlength="4" required><br><br>

    <label>City:</label><br>
    <input type="text" name="city" maxlength="20" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="pass" maxlength="30" required><br><br>

    <input type="submit" name="submit" value="Create">
</form>
</body>
</html>
