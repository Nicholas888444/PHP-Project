<?php
//Used to update the model drop down menu.
//Found this example online for use. Modified it to use in our project
require_once ('config.php'); 
require_once (MYSQLI_CONNECT);
//Get year id from URL
@$year_id=$_GET['year_id'];

//Simple query to get all models that match other two drop down menus
$query_make = "SELECT DISTINCT make FROM cars WHERE year = '$year_id' ORDER BY make ASC";
$result = $dbConnection->query($query_make);
$row = $result->fetch_all(MYSQLI_ASSOC);
$main = array('data'=>$row);
//Return the results
echo json_encode($main);
?>

