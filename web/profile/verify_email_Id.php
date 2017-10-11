<?php
/****************************************************************************************************************************		FILENAME : verify_email_Id.php
*	    CREATED BY : Shobha Kumari
*           MODIFIED BY: Lavesh Rawat
*	    CREATED ON : 16..10.2005
*	    FILES INCLUDED : connect.inc
*	    DESCRIPTION : This file is used to prompt user to either change his/her emailId or continue with the same one.
*			  User is directed to this script only if verification of email is required or emails sent to his
*			  her emailID have bounced back more than 2 times or if both the conditions hold true.
***********************************************************************************************************************/

include_once("connect.inc");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
$db=connect_db();

$data = authenticated($checksum);
if($data)
                login_relogin_auth($data);
if ($data)
{
	$today=date("Y-m-d");//added by lavesh
	$profileid = $data["PROFILEID"];
	$SITE_URL=$data["SITE_URL"];
	
	// query to select emailid of the logged in person
	$sql = "SELECT EMAIL , VERIFY_EMAIL FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID ='$profileid'";
	if($res = mysql_query_decide($sql))
	{
		$row = mysql_fetch_array($res);
		$email = $row["EMAIL"];
		$verifyemail = $row["VERIFY_EMAIL"];
	}
	else
		logError("Error in finding email",$sql);

	$smarty->assign("SITE_URL",$SITE_URL);
	$smarty->assign("CHECKSUM",$checksum);

	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));                       
	$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));                                        
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));

	if($modemail)  // if new email is submitted by user
	{
		if( trim($email)!=trim($newemail) )//added by lavesh
		{
			$diff_email=1;
			if (trim($newemail)!='')
			{
				$check_email=checkemail($newemail); // checks validity of email
				//Changed by lavesh as user should be allowed to use old email earlier used by him.
				//$check_old_email=checkoldemail($newemail); // checks if email provided by user already exists 
				$check_old_email=my_checkoldemail($newemail,$profileid);
				//Ends Here.
					
				if ($check_email || $check_old_email)
					$iserror = 1;
				if ($check_email == 1)
					$msg = "Invalid Email Id. Please Enter a valid Email Id";
				elseif ($check_old_email || $check_email == 2)
					$msg = "This Email-Id already exists with us. Please enter a different Email Id ";
			}
			else
			{
				$iserror = 1;
				$msg = "Please enter a valid Email Id";
			}
		}
		if ($iserror == 1)
		{
			maStripVARS("stripslashes");
			$smarty->assign("bouncemail","1");
			$smarty->assign("EMAIL",$email);
			$smarty->assign("iserror",$iserror);
			$smarty->assign("newemail",$newemail);
			$smarty->assign("msg",$msg);
			$smarty->assign("USERNAME",$data["USERNAME"]);
			$smarty->assign("CHECKSUM",$checksum);
			//$smarty->display("verify_email_Id.htm");
			$smarty->display("verifyemail.htm");
		}
		else
		{
			//Added by lavesh
			$cookie_value=$_COOKIE['INVALID_EMAIL'];
                        settype($cookie_value,"integer");
                        if($cookie_value!=2)
			{
				//No need to send sms as email is updated.
				$sql="DELETE FROM newjs.INVALID_EMAIL_MAILER WHERE PROFILEID='$profileid'";
	                        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

				//No. of times email is updated.
				$sql= "UPDATE MIS.EMAILDETAILS set COUNT=COUNT+1";
				if($diff_email)
					$sql.=",EMAIL_UPDATED=EMAIL_UPDATED+1 ";
				$sql.=" where ENTRY_DATE='$today'";
                        	mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	                        /*if(!mysql_affected_rows_js())
        	                {
                        	        $sql= "INSERT INTO MIS.EMAILDETAILS VALUES('','','','1','$today')";
                                	mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                        	}*/
				setcookie("INVALID_EMAIL","2",0,"/",$domain);
			}
			//Ends Here.
			// code added by neha for archiving contact information
                        $date_now=date("Y-m-d H:i:s");
      	                $ip=FetchClientIP();//Gets ipaddress of user
                        if(strstr($ip, ","))
                        {
        	                $ip_new = explode(",",$ip);
                                $ip = $ip_new[1];
                        }
			if($email!=$newemail)
			{
				$sql_search="SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='EMAIL'";
	                        $res_search=mysql_query_decide($sql_search) or die(mysql_error_js());
				if(mysql_num_rows($res_search)>0)
	                        {
	                                $row_search=mysql_fetch_assoc($res_search);
	                                $changeid=$row_search['CHANGEID'];
	        	                $sql_add= "INSERT INTO CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$email','$newemail') ";
	                                $res_add= mysql_query_decide($sql_add) or die(mysql_error_js());
	                        }
	                        else
	                        {
	                        	$sql_insert= "INSERT INTO CONTACT_ARCHIVE(PROFILEID,FIELD) VALUES($profileid,'EMAIL')";
	                                $res_insert= mysql_query_decide($sql_insert) or die(mysql_error_js());
	                                $sql_search="SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='EMAIL'";
	                                $res_search=mysql_query_decide($sql_search) or die(mysql_error_js());
	                                $row_search=mysql_fetch_assoc($res_search);
       		                        $changeid=$row_search['CHANGEID'];
	                                $sql_add= "INSERT INTO CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$email','$newemail') ";
	                                $res_add= mysql_query_decide($sql_add) or die(mysql_error_js());
	                         }
			}
			//end of code added by neha.
			$objUpdate = JProfileUpdateLib::getInstance();
			$arrParams = array("EMAIL"=>$newemail);
			$sql = "UPDATE newjs.JPROFILE SET EMAIL = '$newemail'";  // updating emailId

			if ($verifyemail == 'Y') {    // in case verification of email was required.
				$sql .= " , VERIFY_EMAIL ='N'";
				$arrParams["VERIFY_EMAIL "] = 'N';
			}
//			$sql.=" WHERE PROFILEID = '$profileid'";
//			$res = mysql_query_decide($sql) or logError("Error in updating email",$sql);
			$result = $objUpdate->editJPROFILE($arrParams,$profileid,'PROFILEID');
			if (false === $result) {
				logError("Error in updating email",$sql." WHERE PROFILEID = '$profileid'");
			}

			$smarty->assign("emailmod","1");
			$smarty->assign("newemail",$newemail);
			//$smarty->display("verify_email_Id.htm");

			$link="<a href=\"mainmenu.php\">Proceed to My Jeevansathi</a>";
			$msg = "Your email has been successfully changed to <b>$newemail</b>";
			$smarty->assign("msg",$msg);
			$smarty->assign("url","1");
			$smarty->assign("link",$link);
			$smarty->display("confirmation.htm");
			
		}
	}
	else
        {	
		// if user wishes to retain current email ID.

		if ($retain_email == 1)
		{
			//Added by lavesh.
			$sql="UPDATE newjs.INVALID_EMAIL_MAILER set MOD_DT='0000-00-00' WHERE PROFILEID ='$profileid'";
		        $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			//Ends Here.

			switch($verify_email)
			{
				// in case verification of email is desired and emails sent to emailID have bounced back too

				case 1 :
					$sql = "DELETE FROM bounces.BOUNCED_MAILS WHERE EMAIL = '$email'";
					$res = mysql_query_decide($sql) or logError("Error in updating email",$sql);

//					$sql = "UPDATE newjs.JPROFILE SET VERIFY_EMAIL = 'N' WHERE PROFILEID = '$profileid'";
//               		$res = mysql_query_decide($sql) or logError("Error in updating email",$sql);
					$objUpdate = JProfileUpdateLib::getInstance();
					$result = $objUpdate->editJPROFILE(array('EMAIL'=>$email),$profileid,'PROFILEID');
					if(false === $result) {
						logError("Error in updating email");
					}
					break;

				// in case emails sent to emailID have bounced back

				case 2:

					$sql = "DELETE FROM bounces.BOUNCED_MAILS WHERE EMAIL = '$email'";
                                       $res = mysql_query_decide($sql) or logError("Error in deleting email",$sql);
				       break;

				// in case verification of email is desired.

				case 3:
//					$sql = "UPDATE newjs.JPROFILE SET VERIFY_EMAIL = 'N' WHERE PROFILEID = '$profileid'";
//                                       $res = mysql_query_decide($sql) or logError("Error in updating email",$sql);
					$objUpdate = JProfileUpdateLib::getInstance();
					$result = $objUpdate->editJPROFILE(array('VERIFY_EMAIL'=>'N'),$profileid,'PROFILEID');
					if(false === $result) {
						logError("Error in updating verify_email");
					}
					break;
			}
			echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/mainmenu.php?checksum=$checksum&profilechecksum=$profilechecksum&username=$username&email=$email\"></body></html>";
		}
		else
		{
			$sql="SELECT EMAIL,PHONE_MOB,COUNTRY_RES FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        $row=mysql_fetch_array($res);
			$email = $row['EMAIL'];
			$mobile=$row["PHONE_MOB"];
	                $country=$row["COUNTRY_RES"];

			if(strstr($email,"@jsxyz.com"))
			{
				//$sql1="SELECT EMAIL FROM newjs.DORMANT_PROFILES WHERE PROFILEID='$profileid'";
				//$res1=mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
				//$row1=mysql_fetch_array($res1);
				//$email=$row1['EMAIL'];
			}

			//added by lavesh
			//Sms can be send only to Indian User
			if(!$_COOKIE['INVALID_EMAIL'])
			{
				if(trim($mobile) && $country==51)
				{
					$sql="INSERT IGNORE INTO newjs.INVALID_EMAIL_MAILER VALUES ('$profileid',now(),1)";
					$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				}

	                        $sql= "UPDATE MIS.EMAILDETAILS set PAGE_DISPLAY=PAGE_DISPLAY+1 where ENTRY_DATE='$today'";//no. of times page is displayed.
        	                mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                	        if(!mysql_affected_rows_js())
                        	{
                                	$sql= "INSERT INTO MIS.EMAILDETAILS VALUES('','1','','','$today')";
	                                mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
        	                }
	                        setcookie("INVALID_EMAIL","1",0,"/",$domain);
			}
			//Ends Here

			$smarty->assign("verify_email",$verify_email);
			$smarty->assign("USERNAME",$data["USERNAME"]);
			$smarty->assign("EMAIL",$email);
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("bouncemail","1");
			//$smarty->display("verify_email_Id.htm");
			$smarty->display("verifyemail.htm");
		}
	}
}
//Created By lavesh
function my_checkoldemail($email,$profileid)     // returns 2 if email id not valid
{
	$flag=0;
	if( trim($email) !="")
	{
		$sql="SELECT COUNT(*) as cnt FROM newjs.OLDEMAIL where OLD_EMAIL='$email'";
		$result = mysql_query_decide($sql) or logError("error",$sql);
		$myrow = mysql_fetch_array($result);
		if($myrow['cnt']>0)
		{
			$sql="SELECT COUNT(*) as CNT FROM OLDEMAIL where OLD_EMAIL='$email' AND PROFILEID='$profileid'" ;
			$result = mysql_query_decide($sql) or logError("error",$sql);
			$myrow = mysql_fetch_array($result);
			if($myrow['CNT']==0)
				$flag=2;
		}
	}
	return $flag;
}

?>
