# Ryan Collicutt Presents...

Another laravel app that uses cookies and sessions, alongside database integrations, to allow users to login to the app with a few key username and password combos, found in members.txt. The app allows a user to leave a review on a book, and then view the table of reviews pulled from the database. If the user spends more than 3 minutes in the site, they will be logged out for security reasons.



## Bootstrap and JQuery

I use both boostrap and jquery in my php apps, they seem to compliment it well. At the top of each of my pages, I make calls to the bootsrap api and jquery api. I also start the session:
```php
echo '<head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
      </head>';
ob_start();
session_start();
echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';
```
## login.php

We use Mysql to create all of our connections. Here we require our constants, which are our database secrets. Then we create a new database connection object with them, killing the program if there is an error.
```php
require_once('constants.php');
$mysqli = new mysqli(DBHOST, DBUSER, DBPASSWORD, DATABASE)
or die(mysqli_connect_error());
```

Using issets and sessions, like in one of our previous [labs](https://github.com/RyanColl/Laravel-React-Cookies-Session), we are going to seed the database with data from members.txt, and then verify the user matches the username and password found in the database. If the username and password match then the user is authorize using the authorize function. The authroize session initializes the session and redirects the user.
```php
function authorize() {
    $_SESSION['username'] = $_GET['username']; // assign session username as the username submitted
    $_SESSION['password'] = $_GET['password']; // assign session password as the password submitted
    setcookie('username', $_GET['username'], time() + 180); // set cookie for 3 minutes
    $_SESSION['logintime'] = time(); // set the login time of the session
    echo "<h3>Username and Pass confirmed, welcome ".$_GET['username']."!</h3>";
    echo "<h3>You are being redirected...</h3>";
    echo "<script>setTimeout(() => {
        window.location.replace('login.php') 
    }, 3000)</script>"; // this code will reload login.php 
}
```

If the user was not able to authorize, they will not be redirected or have a session created, they will have the program die and ask them to retry.
```php
die('The Username or Password did not match <br><br><a href="login.php"><button class="btn btn-warning">Return</button></a>');
```

When the session is set, and the user redirected, they land on a page that gives them 3 choices.
 - Create a Review
 - See all Reviews
 - Logout
```php
if(!isset($_GET['username']) && isset($_SESSION['username'])) {
    setcookie('username', $_SESSION['username'], time() + 180);
    echo "<div class='alert alert-success'>hello you are logged in as " . $_SESSION['username'] . "<br></div>";
    echo "Review books: "." <a href='review.php'><button class='btn btn-info'>Review</button></a><br>";
    echo "View All:"."<a href='all.php' class='text-white'><button class='btn btn-success'>See All</button></a><br>";
    echo "<a href='logout.php'><button class='btn btn-warning'>logout</button></a>";
}
```
### review.php

If you press on review, you are taken to review.php. Similar to login.php, we are presented a form with two fields, one for title and one for summary. When the user hits submit, they are taken to a page where they can choose to return, or see all reviews.

If the form is unsubmitted
```php
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
}
```

If the form is submitted, we create a new database connection and send a query to the books table, ensuring it exists. We then assign our values and submit them into books. Then we display a few buttons for the user to click, including 'return' and 'view all reviews'.
```php
if(isset($_GET['title']) && isset($_GET['summary'])) {
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
    echo "Return: <a href='review.php'><button class='btn btn-warning'>Return</button></a><br><br>";
    echo "See all: <a href='all.php'><button class='btn btn-info'>See All</button></a>";
}
```

## all.php

If the user goes to see all of the reviews, they will see a table with a a list of the title, the summary, and the user. Because we are usign bootstrap, our table looks quite nice.
```php
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
```

You'll notice this one line ```if ($row['reviewer'] == $_SESSION['username'])```, this determines if the user signed in matches the user who created that review. If that is the case, the column is displayed with yellow behind it using ```<tr class='table-warning'>```.

## Cookies and Sessions

If the user has their cookie destroyed, like they would if they spent more than 3 minutes in the app, then they are logged out and forced to log back in.
```php
if(!isset($_SESSION['username']) || !isset($_COOKIE['username'])) {
    function logData() {
        $time = date('h:ia M. d', time());
        $txt = "".$_SESSION['username']." with password ".$_SESSION['password']." logged out at ".$time."\n";
        file_put_contents("log.txt", $txt, FILE_APPEND);
    }
    logData(); // logs the logout time to log.txt
    $_SESSION = array(); // Unset all of the session variables.
    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params(); 
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();// Finally, destroy the session.
    die('You are not currently logged in! <br><br><a href="login.php"><button class="btn btn-warning">Login</button></a>');
}
```

That is the basis of my app! 

Check out my portfolio => [💻💻💻💻](https://www.rcoll-dev.com/)

Thanks !

# Original Laravel Readme


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
