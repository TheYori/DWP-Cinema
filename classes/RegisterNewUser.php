<?php
class RegisterNewUser
{
    public $message;

    public function __construct($fname, $lname, $phone, $birthDate, $email, $street, $postalCode, $city, $password)
    {
        // Trim and sanitize inputs
        $fname = trim($fname);
        $lname = trim($lname);
        $phone = trim($phone);
        $birthDate = trim($birthDate);
        $email = trim($email);
        $street = trim($street);
        $postalCode = trim($postalCode);
        $city = trim($city);
        $pass = trim($password);

        // Create database connection
        $db = new DatabaseCon();

        try {
            // Begin transaction to keep data consistent
            $db->databaseCon->beginTransaction();

            // Check if postal code already exists
            $checkPostal = $db->databaseCon->prepare("SELECT postal_code FROM PostalCodes WHERE postal_code = :postal_code");
            $checkPostal->bindParam(':postal_code', $postalCode, PDO::PARAM_INT);
            $checkPostal->execute();

            // If postal code doesn’t exist, insert it with the provided city
            if ($checkPostal->rowCount() === 0) {
                $insertPostal = $db->databaseCon->prepare("INSERT INTO PostalCodes (postal_code, city) VALUES (:postal_code, :city)");
                $insertPostal->bindParam(':postal_code', $postalCode, PDO::PARAM_INT);
                $insertPostal->bindParam(':city', $city, PDO::PARAM_STR);
                $insertPostal->execute();
            }

            // Hash password securely
            $options = ['cost' => 11];
            $hashed_password = password_hash($pass, PASSWORD_BCRYPT, $options);

            // Insert new user
            $query = $db->databaseCon->prepare("
                INSERT INTO Users 
                (first_name, last_name, phone_number, birth_date, email, street, postal_code, user_password)
                VALUES (:fname, :lname, :phone, :birth_date, :email, :street, :postal_code, :password)
            ");

            $query->bindParam(':fname', $fname);
            $query->bindParam(':lname', $lname);
            $query->bindParam(':phone', $phone);
            $query->bindParam(':birth_date', $birthDate);
            $query->bindParam(':email', $email);
            $query->bindParam(':street', $street);
            $query->bindParam(':postal_code', $postalCode, PDO::PARAM_INT);
            $query->bindParam(':password', $hashed_password);

            if ($query->execute()) {
                $db->databaseCon->commit();
                $this->message = "User registered successfully.";
            } else {
                $db->databaseCon->rollBack();
                $this->message = "User could not be registered.";
            }
        } catch (PDOException $e) {
            $db->databaseCon->rollBack();
            $this->message = "Database error: " . $e->getMessage();
        }

        $db->DatabaseClose();
    }
}

