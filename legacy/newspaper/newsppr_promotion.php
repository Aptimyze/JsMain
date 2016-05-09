<?php
require_once("newsprom_comfunc.inc");
require_once("connect.inc");

$empty = 1;
dbsql2_connect();

if (authenticated($cid))
{  
	$username = getname($cid);
	$db 	  = db_connect();   		// connection to localhost
	$db2      = dbsql2_connect();		// connection to localhost:/tmp/mysql2.sock

	if ($submit)
	{
		if (!$source || !$gender || !$caste || !$age || !$city || !$maritalstatus)
		{
			$empty = 0;	
			$msg = "<br>Please fill in all the details <br/><br/>";
		}
		else
		{
			if(trim($mobileno) == "" && trim($address) == "" && trim($email) == "") // One of these is compulsory
			{
				$empty = 0;
				$msg = "<font color=red><br> Please enter atleast one of these (Email / 
					Mobile no / Address) </font><br><br>";
			}
		}

		if (trim($email) != "" && checkemail($email) || checkoldemail($email))
		{
			$empty = 0;

			if(checkemail($email))
				$msg = "<font color=red>Invalid Email Address !!<br>";

			if (checkoldemail($email))
				$msg.= "<font color=red>This email address already exists!!!<br>";

			$msg.= "Re-Enter a valid email address </font><br>";
		}
		
		if (trim($mobileno) != "" && !is_numeric($mobileno))
		{
			$empty = 0;
			$msg = "<font color=red><br /> Invalid Mobile Number.Enter a valid number</font><br /><br />";
		}
		
		/*if (trim($Name) == "")
		{
			$empty = 0;
	
		}*/

		if ($empty == 0)
		{	
			
			$caste	 = create_dd("$caste","Caste");
	                $city	 = create_dd("$city","City");
        	        $source	 = create_dd("$source","Source");
			$country = create_dd("$country","Country");
		
			$smarty->assign("SOURCE",$source);
			$smarty->assign("NAME",$Name);
			$smarty->assign("GENDER",$gender);
	                $smarty->assign("AGE",$age);
        	        $smarty->assign("CASTE",$caste);
			$smarty->assign("MARITALSTATUS",$maritalstatus);
                	$smarty->assign("EMAIL",$email);
			$smarty->assign("COUNTRY",$country);
			$smarty->assign("CITY",$city);			
	                $smarty->assign("ADDRESS",$address);
        	        $smarty->assign("MOBILENO",$mobileno);
			$smarty->assign("cid",$cid);
			$smarty->assign("mode",$mode);
			$smarty->assign("MSG",$msg);
			$smarty->assign("modcontinue",$modcontinue);
			$smarty->assign("username",$username);
			$smarty->assign("HEAD",$smarty->fetch("head.htm"));
			
			$smarty->display("newsppr_promotion.htm");	
		}
		else
		{
			if (!trim($email))
			{
				$rand_email = email_gen(6);
				$email = $rand_email."@jsxyz.com";
			}
			$mailsql = "INSERT IGNORE INTO jsadmin.AFFILIATE_MAIN (SOURCE , EMAIL , MODE , ENTRYBY , ENTRYTIME) values ('$source', '$email','$mode','$username',NOW())";

	        	$mailres = mysql_query($mailsql,$db2) or die("$mailsql".mysql_error());

			$sql = "INSERT  IGNORE INTO jsadmin.AFFILIATE_DATA (USERNAME , GENDER , MSTATUS, AGE, CASTE, EMAIL , PHONE_MOB , COUNTRY_RES , CITY_RES , CONTACT , ENTRY_DT) values ('$Name','$gender','$maritalstatus','$age','$caste', '$email','$mobileno','$country','$city','$address',NOW())";
			$res = mysql_query($sql,$db2) or die("$sql".mysql_error());

	   		$msg = " Record Inserted <br />";

			if (!$modcontinue)
				$msg.= "<a href=\"newsppr_promotion.php?cid=$cid&name=$name&mode=$mode\">";
			else
				$msg.= "<a href=\"emailvalidation.php?cid=$cid&name=$name&mode=$mode\">";

			$msg.= "Continue </a>";

        	        $smarty->assign("MSG",$msg);
			$smarty->assign("username",$username);
                	$smarty->display("jsadmin_msg.tpl");

		}
	}
	else
	{
		
		$caste	 = create_dd("","Caste");
		$city	 = create_dd("","City");
		$source	 = create_dd("","Source");
		$country = create_dd("","Country");
		
		$smarty->assign("CASTE",$caste);
		$smarty->assign("SOURCE",$source);
		$smarty->assign("COUNTRY",$country);
		$smarty->assign("CITY",$city);
		//$smarty->assign("MOBILENO",$mobileno);
        	$smarty->assign("MSG",$msg);
		$smarty->assign("mode",$mode);
		$smarty->assign("cid",$cid);
		$smarty->assign("name",$name);
		$smarty->assign("modcontinue",$modcontinue);
		$smarty->assign("username",$username);

		$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	        $smarty->display("newsppr_promotion.htm");
	}
}
else
{
        	$msg = "Your session has been timed out<br>  ";
	        $msg.= "<a href=\"index.php\">";
	        $msg.= "Login again </a>";
	        $smarty->assign("MSG",$msg);
	        $smarty->display("jsadmin_msg.tpl");
}
?>
