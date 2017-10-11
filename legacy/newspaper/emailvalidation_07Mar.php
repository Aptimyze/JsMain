<?php
/*****************************************************************************************************************************		FILENAME	:emailvalidation.php
*	   INCLUDED FILES  :connect.inc , newsprom_comfunc.inc	, newsppr_promotion.php
*	   CREATED BY      :Shobha
*          CREATION DATE   :25.05.05
*	   DESCRIPTION     :The purpose it serves is the validation of an emailId only before the actual form is presented to 				 a user to prevent the hassle of filling the entire form to learn that the email already exists!
****************************************************************************************************************************/

require_once("connect.inc");
require_once("newsprom_comfunc.inc");

$invalidemail=1;

dbsql2_connect();

if (authenticated($cid))
{  
	$username = getname($cid);
	$db 	  = db_connect();   		// connection to localhost
	$db2      = dbsql2_connect();		// connection to localhost:/tmp/mysql2.sock
	
	if ($isvalidemail)
	{	
		if (trim($EMAIL) == "")
			$invalidemail = 0;

		if (trim($EMAIL) != "" && checkemail($EMAIL) || checkoldemail($EMAIL))
                {
                        $invalidemail = 0; 
			
                        if (checkemail($EMAIL))
                       	        $msg = "<font color=red><br> Invalid Email Address !!!<br>";
                                       
                        if (checkoldemail($EMAIL))
                                $msg.= "<font color=red><br> This email address already exists !!!<br>";                                       
                        $msg.= "Re-Enter a valid email address </font><br><br>";
                }

		if (!$invalidemail)               // If the email is not valid or already exists
		{
			$smarty->assign("EMAIL",$EMAIL);
                        $smarty->assign("cid",$cid);
			$smarty->assign("username",$username);
                        $smarty->assign("name",$name);
			$smarty->assign("MSG",$msg);
			$smarty->assign("HEAD",$smarty->fetch("head.htm"));

                        $smarty->display("emailvalidation.htm");
		}
		else
		{
			$modcontinue = 1;

			$smarty->assign("mode",	$mode);
                        $smarty->assign("cid",	$cid);
                        $smarty->assign("name",	$name);
			$smarty->assign("username",$username);
			$smarty->assign("EMAIL",$EMAIL);
			$smarty->assign("modcontinue",$modcontinue);
			$smarty->assign("HEAD",$smarty->fetch("head.htm"));

			include ("newsppr_promotion.php");
		}
 	}
	else
	{
			$smarty->assign("MSG",$msg);
                        $smarty->assign("mode",$mode);
                        $smarty->assign("cid",$cid);
                        $smarty->assign("name",$name);
                        $smarty->assign("username",$username);
			 $smarty->assign("HEAD",$smarty->fetch("head.htm"));

			$smarty->display("emailvalidation.htm");
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
