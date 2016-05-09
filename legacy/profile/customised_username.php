<?php
/************************************************************************************************************************
* 	FILE NAME	:	customised_username.php
* 	DESCRIPTION 	: 	Get details for a new profile
* 	MODIFY DATE	: 	23 MAR, 2006
* 	MODIFIED BY	: 	Nikhil Tandon
* 	REASON		: 	For customised username
* 	Copyright  2005, InfoEdge India Pvt. Ltd.
************************************************************************************************************************/

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//include_once("contact.inc");
include_once("connect.inc");
include_once("screening_functions.php");
$db=connect_db();
$data=authenticated($checksum);

//Bms code
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);
//Ends here.

if($data["BUREAU"]==1 && ($_COOKIE['JSMBLOGIN'] || $mbureau=="bureau"))
{
        $fromprofilepage=1;
        mysql_select_db_js('marriage_bureau');
        include('../marriage_bureau/connectmb.inc');
        $mbdata=authenticatedmb($mbchecksum);
        if(!$mbdata)timeoutmb();
        $smarty->assign("source",$mbdata["SOURCE"]);
        $smarty->assign("mbchecksum",$mbdata["CHECKSUM"]);
	$mbchecksum=$mbdata["CHECKSUM"];
        mysql_select_db_js('newjs');
        //$data=login_every_user($profileid);
        $mbureau="bureau1";
}

$profileid=$data["PROFILEID"];
$username=$data["USERNAME"];
if($data)
{
	login_relogin_auth($data);//added by lavesh

	//$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
        //$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
        if($mbureau=="bureau1")
        {
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                $smarty->assign("mb_username_profile",$data["USERNAME"]);
                $smarty->assign("checksum",$data["CHECKSUM"]);
                $smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
                $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
        }
        else
        {
		//added by lavesh for revamp.
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		//Ends for revamp
                //smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                //marty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
        }
	if($Submit)
	{
		//Commented By lavesh as Password field is removed.
			
		//see if the current username/password exists in the dbase or not,if the user has already entered the username password the previous time userpass=1
		/*if($userpass!=1)
		{
			$sql="SELECT USERNAME FROM newjs.JPROFILE WHERE  USERNAME='$username' AND PASSWORD='$password'";
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if(!($res=mysql_fetch_array($res)))
			{
				//username password did not match
				$wrong=1;
				$smarty->assign('username',$username);
				$username_available=1;
				$smarty->assign('username_available',$username_available);
				$smarty->assign('newusername',$newusername);
				$smarty->assign('newusername1',$newusername);
				$smarty->assign('wrong',$wrong);
				$smarty->assign('checksum',$checksum);
				//$smarty->display('customised_username.htm');
				$smarty->display('customized_id.htm');
				exit;
			}
		}*/		
		$isitok=customisedusername($profileid);
                                                                                                                             
                if($isitok==0)
		{
                        error($mbchecksum);
			exit;
		}

		include('cuafunction.php');
		$status=checknewusername($newusername,$profileid);
		$ch=$newusername[0];
		if ( ( ($ch < "a" || "z" < $ch) && ($ch < "A" || "Z" < $ch) ))
		{
			$status=0;
		}
		$smarty->assign('username',$username);
		$smarty->assign('newusername1',$newusername);
		//code added by sriram on May 25th to stop username from screening.
		//if condition to check, weather to screen username or not.
		if(!check_username_email($profileid,$newusername) && !check_obscene_word($newusername) && !check_for_continuous_numerics($newusername,"") && !check_for_intelligent_usage($newusername) && $status==1)
		{
			$sql = "INSERT IGNORE INTO newjs.CUSTOMISED_USERNAME(PROFILEID,OLD_USERNAME,NEW_USERNAME,SCREENED, SCREENEDBY,TIMEOFINSERTION,SCREENED_TIME) VALUES('$profileid','$username','$newusername','A','USER',now(),now())";
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

			//code to send email notification to user.
			$sql="SELECT PASSWORD,EMAIL FROM newjs.JPROFILE WHERE  PROFILEID='$profileid'";
			$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate"); 
			if($row = mysql_fetch_array($res))
			{
				$emailid=$row['EMAIL'];
				$password=$row['PASSWORD'];
			}
			$smarty->assign('newusername',$newusername);
			$smarty->assign('emailid',$emailid);
			$smarty->assign('password',$password);
			$THINGSTODO=thingstodobox($profileid);
			$smarty->assign('THINGSTODO',$THINGSTODO);
			$mail_msg=$smarty->fetch("../jsadmin/mailer_ID_accepted.htm");
			send_email($emailid,$mail_msg,"JeevanSathi.com-Account Activation","register@jeevansathi.com");
			makes_username_changes($profileid,$newusername);
			//end of - code to send email notification to user.

			$msg="Your user ID has been changed successfully.";
			if($mbureau=="bureau1")
				$link="<a href=\"$SITE_URL/marriage_bureau/index1.php?checksum=$mbchecksum\">Click here</a> to go to My Jeevansathi";
			else
				$link="<a href=\"$SITE_URL/profile/mainmenu.php?checksum=$checksum\">Click here</a> to go to My Jeevansathi.";

			$smarty->assign("msg",$msg);
			$smarty->assign("link",$link);
			$smarty->assign("url",1);
			$smarty->display("confirmation.htm");
			exit();
		}
		else
		{
			$userpass=1;
			$alreadyexists=1;
			$smarty->assign("invalid_userid","N");
			if(check_username_email($profileid,$newusername) || check_obscene_word($newusername) || check_for_continuous_numerics($newusername,"") || check_for_intelligent_usage($newusername))
				$smarty->assign("invalid_userid","Y");
			$smarty->assign('userpass',$userpass);
			$smarty->assign('checksum',$checksum);
			$smarty->assign('alreadyexists',$alreadyexists);
			$smarty->display('customized_id.htm');
		}
		//end of code added by sriram on May 25th 2007 to stop username from screening.

		//commented by sriram on May 25th 2007.
		/*if($status==1)
		{
			$sql="INSERT IGNORE INTO newjs.CUSTOMISED_USERNAME(PROFILEID,OLD_USERNAME,NEW_USERNAME,SCREENED,TIMEOFINSERTION) VALUES('$profileid','$username','$newusername','N',now())";
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");


			$msg="Thank you for changing your user ID.<br><b>Your new user ID will be screened and will take 24 hrs to become live on site.</b><p>Note: User ID with Contact No., E-mail ID and other Personal details will not be made live.</p>";
			if($mbureau=="bureau1")
				$link="<a href=\"$SITE_URL/marriage_bureau/index1.php?checksum=$mbchecksum\">Click here</a> to go to My Jeevansathi";
			else
				$link="<a href=\"$SITE_URL/profile/mainmenu.php\">Click here</a> to go to My Jeevansathi.";

			$smarty->assign("msg",$msg);
			$smarty->assign("link",$link);
			$smarty->assign("url",1);
			$smarty->display("confirmation.htm");
			//$smarty->display('thank4cUSERID.htm');
		}
		else
		{
			$userpass=1;
			$alreadyexists=1;
			$smarty->assign('userpass',$userpass);
			$smarty->assign('checksum',$checksum);
			$smarty->assign('checksum',$checksum);
			$smarty->assign('alreadyexists',$alreadyexists);
			//$smarty->display('customised_username.htm');
			$smarty->display('customized_id.htm');
		}*/
	}
	else 
	{
		$isitok=customisedusername($profileid);

		if($isitok==0)
		{
			//Added By lavesh for Re-vamp 
			//$smarty->display('au_updated.htm');
			error($mbchecksum);
			//Ends Here.
		}
		else
		{
			$username_available=1;
			$smarty->assign('checksum',$checksum);
			$smarty->assign('username',$username);
			$smarty->assign('newusername1',$newusername);
			$smarty->assign('username_available',$username_available);	
			//$smarty->display('customised_username.htm');
			$smarty->display('customized_id.htm');
		}
	}
}
else
{
	TimedOut();
}

