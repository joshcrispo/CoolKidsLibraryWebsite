<!--This is a session page that kills all SESSION variables and 
destryos the current session if the user decides to sign out of the system-->
<?php

    //starts the session that allows variables to be used through all pages
    session_start();

    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();

    //Redirects user to login page
    header("location:login.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signing OUT!</title>
</head>
<body>
    
</body>
</html>