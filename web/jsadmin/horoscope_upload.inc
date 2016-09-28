<?php

/**
*       Function        :       horoscope_save
*       Description     :       Inserts or Update the User's picture information
**/
include_once(JsConstants::$docRoot."/classes/ProfileReplaceLib.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
include_once(JsConstants::$docRoot."/classes/ProfileDeleteLib.php");
function horoscope_save($profileid,$horoscope_content)
{
	//echo "here";

        //query to insert the new screened horoscope in HOROSCOPE_UPLOAD table
        /*$sql_insert=" insert into newjs.HOROSCOPE_UPLOAD values ('','$profileid','$horoscope_content','')";
        mysql_query_decide($sql_insert) or die("$sql_insert".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_insert,"ShowErrTemplate");*/

	//make an entry in the log
	$objReplace = ProfileReplaceLib::getInstance();
	
	$sql="SELECT RECEIVE_TIME FROM jsadmin.MAIN_ADMIN WHERE PROFILEID='$profileid' and SCREENING_TYPE='H'";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$resf=mysql_fetch_array($res);
	$rec_time=$resf['RECEIVE_TIME'];
	$date_time=explode(" ",$rec_time);
	$date_y_m_d=explode("-",$date_time[0]);
	$time_h_m_s=explode(":",$date_time[1]);
	$timestamp=mktime($time_h_m_s[0],$time_h_m_s[1],$time_h_m_s[2],$date_y_m_d[1],$date_y_m_d[2],$date_y_m_d[0]);
	$timezone=date("T",$timestamp);
	if($timezone=="EDT")
		$timezone="EST5EDT";
														     
	$sqladmin = "insert into jsadmin.MAIN_ADMIN_LOG(PROFILEID,USERNAME,SCREENING_TYPE,RECEIVE_TIME,SUBMIT_TIME,ALLOT_TIME,SUBMITED_TIME,ALLOTED_TO,STATUS,SUBSCRIPTION_TYPE, SCREENING_VAL,TIME_ZONE, SUBMITED_TIME_IST) select PROFILEID,USERNAME,SCREENING_TYPE,RECEIVE_TIME,SUBMIT_TIME,ALLOT_TIME,NOW(),ALLOTED_TO,'APPROVED',SUBSCRIPTION_TYPE, SCREENING_VAL,'$timezone', CONVERT_TZ(NOW(),'$timezone','IST') from jsadmin.MAIN_ADMIN where PROFILEID='$profileid' AND SCREENING_TYPE='H'";
	mysql_query_decide($sqladmin) or die("$sqladmin".mysql_error_js());
	//delete from the main admin table
	$sql="delete from jsadmin.MAIN_ADMIN where SCREENING_TYPE='H' AND PROFILEID='$profileid' " ;
	mysql_query_decide($sql) or die("$sql".mysql_error_js());

	//update HOROSCOPE_FOR_SCREEN to set UPLOADED='Y'
	$objUpdate = JProfileUpdateLib::getInstance();
	$result = $objUpdate->updateHOROSCOPE_FOR_SCREEN($profileid,array('HOROSCOPE'=>$horoscope_content,'UPLOADED'=>'Y'));
	if(false === $result) {
		die('Issue while updating horoscope at line 42');
	}
//        $sql="UPDATE newjs.HOROSCOPE_FOR_SCREEN SET HOROSCOPE='$horoscope_content',UPLOADED='Y' WHERE PROFILEID='$profileid'";
//        mysql_query_decide($sql) or die("$sql".mysql_error_js());


	/********************** CRON PART STARTS ****************************/	
	//now a cron to shift all entries with UPLOADED='Y' from HOROSCOPE_FOR_SCREEN to HOROSCOPE table and update the HOROSCOPE_SCREENING='1' in ASTRO_DETAILS table

	$result = $objReplace->copyAllHoroscopFromScreen();
	if($result === false) {
		die('Error While copying horoscope at line 47.');
	}
	/*$sql="REPLACE into newjs.HOROSCOPE (PROFILEID,HOROSCOPE) select PROFILEID,HOROSCOPE from newjs.HOROSCOPE_FOR_SCREEN where UPLOADED='Y'";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());*/

	//query to update the HOROSCOPE_SCREENING='1' in ASTRO_DETAILS table
	$result = $objUpdate->updateASTRO_DETAILS($profileid,array('HOROSCOPE_SCREENING'=>'1'));
	if(false === $result) {
		die('Issue while updating horoscope at line 61');
	}
//	$sql_update="update newjs.ASTRO_DETAILS set HOROSCOPE_SCREENING='1' where PROFILEID='$profileid'";
//	mysql_query_decide($sql_update) or die("$sql_update".mysql_error_js());

	//query to insert record into newjs.SWAP_JPROFILE
	$sql_sj="INSERT IGNORE INTO newjs.SWAP_JPROFILE (PROFILEID) VALUES ($profileid)";
	mysql_query_decide($sql_sj) or die("$sql_sj".mysql_error_js());

	//query to remove the entries from HOROSCOPE_FOR_SCREEN table which have UPLOADED field as 'Y' or 'D'
	$objDelete = ProfileDeleteLib::getInstance();
	$result = $objDelete->deleteAllScreenedRecords();

	if(false === $result) {
		die('Issue while updating Horoscope for screen at line 75');
	}
//	$sql_del="delete from newjs.HOROSCOPE_FOR_SCREEN where UPLOADED='Y' or UPLOADED='D'";
//	mysql_query_decide($sql_del) or die("$sql_del".mysql_error_js());
	/********************** CRON PART ENDS ****************************/	
	//Code added by Vibhor as discussed with Lavesh
	global $mysqlObj;
	global $noOfActiveServers;
	$mysqlObj=new Mysql;
	for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
        {
                $myDbName=getActiveServerName($activeServerId);
		$myDb=$mysqlObj->connect("$myDbName");
		$sql_pr="UPDATE HOROSCOPE_REQUEST SET UPLOAD_SEEN='U',UPLOAD_DATE=now() WHERE PROFILEID_REQUEST_BY='$profileid'";
		$mysqlObj->executeQuery($sql_pr,$myDb);
	}
	//end
}

function get_horoscope()
{
	$sql="select HOROSCOPE from HOROSCOPE_FOR_SCREEN where UPLOADED != 'D' AND PROFILEID='$profileid'";
        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($res);
	
	return $row['HOROSCOPE'];

}
?>
