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
} else {
    require_once('constants.php');
    $mysqli = new mysqli(DBHOST, DBUSER, DBPASSWORD, DATABASE)
    or die(mysqli_connect_error());
    echo "<div class='alert alert-success'>You are logged in as ".$_SESSION['username']."!</div>";
    $selectQ = "SELECT * FROM books";
    $result = mysqli_query($mysqli, $selectQ) or die(mysqli_error($mysqli));
    echo "
    <table class='table'>
        <thead>
            <tr>
                <th scope='col'>ID</th>
                <th scope='col'>Title</th>
                <th scope='col'>Summary</th>
                <th scope='col'>Reviewer</th>
            </tr>
        </thead>
        <tbody>";
    while($row = mysqli_fetch_assoc($result))
    {
        if ($row['reviewer'] == $_SESSION['username']) {
            echo "
            <tr class='table-warning'>
                <th scope='row'>".$row['id']."</th>
                <td>".$row['title']."</td>
                <td>".$row['summary']."</td>
                <td>".$row['reviewer']."</td>
            </tr>
            ";
        } else {
            echo "
            <tr>
                <th scope='row'>".$row['id']."</th>
                <td>".$row['title']."</td>
                <td>".$row['summary']."</td>
                <td>".$row['reviewer']."</td>
            </tr>
            ";
        }

    }
    echo "</tbody></table>";
    echo "<br><br>";
    echo "Return: <button class='btn btn-warning'><a href='login.php'>Return</a></button>";
    echo "<br><br>";
    echo "Leave a review: <button class='btn btn-info'><a href='review.php'>Review</a></button>";
}

