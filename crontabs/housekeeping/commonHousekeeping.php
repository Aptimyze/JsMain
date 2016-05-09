<?php

//$db=connect_slave();
/* TIME NEED TO HARD-CODE*/

//$sql="SELECT DATE(DATE_SUB('2010-08-06',INTERVAL 6 MONTH))";
/*
$sql="SELECT DATE(DATE_SUB(now(),INTERVAL 6 MONTH))";
$result=mysql_query($sql,$db);
$row=mysql_fetch_array($result);
$time6Months=$row[0];
*/
$time6Months='2010-03-06';

//$sql="SELECT DATE(DATE_SUB('2010-08-06',INTERVAL 6 MONTH))";
/*
$sql="SELECT DATE(DATE_SUB(now(),INTERVAL 12 MONTH))";
$result=mysql_query($sql,$db);
$row=mysql_fetch_array($result);
$time12Months=$row[0];
*/
$time12Months='2009-09-06';


$contactsHouseKeepingTime=$time6Months;
$photoHouseKeepingTime=$time12Months;
$horoscopeHouseKeepingTime=$time12Months;
$bookmarkHouseKeepingTime=$time12Months;
$viewlogTime=$time6Months;

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function laveshEcho($db, $time_end,$time_ini)
{
        global $counter,$table;
        $counter=$counter+1;
/*
        $sql="SELECT COUNT(*) FROM $table";
        $result=mysql_query($sql,$db);
        $row=mysql_fetch_array($result);
        echo "$table COUNT($counter) ".$row[0],"\n";
*/

        $time = $time_end - $time_ini;
        echo "Time of block $counter ".$time."\n";
        echo "\n\n";
}
function laveshEcho1($time_ini)
{
        $time_end = microtime_float();
        $time = $time_end - $time_ini;
	echo "<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n";
        echo "<<<<<<<<<<<<<<<<<Time of block".$time.">>>>>>>>>>>>>>>>>>>>>>\n";
	echo "<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n";
}
?>
