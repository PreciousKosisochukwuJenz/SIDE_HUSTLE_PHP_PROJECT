<?php
    // database variables
    $datasource = "mysql:host=localhost;dbname=tododb";
    $username = "root";
    $password = "";

    // Connecting to database
    try{
        $db = new PDO($datasource,$username,$password);
        }
    catch(PDOException $e){
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
?>