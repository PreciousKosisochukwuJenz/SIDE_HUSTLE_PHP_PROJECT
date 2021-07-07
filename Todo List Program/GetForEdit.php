<?php
include "db.php";

    $id = $_GET["id"];

      $run_query = $db->query('SELECT * FROM list WHERE Id="'.$id.'"');
        if($run_query){
            echo(json_encode($run_query->fetch(PDO::FETCH_OBJ)));
        }
?>