<?php

    #A method for printing numbers within the range of start input and end input
    function GetRangeOfNumbers($start, $end){
        $arr = array();
        $counter = 0;
        echo("Below are numbers within the range of $start to $end <hr>");
        for($i = $start; $i <= $end; $i++){
            $arr[$counter] = $i;

            echo($arr[$counter]);
            echo("<br>");
            $counter++;
        }
        return $arr;
    }

    #A method for printing the sum of numbers in an array
    function SumArray($arr){
        $arrCount = count($arr);
        $sum = 0;
        for($i = 0; $i < $arrCount; $i++){
            $sum += $arr[$i];
        }
        echo("The sum of the numbers in the array is: $sum");
        return $sum;
    }
?>