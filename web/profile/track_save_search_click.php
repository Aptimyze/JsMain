<?php
//echo "hiii";
include "connect.inc";
connect_slave();

$sql_mis="select ID from MIS.SAVE_SEARCH_CLICKS where DATE=now()";
$result_mis=mysql_query_decide($sql_mis) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_mis,"ShowErrTemplate");
$row_mis=mysql_fetch_array($result_mis);
if($row_mis['ID'])
	$sql_mis="update MIS.SAVE_SEARCH_CLICKS set COUNT=COUNT+1 where DATE=now()";
else
	$sql_mis="INSERT INTO MIS.SAVE_SEARCH_CLICKS(ID,DATE,COUNT) values ('',now(),'1')";
													     
mysql_query_decide($sql_mis) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_mis,"ShowErrTemplate");


?>
