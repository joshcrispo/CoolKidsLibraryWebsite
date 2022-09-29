<?php
   //starts the session that allows variables to be used through all pages
   session_start();
   //session variable is passed as global variable to a local page variable as $userName in php
   $userName = $_SESSION["uName"];
?>
<!DOCTYPE html>
<html>
   <head>
      <title>Welcome to cool libray</title>
      <!--Links CSS file to websites-->
      <link rel="stylesheet" type="text/css" href="style.css">
   </head>
   
   <body>
    <?php
      //Makes sure that the user is logged in, cannot by pass login without logging in 
      if (empty($_SESSION["uName"]))
      {
       header("location:index.html");
      }
      else
      {
      //Header of the website page with a signout button and username is shown
      echo "<header>
      <h1 class=header>Cool kids library</h1><h2 class='login'>Hi $userName<button><a href='signOut.php'>Sign out?</a></button></h2></header>";
      
      //Content of the page with buttons that has links in them done with the use of href
      echo '<h2 id="Quote"><i>A reader lives a thousand lives before he dies - George R. R. Martin</i></h2>';
      echo '<button><h2><a href="search.php">Search a book</a></h2></button><br>'
      . '<button><h2><a href="reserveBook.php">Reserve a book</a></h2></button><br>'
      . '<button><h2><a href="currentlyReserved.php">List of reserved</a></h2></button>';
      }
   ?>
   </body>
   <!--Footer of the website page-->
   <footer>
        <div class="foot">Website Created by: Joshua AL Rasbi © 2021</div>
    </footer>
   
</html>