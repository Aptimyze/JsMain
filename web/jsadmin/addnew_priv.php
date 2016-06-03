<?php

include ("connect.inc");
$empty=1;

if(authenticated($cid))
{
	if($submit)
	{
		if(trim($VALUE)=="")
		{	
			$empty=0;
			$smarty->assign('check_val',1);
		}
		if(trim($LABEL)=="")
		{
			$empty=0;
			$smarty->assign('check_label',1);
		}

		if($empty==0)
		{
			$smarty->assign('VALUE',$VALUE);
			$smarty->assign('LABEL',$LABEL);
			$smarty->assign('ACTIVE',$ACTIVE);
			$smarty->assign('cid',$cid);
			$smarty->display('addnew_priv.htm');
		}
		else
		{     
			if(!$ACTIVE)
			{
				$ACTIVE='N';
			} 
			$sql = "INSERT INTO jsadmin.PRIVILAGE (VALUE,LABEL,ACTIVE) VALUES ('$VALUE' , '$LABEL','$ACTIVE') ";
			mysql_query_decide($sql) or die(mysql_error_js());
			$msg= " Record Inserted<br>  ";
			$msg .="<a href=\"showprivilege.php?cid=$cid\">";
			$msg .="Continue </a>";
			$smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
		}
	}
	else
	{
		$smarty->assign('cid',$cid);
		$smarty->display("addnew_priv.htm");				  
	}  
}
else
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