if($zipIt)
         ob_end_flush();

//Created By lavesh.
function error($mbchecksum)
{
	 global $smarty;
	 $smarty->assign("customised_username",1);
	//$msg="<p><b><font color=red>Sorry!</font> Your user-ID could not be changed due to one of the following reasons-</b><br><ul><li>You have used your contact no.<br><li>You have used your E-mail ID.<br><li>You have used any other contact details.<br><li>You have already initiated a contact in the first one week of creating your User ID.<br></ul></p>";
	$msg="<p><b><font color=red>Sorry!</font> Your User Id can not be changed due to the following reasons</b><br><ul><li> Incase you have initiated a contact using your computer generated User Id the system will not allow you to customize your User Id, basically to avoid confusion, OR<br><br><li> If you have used your contact no., e-mail Id or any other personal details in your User Id<br><br><p>The customized User Ids are screened before making visible on the site. Once the User Id is rejected after screening, the user cannot change the Id again <br>";
													     
	if($mbchecksum)
		//$msg.="<p><b>In case of any query send an e-mail to </b><a href=\"mailto:anshul@jeevansathi.com\" ><font color=\"blue\">anshul@jeevansathi.com</font></a></p>";
		$msg.="<p><b>In case of any query send an e-mail to </b><a href=\"mailto:anshul@jeevansathi.com\" ><font color=\"blue\">anshul@jeevansathi.com</font></a></p>";
	else
		//$msg.="<p><a href=\"$SITE_URL/profile/contact.php\" >Contact Us</a><b> in case of any query</b></p>";
		$msg.="<p>In case of any query <a href=\"$SITE_URL/profile/contact.php\" >Contact Us</a><b> </b></p>";
	$smarty->assign("msg",$msg);
													     
	if($mbchecksum)
		$link.="<p><a href=\"$SITE_URL/marriage_bureau/index1.php?checksum=$mbchecksum\"><font color=\"blue\">Click here</font></a> to go to My Account. <br></p>";
	else
		$link.="<p><a href=\"$SITE_URL/profile/mainmenu.php\">Click here </a>to go to My Jeevansathi. <br>";
	$smarty->assign("link",$link);
	$smarty->assign("url",1);
													     
	$smarty->display("error.htm");
}

?>
