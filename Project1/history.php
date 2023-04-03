<?php
require_once ('config.php'); 
$page_title = 'User History';
include ('header.html');
//Make sure the user is logged in using session
session_start();
if(!isset($_SESSION['userID'])) {
    echo"<p>You must login to continue!</p>";
    echo"<p><a href=\"login.php\">Login Here!</a></p>";
    include ('footer.html');
    exit();
}
//Get the user id and name from session
$userID = $_SESSION['userID'];
$firstName = $_SESSION['name'];

require_once (MYSQLI_CONNECT);
echo "&nbsp&nbsp Welcome {$firstName}!";
?>


<fieldset>
<?php
//Build a table of all of the cars that the user has valued
echo"<h3>The cars you have valued are</h3>";
//Select almost everything the table has stored
$historyQuery = "SELECT year, make, model, base_price, final_price, owner, cond, miles_driven, Tinted_Windows, Heated_Seat, WiFi, AutoEmergencyBreaking, NavSystem FROM cars, users_cars WHERE {$userID} = users_cars.UID AND carID = users_cars.CID";
//Table headers
echo"<table class='output'><tr><th>Year</th><th>Make</th><th>Model</th><th>Base Price</th><th>Final Price</th><th>Owner Type</th><th>Condition</th><th>Miles Driven</th><th>Desirable Options</th></tr>";
//Query database to add data to table
foreach ($dbConnection->query($historyQuery) as $m) {
    echo"<tr><td>{$m['year']}</td> <td>{$m['make']}</td> <td>{$m['model']}</td> <td>\${$m['base_price']}</td> <td>\${$m['final_price']}</td> <td>{$m['owner']}</td> <td>{$m['cond']}</td> <td>{$m['miles_driven']}</td>";
    //This adds a bulleted list of all desirable options in one table column
    echo"<td><ul>";
    if(!empty($m['Tinted_Windows'])) {
        echo "<li>{$m['Tinted_Windows']}</li>";
    }
    if(!empty($m['Heated_Seat'])) {
        echo "<li>{$m['Heated_Seat']}</li>";
    }
    if(!empty($m['WiFi'])) {
        echo "<li>{$m['WiFi']}</li>";
    }
    if(!empty($m['AutoEmergencyBreaking'])) {
        echo "<li>{$m['AutoEmergencyBreaking']}</li>";
    }
    if(!empty($m['NavSystem'])) {
        echo "<li>{$m['NavSystem']}</li>";
    }
    echo"</ul></td>";
    echo "</tr>";
}
echo"</table>";
echo"<p><a href=\"homepage.php\">Home</a></p>";
?>

</fieldset>


<?php // Include the HTML footer.
include ('footer.html');
?>