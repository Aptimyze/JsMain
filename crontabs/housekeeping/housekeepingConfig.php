<?php

$timestamp=mktime(0, 0, 0, date("m")-6  , date("d"), date("Y"));
$inactivityDate=date("Y-m-d",$timestamp);

$timestamp=mktime(0, 0, 0, date("m")  , date("d"), date("Y")-1);
$oldActivityOneYear=date("Y-m-d",$timestamp);

$timestamp=mktime(0, 0, 0, date("m")-6 , date("d"), date("Y"));
$oldActivitySixMonths=date("Y-m-d",$timestamp);

$timestamp=mktime(0, 0, 0, date("m")-7  , date("d"), date("Y"));
$inactivityDate_plus_onemonth=date("Y-m-d",$timestamp);

?>
