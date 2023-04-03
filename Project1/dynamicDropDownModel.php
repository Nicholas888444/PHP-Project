<?php
//Used to update the model drop down menu.
//Found this example online for use. Modified it to use in our project
require_once ('config.php'); 
require_once (MYSQLI_CONNECT);
//Get year_id make_id from URL
@$make_id=$_GET['make_id'];
@$year_id=$_GET['year_id'];

//Simple query to get all models that match other two drop down menus
$query_model = "SELECT DISTINCT model FROM cars WHERE year = '$year_id' AND make = '$make_id' ORDER BY model ASC";
$result = $dbConnection->query($query_model);
$row = $result->fetch_all(MYSQLI_ASSOC);
$main = array('data'=>$row);
//Return the results
echo json_encode($main);
?>
