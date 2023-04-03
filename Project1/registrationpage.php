<?php

require_once 'config.php'; // Get configurations
$page_title = 'Registration'; // Set name title
include ('header.html'); // Get header

if (isset($_POST['submit'])){

    // Database connection
    require_once (MYSQLI_CONNECT);

    // Trim's input's
    $trimmed = array_map('trim', $_POST);
    
    // Set variables false, used later for querying.
    $fName = $lName = $email = $password = FALSE;

    // Check for a first name:
    if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['first_name'])) {
        $fName = mysqli_real_escape_string($dbConnection, $trimmed['first_name']);
    } else {
        echo '<p class="error">Please enter your first name!</p>';
    }

    // Check for a last name:
    if (preg_match ('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name'])) {
        $lName = mysqli_real_escape_string ($dbConnection, $trimmed['last_name']);
    } else {
        echo '<p class="error">Please enter your last name!</p>';
    }

    // Check for an email address:
    if (preg_match ('/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $trimmed['email'])) {
        $email = mysqli_real_escape_string ($dbConnection, $trimmed['email']);
    } else {
        echo '<p class="error">Please enter a valid email address!</p>';
    }

    // Check for a password and match against the confirmed password:
    if (preg_match ('/^\w{4,20}$/', $trimmed['password']) ) {
        if ($trimmed['password'] == $trimmed['passwordConfirm']) {
            $password = mysqli_real_escape_string ($dbConnection, $trimmed['password']);
        } else {
            echo '<p class="error">Your password did not match the confirmed password!</p>';
        }
    } else {
        echo '<p class="error">Please enter a valid password!</p>';
    }

    if ($fName AND $lName AND $email AND $password) { // If everything's OK...

        //Query to check if the email address is available:
        $query_email = "SELECT userID FROM car_owners WHERE email= '{$email}'";

        if ( !( $result = $dbConnection->query($query_email))) {
            trigger_error("Query: $query_email\n<br />MySQL Error: " . $dbConnection->error);
            //trigger_error is used to trigger a user error condition, usually used in conjunction
            //with the built-in error handler
        }

        if ($result->num_rows == 0) { // Available.
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Add the user to the database:
            $query_insert = "INSERT INTO car_owners (email, password, first_name, last_name) 
            VALUES ('{$email}', '{$hash}', '{$fName}', '{$lName}')";


            if ( !( $result = $dbConnection->query($query_insert))) {
                trigger_error("Query: $query_insert\n<br />MySQL Error: " . $dbConnection->error);
            }

            if ($dbConnection->affected_rows == 1) { // If it ran OK.
                // Finish the page:
                echo '<h3>Thank you for registering! Please click <a href="login.php">here</a> to log in. </h3>';
                include ('footer.html');
                exit(); // Stop the page.

            } else { // If it did not run OK.
                echo '<p class="error">You could not be registered due to a system error!</p>';
            }

        } else { // The email address is not available.
            echo '<p class="error">That email address has already been registered.</p>';
        }

    } else { // If one of the data tests failed.
        echo '<p class="error">Please re-enter your passwords and try again.</p>';
    }

    $dbConnection->close();

} // End of the main Submit



?>


<form action="registrationpage.php" method="post">
    <fieldset>

        <div class="myRow">
            <label class="labelCol" for="firstName">First Name</label>
            <input type="text" name="first_name" size="20" maxlength="20" value="<?php if (isset($trimmed['first_name']))
                echo $trimmed['first_name']; ?>" />
        </div>

        <div class="myRow">
            <label class="labelCol" for="lastName">Last Name</label>
            <input type="text" name="last_name" size="20" maxlength="40" value="<?php if (isset($trimmed['last_name']))
                echo $trimmed['last_name']; ?>" />
        </div>

        <div class="myRow">
            <label class="labelCol" for="email">Email Address</label>
            <input type="text" name="email" size="30" maxlength="80" value="<?php if (isset($trimmed['email']))
                echo $trimmed['email']; ?>" />
        </div>

        <div class="myRow">
            <label class="labelCol" for="password">Password</label>
            <input type="password" name="password" size="20" maxlength="20" />
        </div>
        <small>Use only letters, numbers, and the underscore. Must be between 6 and 20 characters long.</small>

        <div class="myRow">
            <label class="labelCol" for="passwordConfirm">Confirm Password</label>
            <input type="password" name="passwordConfirm" size="20" maxlength="20" />
        </div>

        <div class="mySubmit">
            <input type="submit" name="submit" value="Register" />
        </div>
    </fieldset>
</form>

<?php // Include the HTML footer.
include ('footer.html');
?>