<?php
/*****************************************************************************************************************************		 FILE NAME      : change_passwd.php
*	    DESCRIPTION    : Changes password for the user
*	    FILES INCLUDED : connect.inc ; functions used : authenticated()(for authentication of the user) , getuser()(to				  get user login name)
*           CREATION DATE  : 10 June, 2005
*           CREATED BY     : Shobha Kumari
* 	    Copyright  2005, InfoEdge India Pvt. Ltd.
****************************************************************************************************************************/

include("connect.inc");
 $db1    = db_connect();
if(authenticated($cid))
{
	$name=getuser($cid);
	$is_error = 0;

	if($submit)
	{
		// Get the user's current password

		$old_pwd_sql = "SELECT PASSWORD FROM jsadmin.PSWRDS WHERE USERNAME='$name'";
                $old_pwd_res = mysql_query($old_pwd_sql) or die("$old_pwd_sql".mysql_error());
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
		elseif(trim($old_passwd) != $old_pwd)
		{
			$iserror++;
                        $msg.= "<font color=\"red\">Current Password doesn't match with the one in our database!!<br>Re-Enter your current password</font>";		
		}

		if ($iserror > 0 )
		{
			$smarty->assign("isempty_new_pwd",$isempty_new_pwd);
			$smarty->assign("isempty_confirm_new_pwd",$isempty_confirm_new_pwd);
			$smarty->assign("isempty_old_passwd",$isempty_old_passwd);
				
			$smarty->assign("old_passwd",$old_passwd);
			$smarty->assign("msg",$msg);
			$smarty->assign("name",$name);
			$smarty->assign("cid",$cid);
			$smarty->display("change_passwd.htm");
		}
		else
		{	// update the password

			$new_pwd_sql="UPDATE jsadmin.PSWRDS SET PASSWORD ='$new_pwd' WHERE USERNAME ='$name'";
			$new_pwd_res = mysql_query($new_pwd_sql) or die("$new_pwd_sql".mysql_error());

			$msg.="Your password has been changed<br>";
			$msg.="<a href=\"mainpage.php?cid=$cid&name=$name\">Continue</a>";

			$smarty->assign("name",$name);
                        $smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
		}
	}
	else
	{	
		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("change_passwd.htm");
	}
}
else
{
	$msg = "Your session has been timed out<br>  ";
	$msg.= "<a href=\"index.htm\">";
	$msg.= "Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
