<?php
    //starts the session that allows variables to be used through all pages
    session_start();
    //session variable is passed as global variable to a local page variable as $userName in php
    $userName = $_SESSION["uName"];

    echo "<header><h1>$userName</h1></header>";
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
    <title>Search for a book</title>
    <!--Links CSS file to websites-->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <!--FORM that captures user input in input fields-->
    <h2>Search:</h2>
        <form method="post" action="search.php">
        <p>Book Title/Author:
        <input type="text" name="bookOrAuthor"></p>
        <label for="Category">Category:</label>
          <select name='Category'>
              <option value = ''></option>

              <?php 
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "WebDevProject";
            
                //Setting up a connection to database with PHP 
                $conn = new mysqli($servername, $username, $password, $database);

                $result = $conn->query("SELECT categoryDescription from Category");

                // while there are values populate menu with data from database
                while ($row = $result->fetch_assoc()) 
                {

                    unset($categoryType);
                    $categoryDescription = $row['categoryDescription'];
                    echo '<option value="'.$categoryDescription.'">'.$categoryDescription.'</option>';

                }

              ?>

          </select><br><br>
        <p><input type="submit" value="Search"/></p>
        <!--Button gives user option to go back to the welcome page-->
        <button><a href="welcome.php">Go back</a></button>
        </form>
    <?php
        //Header of the website page with a signout button and username is shown
        echo "<header>
        <h1 class=header>Cool kids library</h1><h2 class='login'>Hi $userName<button><a href='signOut.php'>Sign out?</a></button></h2></header>";

        //Setting up database variables to connect
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "WebDevProject";
    
        //Setting up a connection to database with PHP 
        $conn = new mysqli($servername, $username, $password, $database);

        //if statement to check the input from form has been set for BookTitle/Author input field
        if (isset($_POST['bookOrAuthor']))
        {   
            //BookTitle/Author input passed through form to become PHP variables
            $bTA = $conn->real_escape_string($_POST['bookOrAuthor']);
            
            //Catches if it is variable passed with input is empty
            if (empty($bTA))
            {
                //Do nothing
            }
            //If not empty display related books 
            else 
            {
                //SQL Query to to get row from database function CONCAT allows for partial search
                $sql = "SELECT * FROM Book INNER JOIN Category ON Book.CategoryID=Category.categoryID 
                WHERE BookTitle LIKE CONCAT('%', '$bTA', '%') OR Author 
                LIKE CONCAT('%', '$bTA', '%')";

                //Places results from SELECT SQL query to be placed in variable result
                $result = $conn->query($sql);

                //Displays books
                echo "<h2>Books:</h2><br>";

                //Checks the number of rows generated that is returned from the SQL query
                if ($result->num_rows > 0)
                {  
                    //Displays the books associated
                    while($row = $result->fetch_assoc())
                    {
                        echo "Book Title: " . $row["BookTitle"] 
                            . " - Author: " . $row["Author"]
                            . " - Edition: " . $row["Edition"] 
                            . " - Year: " . $row["Year"]
                            . " - Category: " . $row["categoryDescription"] . "<br><br>";
                    } 
                }
            }
        }   
        else
        {
            echo "0 result";
        }

        //if statement to check the input from form has been set for Category dropdown menu
        if (isset($_POST['Category']))
        {
            //category dropdown input passed through form to become PHP variables
            $category = $conn->real_escape_string($_POST['Category']);

            //SQL Query to to get row from database that has a category that matches with the category description in the table BOOK
            $sql ="SELECT * FROM Book INNER JOIN Category ON Book.CategoryID=Category.categoryID 
            WHERE (categoryDescription='$category')";

            //Places results from SELECT SQL query to be placed in variable result
            $result = $conn->query($sql);
            
            //Checks the number of rows generated that is returned from the SQL query
            if ($result->num_rows > 0)
            {  
                //Displays the books associated
                while($row = $result->fetch_assoc())
                {
                    echo "Book Title: " . $row["BookTitle"] 
                        . " - Author: " . $row["Author"]
                        . " - Edition: " . $row["Edition"] 
                        . " - Year: " . $row["Year"]
                        . " - Category: " . $row["categoryDescription"] . "<br><br>";
                }
            }

        }
    ?>
    <!--FOOTER OF THE WEBSITE PAGE-->
    <footer>
        <div class="foot">Website Created by: Joshua AL Rasbi © 2021</div>
    </footer>
</body>
</html>