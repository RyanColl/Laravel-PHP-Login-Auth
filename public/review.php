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
    die('You are not currently logged in! <br><br><button class="btn btn-warning"><a href="login.php">Login</a></button>');
}
if (!isset($_GET['title']) || !isset($_GET['summary'])) {
    echo "
        <form class='alert alert-info' action='review.php' method='get'>
                <h3>Review a Book! </h3>
                <label for='title'>Title: </label>
                <input type='text' name='title'>
                <div>
                    <label for='summary'>Summary: </label>
                    <input type='text' name='summary'>
                </div>
                <div>
                    <input type='submit' value='Submit' class='btn btn-primary'>&nbsp;&nbsp;&nbsp;
                    <button class='btn btn-warning'><a href='login.php'>Return</a></button>
                </div>

        </form>
    ";
} if(isset($_GET['title']) && isset($_GET['summary'])) {
    echo "<div class='alert alert-warning'>Leaving a book review now...</div>";
    require_once('constants.php');
    $mysqli = new mysqli(DBHOST, DBUSER, DBPASSWORD, DATABASE)
    or die(mysqli_connect_error());
    $createQ = "CREATE TABLE IF NOT EXISTS books(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(64) NOT NULL,
            summary VARCHAR(256) NOT NULL,
            reviewer VARCHAR(64) NOT NULL
        )";
    mysqli_query($mysqli, $createQ) or die(mysqli_error($mysqli));
    $title = $_GET['title'];
    $summary = $_GET['summary'];
    $user = $_SESSION['username'];
    $reviewQ = "INSERT INTO books(title, summary, reviewer)
    VALUES
    ('$title', '$summary', '$user')";
    mysqli_query($mysqli, $reviewQ) or die(mysqli_error($mysqli));
    echo "Success!";
    echo "<br><br>";
    echo "<button class='btn btn-warning'><a href='review.php'>Return</a></button>";
}
