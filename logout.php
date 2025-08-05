<?php
session_start();
session_destroy(); // Destroy session
header("Location: hostelerlogin.html"); // Redirect to login page
exit();
?>
