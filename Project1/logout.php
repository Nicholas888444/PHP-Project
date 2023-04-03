<?php
// This is the logout page for the site.

session_start();
session_destroy();
header('Location: login.php');
exit;
?>
