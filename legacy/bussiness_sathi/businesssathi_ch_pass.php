<?php
/*********************************************************************************************
* FILE NAME     : businesssathi_ch_pass.php
* DESCRIPTION   : Displays Business Sathi Change Password page after putting Head and Left 
*		: panels in place
* FUNCTIONS	: connect_db()		: To connect to the database server
*		: authenticated()	: To check if the user is authnticated or not
*		: TimedOut()		: To take action if the user is not authenticated
* CREATION DATE : 16 June, 2005
* CREATED BY  : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
$db=connect_db();

$data=authenticated($checksum);
$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
$smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));
 
if(isset($data))
{
        if($submit)
        {
                $isError=0;
                $msg="";
 
                $currPwd=trim($currPwd);
                $newPwd=trim($newPwd);
                $renewPwd=trim($renewPwd);
                $affid=$data["AFFILIATEID"];
 
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
                        $sql_pwd="SELECT PASSWORD FROM affiliate.AFFILIATE_DET WHERE AFFILIATEID=$affid";
                        $res_pwd=mysql_query($sql_pwd) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_pwd,"ShowErrTemplate");
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
                        $sql_newPwd="UPDATE affiliate.AFFILIATE_DET SET PASSWORD='$newPwd' WHERE AFFILIATEID='$affid'";
                        $res_newPwd=mysql_query($sql_newPwd) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_newPwd,"ShowErrTemplate");
                        $msg="Your Password has been changed";
                        $smarty->assign("MSG",$msg);
 
                        $lnk="<a href=\"businesssathi_mybusi_sathi.php?checksum=$checksum\">My Business Sathi</a>";
                        $smarty->assign("LINK1",$lnk);
			$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("business_sathi/left.htm"));
                        $smarty->display("business_sathi/businesssathi_chpwd_confirm.htm");
                }
                else
                {
                        $smarty->assign("ERR",$isError);
                        $smarty->assign("MSG",$msg);
                        $smarty->display("business_sathi/businesssathi_ch_pass.htm");
                }
        }
        else
        {
                $smarty->display("business_sathi/businesssathi_ch_pass.htm");
        }
}
else
{
	TimedOut();
}
?>

