<?php
ini_set('max_execution_time','0');
include('../profile/connect.inc');
//include("contacts_functions.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
               $cc='eshajain88@gmail.com';
               $to='bhavanakadwal@gmail.com';
               $msg1='misspelled subcaste to caste is being hit. We can wrap this to JProfileUpdateLib';
               $subject="misspelled subcaste to caste";
               $msg=$msg1.print_r($_SERVER,true);
               send_email($to,$msg,$subject,"",$cc);

$db = connect_db();

$cst_arr = array("14","2","149","154");

$sql = "SELECT SUBCASTE, VALUE, PARENT FROM SUBCASTE_FINAL WHERE 1";
$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
while($row = mysql_fetch_array($res))
{
	if($row['PARENT'] == '1')
		$subcst[14][] = $row['SUBCASTE']."|x|".$row['VALUE'];
	if($row['PARENT'] == '2')
		$subcst[2][] = $row['SUBCASTE']."|x|".$row['VALUE'];
	if($row['PARENT'] == '3')
		$subcst[149][] = $row['SUBCASTE']."|x|".$row['VALUE'];
	if($row['PARENT'] == '4')
		$subcst[154][] = $row['SUBCASTE']."|x|".$row['VALUE'];
}

for($i=0;$i<count($cst_arr);$i++)
{
	$sql = "SELECT SUBCASTE,CASTE,PROFILEID,SUBSCRIPTION FROM JPROFILE WHERE CASTE='$cst_arr[$i]' AND ACTIVATED='Y' AND SUBCASTE<>''";
	$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row = mysql_fetch_array($res))
	{
		$x=0;
		for($j=0;$j<count($subcst[$cst_arr[$i]]);$j++)
		{
			$subcst_val = explode("|x|", $subcst[$cst_arr[$i]][$j]);
			if(strtolower($row['SUBCASTE']) == strtolower($subcst_val[0]))
			{
				$x++;
				$subcaste = $subcst_val[0];
				$caste = $subcst_val[1];
				break;
			}
			unset($subcst_val);
		}
		if($x == 1)
		{
	mysql_ping_js();
			$old_caste = $row['CASTE'];
	
			$sql_up = "UPDATE JPROFILE SET CASTE = '$caste' WHERE PROFILEID = '$row[PROFILEID]'";
			$res_up = mysql_query_decide($sql_up) or die("$sql_up".mysql_error_js());

			//Sharding of CONTACTS done by Sadaf

			$receiversIn=$row["PROFILEID"];
			$contactResult=getResultSet("COUNT(*) AS CNT",'','',$receiversIn);
			if(is_array($contactResult))
			{
				$contact_count=$contactResult[0]["CNT"];
			}
			$sql_ins = "INSERT INTO CASTE_CHANGE_LOG (PROFILEID,ENTRY_DATE,CONTACTS,SUBSCRIPTION,OLD_VALUE,NEW_VALUE,SUBCASTE) VALUES ('$row[PROFILEID]',NOW(),'$contact_count','$row[SUBSCRIPTION]','$old_caste','$caste','" . addslashes($row['SUBCASTE']) . "')";
                        mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());
		}
		
	}
}
?>
