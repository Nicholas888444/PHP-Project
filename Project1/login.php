<?php
// This is the login page for the site.
require_once ('config.php'); 
$page_title = 'Login Page';
include ('header.html');
require_once (MYSQLI_CONNECT);
session_start();
$displayForm = true;

if (isset($_POST['submit'])) {
	$trimmed = array_map('trim', $_POST);

	// Validate the email address:
	if (!empty($_POST['email'])) {
		$email = $trimmed['email'];
	} else {
		$email = FALSE;
		echo '<p class="error">You forgot to enter your email address!</p>';
	}
	
	// Validate the password:
	if (!empty($_POST['pass'])) {
		$password = $trimmed['pass'];
	} else {
		$password = FALSE;
		echo '<p class="error">You forgot to enter your password!</p>';
	}
	
	if ($email && $password) { // If everything's OK.
		// Query the database: 
		$query_user = "SELECT userID, first_name, email, password FROM car_owners WHERE email='$email'";
		$result  = $dbConnection->query($query_user);
		if ($result->num_rows == 1) { // A match was found
		  $row = $result->fetch_assoc();
		  if (password_verify($password, $row['password'])) { 
			// Register the values & redirect:
			$_SESSION['userID'] = $row['userID'];
			$_SESSION['name'] = $row['first_name'];
			echo htmlspecialchars("Hi {$row['first_name']}, you are now logged in as {$row['email']}");
			header("Location: homepage.php");
			$displayForm = false;

		  }	else { // No match was made.
				echo '<p class="error">Either the email address and password entered do not match those on file or you have no account yet.</p>';
		  }
	    } else {
			echo '<p class="error">Either the email address and password entered do not match those on file or you have no account yet.</p>';
	  }
		
	   } else { 
		echo '<p class="error">Please try again.</p>';
	}

    $dbConnection->close();

} // End of SUBMIT conditional.

if ($displayForm) {
?>
<form action="login.php" method="post">
	<fieldset>
	<div class="myRow">
		<br>
		<label class="labelCol" for="email">Email</label> 
		<input type="text" name="email" size="20" maxlength="40" />
	</div>
	<div class="myRow">
		<label class="labelCol" for="[assw">Password</label>
		<input type="password" name="pass" size="20" maxlength="20" />
    </div>
	<div class="mySubmit">
		<input type="submit" name="submit" value="Login" /></div>
	</div>
	</fieldset>

</form>
<?php
}
?> 

