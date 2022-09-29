<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Registration</title>
    <!--Links CSS file to websites-->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php
    //Header for the website
    echo "<header id=registrationHeader>
    <h1 id=registerHead>Cool kids library - Registration</h1><button id='loginRegister'><a href='Login.php'>Log In?</a></button></header>";

    //Setting up database variables to connect
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "WebDevProject";

    //Setting up a connection to database with PHP 
    $conn = new mysqli($servername, $username, $password, $database);

    //if statement to checks the inputs from form has been set 
    if (isset($_POST['fName']) && isset($_POST['lName']) && isset($_POST['uName']) && isset($_POST['password']) && isset($_POST['confirmPassword'])
    && isset($_POST['mobile']) && isset($_POST['telephone']) && isset($_POST['address']))
    {
        //Changes inputs from form to php variables
        $fN = $conn -> real_escape_string($_POST['fName']);
        $lN = $conn -> real_escape_string($_POST['lName']);
        $uN = $conn -> real_escape_string($_POST['uName']);
        $pW = $conn -> real_escape_string($_POST['password']);
        $ConfirmpW = $conn -> real_escape_string($_POST['confirmPassword']);
        $mobile = $conn -> real_escape_string($_POST['mobile']);
        $telephone = $conn -> real_escape_string($_POST['telephone']);
        $address = $conn -> real_escape_string($_POST['address']);

        //php Variables for checking if there is already a user with the same username inputted
        $user_check_query = "SELECT * FROM USER WHERE userName='$uN' LIMIT 1";
        $result = mysqli_query($conn, $user_check_query);
        $user = mysqli_fetch_assoc($result);
        
        if ($user) 
        {   
            // if user exists with same username, don't proceed
            if ($user['userName'] === $uN) 
            {
                echo '<h4 class="error">Username already exist</h4>';
            }
        }
        //else statement handles if username isn't taken then proceed to next steps
        else
        {
            //makes sure password is 6 characters long
            if (strlen($pW) != 6)
            {
                echo '<h4 class="error">Password needs to be 6 characters!</h4>';
            }
            else
            {
                //checks that mobile number is 10 digits long
                if (strlen($mobile) != 10)
                {
                    echo '<h4 class="error">Mobile needs 10 digits!</h4>';
                }
                //if all conditions are meet then proceeds
                else
                {
                    //Checks if the password is the same as the retyping of password and if it is create the account
                    if ($pW == $ConfirmpW) 
                    {
                        //SQL Query to add the user in the database as they have registered
                        $sql = "INSERT INTO User (userName, passWord, firstName, lastName, mobNumber, telNumber, address) 
                        VALUES ('$uN', '$ConfirmpW', '$fN', '$lN', '$mobile', '$telephone', '$address')";

                        //runs the sql query through the connection of the database
                        $conn->query($sql);

                        //Tells user they have been registered
                        echo "<h4>You have successfully: Registered with The Best Library! $fN $lN</h4>";
                        echo "<button><a href='Login.php'>LOGIN</a></button>";
                        
                        return;
                    }

                    //else statement catches if the password and retyping of password doesn't match.
                    else 
                    {
                    echo '<h4 class="error">Password must match with retyped passworld</h4>';
                    }
                }
            }   
        }
    }
    ?>
    <!--Form that allows user to input to input fields to register-->
    <form method="post" id="register">
    <p>First Name:
    <input type="text" name="fName" required></p> 
    <p>Last Name:
    <input type="text" name="lName" required></p>
    <p>User Name:
    <input type="text" name="uName" required></p>
    <p>Password:
    <input type="password" name="password" required></p>
    <p>Retype Password:
    <input type="password" name="confirmPassword" required></p>
    <p>Mobile Number:
    <input type="number" name="mobile" required></p>
    <p>Telephone Number:
    <input type="number" name="telephone" required></p>
    <p>Address:
    <input type="text" name="address" required></p>
    <p><input type="submit" value="Register"/>
    <button><a href="Login.php">Go back</a></p></button>
    </form>
    <!--Footer of the website-->
    <footer>
        <div class="foot">Website Created by: Joshua AL Rasbi © 2021</div>
    </footer>
</body>
</html>