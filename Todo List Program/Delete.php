<?php
include "db.php";

    $id = $_GET["id"];

      $run_query = $db->query('DELETE FROM list WHERE Id="'.$id.'"');
        if($run_query){
            echo "Deletion successful";
        }else{
            echo "Deletion failed";
        }
?>