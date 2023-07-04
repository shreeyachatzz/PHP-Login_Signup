<?php
//variable to indicate invalid login details
$is_invalid = false;

if($_SERVER["REQUEST_METHOD"] === "POST"){

    //accessing the database to check presence
    $mysqli= require __DIR__ . "/database.php";

    //checking existing presence of data
    $sql = sprintf("SELECT * FROM user
            WHERE email= '%s'",
            //to prevent injection attack
            $mysqli->real_escape_string($_POST["email"]));
    //to execute this query
    $result = $mysqli->query($sql);

    //to get the data from the result object
    $user = $result->fetch_assoc();

    //if record is found
    if($user){
        if(password_verify($_POST["password"], $user["password_hash"])){
            
            session_start();

            //as we are starting the session at the top of the index page, when we load the login page, the session will already be started; this could make the code vulnerable to a session fiation attack.
            //to avoid this, once we have logged in, we regenerate the session id by calling the function below.
            session_regenerate_id();

            //by default these values are stored in files in the server so we store as little info as possible in the session.
            $_SESSION["user_id"] = $user["id"];            
            // die("Login sucessful");

            header("Location: index.php");
            exit;
        }
    }

    //if the above block was not executed then it means that there must have been some error and thus we set is_invalid true, ie there was a problem in the login
    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    </head>
    <body>
        <h1>
            Login
        </h1>
        <!-- this line shows that there was an error in the login. We want to show minimum information to the attacker so we do not specify if login email or password was incorrect -->
        <?php if($is_invalid):?>
            <em style="color: brown;">Invalid Login</em>
        <?php endif; ?>


        <form method="post">
            <div>
                <label for="email">Email</label>
                <!-- to make sure that if email is entered it does not have to be entered again and again we use htmlspecialchars($_POST["email"] ?? "") -->
                <input type="email" id="email" name="email"
                value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>     

            <button>Log in</button>
        </form>
        <a href="forgot-password.php">Forgot Password?</a>
    </body>
</html>