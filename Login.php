<?php
    //starts the session that allows variables to be used through all pages
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!--Links CSS file to websites-->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php
        //Header for the page
        echo "<header>
        <h1 class=header>Cool kids library</h1></header>";

        //Setting up database variables to connect
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "WebDevProject";
        
        //Setting up a connection to database with PHP 
        $conn = new mysqli($servername, $username, $password, $database);

        //if statement to check the input from form has been set
        if (isset($_POST['uName']) && isset($_POST['password']))
        {
            
            //username and password input passed through form to become PHP variables
            $uN = $conn -> real_escape_string($_POST['uName']);
            $pW = $conn -> real_escape_string($_POST['password']);

            //SQL query held as string variable for php
            $sql = "SELECT * FROM User WHERE userName ='$uN' AND passWord = '$pW'";

            //run query in SQL and return result
            $result = $conn->query($sql);
            
            //if statement check if there is a row in the database associated with username and password that was passed through form
            if ($result->num_rows > 0)
            {
                //Places variable from post to be placed as a global variable accessed by all pages through a SESSION!
                $_SESSION["uName"] = $_POST["uName"];
                //Redirects user to the welcome page 
                header("location:welcome.php");
            }
            //else is used if theres is no row associated with the username and password
            else
            {
                echo '<h4 class="error">Invalid Username/Password</h4>';
            }
        }
    ?>
        <!--Form allows user to input their dates-->
        <h2>Login:</h2>
            <form method="post">
            <p>User Name:
            <input type="text" name="uName"></p>
            <p>Password:
            <input type="password" name="password"></p>
            <p><input type="submit" value="Login"/></p>
            <!--This allowers user to register with the use of href to link the register.php page-->
            <p>Don't have an account?<button><a href="register.php">Register</a></button></p>
        </form>

    <!--FOOTER-->
    <footer>
        <div class="foot">Website Created by: Joshua AL Rasbi © 2021</div>
    </footer>
</body>
</html>