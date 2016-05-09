<?php
/*********************************************************************************************
* FILE NAME     : offline_messages_chat.php
* DESCRIPTION   : Takes Distinct usernames to give links for offline messages in chat
* CREATION DATE : 5 December, 2005
* CREATED BY    : NIKHIL TANDON
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include('connect_db.php');
$db=connect_db();

$sql="SELECT DISTINCT FROM_PROFILEID,FROM_USERNAME FROM userplane.OFFLINE_MESSAGES WHERE FOR_PROFILEID='$profileid' ORDER BY TIMEOFINSERTION asc";
//$sql="SELECT DISTINCT o.FROM_PROFILEID,j.USERNAME,j.PROFILEID  FROM userplane.OFFLINE_MESSAGES o,newjs.JPROFILE j,userplane.blocked b WHERE o.FOR_PROFILEID ='$profileid' AND j.PROFILEID=o.FROM_PROFILEID AND b.destinationUserId != j.PROFILEID ORDER BY TIMEOFINSERTION";
$res=mysql_query_decide($sql,$db) or mysql_error_js($sql);
$i=1;
while($row=mysql_fetch_array($res))
{
	if($i>5)
		break;
        $whocontactedhim_id[$i]=$row['FROM_PROFILEID'];
	$whocontactedhim_name[$i]=$row['FROM_USERNAME'];
	$sql_i="SELECT userID from userplane.blocked where destinationUserId='$whocontactedhim_id[$i]' AND userID='$profileid'";
	$res_i=mysql_query_decide($sql_i,$db) or mysql_error_js($sql_i);
	if(!($row_i=mysql_fetch_array($res_i)))
	{
		echo "&whocontactedhim_id$i=".$whocontactedhim_id[$i]."&whocontactedhim_name$i=".$whocontactedhim_name[$i];
	}
	else
	{
		$i--;
	}
	$i++;
}
if($i>1)
	echo "&arethereofflinemessages=1"."&i=".($i-1);
else
	echo "&arethereofflinemessages=0";
?>
