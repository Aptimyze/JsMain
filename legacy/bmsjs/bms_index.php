<?PHP
/***************************************************bms_adminindex.php******************************************************/  /*
   *  Created By         : Abhinav Katiyar
   *  Last Modified By   : Abhinav Katiyar
   *  Description        : This file is used for different logins viz that of admin , client etc.
   *  Includes/Libraries : bms_connect.php
  */
/***************************************************************************************************************************/

include ("./includes/bms_connect.php");
$ip = FetchClientIP();
global $dbbms;


/**************************************************************************************
            This function performs form validation
/**************************************************************************************/

function checkForm($username,$password)
{
	global $smarty;
	$check=1;
	
	$username = trim($username);
	$password = trim($password);

	if ($username == "")
	{
		$error = "The username column cannot be left blank.Kindly put in a valid username to login.";
		$check = 0;
	}
	elseif ($password == "")
	{
		$error = "The password column cannot be left blank.Kindly put in a valid password to login.";
		$check = 0;
	}
	if ($check == 0)
	{
		$smarty->assign("errormsg",$error);
	 	return 0;
	}
	else 
		return 1;
}
if ($cancel_x)  
{

	$smarty->display("./$_TPLPATH/bms_loginpage.htm");
}

elseif ($login_x || $username || $password || $bms_sums)
{
	if ($bms_sums == "Y")
	{
		$sql = "SELECT user_name , pswd FROM clientprofile.client_reg WHERE company_id='$company_id'";
		$res = mysql_query($sql) or die("$sql".mysql_query());
		$row = mysql_fetch_array($res);
		$username = $row['user_name'];
		$password = $row['pswd'];
	}
	else
	{
		$username = mysql_real_escape_string(trim($username));
		$password = mysql_real_escape_string(trim($password));
	}

	if (checkForm($username,$password))
	{
		$data = loginBms($username, $password,$ip);

		if ($data)
		{
			$privilege	= $data["PRIVILEGE"];
			$id		= $data["ID"];
			$site 		= $data["SITE"];

			if ($privilege == "banadmin")
				include("bms_adminindex.php");    // mainpage for admin login
			elseif ($privilege == "sales")
				include("bms_salesindex.php");    // mainpage for sales login
			elseif ($privilege == "designer")
				include("bms_designerindex.php"); // mainpage for designer's login
			elseif ($privilege == "client")
				include("bms_clientmis.php");
			elseif ($privilege == "book")
                                include("bms_bookindex.php");	  // mainpage for booking new campaigns
			elseif ($privilege == "operations")
				include("bms_operation.php");
			elseif ($privilege == "99acresadmin")	// added for 99 acres separate login
			{
				include("bms_99acresadminindex.php");
			}
			else 
			{	echo "check ur privilege";
				exit;
			}
		}
		else
		{
			$errormsg = "You have entered an incorrect username and password.Please try again.";
			$smarty->assign("errormsg",$errormsg);
			$smarty->display("./$_TPLPATH/bms_loginpage.htm");
		}
		
	}
	else
	{
		$smarty->display("./$_TPLPATH/bms_loginpage.htm");
	}
}
else
{
	$smarty->display("./$_TPLPATH/bms_loginpage.htm");
}	
?>
