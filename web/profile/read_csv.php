<?php

include("connect.inc");
$db=connect_db();

$row = 1;
if(($handle = fopen("/home/anurag/Desktop/final_cs.csv", "r")) !== FALSE)
{
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
    {
           $num = count($data);
	   $parent_element=$data[0];
	   $sql="SELECT VALUE,TYPE FROM newjs.COMMUNITY_PAGES WHERE LABEL_NAME='$parent_element'";
	   $res=mysql_query($sql,$db) or die(mysql_error1($db));
	   while($row_1=mysql_fetch_array($res))
	   {
	 	$par_val=$row_1['VALUE'];
	 	$par_type=$row_1['TYPE'];
	   }
           for ($c=1; $c < $num; $c++)
	   {
		$sql_anu="SELECT VALUE,TYPE FROM newjs.COMMUNITY_PAGES WHERE LABEL_NAME='$data[$c]'";
		$res_anu=mysql_query($sql_anu,$db) or die(mysql_error1($db));
		if($row_anu=mysql_fetch_array($res_anu))
		{
			$val=$row_anu['VALUE'];
			$type=$row_anu['TYPE'];
	echo		$sql_anu_1="INSERT INTO `COMMUNITY_PAGES_MAPPING` (`ID`,`PARENT_VALUE`,`PARENT_TYPE`,`PARENT_LABEL`,`MAPPED_VALUE`,`MAPPED_TYPE`,`MAPPED_LABEL`) VALUES ('','$par_val','$par_type','$parent_element','$val', '$type','$data[$c]')";
			mysql_query($sql_anu_1,$db) or die(mysql_error1($db));
		}
           }
	   unset($parent_element);
	   unset($par_val);
	   unset($val);
           $row++;
    }
   fclose($handle);
}
?>
