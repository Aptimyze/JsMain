<?php
ini_set('max_execution_time','0');
include('connect.inc');
$db = connect_db();
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
               $cc='eshajain88@gmail.com';
               $to='bhavanakadwal@gmail.com';
               $msg1='subcaste to caste is being hit. We can wrap this to JProfileUpdateLib';
               $subject="subcaste to caste";
               $msg=$msg1.print_r($_SERVER,true);
               send_email($to,$msg,$subject,"",$cc);
$castearr = array('14','2','149','154');
$castecnt = count($castearr);

//$sql = "SELECT SUBSTRING( SMALL_LABEL, 2 ) , LABEL, VALUE FROM `CASTE` WHERE `PARENT` IN ('1','3','2','4')";
$sql = "SELECT REPLACE( SMALL_LABEL, '-','' ) AS SUBCASTE, LABEL, VALUE, PARENT FROM CASTE WHERE PARENT IN ('1', '3', '2', '4')";
$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
while ($row = mysql_fetch_array($res))
{
	if ($row['PARENT'] == '1')
	{
		if (!strstr($row['SUBCASTE'],"-"))
			$subcaste[14][] = $row['SUBCASTE']."|x|".$row['VALUE'];
	}
	if ($row['PARENT'] == '3')
	{
		if (!strstr($row['SUBCASTE'],"-"))
			$subcaste[2][] = $row['SUBCASTE']."|x|".$row['VALUE'];
	}
	if ($row['PARENT'] == '2')
        {
                if (!strstr($row['SUBCASTE'],"-"))
                        $subcaste[149][] = $row['SUBCASTE']."|x|".$row['VALUE'];
        }
	if ($row['PARENT'] == '4')
        {
                if (!strstr($row['SUBCASTE'],"-"))
                        $subcaste[154][] = $row['SUBCASTE']."|x|".$row['VALUE'];
        }
	
}
//mysql_select_db_js("test");
//print_r($subcaste);die;
for ($i = 0;$i < $castecnt;$i++)
{
	$sql = "SELECT SUBCASTE,CASTE,PROFILEID,SUBSCRIPTION FROM JPROFILE WHERE CASTE='$castearr[$i]' AND ACTIVATED='Y' AND SUBCASTE<>''";
	$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	while ($row = mysql_fetch_array($res))
	{
		$subcastecnt = count($subcaste[$castearr[$i]]);
		$x = 0;
		for ($j =0;$j < $subcastecnt;$j++)
		{
			$subcasteval = explode("|x|",$subcaste[$castearr[$i]][$j]);
			//echo "<br>".$row['SUBCASTE']."::".$subcasteval[0];
			if (strtolower(trim($row['SUBCASTE'])) == strtolower($subcasteval[0]))
			{
				$x++;
				$SUBCASTE = $subcasteval[0];	
				$CASTE = $subcasteval[1];
				//$x++;
				break;	
			}
			unset($subcasteval);
		}
		if ($x == 1)
		{
			$old_value = $row['CASTE'];
			if($old_value!=$CASTE)
			{
			//$comment = "Value Changed from ".$row['CASTE']." to ".$CASTE;
			$sql = "UPDATE JPROFILE SET CASTE='$CASTE' WHERE PROFILEID ='$row[PROFILEID]'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());

			//Sharding On Contacts done by Lavesh Rawat		
			$contactResult=getResultSet("count(*) as CNT","","",$row['PROFILEID']);
			$row_contact["CNT"]=$contactResult[0]['CNT'];
			//Sharding On Contacts done by Lavesh Rawat		

			$sql = "INSERT INTO CASTE_CHANGE_LOG (PROFILEID,ENTRY_DATE,CONTACTS,SUBSCRIPTION,OLD_VALUE,NEW_VALUE,SUBCASTE) VALUES ('$row[PROFILEID]',NOW(),'$row_contact[CNT]','$row[SUBSCRIPTION]','$old_value','$CASTE','" . addslashes($row['SUBCASTE']) . "')";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			}
		}
	}
	
}
?>
