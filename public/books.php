<?php
echo '
      <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
      </head>
      ';

ob_start();
session_start();
echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';

if(!isset($_SESSION['username']) || !isset($_COOKIE['username'])) {
   echo "
       <div class='alert alert-failure'>
           <p>User is not logged in, please Login</p>
           <button class='btn btn-warning'><a href='login.php'>login</a></button>
       </div>
   ";
}
if(isset($_SESSION['username']) && isset($_COOKIE['username'])) {
    echo "Hello! Welcome<br>". $_SESSION['username']."<br>";
    require_once('constants.php');
    $mysqli = new mysqli(DBHOST, DBUSER, DBPASSWORD, DATABASE);

    $q4 = "SELECT * FROM book";
    $result = mysqli_query($mysqli, $q4) or die(mysqli_error($mysqli));
    while($row = mysqli_fetch_assoc($result))
    {
        foreach($row as $key=>$value)
        {
            echo "$key is $value<br>";
        }
        echo "<hr>";
    }
}
