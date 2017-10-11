<?php
/*********************************************************************************************
* FILE NAME   		: 1min.php
* DESCRIPTION 		: script for quick 1 minute registration
* CREATION DATE         : 5 Oct, 2005
* CREATED BY        	: Gaurav Arora
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
                                                                                                 
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        $zipIt = 1;
if($zipIt)
        ob_start("ob_gzhandler");
//end of it
                                                                                                 
include_once("connect.inc");
connect_db();

if($source)
	setcookie("JS_SOURCE",$source,time()+2592000,"/");

//if the page has been submitted
if($Submit)
{
	// if both email and phone number field are not filled then redirct the person to registration page
	if(!($Email && $Phone_Res))
	{
		//header("Location: $SITE_URL/profile/inputprofile.php");	
		//exit;
		header("Location: $SITE_URL/profile/inputprofile.php");	
	}
	$check_email=checkemail($Email,'N');
	$check_email_af=checkemail_af($Email);
	$check_old_email=checkoldemail($Email);
	if($check_email || $check_old_email || $check_email_af)
	{
		$email_error++;
		//echo "a=".$email_error;
		//echo "<br>";
		/*$SHOW_EMAIL='Y';
		if($check_email_af)
			$check_email='Y';
			//$check_email=$check_email_af;
		if($check_old_email==2)
			$check_oldemail='Y';*/
			//$check_email=$check_old_email;
	}

	$check_phone_res=checkrphone($Phone_Res);
	if ($check_phone_res==1)
	{
		//$SHOW_PHONE_MOB='Y';
		$phone_error=1;
		//echo "b=".$phone_error;
		//$smarty->assign("phone_msg","Phone no. has invalid characters");
	}
	//if both email and phone number are filled wrong redirect to inputprofile
	if($email_error && $phone_error)
	{
		//header("Location: $SITE_URL/profile/inputprofile.php");	
		header("Location: $SITE_URL/profile/inputprofile.php");	
		//exit;
	}
	
	//if some error occur in the form
	/*if($is_error>0)
	{
		$smarty->assign("NO_OF_ERROR",$is_error);
		$smarty->assign("INFORMATION",$Information);
		$smarty->assign("AGE",$age);
		//$smarty->assign("",$age);
		$smarty->assign("PHONE_RES",$Phone_Res);
		$smarty->assign("EMAIL",$Email);
		$smarty->assign("GENDER",$Gender);
		$smarty->display("1min.htm");
	}*/
		if($Year && $Month && $Day)
		{
			$array = array($Year, $Month, $Day);
	                $date_of_birth= implode("-", $array);
        	        if($date_of_birth)
                	{
                        	$date_of_birth= implode("-", $array);
	                        $age=getAge($date_of_birth);
        	        }
		}
                //else
                        //$age=;

		$ip=FetchClientIP();//Gets ipaddress of user
		/*// section to generate auto username
		$sql_auto_user="SELECT MAX(PROFILEID) AS ID FROM newjs.JPROFILE";
		$res_auto_user=mysql_query_decide($sql_auto_user) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_auto_user,"ShowErrTemplate");
		$row_auto_user=mysql_fetch_array($res_auto_user);
		$pid_auto_user=$row_auto_user['ID']+1;
		$pid_auto_user=substr($pid_auto_user,-4);*/
		//$Username_auto=username_gen($pid_auto_user);
		// end of section to generate auto username
		while(1)
		{
			$Username_auto=username_gen();
														    
			$sql="SELECT COUNT(*) as cnt FROM JPROFILE WHERE USERNAME='$Username_auto'";
			$res_username=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row_username=mysql_fetch_array($res_username);
														    
			$sql="SELECT COUNT(*) as cnt FROM JPROFILE_AFFILIATE WHERE USERNAME='$Username_auto'";
			$res_username2=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row_username2=mysql_fetch_array($res_username2);
														    
			if($row_username['cnt']==0 && $row_username2['cnt']==0)
				break;
		}

		if(!$email_error && !$phone_error)
		{
			$sql = "INSERT INTO JPROFILE_AFFILIATE(USERNAME,EMAIL,GENDER,PHONE_RES,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,INCOMPLETE,DTOFBIRTH,AGE,IPADD,SOURCE,ACTIVATED,YOURINFO,MOVED,EMAIL_VALIDATE,BACKEND) VALUES ('$Username_auto','$Email','$Gender','$Phone_Res',now(),now(),'$today',now(),'Y','$date_of_birth','$age','$ip','$source','N','".addslashes(stripslashes($Information))."','N','Y','T')";
		}
		elseif(!$email_error && $phone_error)
		{
			$sql = "INSERT INTO JPROFILE_AFFILIATE(USERNAME,EMAIL,GENDER,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,INCOMPLETE,DTOFBIRTH,AGE,IPADD,SOURCE,ACTIVATED,YOURINFO,MOVED,EMAIL_VALIDATE,BACKEND) VALUES ('$Username_auto','$Email','$Gender',now(),now(),'$today',now(),'Y','$date_of_birth','$age','$ip','$source','N','".addslashes(stripslashes($Information))."','N','Y','T')";
		}
		elseif($email_error && !$phone_error)	
		{
			$sql = "INSERT INTO JPROFILE_AFFILIATE(USERNAME,GENDER,PHONE_RES,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,INCOMPLETE,DTOFBIRTH,AGE,IPADD,SOURCE,ACTIVATED,YOURINFO,MOVED,EMAIL_VALIDATE,BACKEND) VALUES ('$Username_auto','$Gender','$Phone_Res',now(),now(),'$today',now(),'Y','$date_of_birth','$age','$ip','$source','N','".addslashes(stripslashes($Information))."','N','Y','T')";
		}
		if(($email_error && !$phone_error) || (!$email_error && $phone_error) || (!$email_error && !$phone_error))
		{	
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$id=mysql_insert_id_js();
			$checksum=md5($id)."i".$id;
			
			//section to save incomplete profiles in INCOMPLETE_PROFILES table
			$sql="INSERT INTO INCOMPLETE_PROFILES(PROFILEID,REG_DATE) VALUES ('$id',now())";		
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			//end of section to save incomplete profiles in INCOMPLETE_PROFILE table
			// section to save values in JPARTNER_PAGE3 TABLE
			//to save HAGE, LAGE as AGE+7, AGE-7
        	        if($Gender=='M')
                	{
                        	if($age<25)
                                	$lage=18;
                        	else
                                	$lage=$age-7;
                        	$hage=$age;
                	}
                	else
                	{
                        	$hage=$age+7;
                        	if($age<21)
                                	$lage=21;
                        	else
                                	$lage=$age;
                	}
			if($Gender=='M')
				$sql_jpartner="INSERT into JPARTNER_PAGE3(PROFILEID,GENDER,LAGE,HAGE,DATE) values ('$id','F','$lage','$hage',now())";
			else
				$sql_jpartner="INSERT into JPARTNER_PAGE3(PROFILEID,GENDER,LAGE,HAGE,DATE) values ('$id','M','$lage','$hage',now())";
												 
			mysql_query_decide($sql_jpartner) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_jpartner,"ShowErrTemplate");

                // end of section to save values in JPARTNER_PAGE3 table
			//take the person to the validation module
			header("Location: $SITE_URL/profile/validate_function.php?checksum=$checksum&source=$source");
		}
		//header("Location: $SITE_URL/profile/validate_input.php?checksum=$checksum&source=$source");
}
else
{
	$smarty->assign("SOURCE",$source);
	$smarty->display("landingpage.htm");
}

?>
