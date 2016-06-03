<?php
/*******************************************************************************************************
Filename      : registered_by_me_101.php
Description   : List of 101 members registered by an operator [3271]
Created On    : 3 September 2008
Author        : Sadaf Alam
********************************************************************************************************/

include("connect.inc");

$db=connect_db();

if(authenticated($cid))
{
	$sql="SELECT PROFILEID FROM jsadmin.ASSIGNED_101 WHERE OPERATOR='$name'";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$count=0;
	if(mysql_num_rows($res))
	{
		while($row=mysql_fetch_assoc($res))
		{
			unset($val);
			unset($sub);
			$sub=array();
			$sqldet="SELECT USERNAME,PASSWORD,STD,PHONE_RES,PHONE_MOB,SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
			$resdet=mysql_query_decide($sqldet) or die("$sql".mysql_error_js());
			$rowdet=mysql_fetch_assoc($resdet);
			mysql_free_result($resdet);
			if($rowdet["SUBSCRIPTION"])
				$sub=explode(",",$rowdet["SUBSCRIPTION"]);
			if(in_array("1",$sub))
                                ;
                        else
                                continue;
			$val["USERNAME"]=$rowdet["USERNAME"];
			$val["PASSWORD"]=$rowdet["PASSWORD"];
			if($rowdet["PHONE_RES"])
			{
				if($rowdet["STD"])
					$val["CONTACT"]=$rowdet["STD"]."-".$rowdet["PHONE_RES"];
				else
					$val["CONTACT"]=$rowdet["PHONE_RES"];
			}
			if($rowdet["PHONE_MOB"])
			{
				if($val["CONTACT"])
					$val["CONTACT"]=$val["CONTACT"].",".$rowdet["PHONE_MOB"];
				else
					$val["CONTACT"]=$rowdet["PHONE_MOB"];
			}
			if(!$val["CONTACT"])
				$val["CONTACT"]='';
			$table[]=$val;
			
		}
		mysql_free_result($res);
		$smarty->assign("table",$table);
		$smarty->assign("count",count($table));
	}
	else
		$smarty->assign("NOREC",1);
	$smarty->assign("cid",$cid);
	$smarty->assign("name",$name);
	$smarty->display("registered_by_me_101.htm");
}
else
{
	$msg="Your session has been timed out";
	$smarty->assign("msg",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
