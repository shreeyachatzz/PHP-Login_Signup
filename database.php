<?php

$host="localhost";
$dbname= "login_db";
$username="root";
$password = "";

$mysqli = new mysqli(
    hostname:$host, 
    username: $username,
    password: $password,
    database: $dbname
);

//in case an error occurs in loading the page then php mysqli sends an error code. Otherwise $mysqli->connect_errno is set to 0. So in case it is not zero, the error msg should pop up as given below.
if($mysqli->connect_errno){
    die("Connection error: ". $mysqli->connect_error);
}

return $mysqli;