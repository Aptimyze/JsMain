<?php

include("connect.inc");
$db=connect_db();
$row = 0;

if(($handle = fopen("/home/anurag/Desktop/phase2_csv.csv", "r")) !== FALSE)
{
    while (($data = fgetcsv($handle, 1000000, ",")) !== FALSE)
    {
//           $num = count($data);

	  $parent_0=htmlspecialchars(stripslashes($data[0]),ENT_QUOTES);
          $parent_1=htmlspecialchars(stripslashes($data[1]),ENT_QUOTES);
	  $parent_2=htmlspecialchars(stripslashes($data[2]),ENT_QUOTES);
	  $parent_3=htmlspecialchars(stripslashes($data[3]),ENT_QUOTES);
	  $parent_4=htmlspecialchars(stripslashes($data[4]),ENT_QUOTES);
	  $parent_5=htmlspecialchars(stripslashes($data[5]),ENT_QUOTES);
	  $parent_6=htmlspecialchars(stripslashes($data[6]),ENT_QUOTES);
          $parent_7=$data[7];
	  $parent_8=$data[8];
          $parent_9=$data[9];

/*	   $parent_0=$data[0];
	   $parent_1=$data[1];
	   $parent_2=$data[2];
	   $parent_3=$data[3];
	   $parent_4=$data[4];
	   $parent_5=$data[5];
	   $parent_6=$data[6];
	   $parent_7=$data[7];
	   $parent_8=$data[8];
	   $parent_9=$data[9];*/

	   // Need to Add Follow Flag in CSV as well as in DB 
	   if($parent_6=='No')
	   	$parent_6='N';
	   else if($parent_6=='Yes')
	   	$parent_6='Y';
	
	  if($parent_7!='#N/A')
	  {
echo	   	$sql="UPDATE newjs.COMMUNITY_PAGES_MAPPING SET URL='$parent_1',CONTENT='$parent_0',TITLE='$parent_2',DESCRIPTION='$parent_3',KEYWORDS='$parent_4',H1_TAG='$parent_5',IMG_URL='$parent_8',FOLLOW='$parent_6',ALT_TAG='$parent_9' WHERE ID='$parent_7' ";
	   	mysql_query($sql,$db) or die();
		$row++;
  	  }
 

/*	   $sql="SELECT VALUE,TYPE FROM newjs.COMMUNITY_PAGES WHERE LABEL_NAME='$parent_element'";
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
//			mysql_query($sql_anu_1,$db) or die(mysql_error1($db));
		}
           }*/
	   unset($parent_0);
	   unset($parent_1);
	   unset($parent_2);
	   unset($parent_3);
	   unset($parent_4);
	   unset($parent_5);
	   unset($parent_6);
	   unset($parent_7);
	   unset($parent_8);
//           $row++;
    }
    echo $row;
   fclose($handle);
}
?>
