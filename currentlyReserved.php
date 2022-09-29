<?php
    //starts the session that allows variables to be used through all pages
    session_start();
    //session variable is passed as global variable to a local page variable as $userName in php
    $userName = $_SESSION["uName"];
    //Makes sure that the user is logged in, cannot by pass login without logging in 
    if (empty($_SESSION["uName"]))
    {
       header("location:index.html");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Reserved books</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php
        //HEADER OF THE WEBSITE PAGE WITH User and a signout button
        echo "<header>
        <h1 class=header>Cool kids library</h1><h2 class='login'>Hi $userName
        <button><a href='signOut.php'>Sign out?</a></button></h2></header>";

        //Setting up database variables to connect
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "WebDevProject";

        //Setting up a connection to database with PHP 
        $conn = new mysqli($servername, $username, $password, $database);

        //SQL Query to get rows associated in database when the book is reserved and reserved by the user logged in
        $sql = "SELECT * FROM Book JOIN Reservations ON Book.ISBN=Reservations.ISBN JOIN Category ON Book.CategoryID=Category.categoryID WHERE Reserved=1 AND userName='$userName'";

        //Runs the query and returns the result in result variable 
        $result = $conn->query($sql);

        echo "<h2>Reserved:</h2><br>";

        //if checks if there are rows that exist that followed condition
        if ($result->num_rows > 0)
        {
            //displays the books that are reserved currrently by that user
            while($row = $result->fetch_assoc())
            {
                echo " ISBN : " . $row["ISBN"]
                    . " - Reservation ID: " . $row["rID"]
                    . " - Book Title: " . $row["BookTitle"] 
                    . " - Author: " . $row["Author"]
                    . " - Edition: " . $row["Edition"] 
                    . " - Year: " . $row["Year"]
                    . " - Category: " . $row["categoryDescription"] 
                    . " - Reserved by:" . $row["userName"] . "<br><br>";
            }
            echo '<button><a href="welcome.php">Go back</a></button>';    
        }

        //if catches if there are no rows, hence the user hasn't reserved anything
        else
        {
            //Displays no reservations to user
            echo "No reservations"; 
        }
        
        //if statement to check the input from form has been set for both ISBN and reservation ID field 
        if (isset($_POST['ISBN']) && isset($_POST['rID']))
        {
            //Changes inputs from form to php variables
            $ISBN = $conn ->real_escape_string($_POST['ISBN']);
            $rID = $conn ->real_escape_string($_POST['rID']);

            //if statement checks if either variables are empty and if one of them are, show user to fill in all input fields
            if (empty($ISBN) || empty($rID))
            {
                //Displays to user that all fields must be inputed
                echo '<h4 class="error">Please fill in all fields!</h4>';
            }
            else
            {
                //SQL Query to delete rows associated in database where the reservationID matches that of the ID that is show to the user
                $sql = "DELETE FROM `Reservations` WHERE `Reservations`.`rID` = $rID";

                //runs the query in the database, deleting associated row
                $conn->query($sql);

                //SQL Query to update the BOOK table to remove the reserved book from reservation with the use of ISBN
                $sql = "UPDATE Book SET Reserved=0 WHERE ISBN='$ISBN'";
                
                //runs the query in the database, updatnig the associated row from being reserved to not being reserved
                $conn->query($sql);
                
                //Displays to user that reservation is cancelled
                echo "<h4>You have cancelled your Registration!</h4>";
                echo "<button><a href='currentlyReserved.php'>Check your reservations</a></button>";
                
                return;
            }
        }
            
    ?>
    <!--Cancel reservation form that allows user to cancel reservation with the use of ISBN and the reservation ID-->
    <p>Cancel reservation: <br></p>
    <form method="post">
    <p>ISBN: </p>
    <input type="text" name="ISBN"></p>
    <p>Reservation ID: </p>
    <input type="number" name="rID"></p>
    <p><input type="submit" value="Delete" onclick="return confirm('Are you sure?')"/>
    </form>
    <!--FOOTER OF THE WEBSITE-->
    <footer>
        <div class="foot">Website Created by: Joshua AL Rasbi © 2021</div>
    </footer>
</body>
</html>