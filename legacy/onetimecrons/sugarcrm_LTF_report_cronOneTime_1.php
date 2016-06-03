<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("../connect.inc");

$db2=connect_db();
$db=connect_slave();

	//************************************    Condition after submit state  ***************************************
		$recordsCnt 	=6;
		$typeArr 	=array("REG","EMAIL","ADDR","PHONE","ACT","PHOTO");
		

		//$sql ="select EXECUTIVE,PROFILEID,count(*) as CNT from MIS.LTF GROUP BY PROFILEID HAVING CNT<'$recordsCnt'";
		$sql ="select EXECUTIVE,PROFILEID from MIS.LTF WHERE TYPE='PHONE'";
		$res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());	 
		while($row =mysql_fetch_array($res))
		{
			$pid =$row['PROFILEID'];		
			$exec_name =$row['EXECUTIVE'];

			// Phone Verified
			$sql_j ="SELECT MOB_STATUS,LANDL_STATUS from newjs.JPROFILE where PROFILEID='$pid'";
			$res_j =mysql_query_decide($sql_j,$db) or die("$sql_j".mysql_error_js());
			if($row_j =mysql_fetch_array($res_j))
			{
				if($row_j['MOB_STATUS']=='Y' || $row_j['LANDL_STATUS']=='Y')
				{
					$sql_phone ="SELECT ENTRY_DT from jsadmin.PHONE_VERIFIED_LOG WHERE  PROFILEID='$pid' ORDER BY ID DESC LIMIT 1";
                			$phone_res =mysql_query_decide($sql_phone,$db) or die("$sql_phone".mysql_error_js());
                			if($phone_row=mysql_fetch_array($phone_res))
					{
						$phone_dt =$phone_row['ENTRY_DT'];
						$sql_ins ="UPDATE MIS.LTF SET DATE='$phone_dt' WHERE PROFILEID='$pid' AND TYPE='PHONE'";
						mysql_query_decide($sql_ins,$db2) or die("$sql_ins".mysql_error_js());
					}
				}
				else{
                                        $sql ="DELETE FROM MIS.LTF WHERE `PROFILEID`='$pid' AND `TYPE`='PHONE'";
                                        mysql_query_decide($sql,$db2) or die("$sql".mysql_error_js());
				}
			}

		} //while loop condition ends

?>
