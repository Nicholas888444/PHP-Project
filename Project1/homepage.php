<?php

require_once ('config.php'); 
$page_title = 'Home Page';
include ('header.html');
$displayForm = true;

//Makes sure the user is logged in
session_start();
if(!isset($_SESSION['userID'])) {
    echo"<p>You must login to continue!</p>";
    echo"<p><a href=\"login.php\">Login Here!</a></p>";
    include ('footer.html');
    exit();
}
//Get user id and first name from session
$userID = $_SESSION['userID'];
$firstName = $_SESSION['name'];

require_once (MYSQLI_CONNECT);

$displayForm = TRUE;
$inputError = FALSE;

echo "<br><br>&nbsp&nbspWelcome {$firstName}!";
//If has submitted, continue
if(isset($_POST['submit'])) {
    //This will have the queries and fun math
    echo "<fieldset>";
    $displayForm = FALSE;
    //Initialize variables
    $carID = 0;
    $year = $_POST['year'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $basePrice = 0;
    //row count sees if there is a result from the query
    $rowCount = 0;
    $sqlQuery = "SELECT * FROM cars WHERE year = '{$year}' AND make = '{$make}' AND model = '{$model}'";
    echo "<h3>Your Car is a {$year} {$make} {$model}</h3>";
    //echo "<table><tr><th>Year</th><th>Make</th><th>Model</th><th>Base Price</th></tr>";
    foreach ($dbConnection->query($sqlQuery) as $m) {
                //echo "<td>{$m['year']}</td> <td>{$m['make']}</td> <td>{$m['model']}</td> <td>\${$m['base_price']}</td>";
                $carID = $m['carID'];
                $basePrice = $m['base_price'];
                $rowCount += 1;
    }
    echo"</table>";
    if($rowCount==0) {
        //query found no results
        echo "<p>There are no cars matching this description</p>";
    } else {
        //Do calculations if ther is a match. Display base price
        echo "<p>The base price is \${$basePrice}</p>";

        $price_type = $_POST['price'];
        echo "<p>You selected {$price_type}</p>";

        //Change the base price
        $finalPrice = $basePrice;
        if($price_type == "Dealership") {
            $finalPrice = $basePrice + $basePrice*0.15;
        } else if($price_type == "Certified") {
            $finalPrice = $basePrice + $basePrice*0.25;
        }

        $condition = $_POST['condition'];
        echo "<p>The car is in {$condition} condition</p>";
        //Don't need a fair because that is the same as the base price
        if($condition == "Good") {
            $finalPrice = intval($finalPrice + 0.05*$basePrice);
        } else if($condition == "Very Good") {
            $finalPrice = intval($finalPrice + 0.1*$basePrice);
        } else if($condition == "Excellent") {
            $finalPrice = intval($finalPrice + 0.15*$basePrice);
        }

        //Mileage
        //Gives a percentage of the price off for so many miles driven
        $miles_driven = intval($_POST['miles']);
        $percent_off = 1.00;
        if($miles_driven <= 10000) {
            $percent_off = 1.00;
        } else if($miles_driven <= 40000) {
            $percent_off = 0.95;
        } else if($miles_driven <= 100000) {
            $percent_off = 0.90;
        } else {
            $percent_off = 0.85;
        }

        $finalPrice = intval($finalPrice*$percent_off);
        echo "<p>Miles driven: {$miles_driven}</p>";

        //Desirable Options
        //If the box is checked, then add a set amount to the final price
        echo "<p>Your desirable options</p><ul>";
        if(!empty($_POST["TintedWindows"])) {
            echo "<li>Tinted Windows(\$500)</li>";
            $finalPrice = $finalPrice + 500;
        }
        if(!empty($_POST["HeatedSeat"])) {
            echo "<li>Heated Seats(\$250)</li>";
            $finalPrice = $finalPrice + 250;
        }
        if(!empty($_POST["WiFi"])) {
            echo "<li>WiFi(\$125)</li>";
            $finalPrice = $finalPrice + 125;
        }
        if(!empty($_POST["AutoEmergency"])) {
            echo "<li>Automatic Emergency Breaking(\$400)</li>";
            $finalPrice = $finalPrice + 400;
        }
        if(!empty($_POST["NavSystem"])) {
            echo "<li>Navigation System(\$350)</li>";
            $finalPrice = $finalPrice + 350;
        }
        echo "</ul><p>The final price is \${$finalPrice}</p>";
    }

    //Insert the car into users_cars
    $ucq = "INSERT INTO users_cars(UID, CID, owner, cond, miles_driven, Tinted_Windows, Heated_Seat, WiFi, AutoEmergencyBreaking, NavSystem, final_price)";
    $ucq .= "VALUES ('{$userID}','{$carID}', '{$price_type}', '{$condition}', '{$miles_driven}', '{$_POST["TintedWindows"]}', '{$_POST["HeatedSeat"]}','{$_POST["WiFi"]}','{$_POST["AutoEmergency"]}','{$_POST["NavSystem"]}', '{$finalPrice}')";
    if ( !( $result = $dbConnection->query($ucq))) {
        trigger_error("Query: $ucq\n<br />MySQL Error: " . $dbConnection->error);
    }
    if ($dbConnection->affected_rows == 1) { // If it ran OK.
        echo '<h3>Your Car has been Valued and Saved</h3>';
    }
    echo"<p><a href=\"homepage.php\">Value Another Car</a></p>";
    echo"<p><a href=\"history.php\">View Cars You Have Valued</a></p>";
    echo "</fieldset>";

}



//User input form
if ($displayForm){
    ?>
    <h1>Value your Car</h1>
<form name = "testform" method="POST" action="homepage.php">
    <fieldset>
    <label for = "year">Select the year of your car:</label>
    <select name="year" value="year" id="year" onchange="AjaxFunctionYear()"> <!--onchange runs javascript to update other dropdowns. (Bottom of page)-->
        <option value = "Select Year">Select Year</option>
    <?php

            //Query the cars database to get the dropdown menu
            $query_year = "SELECT DISTINCT year FROM cars ORDER BY year ASC";
            //Now make the options
            foreach ($dbConnection->query($query_year) as $m) {
                echo "<option value = \"{$m['year']}\">{$m['year']}</option>";
            }

        ?>
    </select><br><br>
    <label for = "make">Select the make of your car:</label>
    <select name="make" value="make" id="make" disabled = "true" onchange="AjaxFunctionMake()">
        <option value = "Select Make">Select Make</option>
        <?php
            //Query the cars database to get the dropdown menu
            $query_make = "SELECT DISTINCT make FROM cars ORDER BY make ASC";
            $r1  = $dbConnection->query($query_make);
            $dynam1 = $r1->fetch_assoc();
            //Now make the options
            foreach ($dbConnection->query($query_make) as $m) {
                echo "<option value = \"{$m['make']}\">{$m['make']}</option>";
            }
        ?>
    </select><br><br>
    <label for = "model">Select the model of your car:</label>
    <select name="model" value="model" id="model" disabled = "true" onchange="undisableSubmit()">
        <option value = "Select Model">Select Model</option>
        <?php
            //Query the cars database to get the dropdown menu
            $query_cars = "SELECT DISTINCT model FROM cars ORDER BY model ASC";
            //Now make the options
            foreach ($dbConnection->query($query_cars) as $m) {
                echo "<option value = \"{$m['model']}\">{$m['model']}</option>";
            }
        ?>
    </select><br><br>

    <label for = "price">Select the option that describes the owner:</label>
    <select name="price" value="price" id="price">
        <option value = "Private Owner">Private Owner</option>
        <option value = "Dealership">Dealership(+15%)</option>
        <option value = "Certified">Certified Preowned(+25%)</option>
    </select><br><br>

    <label for = "condition">Enter the condition of the car:</label>
    <select name="condition" value="condition" id="condition">
        <option value = "Fair">Fair(+0%)</option>
        <option value = "Good">Good(+5%)</option>
        <option value = "Very Good">Very Good(+10%)</option>
        <option value = "Excellent">Excellent(+15%)</option>
        
    </select><br><br>
    <label for="miles"> Miles Driven: </label>
    <input type="text" id="miles" name="miles">

    <p>Select additional features you would like to add:</p>
    <input type="checkbox" id="TintedWindows" name="TintedWindows" value="Tinted Windows">
    <label for="TintedWindows"> Tinted Windows</label><br>
    <input type="checkbox" id="HeatedSeat" name="HeatedSeat" value="Heated Seat">
    <label for="HeatedSeat">Heated Seats</label><br>
    <input type="checkbox" id="WiFi" name="WiFi" value="WiFi">
    <label for="WiFi">WiFi</label><br>
    <input type="checkbox" id="AutoEmergency" name="AutoEmergency" value="Automatic Emergency Breaking">
    <label for="AutoEmergency">Automatic Emergency Breaking</label><br>
    <input type="checkbox" id="NavSystem" name="NavSystem" value="Navigation System">
    <label for="NavSystem">Navigation System</label><br><br>

    <input type="submit" value="Submit" id ="submit" name = "submit" disabled = "true" onclick="undisable()">
    <input type="reset" value="Reset" id ="reset" name = "reset" onclick="undisableYear()"><br>
    <p><a href=history.php>View Cars You Have Valued</a></p>
    </fieldset>
</form>
<?php } 
//Everything below is javascript for selecting cars
?>

<script language="javascript" type="text/javascript">
function undisableYear() {
    document.testform.year.disabled = false;
    document.testform.make.disabled = true;
    document.testform.model.disabled = true;
    document.testform.submit.disabled = true;
}

function undisableSubmit() {
    document.testform.model.disabled = true;
    document.testform.submit.disabled = false;
}


function undisable()
{
    document.testform.year.disabled = false;
    document.testform.make.disabled = false;
    document.testform.model.disabled = false;
}
</script>
<script language="javascript" type="text/javascript">
function AjaxFunctionYear()
{
var httpxml;
try
  {
  // Firefox, Opera 8.0+, Safari
  httpxml=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
          try
                    {
                 httpxml=new ActiveXObject("Msxml2.XMLHTTP");
                    }
            catch (e)
                    {
                try
            {
            httpxml=new ActiveXObject("Microsoft.XMLHTTP");
             }
                catch (e)
            {
            alert("Your browser does not support AJAX!");
            return false;
            }
            }
  }
function stateck() {
    if(httpxml.readyState==4)
      {
    //alert(httpxml.responseText);
    var myarray = JSON.parse(httpxml.responseText);
    // Remove the options from 2nd dropdown list 
    for(j=document.testform.make.options.length-1;j>=0;j--)
    {
    document.testform.make.remove(j);
    }

    var optn = document.createElement("OPTION");
    optn.text = "Select Make";
    optn.value = "Select Make";  // You can change this to subcategory 
    document.testform.make.options.add(optn);

    for (i=0;i<myarray.data.length;i++) {
    var optn = document.createElement("OPTION");
    optn.text = myarray.data[i].make;
    optn.value = myarray.data[i].make;  // You can change this to subcategory 
    document.testform.make.options.add(optn);
    } 
    }
} // end of function stateck
    var url="dynamicDropDownYear.php";
    var year_id=document.getElementById('year').value;
    url=url+"?year_id="+year_id;
    url=url+"&sid="+Math.random();
    httpxml.onreadystatechange=stateck;
    //alert(url);
    httpxml.open("GET",url,true);
    httpxml.send(null);
    document.testform.make.disabled = false;
    document.testform.year.disabled = true;
  }
</script>

<script language="javascript" type="text/javascript">
function AjaxFunctionMake()
{
var httpxml;
try
  {
  // Firefox, Opera 8.0+, Safari
  httpxml=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
          try
                    {
                 httpxml=new ActiveXObject("Msxml2.XMLHTTP");
                    }
            catch (e)
                    {
                try
            {
            httpxml=new ActiveXObject("Microsoft.XMLHTTP");
             }
                catch (e)
            {
            alert("Your browser does not support AJAX!");
            return false;
            }
            }
  }
function stateck2() {
    if(httpxml.readyState==4)
      {
    //alert(httpxml.responseText);
    var myarray = JSON.parse(httpxml.responseText);
    // Remove the options from 2nd dropdown list 
    for(j=document.testform.model.options.length-1;j>=0;j--)
    {
    document.testform.model.remove(j);
    }
    var optn = document.createElement("OPTION");
    optn.text = "Select Model";
    optn.value = "Select Model";  // You can change this to subcategory 
    document.testform.model.options.add(optn);
    
    for (i=0;i<myarray.data.length;i++) {
    var optn = document.createElement("OPTION");
    optn.text = myarray.data[i].model;
    optn.value = myarray.data[i].model;  // You can change this to subcategory 
    document.testform.model.options.add(optn);
    } 
    }
} // end of function stateck
    var url="dynamicDropDownModel.php";
    var make_id=document.getElementById('make').value;
    url=url+"?make_id="+make_id;

    document.testform.year.disabled = false;
    var year_id=document.getElementById('year').value;
    url=url+"&year_id="+year_id;
    document.testform.year.disabled = true;

    url=url+"&sid="+Math.random();
    httpxml.onreadystatechange=stateck2;
    //alert(url);
    httpxml.open("GET",url,true);
    httpxml.send(null);
    document.testform.model.disabled = false;
    document.testform.make.disabled = true;
  }
</script>
