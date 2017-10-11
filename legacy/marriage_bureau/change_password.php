<?php
/*********************************************************************************************
* FILE NAME     : change_password.php
* DESCRIPTION   : Changes password for the user
* CREATION DATE : 18 May, 2006
* CREATEDED BY  : Nikhil Tandon
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
	
include("connectmb.inc");
$db=connect_dbmb();
$data=authenticatedmb($mbchecksum);

if(isset($data))
{
	$smarty->assign('source',$data['SOURCE']);
	$smarty->assign('cid',$data['CHECKSUM']);
	$smarty->assign('mbchecksum',$data['CHECKSUM']);
	assign_template_pathprofile();
	$HEAD=$smarty->fetch('top_band.htm');
	assign_template_pathmb();
	$smarty->assign('HEAD',$HEAD);
	if($Submit)
	{
		$isError=0;
		$msg="";

		$currPwd=trim($currPwd);
		$newPwd=trim($newPwd);
		$renewPwd=trim($renewPwd);
		$profileid=$data["PROFILEID"];

		if($currPwd=="")
		{
			$isError++;
			$smarty->assign("CPWDERR","Y");
		}
		if($newPwd=="")
		{
			$isError++;
			$smarty->assign("NPWDERR","Y");
		}
		if($renewPwd=="")
		{
			$isError++;
			$smarty->assign("RPWDERR","Y");
		}
		else
		{
			$sql_pwd="SELECT PASSWORD FROM marriage_bureau.BUREAU_PROFILE WHERE PROFILEID=$profileid";
			$res_pwd=mysql_query_decide($sql_pwd) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_pwd,"ShowErrTemplate");
			$row_pwd=mysql_fetch_array($res_pwd);
			$pwd=$row_pwd['PASSWORD'];
			if($pwd!=$currPwd)
			{
				$isError++;
				$smarty->assign("CPWDMATCHERR","Y");
				$smarty->assign("CPWDERR","Y");
			}
			else if($newPwd!=$renewPwd)
			{
				$isError++;
				$smarty->assign("NEWPWDMATCHERR","Y");
				$smarty->assign("NPWDERR","Y");
				$smarty->assign("RPWDERR","Y");
			}
		}

		if($isError==0)
		{
			$sql_newPwd="UPDATE marriage_bureau.BUREAU_PROFILE SET PASSWORD='$newPwd' WHERE PROFILEID='$profileid'";
			$res_newPwd=mysql_query_decide($sql_newPwd) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_newPwd,"ShowErrTemplate");
			$msg="Your Password has been changed";
			$smarty->assign("MSG",$msg);
			
			$lnk="<a href=\"index1.php\">Return to My Account</a>";
			$smarty->assign("LINK1",$lnk);
			assign_template_pathprofile();
			$smarty->display("confirmation2.htm");
		}
		else
		{
			$smarty->assign("ERR",$isError);
			$smarty->assign("MSG",$msg);
			assign_template_pathprofile();
			$smarty->display("reset_password.htm");
		}
	}
	else
	{
		 assign_template_pathprofile();
		$smarty->display("reset_password.htm");
	}
}
else
{
	 timeoutmb();
}

// flush the buffer
if($zipIt)
	ob_end_flush();
		
?>
