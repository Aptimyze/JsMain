<?php
/*********************************************************************************************
* FILE NAME     : mail_aff.php
* DESCRIPTION   : Sends mail to Approved and Disapproved Affiliates
* CREATION DATE : 30 June, 2005
* CREATED BY  	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
function mailap($ID)
{
	global $smarty;
	$sql_aff="SELECT USERNAME,PASSWORD,EMAIL FROM affiliate.AFFILIATE_DET WHERE AFFILIATEID='$ID'";
	$res_aff=mysql_query_decide($sql_aff) or logError(mysql_error_js(),$sql_aff);

	if(mysql_num_rows($res_aff)==0)
	{
		logError("There seems to be an error",$sql_aff,"ShowErrTemplate");
	}
	else
	{
		$row=mysql_fetch_array($res_aff);
		$uname=$row["USERNAME"];
		$pwd=$row["PASSWORD"];
		$email=$row["EMAIL"];
		$from="Businessathi@jeevansathi.com";
		$subject="Congratulations ! You are now a Businessathi";

		$smarty->assign("uname",$uname);
		$smarty->assign("pwd",$pwd);
		$msg=$smarty->fetch("businesssathi_approval.html");
		$boundry = "b".md5(uniqid(time()));
        	$MP = "/usr/sbin/sendmail -t  ";
	        $spec_envelope = 1;
        	if($spec_envelope)
	        {
        	        $MP .= " -N never -R hdrs -f $from";
	        }
        	$fd = popen($MP,"w");
	        fputs($fd, "X-Mailer: PHP3\n");
        	fputs($fd, "MIME-Version:1.0 \n");
	        fputs($fd, "To: $email\n");
        	fputs($fd, "From: $from \n");
	        fputs($fd, "Subject: $subject \n");
        	fputs($fd, "Content-Type: text/html; boundary=$boundry\n");
	        fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
        	fputs($fd, "$msg\r\n");
	        fputs($fd, "\r\n . \r\n");
	        $p=pclose($fd);
		return $p;
	}
}

function maildisap($ID)
{
	global $smarty;
	$sql_aff="SELECT USERNAME,EMAIL FROM affiliate.AFFILIATE_DET WHERE AFFILIATEID='$ID'";
	$res_aff=mysql_query_decide($sql_aff) or logError(mysql_error_js(),$sql_aff);

	if(mysql_num_rows($res_aff)==0)
        {
                logError("There seems to be an error",$sql_aff,"ShowErrTemplate");
        }
        else
        {
                $row=mysql_fetch_array($res_aff);
                $uname=$row["USERNAME"];
                $email=$row["EMAIL"];
                $from="Businessathi@jeevansathi.com";
                $subject="Businessathi application declined";
                                                                                                                            
                $msg=$smarty->fetch("businesssathi_Declined.html");
                                                                                                                            
                $boundry = "b".md5(uniqid(time()));
                $MP = "/usr/sbin/sendmail -t  ";
                $spec_envelope = 1;
                if($spec_envelope)
                {
                        $MP .= " -N never -R hdrs -f $from";
                }
                $fd = popen($MP,"w");
                fputs($fd, "X-Mailer: PHP3\n");
                fputs($fd, "MIME-Version:1.0 \n");
                fputs($fd, "To: $email\n");
                fputs($fd, "From: $from \n");
                fputs($fd, "Subject: $subject \n");
                fputs($fd, "Content-Type: text/html; boundary=$boundry\n");
                fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
                fputs($fd, "$msg\r\n");
                fputs($fd, "\r\n . \r\n");
                $p=pclose($fd);
                return $p;
        }
}
?>
