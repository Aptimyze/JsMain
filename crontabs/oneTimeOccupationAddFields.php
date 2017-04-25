<?php

include("connect.inc");

connect_db();

$toUpdateFields = array(0=>array("sortby" => "4","grouping" =>"11","value" => "Admin"),
                        1=>array("sortby" => "9","grouping" =>"2","value" => "Analyst"),
                        2=>array("sortby" => "15","grouping" =>"7","value" => "Chartered accountant"),
                        3=>array("sortby" => "38","grouping" =>"8","value" => "Manager"),
                        4=>array("sortby" => "44","grouping" =>"11","value" => "Operator/Technician"),
                        5=>array("sortby" => "47","grouping" =>"5","value" => "Product manager"),
                        6=>array("sortby" => "50","grouping" =>"2","value" => "Program Manager"),
                        7=>array("sortby" => "51","grouping" =>"3","value" => "Psychologist"),
                        8=>array("sortby" => "68","grouping" =>"2","value" => "UI/UX designer"));
$c=63;

foreach($toUpdateFields as $key=>$val){   

$sql="UPDATE OCCUPATION SET SORTBY=SORTBY+1 WHERE SORTBY>=".$val['sortby'];
mysql_query($sql) or die(mysql_error());

$sql = "INSERT INTO OCCUPATION VALUES(".($c+1).",'".$val['value']."',".$c.",".$val['sortby'].",".$val['grouping'].")";
mysql_query($sql) or die(mysql_error());
$c++;
}