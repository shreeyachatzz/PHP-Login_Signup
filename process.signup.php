<?php

if(empty($_POST["name"])){
    die("Name is required");
}

//validate the email
if(! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    die("Valid email is required");
}

//password check

//length check
if (strlen($_POST["password"])<8){
    die("Password must be at least 8 characters long");
}

//format check
if(! preg_match("/[a-z]/i", $_POST["password"])){
    die("Password must contain at least one letter");
}
if(! preg_match("/[0-9]/", $_POST["password"])){
    die("Password must contain at least one number");
}

//password and confirm password must match
if ($_POST["password"] !== $_POST["password_confirmation"]){
    die("Passwords must match");
}

//to not store the password as a string we hash the password while storing
$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

//__DIR__ directs to the current directory
//to allow return statements we put the command in a variable ie $mysqli
$mysqli = require __DIR__ . "/database.php";

//sql command to insert values into the users table
//?: these are placeholders
$sql = "INSERT INTO user(name, email, password_hash) VALUES (?,?,?)";
//to create a new prepared statement object by calling the statement inti method on the mysqli connection object like below
$stmt = $mysqli->stmt_init();

//we prepare the sql statement for execution by calling the prepare method on the statement object passing on the sql string as the argument like below
//this is where any syntax errors in the sql command will be caught- if the prepare method returns false then we know that there is a problem with our sql query so to check this we put this in an if statement and if it is false then we stop the running of the script and print the error
if(! $stmt->prepare($sql)){
    die("SQL error: ". $mysqli->error);
}

//to send values to the placeholder characters in the SQL statement above
$stmt->bind_param("sss",
                   $_POST["name"],
                   $_POST["email"],
                   $password_hash);

//to execute the sql command we use the below code
// if ($stmt->execute()) {
//     echo "Signup Successful";
// } else {
//     if ($mysqli->errno === 1062) {
//         die("Email already taken");
//     } else {
//         die($stmt->error . " " . $stmt->errno);
//     }
// }

try {
    //redirects to the page after signup page
    if ($stmt->execute()) {
        header("Location: signup-success.html");
        exit;
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) {
        die("Email already taken");
    } else {
        die($e->getMessage());
    }
}


// print_r($_POST);
// //posting the hashed password to check functionality
// var_dump($password_hash);