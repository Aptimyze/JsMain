<?
/********************************************************/
/* Filename:swap.php
   This Script rename the TEMP tables with original table by swapping 
   and inserting the current records into Newly created original table.
    Created by Nikhil dhiman
    On 26 May 2006                                      */
/********************************************************/


 include_once("connect.inc");
$db=connect_db();
$temp="TEMP_";
@mysql_select_db("newjs",$db);
        
      
                                                                                                         
for($i=1;$i<11;$i++)
{
	$sSQL=" RENAME TABLE VIEW_LOG_TRIGGER_$temp$i to `TEST`,VIEW_LOG_TRIGGER_$i to VIEW_LOG_TRIGGER_$temp$i, `TEST` to VIEW_LOG_TRIGGER_$i ";    
	mysql_query_decide($sSQL);
}
for($i=1;$i<11;$i++)
{
 $sSQL=" Replace into VIEW_LOG_TRIGGER_$i select * from VIEW_LOG_TRIGGER_$temp$i ";
mysql_query_decide($sSQL);
}


?>

