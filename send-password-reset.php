<?php

$email = $_POST["email"];

//random bytes returns unprintable numbers so we convert its value into a hexadecmal string using bin2hex
$token = bin2hex(random_bytes(16));

//for more security we store the hash of this instead of the plain value. The algorithm used is the sha256 which returns a 64bit string value as hashed
$token_hash = hash("sha256", $token);

//generating an expiry time so that a brute force attack can't be used to guess the valid token as we will make the token valid for only a short time.
//we are making the token valid for only 20mins
$expiry = date("Y-m-d H:i:s", time() + 60 * 20);

$mysqli = require __DIR__ . "/database.php";

$sql = "UPDATE user
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

//to avoid an sql injection attack
$stmt = $mysqli->prepare($sql);

$stmt->bind_param("sss", $token_hash, $expiry, $email);

$stmt->execute();

//rows will be affected only if the email already exists and row is updated
if ($mysqli->affected_rows) {

    $mail = require __DIR__ . "/mailer.php";

    //this is sample data.
    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END

    Click <a href="http://example.com/reset-password.php?token=$token">here</a> 
    to reset your password.

    END;

    //sending the email
    try {

        $mail->send();

    } catch (Exception $e) {

        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";

    }

}

try {
    //redirects to the page after mail is sent
    if ($stmt->execute()) {
        header("Location: mail-send-conf.html");
        exit;
    }
} catch (mysqli_sql_exception $e) {
    die($e->getMessage());
}