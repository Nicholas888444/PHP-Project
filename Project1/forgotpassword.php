<?php

require_once 'config.php'; // Get configurations
$page_title = 'Forgotten Password'; // Set name title
include ('header.html'); // Get header

if (isset($_POST['submit'])){

    // Database connection
    require_once (MYSQLI_CONNECT);

    // Trim's input's
    $trimmed = array_map('trim', $_POST);

    // Set variables false, used later for querying.
    $emailaddress = $newPassword = FALSE;

    // Check for email address
    if (preg_match ('/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $trimmed['email'])) {
        $emailaddress = mysqli_real_escape_string ($dbConnection, $trimmed['email']);
    } else {
        echo '<p class="error">Please enter a valid email address!</p>';
    }

    // Check for new password and compares if it matches
    if (preg_match ('/^\w{4,20}$/', $trimmed['newpassword']) ) {
        if ($trimmed['newpassword'] == $trimmed['confirm_newpassword']) {
            $newPassword = mysqli_real_escape_string ($dbConnection, $trimmed['newpassword']);
        } else {
            echo '<p class="error">Your password did not match the confirmed password!</p>';
        }
    } else {
        echo '<p class="error">Please enter a valid password!</p>';
    }

    if ($emailaddress AND $newPassword) { // If everything went okay...

        // Get users id from email
        $query_getUserID = "SELECT userID FROM car_owners WHERE email = '{$emailaddress}'";
        

        if ( !( $result = $dbConnection->query($query_getUserID))) {
            trigger_error("Query: $query_getUserID\n<br />MySQL Error: " . $dbConnection->error);
        }

        if ($result->num_rows != 0){ // If results come back with the user ID

            // Hash password
            $hash_newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database with the new password for the user
            $query_updatePassword = "UPDATE car_owners SET password='{$hash_newPassword}' WHERE email='{$emailaddress}'";
            


            if ( !( $result = $dbConnection->query($query_updatePassword))) {
                trigger_error("Query: $query_updatePassword\n<br />MySQL Error: " . $dbConnection->error);
                //trigger_error is used to trigger a user error condition, usually used in conjunction
                //with the built-in error handler
            }

            if ($dbConnection->affected_rows == 1) { // If it ran OK.
                // Finish the page:
                echo '<h3>Password has been updated!</h3>';
                include ('footer.html');
                exit(); // Stop the page.

            } else { // If it did not run OK.
                echo '<p class="error">We could not update your password due to a system error!</p>';
            }
        }

    } else {
        echo '<p class="error">Please re-enter your passwords and try again.</p>';
    }

    $dbConnection->close();
}
?>

    <form action="forgotpassword.php" method="post">
        <fieldset>
            <div class="myRow">
                <br>
                <label class="labelCol" for="email">Email Address: </label>
                <input type="text" name="email" size="30" maxlength="80" value="<?php if (isset($trimmed['email']))?>" />
            </div>

            <div class="myRow">
                <label class="labelCol" for="newpassword">New Password: </label>
                <input type="password" name="newpassword" size="20" maxlength="20" />
            </div>
            <small>Use only letters, numbers, and the underscore. Must be between 6 and 20 characters long.</small>

            <div class="myRow">
                <label class="labelCol" for="confirm_newpassword">Confirm New Password: </label>
                <input type="password" name="confirm_newpassword" size="20" maxlength="20" />
            </div>

            <div class="mySubmit">
                <input type="submit" name="submit" value="Change Password">
            </div>
        </fieldset>
    </form>


<?php
include ('footer.html');
?>
