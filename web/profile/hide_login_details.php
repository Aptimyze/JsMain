<?php
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it

	include("connect.inc");
	$db = connect_db();
	
	$data=authenticated($checksum);

	/*************************************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",18);
       	$smarty->assign("bms_right",28);
       	$smarty->assign("bms_bottom",19);
       	$smarty->assign("bms_left",24);
       	$smarty->assign("bms_new_win",32);
	/************************************************End of Portion of Code*****************************************/

	if($data)
	{
		$profileid = $data['PROFILEID'];
		$sql = "Select PASSWORD from newjs.JPROFILE where  activatedKey=1 and PROFILEID = $profileid";
		$result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow = mysql_fetch_array($result);
		include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		if($CMDhide)
		{
			if(!PasswordHashFunctions::validatePassword($passwd, $myrow['PASSWORD']))
			{
				$smarty->assign("CHECKSUM",$checksum);
	                        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
        	                $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                	        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	                        //$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
        	                //$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
                	        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				$smarty->assign("FLAG_PSWD","N");
				$smarty->assign("hide",$hide);
                        	$smarty->display("hide_login_details.htm");
			}
			else  
			{
				// close mysql connection to 205
				//mysql_close($db);

				// take connection to 241
				$db=connect_slave81();

				/*$sql="update matchalerts.HIDE_LOGIN_DETAILS set HIDE_DETAILS='Y' where PROFILEID='$profileid'";
				$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				if(mysql_affected_rows_js()==0)*/
        			{
                			$sql="INSERT IGNORE into matchalerts.HIDE_LOGIN_DETAILS values ('$profileid','Y')";
                			mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        			}

				// close connection to 241
				@//mysql_close($db);

				$smarty->assign("hide",$hide);
				$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
				//$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				$smarty->assign("HEADING","Login Information Hidden");
				$smarty->assign("MSG","Your login information has been hidden.To unhide it now click on the link below");
				$smarty->assign("LINK1","<a href=\"hide_login_details.php?hide=1&checksum=$checksum\">Unhide your login details</a>");
				$smarty->display("confirmation1.htm");
			}
		}
		elseif($CMDunhide)
		{
			if(!PasswordHashFunctions::validatePassword($passwd, $myrow['PASSWORD']))
			{
				$smarty->assign("CHECKSUM",$checksum);
	                        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
        	                $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                	        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                        	//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	                        //$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
        	                $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
                                $smarty->assign("FLAG_PSWD","N");
				$smarty->assign("hide",$hide);
        	                $smarty->display("hide_login_details.htm");

			}
			else   
			{	
				// close connection to 205
				//mysql_close($db);

				// establish a connection to 241
				$db = connect_slave81();

				$sql="update matchalerts.HIDE_LOGIN_DETAILS set HIDE_DETAILS='N' where PROFILEID='$profileid'";
                                $res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

				// close connection to 241
				@//mysql_close($db);
			
				$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
				//$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				$smarty->assign("HEADING","Login Information Unhidden");
				$smarty->assign("MSG","You will receive login information in matchalerts henceforth.<br>To not to receive the same click on the link below");
				$smarty->assign("LINK1","<a href=\"hide_login_details.php?hide=0&checksum=$checksum\">Hide your login details</a>");
				$smarty->display("confirmation1.htm");
			}
		}
		else 
		{
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			$smarty->assign("hide",$hide);
			
			$smarty->display("hide_login_details.htm");
		}
	}
	else 
	{
		TimedOut();
	}
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
