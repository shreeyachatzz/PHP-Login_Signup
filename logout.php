<?php

//this line is essential to indicate the start of the session so that log out can happen
session_start();

//destroy the session
session_destroy();

//redirecting back to the index page
header("Location: index.php");

//although the exit line is not required (As it is the last line of the script) it is good practice to add an <exit;> line after every location header
exit;

//we do not need the php closing tag here because this file only contains php