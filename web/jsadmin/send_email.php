<?php
include("connect.inc");
include("matches_display_results.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

if(authenticated($cid))
{
	if($email)
	{
		$from="offlinematches@jeevansathi.com";
		$subject="Matches for you from jeevansathi";
		$msg="Please find attached profiles which our operators thought would be of interest to you. These profiles have been exclusively searched and filtered for you based on your preferences registered with us.";

		$op_detail=get_operator_detail($cid);
		$msg.="\n\nRegards, \n".ucfirst($op_detail['USERNAME'])." \nJeevansathi Match Point Executive \nEmail id: ".$op_detail['EMAIL']."\n";	
		if($op_detail['PHONE'])
		{
			$msg.="Phone: ".$op_detail['PHONE']."\n";
		}
		if($checkbox)
		{
			$sql="REPLACE INTO jsadmin.OFFLINE_EMAIL (PROFILEID,EMAIL) VALUES('$profileid','$email')";
			mysql_query_decide($sql) or die(mysql_error_js());
		}
		$pid_arr=explode(",",$pids);
		$cnt=count($pid_arr);
		$db=connect_db();
		$sql="SELECT USERNAME,PROFILEID FROM newjs.JPROFILE WHERE PROFILEID IN ($pids)";
		$res= mysql_query_decide($sql) or die (mysql_error_js());
		while($row=mysql_fetch_assoc($res))
		{
			$PROFILEID=$row["PROFILEID"];
			$uname[$PROFILEID]=$row["USERNAME"];
		}
		while($cnt)
		{
			$cnt--;
			$searchid=$pid_arr[$cnt];
			assigndetails($profileid,$searchid);
			$username=$uname[$searchid];
			if($accept)
			{
				$smarty->assign("show_contact","Y");
				contact($searchid,1);
			}
			$viewprofile[$username]=$smarty->fetch("displayprofile_mail.htm");
			
		}	
		if(strstr($email,"@gmail"))
		{
			$msg.="\n Tip: For better viewing experience, please download the profiles and open them in your browser instead of clicking on 'view' in your Gmail";
		}
		send_email_attach($email,$msg,$subject,$from,'','',$viewprofile);
		$smarty->assign("msg","Email Sent!!");
	}
	else
	{
		$sql="SELECT EMAIL FROM jsadmin.OFFLINE_EMAIL WHERE PROFILEID=$profileid ";
		$res= mysql_query_decide($sql) or die(mysql_error_js());
		if(mysql_num_rows($res))
		{
			$row=mysql_fetch_assoc($res);
			$email=$row["EMAIL"];
			$smarty->assign("email",$email);
		}
	}
	$smarty->assign("accept",$accept);
	$smarty->assign("cid",$cid);
	$smarty->assign("profileid",$profileid);
	$smarty->assign("pids",$pids);
	$smarty->display("send_email.htm");
}
else
{
        $msg="Your session has been timed out  ";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}
?>
