<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it

include("profileselect.php");
include("sphinx_search_function.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
//include(JsConstants::$docRoot."/commonFiles/flag.php");

$db=connect_db();
$data = authenticated($checksum);
if(!$data)
{
	$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
	if($isMobile)
		$smarty->display("mobilejs/jsmb_login.html");
	else
	{
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/include_file_for_login_layer.php");
	$smarty->display("login_layer.htm");
	}
	die;
}
else {
    $smarty->assign("LOGGEDIN", 1);
}


$profileObj = Profile::getInstance("",$data["PROFILEID"]);
$profileObj->getDetail("","","USERNAME,EMAIL");

        if($name)
                $name=ucfirst($name);
        else
                $name=$profileObj->getUSERNAME();
                               

connect_db();
$profileid=JsCommon::getProfileFromChecksum($profilechecksum);
$lang=$_COOKIE["JS_LANG"];
$VIEWPROFILE_IMAGE_URL="http://ser4.jeevansathi.com/profile";
if ($crmback == "admin")
{
	$data = infovision_auth($inf_checksum); // authentication of user in case of infovision
	$smarty->assign("crmback",$crmback);
	$smarty->assign("inf_checksum",$inf_checksum);
	$smarty->assign("cid",$cid);
}
else
	$data=authenticated($checksum);
if($data["BUREAU"]==1 && ($_COOKIE['JSMBLOGIN'] || $mbureau=="bureau"))
{
	$fromprofilepage=1;
	mysql_select_db_js('marriage_bureau');
	include_once('../marriage_bureau/connectmb.inc');
	$mbdata=authenticatedmb($mbchecksum);
	if(!$mbdata)timeoutmb();
	$smarty->assign("source",$mbdata["SOURCE"]);
	$smarty->assign("mbchecksum",$mbdata["CHECKSUM"]);
	mysql_select_db_js('newjs');
	$mbureau="bureau1";
}

/*************************************Portion of Code added for display of Banners*******************************/
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);
//$regionstr=8;
//include("../bmsjs/bms_display.php");
/************************************************End of Portion of Code*****************************************/
$flag=0;
if($_POST['isJson']){
	$dataIn = $_POST['dataArrObj'];
	$finalData= json_decode($dataIn,true);
	$email=$finalData["email"];
	$name=$finalData["name"];
	$femail[0]=$finalData["femail[]"];
	$profilechecksum=$finalData["profilechecksum"];
	$profileid=JsCommon::getProfileFromChecksum($profilechecksum);
	$ajax_error=$finalData["ajax_error"];
	$invitation=$finalData["invitation"];
	$send=$finalData["send"];
	$message=$finalData["message"];
	$username=$finalData["username"];
	$profileid_sender=$data['PROFILEID'];
}
if($send)
{
        $email = $profileObj->getEMAIL();
        if((trim($fname[0])=="" && trim($fname[1])==""  && trim($fname[2])=="") && $ajax_error=="")
	{
		$smarty->assign("cfname",'1');
		$flag=1;
	}
	if((trim($femail[0])=="" || checkemail($femail[0],'N')==1) && (trim($femail[1],'N')=="" || checkemail($femail[1],'N')==1) && (trim($femail[2])=="" || checkemail($femail[2],'N')==1))
	{
		$smarty->assign("cfemail",'1');
		$flag=1;
		$femail_error=1;
	}
	if((trim($name)=="") && $ajax_error=="")
	{
		$smarty->assign("cname",'1');
		$flag=1;
	}
	if(trim($email)=="" || checkemail($email,'N')==1)
	{
		$smarty->assign("cemail",'1');
		$email_error=1;
		$flag=1;
	}
	if($flag==1)
	{
		if($mbureau=="bureau1")
		{
			$smarty->assign("mb_username_profile",$data["USERNAME"]);
			$smarty->assign("checksum",$data["CHECKSUM"]);
		}
		$smarty->assign("PROFILECHECKSUM",$profilechecksum);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("name",$name);
		$smarty->assign("email",$email);
		$smarty->assign("fname",$fname);
		$smarty->assign("femail",$femail);
		if($ajax_error)
		{
			if($femail_error)
				$err_mes="Friend Emailid not provided";
			if($email_error)
				$err_mes="Your Emailid not provided";
			echo "ERROR#$err_mes";
			die;
		}
		$smarty->display("email_to_a_friend.htm");
	}
	else
	{
		$smarty->assign("name",$name);
		$smarty->assign("email",$email);
                if(!$fname[0])
                        $fname[0]=$femail[0];
                else
                        $fname[0]=  ucfirst($fname[0]);
		$smarty->assign("REC_NAME",$fname[0]);
		selectprofile("Y");
		if($mbureau=="bureau1")
		{
			$smarty->assign("mb_username_profile",$data["USERNAME"]);
			$smarty->assign("checksum",$data["CHECKSUM"]);
		}
        $sql="select * from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	// if no profile is found for this profileid, show error message
        if($ajax_error && mysql_num_rows($result) <= 0)
		die("ERROR#Profile doesnot exist");

        if(mysql_num_rows($result) <= 0)
                showProfileError();

        $myrow=mysql_fetch_array($result);
	if($data["GENDER"]==$myrow["GENDER"])                 
		$samegender=1;
		$myrow["PHOTO_DISPLAY"];
		
		$havephoto=$myrow["PHOTO_DISPLAY"];
		if(!$havephoto)
		{
			$havephoto = 'A';
		}
		$image_file=return_image_file($myrow["PHOTO_DISPLAY"],$myrow["GENDER"]);
                if(SymfonyPictureFunctions::haveScreenedMainPhoto($profileid) || $main_photo_is_screened)	//Symfony Photo Modification
                {
			$no_album=1;
			//$photochecksum_new = intval(intval($profileid)/1000) . "/" . md5($profileid+5);
			$photochecksum = md5($profileid+5)."i".($profileid+5);	
			
			if($havephoto=='L' || $havephoto=='C' || $havephoto=='F' || $havephoto=='H' || $havephoto=='P' || $havephoto=='U')
                	{
				$no_album=0;
                        	if($havephoto=='L')
                                	$my_photo="<a href=\"$SITE_URL/profile/viewprofile.php?profilechecksum=$profilechecksum&CALL_ME=$SITE_URL/profile/login.php%3FSHOW_LOGIN_WINDOW%3D1\"><div style=\" display:inline; margin:0 3px 3px 0; \">'><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\"  vspace=\"0\" border=\"0\" ></div></a>";
	                        else
        	                        $my_photo="<a href=\"$SITE_URL/profile/viewprofile.php?profilechecksum=$profilechecksum\"><div style=\" display:inline; margin:0 3px 3px 0; \"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\"  vspace=\"0\" border=\"0\" ></div></a>";

                	}
	                elseif($havephoto=='Y' || $havephoto=='A'){
					//Symfony Photo Modification.
					$profilePicObjs = SymfonyPictureFunctions::getProfilePicObjs($profileid);
					$profilePicObj = $profilePicObjs[$profileid];
					if ($profilePicObj)
						$thumbnailUrl = $profilePicObj->getThumbailUrl();
					else
						$thumbnailUrl = null;
					$my_photo="<a href=\"$SITE_URL/profile/viewprofile.php?profilechecksum=$profilechecksum&CALL_ME=$SITE_URL/profile/layer_photocheck.php%3Fchecksum%3D%26profilechecksum%3D$profilechecksum%26seq%3D1\"><div style=\" cursor:pointer;float:left; margin:0 3px 3px 0; \" align='left'><img src=\"$thumbnailUrl\" width=\"60\" height=\"60\" border=\"0\" galleryimage=\"no\"></div></a>";
					//Symfony Photo Modification.
			}
			elseif($myrow['HAVEPHOTO']=="U"){
                                if($myrow['GENDER']=="M")
                                        $thumbnailUrl="$IMG_URL/profile/images/ph_cmgsoon_sm_b.gif";
                                elseif($myrow['GENDER']=="F")
                                        $thumbnailUrl="$IMG_URL/profile/images/ph_cmgsoon_sm_g.gif";
				$my_photo="<a href=\"$SITE_URL/profile/viewprofile.php?profilechecksum=$profilechecksum\"><div style=\" display:inline; margin:0 3px 3px 0; \"><img src=\"$thumbnailUrl\" width=\"60\" height=\"60\"  vspace=\"0\" border=\"0\" ></div></a>";
                        }
			else
				$my_photo="<a href=\"$SITE_URL/profile/viewprofile.php?profilechecksum=$profilechecksum\"><div style=\" display:inline; margin:0 3px 3px 0; \"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\"  vspace=\"0\" border=\"0\" ></div></a>";
			if($no_album==1)
			{
				//Symfony Photo Modification.
                            	$is_album = SymfonyPictureFunctions::checkMorePhotos($profileid);
			}
			else
				$is_album=0;

			$smarty->assign("is_album",$is_album);
			$smarty->assign("MY_PHOTO", $my_photo);
				
		}
		else
		{
			if(!$image_file)
			{
				if($myrow['HAVEPHOTO']=="U"){
        	                        if($myrow['GENDER']=="M")
                	                        $image_file="ph_cmgsoon_sm_b.gif";
                        	        elseif($myrow['GENDER']=="F")
	                                        $image_file="ph_cmgsoon_sm_g.gif";
				$my_photo="<a href=\"$SITE_URL/profile/viewprofile.php?profilechecksum=$profilechecksum\"><div style=\" display:inline; margin:0 3px 3px 0; \"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\"  vspace=\"0\" border=\"0\" ></div></a>";
        	                }
				else
				{	
					if($myrow['GENDER']=='F')
						$image_file="Request-a-photo-Female_small.gif";
					else
						$image_file="Request-a-photo-male_small.gif";
					$my_photo="<a href=\"$SITE_URL/profile/viewprofile.php?profilechecksum=$profilechecksum&CALL_ME=$SITE_URL/social/photoRequest%3Fshowtemp%3DY%26other_username%3D$myrow[USERNAME]%26checksum%3D%26profilechecksum%3D$profilechecksum\"><div style=\" display:inline; margin:0 3px 3px 0; \"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\"  vspace=\"0\" border=\"0\" ></div></a>";
				}
			}
			
			$smarty->assign("MY_PHOTO", $my_photo);
		}
		$smarty->assign("message",htmlspecialchars(stripslashes($message),ENT_QUOTES));
		$smarty->assign("username",$username);
		$height = FieldMap::getFieldLabel("height",$myrow["HEIGHT"]);
		$occupation = FieldMap::getFieldLabel("occupation",$myrow["OCCUPATION"]);
		$city = FieldMap::getFieldLabel("city_india",$myrow["CITY_RES"]);
		$country = FieldMap::getFieldLabel("country",$myrow["COUNTRY_RES"]);
		$smarty->assign("COUNTRY_RES",$country);
		$smarty->assign("CITY_RES",$city);
		$smarty->assign("OCCUPATION",$occupation);
		$smarty->assign("HEIGHT",$height);
		//$msg=$smarty->fetch("forward2friend.htm");
		$msg=$smarty->fetch("mail_to_friend.htm");
		for($i=0;$i<count($femail);$i++)
		{
			if($femail[$i])
			{       
                $shareProfileLibObj = new checkForSharingProfile;
                $shareProfileStoreObj = new PROFILE_SHARE_PROFILE;
                $sendingMailResponse=$shareProfileLibObj->getsendMailCriteria($profileid,$shareProfileStoreObj);
                if($sendingMailResponse["RESPONSE"]=="YES"){
                               /*include_once "../crm/func_sky.php";
				$Cc="nitesh.s@jeevansathi.com";
				send_mail($femail[$i],$Cc,$Bcc,$msg,"A profile from Jeevansathi.com",$email);*/
				//send_email("dhiman_nikhil@yahoo.com,nikcomestotalk@gmail.com,dhiman_nikhil@yahoo.com",$msg,"A profile from Jeevansathi.com",$email);
				send_email($femail[$i],$msg,$name." has shared a profile with you","info@jeevansathi.com","","","","","","",1,$email);
			}
			else
				die("Mail not sent");
			$shareProfileLibObj->updateAfterEmailSend($profileid,$sendingMailResponse["TIME"],$sendingMailResponse["COUNT"],$sendingMailResponse["RESPONSE"]);
			}
		}
		if($ajax_error)
			die("bye");
		
		$smarty->display("mailsent.htm");
	}
}
else
{
        $email = $profileObj->getEMAIL();
	//If it's ajax request..
	if($ajax_error)
	{
		if($data)
	        {
        	        $sql = "SELECT EMAIL FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$data[PROFILEID]'";
                	$res = mysql_query_decide($sql);
	                $row = mysql_fetch_array($res);
        	        $smarty->assign("email","$row[EMAIL]");
        	}
        //else
        //      TimedOut();
        	$smarty->assign("initial","1");
	        $smarty->assign("profileid","$data[PROFILEID]");
		$smarty->assign("PROFILECHECKSUM",$profilechecksum);
		$smarty->assign("username",$username);
        	$smarty->display("forward_friend.htm");
	}
	else
	{	
		if($mbureau=="bureau1")
		{
			$smarty->assign("mb_username_profile",$data["USERNAME"]);
			$smarty->assign("checksum",$data["CHECKSUM"]);
		}
                $smarty->assign("email","$email");
		$smarty->assign("PROFILECHECKSUM",$profilechecksum);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->display("email_to_a_friend.htm");
	}
}
// flush the buffer
if($zipIt)
	ob_end_flush();
	
?>
