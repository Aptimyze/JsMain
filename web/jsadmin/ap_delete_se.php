<?php
include("connect.inc");
$db=connect_db();

if(authenticated($cid))
{
	$user=getname($cid);
	if($submit)
	{
		if($deleteSe==$reassignSe)
			$smarty->assign("errorMessage","Please chose a different SE for reassigning");
		else
		{
			$sql="SELECT PROFILEID FROM Assisted_Product.AP_PROFILE_INFO WHERE SE='$deleteSe'";
			$res=mysql_query_decide($sql) or die("Error while fetching assigned profiles  ".$sql."   ".mysql_error_js());
			if(mysql_num_rows($res))
			{
				while($row=mysql_fetch_assoc($res))
					$insertString.="('$row[PROFILEID]','$reassignSe',NOW(),'$user'),";
				$insertString=trim($insertString,",");
				$sql2="UPDATE Assisted_Product.AP_PROFILE_INFO SET SE='$reassignSe' WHERE SE='$deleteSe'";
				$res2=mysql_query_decide($sql2) or die("Error while updating records  ".$sql2."  ".mysql_error_js());
				if(mysql_affected_rows_js())
				{
					$sql2="INSERT INTO Assisted_Product.AP_ASSIGN_LOG(PROFILEID,USER,DATE,ASSIGNED_BY) VALUES$insertString";
					mysql_query_decide($sql2) or die("Error while logging records  ".$sql2."  ".mysql_error_js());
				}
			}

			$sql="UPDATE newjs.OFFLINE_REGISTRATION SET EXECUTIVE='$reassignSe' WHERE EXECUTIVE='$deleteSe'";
			mysql_query_decide($sql) or die("Error while reassigning profiles  ".$sql."   ".mysql_error_js());

			$sql="UPDATE jsadmin.PSWRDS SET ACTIVE='N',MOD_DT=NOW(),ENTRYBY='$user' WHERE USERNAME='$deleteSe'";
                        mysql_query_decide($sql) or die("Error while deleting se   ".$sql."  ".mysql_error_js());
	
			$smarty->assign("errorMessage","$deleteSe is deactivated");
		}
	}
	$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%SE%' AND ACTIVE='Y'";
	$res=mysql_query_decide($sql) or die("Error while fetching se names   ".$sql."   ".mysql_error_js());
	if(mysql_num_rows($res))
	{
		while($row=mysql_fetch_assoc($res))
		{
			$se[]=$row["USERNAME"];
		}
		$smarty->assign("se",$se);
	}
	$smarty->assign("user",$user);
	$smarty->assign("cid",$cid);
	$smarty->display("ap_delete_se.htm");
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
