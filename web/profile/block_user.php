<?php
$path=$_SERVER['DOCUMENT_ROOT'];
include("$path/profile/connect.inc");
$db=connect_db();
$smarty->assign("MODE",$MODE);
$data=authenticated();
if(!$data['PROFILEID'])
{

	die;
}

//echo is_numeric(intval($cid));
if(is_numeric($cid) && $cid!="")
{
	$sql="select count(*) from userplane.blocked where userID='$data[PROFILEID]' and destinationUserID='$cid'";
	$res=mysql_query_decide($sql);
	$row=mysql_fetch_row($res);
	if($jst_check)
	{
		if($row[0])
			echo 1;
		else
			echo 0;
		die;
	}
	if($row[0]>0)
	{
		$sql="delete from userplane.blocked where userID='$data[PROFILEID]' and destinationUserID='$cid'";
	}
	else
	{
		
		$sql="insert ignore into userplane.blocked values('$data[PROFILEID]','$cid')";
	}
	mysql_query_decide($sql);
}
die;
?>
