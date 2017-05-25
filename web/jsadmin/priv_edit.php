<?php

/*************************************************************************************************************************
*    FILENAME        : priv_edit.php 
*    DESCRIPTION     : Edits the privilege of a person 
**************************************************************************************************************************/  
include ("connect.inc");
$empty=1;
  
if (authenticated($cid))
{
	if ($submit)
	{
		if (trim($MOD_VALUE)=="")
		{
			$empty=0;
			$smarty->assign('check_val',1);
		}
		if (trim($MOD_LABEL)=="")
		{
			$empty=0;
			$smarty->assign('check_label',1);
		}                                                                                                                                           
		if ($empty==0)
		{
			$smarty->assign('VALUE',$MOD_VALUE);
			$smarty->assign('LABEL',$MOD_LABEL);
			$smarty->assign('ACTIVE',$MOD_ACTIVE);
			$smarty->assign('ID',$ID);
			$smarty->assign('cid',$cid);
			$smarty->display('priv_edit.htm');
		}
		else
		{
			if(!$MOD_ACTIVE)
				$MOD_ACTIVE='N';

			$sql= "UPDATE jsadmin.PRIVILAGE SET  VALUE='$MOD_VALUE' , LABEL ='$MOD_LABEL' ,ACTIVE='$MOD_ACTIVE'  WHERE ID='$ID' " ;
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
										       
			$msg= " Record Updated<br>  ";
			$msg .="<a href=\"showprivilege.php?cid=$cid\">";
			$msg .="Continue </a>";
			$smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
		}
	}
	else
	{                
		if($act)
		{
			$sql="update jsadmin.PRIVILAGE set ACTIVE='$act' where ID='$ID'";
			mysql_query_decide($sql) or die(mysql_error_js());

			$msg= " Record Updated<br>  ";
			$msg .="<a href=\"showprivilege.php?cid=$cid\">";
			$msg .="Continue </a>";                                
			$smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
		}
		else
		{
			$sql = "SELECT * FROM jsadmin.PRIVILAGE  WHERE ID='$ID'" ;
			$result = mysql_query_decide($sql) or die(mysql_error_js());
			$row=mysql_fetch_array($result);
			$smarty->assign('ID',$row["ID"]);
			$smarty->assign('VALUE',$row["VALUE"]);
			$smarty->assign('LABEL',$row["LABEL"]);
			$smarty->assign('ACTIVE',$row["ACTIVE"]);
			$smarty->assign("cid",$cid);	
			$smarty->display('priv_edit.htm');
		}   
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
