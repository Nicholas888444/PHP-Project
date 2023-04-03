<?php
/* This configuration script:
 * - define constants and settings
 * - dictates how errors are handled
 * - defines useful functions
 */
 
// Document who created this site, when, why, etc.

// ********************************** //
// ************ SETTINGS ************ //

//Fefine some important constants:

//Flag for site status:
define('LIVE', FALSE);

// Admin contact address:
define('EMAIL', 'ADMINEMAILHERE');

// Site URL (base for all redirections):
define ('BASE_URL', 'http://localhost/project1/login');

// Location of the MySQL connection script:
define ('MYSQLI_CONNECT', 'mysqli_connect.php');

// Adjust the time zone for PHP 5.1 and greater:
date_default_timezone_set ('US/Eastern');

// ************ SETTINGS ************ //
// ********************************** //


// ****************************************** //
// ************ ERROR MANAGEMENT ************ //


?>
