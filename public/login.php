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
if(!isset($_GET['username']) && !isset($_SESSION['username'])) {
    echo "
        <form class='alert alert-success' action='login.php' method='get'>
                <h3>Login: </h3>
                <label for='username'>Username: </label>
                <input type='text' name='username'>
                <div>
                    <label for='password'>Password: </label>
                    <input type='text' name='password'>
                </div>
                <div><input type='submit' value='Submit' class='btn btn-primary'></div>
        </form>
    ";
}
if(isset($_GET['username']) && !isset($_SESSION['username'])) {
    require_once('constants.php');
    $mysqli = new mysqli(DBHOST, DBUSER, DBPASSWORD, DATABASE)
    or die(mysqli_connect_error());

    echo "<h4>Welcome <b>".$_GET['username']."</b></h4>";
    echo "<h3>Authenticating...</h3>";
    function authenticate($mysqli) {
        // create statement
        $createQ = "CREATE TABLE IF NOT EXISTS members(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(64) NOT NULL,
            password VARCHAR(64) NOT NULL
        )";
        mysqli_query($mysqli, $createQ) or die(mysqli_error($mysqli));

        // select statement
        $q4 = "SELECT * FROM members";
        $result = mysqli_query($mysqli, $q4) or die(mysqli_error($mysqli));

        // get array result
        $row = mysqli_fetch_assoc($result);

        // if table is empty
        if(!isset($row)) {
            // read members.txt
            $contents = file_get_contents("./members.txt");

            //break into a string array on newline
            $arr = explode("\n", $contents);

            foreach ($arr as $key=>$str) {
                if(!$key == 0) {
                    // further explode the string on hyphen
                    $data = explode("-", $str);

                    // key starts at 0 so add 1
                    $tempId = $key;
                    if(count($data) == 2) {
                        $user = $data[0];
                        $pass = $data[1];
                        var_dump($user, $pass);
                        // data[0] is username and data[1] is password
                        $insertQ = "INSERT INTO members(id, username, password)
                        VALUES
                        ($tempId, $user, $pass)";
                        // sql statement for insert
                        mysqli_query($mysqli, $insertQ) or die(mysqli_error($mysqli));
                    }

                }

            }
        }
//        if(isset($row)) {
//            while($row)
//            {
//                foreach($row as $key=>$value)
//                {
//                    echo "$key is $value<br>";
//                }
//                echo "<hr>";
//            }
//            die();
//        }
//        else {
//            // select statement
//            $q4 = "SELECT * FROM members";
//            $result = mysqli_query($mysqli, $q4) or die(mysqli_error($mysqli));
//            $newRow = mysqli_fetch_assoc($result);
//            while($newRow)
//            {
//                foreach($row as $key=>$value)
//                {
//                    echo "$key is $value<br>";
//                }
//                echo "<hr>";
//            }
//        }

    }
    authenticate($mysqli);
    $_SESSION['username'] = $_GET['username'];
    $_SESSION['password'] = $_GET['password'];
    setcookie('username', $_GET['username'], time() + 180);
    $_SESSION['logintime'] = time();
    echo "<script>setTimeout(() => {
            window.location.replace('login.php')
        }, 30000)</script>";
}
if(!isset($_GET['username']) && isset($_SESSION['username'])) {
    setcookie('username', $_SESSION['username'], time() + 180);
    echo "hello you are logged in as " . $_SESSION['username'] . "<br>";
    echo "Investigate books: "." <button class='btn btn-info'><a href='books.php'>Books</a></button><br>";
    echo "<button class='btn btn-warning'><a href='logout.php'>logout</a></button>";
}

ob_end_flush();
