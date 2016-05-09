<?php
/******************************************************************************************************************
file	    : screen_address.php
Description : script to upload, browse and verify address of a user
Created By  : Neha Verma
Created On  : 29 Dec 2008
*******************************************************************************************************************/

include("connect.inc");
$db=connect_db();
if(authenticated($cid))
{
	$sql="SELECT PROFILEID FROM jsadmin.ADDRESS_VERIFICATION WHERE SCREENED='X' ORDER BY DATE DESC";
	$res=mysql_query_decide($sql) or die($sql.mysql_error_js());
	if(mysql_num_rows($res))
	{
		while($row=mysql_fetch_array($res))
		{
			$pids[]=$row['PROFILEID'];
			$data[$row['PROFILEID']]='';
		}
		$pid_str=implode(',',$pids);	
		$sql="SELECT USERNAME,PROFILEID FROM newjs.JPROFILE WHERE PROFILEID IN ($pid_str)";
		$res=$res=mysql_query_decide($sql) or die($sql.mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$data[$row['PROFILEID']]=$row['USERNAME'];
		}
		$smarty->assign("data",$data);
	}	
	$smarty->assign("user",$name);
	$smarty->assign("cid",$cid);
	$smarty->display("screened_addresses.htm");
}
else
{
        $msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
