<?php
// find the host, database, table, row, field (in that order)

require_once('constants.php');

$mysqli = new mysqli(DBHOST, DBUSER, DBPASSWORD)
    or die(mysqli_connect_error());

//library db cannot be created in heroku, but tables can
//$q = "CREATE DATABASE IF NOT EXISTS library";
//$query = mysqli_query($mysqli, $q) or die(mysqli_error($mysqli));
//
//
//if($query === false)
//{
//	die("uh oh");
//}
//else
//{
//	echo "library created";
//}

mysqli_select_db($mysqli, DATABASE) or die(mysqli_error($mysqli));



// book has been created
$q2 = "CREATE TABLE IF NOT EXISTS book(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(64) NOT NULL,
isbn VARCHAR(13) NOT NULL UNIQUE,
authorlastname VARCHAR(32) NOT NULL,
datepublished DATE
)";
//$query2 = mysqli_query($mysqli, $q2) or die(mysqli_error($mysqli));
//if($query2 === false)
//{
//	die("uh oh");
//}
//else
//{
//	echo "book table created";
//}





$q3 = "INSERT INTO book(id, title, isbn, authorlastname, datepublished)
VALUES
(102, 'four hour chicken wing', '46789', 'chickn', '2020-02-28'),
(103, 'getting real', 'abc123', '37 signals', '2020-03-12'),
(104, 'getting things done', 'xyz456', 'allen', '1999-12-25')";
$query3 = mysqli_query($mysqli, $q3) or die(mysqli_error($mysqli));
if($query3 === false)
{
	die("uh oh");
}
else
{
	echo "book table populated";
}




/*
+--------------------------------------------------------------------+
|id   title                   isbn      authorlastname  datepublished
(100, 'four hour workweek',  '444444', 'ferriss',      '2000-02-28'
(200, 'getting real',        'abc123', '37 signals',    null),
(300, 'getting things done', 'xyz456', 'allen',        '1999-12-25')";
+--------------------------------------------------------------------+
*/
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


