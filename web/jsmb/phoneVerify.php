<?php
	$phoneVerificationFromMobile=true;
	global $smarty;
	include_once(JsConstants::$docRoot."/profile/connect.inc");
	include_once(JsConstants::$docRoot."/profile/common_functions.inc");
	include_once(JsConstants::$docRoot."/profile/mobile_detect.php");
	include_once(JsConstants::$docRoot."/profile/phoneFunctions.php");
	include_once(JsConstants::$docRoot."/ivr/knowlarityFunctions.php");
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	connect_db();
	if(!$data['PROFILEID'])
		$data = authenticated();
	$profileid = $data['PROFILEID'];
	$value = memcache_call($profileid."_PHONE_VERIFIED");
	if($value=="Y")
	{
		if($data['HAVEPHOTO']=='N' || strlen($data['HAVEPHOTO'])==0)
		{
			header("Location:$SITE_URL/profile/viewprofile.php?ownview=1");
		}
		else
		{
			header("Location:$SITE_URL/search/partnermatches");
		}
		die;
	}

	assignHamburgerSmartyVariables($profileid);
	if($_POST['mobilePost'])
	{
		$type=$_POST['type'];
		if($type=="mob")
		{
			$phoneType="M";
			$phone = $_POST['mobNew'];
		}
		elseif($type=="alt")
		{
			$phoneType="A";
			$phone = $_POST['altNew'];
		}
		if($type=="mob"||$type=="alt")
		{
                        $NUMBER_VALID=checkMobileNumber($phone,'','',$isd);
                        $checkDuplicate=chkDuplicatePhone($phone,$phoneType,$profileid);
		}
		if($type=="landl")
		{
			$phoneType="L";
			$std = $_POST['stdNew'];
			$phone = ltrim($_POST['landlNew'],0);
			$landl = $std."-".$phone;
                        $NUMBER_VALID=checkLandlineNumber($phone,$std,'','',$isd);
		}
		if($NUMBER_VALID == 'Y')
			savePhone($profileid,$phoneType,$phone,$std,$isd,$screenflag);
		else
		{
			if($phoneType=="M")
			{
				$setData['MOBILE_VALID']='N';
				$setData['MOBILE']=$phone;
			}
			if($phoneType=="L")
			{
				$setData['LANDLINE_VALID']='N';
				$setData['LANDLINE']=$_POST['landlNew'];
				$setData['STD']=$_POST['stdNew'];
			}
			if($phoneType=="A")
			{
				$setData['ALTERNATE_VALID']='N';
				$setData['ALTERNATE']=$phone;
			}
		}
	}

		$profileDetails = getProfilePhoneDetails($profileid);
		$count =0;
		if($setData['MOBILE_VALID'])
			$profileDetails['MOBILE_VALID'] = $setData['MOBILE_VALID'];
		if($setData['LANDLINE_VALID'])
			$profileDetails['LANDLINE_VALID'] = $setData['LANDLINE_VALID'];
		if($setData['MOBILE_VALID'])
			$profileDetails['ALTERNATE_VALID'] = $setData['ALTERNATE_VALID'];
		if($setData['LANDLINE']||$setData['STD'])
		{
			$profileDetails['LANDLINE']=$setData['LANDLINE'];
			$profileDetails['STD']=$setData['STD'];
		}
		if($setData['MOBILE'])
			$profileDetails['MOBILE'] = $setData['MOBILE'];
		if($setData['ALTERNATE'])
			$profileDetails['ALTERNATE'] = $setData['ALTERNATE'];
		if($profileDetails['MOBILE_VALID']=="Y")
			$count++;
		if($profileDetails['LANDLINE_VALID']=="Y")
			$count++;
		if($profileDetails['ALTERNATE_VALID']=="Y")
			$count++;
		if($count>1)
			$multipleNumbers ="Y";//true;
		else
			$multipleNumbers ="N";//false;
		$virtualNumber =getAllProfileVirtualNumbers($profileid);
		//Pixel code
		if($profileid && $_GET['groupname'])
		{
			$sql="select CITY_RES,USERNAME,AGE,GENDER,PROFILEID from newjs.JPROFILE where PROFILEID=$profileid";
			$res=mysql_query_decide($sql);
			if($row=mysql_fetch_assoc($res))
			{
				$city_pixel=$row[CITY_RES];
				$username_pixel=$row[USERNAME];
				$age_pixel=$row[AGE];
				$gender_pixel=$row[GENDER];
				$groupname=$_GET['groupname'];
				$adnetwork1=$_GET['adnetwork1'];
				
				$smarty->assign("pixelcode",pixelcode_reg($groupname,$city_pixel,$age_pixel,$gender_pixel,$profileid,$username_pixel,$adnetwork1));
			}
		}
		$showMissed = ($profileDetails['ISD']=="91")?"Y":"N";
		$smarty->assign("multipleNumbers",$multipleNumbers);
		$smarty->assign("VIRTUAL_NUMBER",$virtualNumber);
		$smarty->assign("showMissed",$showMissed);
		$smarty->assign("MOBILE",$profileDetails['MYMOBILE']);
		$smarty->assign("LANDLINE",$profileDetails['MYLANDLINE']);
		$smarty->assign("ALTERNATE",$profileDetails['ALT_MOBILE']);
		$smarty->assign("STD",$profileDetails['STD']);
		$smarty->assign("ISD",$profileDetails['ISD']);
		$smarty->assign("MOBILE_VALID",$profileDetails['MOBILE_VALID']);
		$smarty->assign("LANDLINE_VALID",$profileDetails['LANDLINE_VALID']);
		$smarty->assign("ALTERNATE_VALID",$profileDetails['ALTERNATE_VALID']);
		$smarty->assign("CUSTOMER_CARE","18004192699");
		$smarty->display("mobilejs/phoneVerify.html");
		die;
?>
