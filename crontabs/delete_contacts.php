<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	ini_set('max_execution_time','0');
	ini_set('memory_limit',-1);

	chdir("$_SERVER[DOCUMENT_ROOT]/profile");
	include("$_SERVER[DOCUMENT_ROOT]/profile/connect.inc");

	// take mysql connection on slave
	$db=connect_slave();

	// No. of months before which the records are to be deleted
	$DIFFMONTH=6;

	$sql="select DATE_SUB(CURDATE(), INTERVAL $DIFFMONTH MONTH) as date1";
	$result=mysql_query_decide($sql) or logError($sql);

	$myrow=mysql_fetch_array($result);

	$date1=$myrow["date1"];
	
	mysql_free_result($result);
	
	$sql="select CONTACTID,SENDER,RECEIVER from CONTACTS where TIME	< '$date1' and TYPE='I'";
	$result=mysql_query_decide($sql) or logError($sql);
	
	// close mysql connection to slave
	//mysql_close($db);
	
	// take mysql connection on master
	$db=connect_db();
	
	while($myrow=mysql_fetch_array($result))
	{
		$contactid=$myrow["CONTACTID"];
		$sender=$myrow["SENDER"];
		$receiver=$myrow["RECEIVER"];
		
		// time and type condition has been added to this query to ensure that if the slave does not return correct result(could be due to stoppage of replication) then we don't accidently delete those records that are not to be deleted
		$sql="insert into OLD_CONTACTS select * from CONTACTS where CONTACTID='$contactid' and TIME < '$date1' and TYPE='I'";
		$res=mysql_query_decide($sql) or logError($sql);
	
		// check whether record has been inserted in OLD_CONTACTS and only then delete from CONTACTS
		if($res && mysql_affected_rows_js()>0)
		{
			$sql="delete from CONTACTS where CONTACTID='$contactid'";
			mysql_query_decide($sql) or logError($sql);

			$sql="insert into MESSAGE_LOG_EXPIRE select * from MESSAGE_LOG where SENDER='$sender' and RECEIVER='$receiver'";
			$res_mes=mysql_query_decide($sql,"","1") or logError($sql);

			if($res_mes)
			{
				$sql="delete from MESSAGE_LOG where SENDER='$sender' and RECEIVER='$receiver'";
				mysql_query_decide($sql,"","1") or logError($sql);
			}

			$sql="insert into MESSAGE_LOG_EXPIRE select * from MESSAGE_LOG where SENDER='$receiver' and RECEIVER='$sender'";
                        $res_mes1=mysql_query_decide($sql,"","1") or logError($sql);

			if($res_mes1)
                        {
	                        $sql="delete from MESSAGE_LOG where SENDER='$receiver' and RECEIVER='$sender'";
        	                mysql_query_decide($sql,"","1") or logError($sql);
			}
		}
	}

?>
