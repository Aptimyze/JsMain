<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        $zipIt = 1;
if($zipIt)
        ob_start("ob_gzhandler");
//end of it

include("connect.inc");

$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	list($mm,$yy)=explode("-",$month);

	if($mm<10)
		$mm="0".$mm;

	if($day<10)
		$day="0".$day;

	$st_date=$yy."-".$mm."-".$day." 00:00:00";
	$end_date=$yy."-".$mm."-".$day." 23:59:59";

	$sql="SELECT USERNAME,PROFILEID FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND SOURCE='$sourceid' AND ACTIVATED='$activated'";
	$res=mysql_query_decide($sql,$db) or die(mysql_error_js($db));
	while($row=mysql_fetch_array($res))
	{
		$userarr[]=array("USERNAME" => $row['USERNAME'],
				"PROFILEID" => $row['PROFILEID']);
	}

	$smarty->assign("userarr",$userarr);
	$smarty->assign("cid",$cid);
	$smarty->display("get_source_members_list1.htm");
}
else
{
	$smarty->display("jsconnectError.tpl");
}

// flush the buffer
if($zipIt)
        ob_end_flush();
?>
