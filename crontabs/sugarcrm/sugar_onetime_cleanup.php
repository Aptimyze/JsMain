<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

$path=$_SERVER[DOCUMENT_ROOT];
require_once("$path/profile/connect.inc");
$db=connect_db();
	$filename = "Connected_Report_New_LTF.csv";
	$fp = fopen($filename, "r") or exit("Unable to open file!");
	status_update($fp);
	//Output a line of the file until the end is reached
	fclose($fp);
	$filename2="Connected_Call_Report_Outbound_New_LTF2.csv";
		$fp2= fopen($filename2,"r") or exit("Unable to open file $filename2!");
	status_update($fp2);
	fclose($fp2);
	function status_update($fp){
	while(!feof($fp))
	{
        	$line=fgets($fp);
	        $data=explode(",",$line);
        	$disp=trim($data[4]);
	        $phone1=trim($data[9]);
			$phone2=trim($data[10]);
			if($disp=='')
				$status='13';
			$sql="select STATUS_VALUE from sugarcrm.SUGAR_DIALER_DISPOSITION_MAPPING where DIALER_DISP='$disp'";
			$res=mysql_query_decide($sql);
			$row=mysql_fetch_assoc($res);
            $status=$row['STATUS_VALUE'];
			if($phone1!=0 && $phone2!=0)
			$sql1="select id from sugarcrm.leads where phone_mobile='$phone1' OR phone_home='0$phone1' OR phone_mobile='$phone2' OR phone_home='0$phone2'";
			else if($phone1!=0)
				$sql1="select id from sugarcrm.leads where phone_mobile='$phone1' OR phone_home='0$phone1'";
			else if($phone2!=0)
			$sql1="select id from sugarcrm.leads where phone_mobile='$phone2' OR phone_home='0$phone2'";
			else continue;
	//		echo "$sql1\n";
			$res1=mysql_query_decide($sql1);
			while($row=mysql_fetch_row($res1)){
	//			echo $row[0]." $phone1 $phone2 $status\n";
				$sql2="UPDATE sugarcrm.leads SET status='$status' where id='".$row[0]."'";
				mysql_query_decide($sql2); 
			}
//		echo $row['STATUS_VALUE'].",".$phone1.",".$phone2."\n";
	}
	}
?>
