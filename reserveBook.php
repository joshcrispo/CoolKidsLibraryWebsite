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

    echo "<header><h1>$userName</h1></header>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserving a book</title>
    <!--Links CSS file to websites-->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
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

        //Sets the page number of the page used to make sure there is only 5 rows per page
        if (isset($_GET['page_no']) && $_GET['page_no']!="") 
        {
            $page_no = $_GET['page_no'];
        } 
        else 
        {
            $page_no = 1;
        }
        
        //setting up variables for paging
        $total_records_per_page = 5;
        $offset = ($page_no-1) * $total_records_per_page;
        $previous_page = $page_no - 1;
        $next_page = $page_no + 1;
        $adjacents = "2";

        //counting the amount of rows with SQL query
        $result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM Book");
        $total_records = mysqli_fetch_array($result_count);
        $total_records = $total_records['total_records'];

        //Making sure each page only contains 5 rows
        $total_no_of_pages = ceil($total_records / $total_records_per_page);
        $second_last = $total_no_of_pages - 1; // total pages minus 1

        //SQL Query to to get all row from Book Table in the database LIMIT Makes sure only 5 rows per page 
        $sql = "SELECT * FROM Book INNER JOIN Category ON Book.CategoryID=Category.categoryID LIMIT $offset, $total_records_per_page";

        //Places results from SELECT SQL query to be placed in variable result
        $result = $conn->query($sql);

        //Shows list off books in the library!
        if ($result->num_rows > 0)
        {
            echo "<h2>Reserve a book:</h2>";
            echo "<div class=container>";
            
            while($row = $result->fetch_assoc())
            {
                echo "Book Title: " . $row["BookTitle"] 
                    . " - Author: " . $row["Author"]
                    . " - Edition: " . $row["Edition"] 
                    . " - Year: " . $row["Year"]
                    . " - Category: " . $row["categoryDescription"] . "<br><br>";
            }       
            echo "</div>";
        }

        //If creates the pages based on the amount of rows per page
        if ($total_no_of_pages <= 10)
        {  	 
            for ($counter = 1; $counter <= $total_no_of_pages; $counter++)
            {
                if ($counter == $page_no) 
                {
                    echo "<li class='active'><a>$counter</a></li>";	
                }
                else
                {
                    echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                }
            }
        }
        //if statement to check the input from form has been set for bookTitle
        if (isset($_POST['book']))
        {
            //BookTitler input passed through form to become PHP variables
            $book = $conn -> real_escape_string($_POST['book']);

            //SQL Query to to get row from database where bookTitle is equal to the input passed from form
            $sql = "SELECT * FROM Book WHERE BookTitle ='$book'";

            //Places results from SELECT SQL query to be placed in variable result
            $result = $conn->query($sql);

            //puts the relevant row in a row associative array
            $row = $result->fetch_assoc();

            //takes the value from associated row  with Key ISBN to be placed into a PHP variable $ISBN
            $ISBN = $row["ISBN"];
            
            //Checks if the user is logged in then continue
            if (isset($_SESSION["uName"]))
            {
                //Checks the book if it is not reserved
                if ($row["Reserved"] == 0)
                {
                    //SQL query to update the BOOK to reserved when Book is equal to the inputted value for the form
                    $sql = "UPDATE Book SET Reserved=1 WHERE BookTitle='$book'";
                    
                    //run query in SQL
                    $result = $conn->query($sql);


                    $sql = "INSERT INTO Reservations (ISBN, userName, reservedDate) VALUES ('$ISBN', '$userName', now())";
                    //run query in SQL
                    $result = $conn->query($sql);

                    //Display to user that reservation was successful
                    echo '<h4 class="success">Successful reservation!!!</h4>';
                }
                //Checks if book is reserved
                else
                {
                    //Displays to user book is reserved
                    echo '<h4 class="error">Sorry this is reserved</h4>';
                }
            }
            //else statement catches if the user isn't logged it, prompts the user to log in
            else 
            {
                //Displays to user
                echo '<h4 class="error">Sorry you aren\'t logged in, Please login to Reserve.</h4>';  
                echo "<button><a href='Login.php'>LOGIN</a></button>";
            }
        }

    ?>
    <!--FORM that captures user input in input fields-->
    <form method="post" action="reserveBook.php">
        <p>Book Title:
        <input type="text" name="book"></p>
        <p><input type="submit" value="Reserve"/></p>
        <button><a href="welcome.php">Go back</a></button>
    </form>
    <footer>
        <div class="foot">Website Created by: Joshua AL Rasbi © 2021</div>
    </footer>
</body>
</html>