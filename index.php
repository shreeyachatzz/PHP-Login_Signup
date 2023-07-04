<?php

session_start();

if(isset($_SESSION["user_id"])){
    
    //to access the database
    $mysql= require __DIR__ . "/database.php";

    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";

    // this value is trusted as this is a value we have set ourselves so we dont need to escape it

    //to run the above sql query
    $result = $mysql->query($sql);

    $user = $result->fetch_assoc();

}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    </head>
    <body>
        <h1>
            HOME
        </h1>

        <!-- if there is a set user_id in the session then -->
        <?php if(isset($user)): ?>
            <p>Hello <?= htmlspecialchars($user["name"]) ?></p>
            <!-- htmlspecialchars() helps us escape any untrusted content -->
            <p>You are logged in.</p>
            <a href="logout.php"><button>Log Out</button></a>
        <!-- Otherwise if not in session-->
        <?php else: ?>
        <p><a href="login.php">Log In</a> or <a href="signup.html">Sign Up</a></p>
        <!-- ending if tag -->
        <?php endif;?>
    </body>
</html>