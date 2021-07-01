<?php
    session_start();
    // Unset login variable
    $_SESSION["IsLoggedIn"] = false;
    
    // Redirect to login
    header("Location: login.php");
    exit();
?>