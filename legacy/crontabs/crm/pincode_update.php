<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");



ini_set("max_execution_time","0");
include($_SERVER['DOCUMENT_ROOT']."/crm/connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/profile/comfunc.inc");
$db = connect_db();


$arr=array();
$sql = "SELECT ID,SUB_LOCATION_LABEL from incentive.BRANCH_PINCODE";
$myres=mysql_query($sql,$db) or die("$sql".mysql_error($db));
while($myrow=mysql_fetch_array($myres))
{
	$location 	=$myrow['SUB_LOCATION_LABEL'];
	$id =		$myrow["ID"];

	if($location){	
                $location= addslashes(stripslashes($location));
                $rep_values =array("'", "\"");
                $location =trim(str_replace($rep_values,'',$location));

        	$sql1= "SELECT VALUE FROM incentive.BRANCH_CITY WHERE LABEL LIKE '%$location%'";
        	$res1 =mysql_query($sql1,$db) or die($sql1.mysql_error($db));
		while($row1=mysql_fetch_array($res1))
		{
			$value       =$row1['VALUE'];
			if($value)
			{
				$arr[] =$value;
				$sql2 ="update incentive.BRANCH_PINCODE SET SUB_LOCATION='$value' WHERE ID='$id'";
				mysql_query($sql2,$db) or die($sql1.mysql_error($db2));
			}
		}
	}
}

//print_r($arr);


?>
