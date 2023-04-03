<?php

/*//phpmyadmin login
$user = 'root';
$password = 'root';
$db = 'project1';
$host = 'localhost';
$port = 3306;

try {
    $dbConnection = new PDO("mysql:host=" . $host . ";dbname=" . $db, $user, $password);
}
catch (PDOException $e){
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}*/

/* Turing */
$dbHost = 'localhost';//use localhost on both AMPPS and Turing.
$dbUser = 'nrthomso'; //use your username for Turing
$dbPassword = 'nrthomso';//leave empty or type in your username for Turing
$dbName = 'nrthomso';//use your username as the database name for Turing


/* MAMPS */
//$dbHost = 'localhost';//use localhost on both AMPPS and Turing.
//$dbUser = 'root'; //use root on AMPPS
//$dbPassword = 'root';//use empty string on AMPPS or your password if you have one
//$dbName = 'project1';//use your database name on AMPPS

//Make the connection:
$dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

//Check the status of the connection:
if ($dbConnection->connect_error) {
	die( "Could not connect to the database server: " .
		$dbConnection->connect_error  . " " . $dbConnection->connect_errno .
			"</body></html>" );
}
