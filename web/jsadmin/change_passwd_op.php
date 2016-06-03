<?php

include("connect.inc");

if(authenticated($cid))
{	
	if($submit2)
	{
		$enter_passwd=1;
		
		$uname=getuser($cid);
		$is_error = 0;
		$uname= shiv;
	
		$old_pwd_sql = "SELECT PASSWORD FROM jsadmin.PSWRDS WHERE USERNAME='$operator'";
                $old_pwd_res = mysql_query_decide($old_pwd_sql) or die("$old_pwd_sql".mysql_error_js());
                $old_pwd_row = mysql_fetch_array($old_pwd_res );
                                                                                                                             
                $old_pwd     = $old_pwd_row["PASSWORD"];
		
		if (trim($old_passwd) == "")
                {
                        $iserror++;
                        $isempty_old_passwd = 1;
                }

		if (trim($new_pwd) == "") 
		{	
			$iserror++;
			$isempty_new_pwd = 1;
		}
		if (trim($confirm_new_pwd) == "")
		{	
			$iserror++;
			$isempty_confirm_new_pwd = 1;
			
		}
		elseif (trim($new_pwd != $confirm_new_pwd))
		{
			$iserror++;
			$msg.= "<font color=\"red\">New Password and Confirmation Password do not match!!<br>Re-Enter your new password</font>";
		}
		elseif(md5(trim($old_passwd)) != $old_pwd)
		{
			$iserror++;
                        $msg.= "<font color=\"red\">Current Password doesn't match with the one in our database!!<br>Re-Enter your current password</font>";		
		}

		if ($iserror > 0 )
		{
			$smarty->assign("enter_passwd",$enter_passwd);
			$smarty->assign("isempty_new_pwd",$isempty_new_pwd);
			$smarty->assign("isempty_confirm_new_pwd",$isempty_confirm_new_pwd);
			$smarty->assign("isempty_old_passwd",$isempty_old_passwd);
				
			$smarty->assign("old_passwd",$old_passwd);
			$smarty->assign("msg",$msg);
			$smarty->assign("operator",$operator);
			$smarty->assign("cid",$cid);
			$smarty->display("change_passwd_op.tpl");
		}
		else
		{	// update the password
			$new_pwd = md5($new_pwd);

			$new_pwd_sql="UPDATE jsadmin.PSWRDS SET PASSWORD ='$new_pwd' WHERE USERNAME ='$operator'";
			$new_pwd_res = mysql_query_decide($new_pwd_sql) or die("$new_pwd_sql".mysql_error_js());

			$msg.="Operator's password has been changed<br>";
			$msg.="<a href=\"mainpage.php?cid=$cid&name=$name\">Continue</a>";

			$smarty->assign("name",$uname);
                        $smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
		}
	
	}
	elseif($submit1)
	{
		$enter_passwd=1;
		$smarty->assign("enter_passwd",$enter_passwd);
		$smarty->assign("operator",$operator);
		$smarty->assign("cid",$cid);
		$smarty->display("change_passwd_op.tpl");
	}
	else
	{

 	 $name= getuser($cid);
	 $flag= true;
	 $center_sql= "SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME = '$name'";
	 $center_res= mysql_query_decide($center_sql) or die("$center_sql".mysql_error_js());
	 $center_row= mysql_fetch_array($center_res);
	 $center= $center_row["CENTER"];


	 $opr_uname_sql= "SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%OB%' AND CENTER ='$center'";
	 $opr_uname_res= mysql_query_decide($opr_uname_sql) or die(mysql_error_js());
	 while($opr_uname_row= mysql_fetch_array($opr_uname_res))
	 {
		$opr_uname[]= $opr_uname_row['USERNAME'];
	 }
	
	 $smarty->assign("operator",$opr_uname);
	 $smarty->assign("cid",$cid);
	 $smarty->display("change_passwd_op.tpl");
	 $flag= false;
	 }
	}
	 
	

else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}
?>


