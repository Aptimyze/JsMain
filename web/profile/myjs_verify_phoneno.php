<?php
	include(JsConstants::$docRoot."/profile/connect.inc");
	include_once(JsConstants::$docRoot."/ivr/jsivrFunctions.php");
	include_once(JsConstants::$docRoot."/ivr/knowlarityFunctions.php");
	include_once(JsConstants::$docRoot."/profile/phoneFunctions.php");
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	include_once(JsConstants::$docRoot."/commonFiles/flag.php");
	$inboundApiForDncStatus = false;
	$outboundApiStatus = true;
	$smsVerificationApiStatus = true;
	$opsVerificationStatus = true;
	$db=connect_db();
	$data=authenticated();
        if(!$data)
        {
		if($_POST['ajax']==1)	die("#LOGIN");
		if($fromNewSearch)	header("Location:/static/newLoginLayer?searchId=$searchId&currentPage=1&width=413&ajax_error=1");
		else
		{
			include_once(JsConstants::$docRoot."/profile/include_file_for_login_layer.php");
			$smarty->display("login_layer.htm");
		}
                die;
        }
	/********   Code when Ajax request served -Starts  ************/
	if($_POST['changeSetting']==1)
	{
		$profileid	=$data["PROFILEID"];
		$settingType=$_POST['settingType'];
		$flag=$_POST['flag'];
		if(strstr($settingType,"hideNumber"))
				hideNumbers($profileid,$flag);
		elseif(strstr($settingType,"promo_mails"))
				offerCallSettingsChange($profileid,$flag);
		die;
	}
	elseif($_POST['ajax']==1)
	{
		include_once(JsConstants::$docRoot."/ivr/jsPhoneVerify.php");
		$profileid	=$data["PROFILEID"];
		$phoneType 	=$_POST['phoneType'];
		$vcode	   	=trim($_POST['vcode']);
		$phone		=$_POST['number'];
		$saveNumber	=$_POST['saveNumber'];
		$sourcePage	=$_POST['sourcePage'];
		$isd		=removeAllSpecialChars($_POST['isd']);
		$isdFlag	=$_POST['isdFlag'];
		if($sourcePage)
		{
			$trackOnly=$_POST['trackOnly'];
			updateLastClickOn($profileid,$sourcePage);
			if($trackOnly==1)
				die;
		}
		if($phoneType=='L')
		{
			$numberArray=explode("-",$phone);
			$std=$numberArray[0];
			$std=ltrim($std,'0');
			$phone=ltrim($numberArray[1],'0');
			$landl=$std."-".$phone;
			$NUMBER_VALID=checkLandlineNumber($phone,$std,'','',$isd);
			$checkDuplicate=chkDuplicatePhone($landl,$phoneType,$profileid);
		}
		else
		{
			$NUMBER_VALID=checkMobileNumber($phone,'','',$isd);
			$checkDuplicate=chkDuplicatePhone($phone,$phoneType,$profileid);
		}
		if($NUMBER_VALID != 'Y')
		{
				echo $phoneType."|INVALID";
				die;
		}

		if(strstr($checkDuplicate,'D')&&($saveNumber==1)&&(!$vcode))
			$vcode =getVerificationCode($profileid);
                if($inboundApiForDncStatus===false && $outboundApiStatus===true)
		{
			if($landl)
				$numberPhone = $landl;
			else
				$numberPhone = $phone;
			$vNo=getVirtualNumber($profileid,$numberPhone,$isd);
			$dncFlag="Y";
		}
		if($inboundApiForDncStatus || ($outboundApiStatus && $dncFlag!='Y'))
			$ivrStatus=ivrPhoneVerification($profileid,$phone,$std,'pull',$vcode,$isd);
		if($saveNumber==1)
			savePhone($profileid,$phoneType,$phone,$std,$isd,$screenflag,$isdFlag);
		if (JsCommon::showConsentMessage($profileid)) $showConsentMsg='Y'; else $showConsentMsg='N'; 
				echo $phoneType."|".$phone."|".$checkDuplicate."|".$vcode."|".$dncFlag."|".$vNo."|".$showConsentMsg;

		JsMemcache::getInstance()->set('showConsentMsg_'.$profileid,$showConsentMsg);
		die;
	}
	/********   Code when Ajax request served -Ends  ************/
	else
	{
		/************************   Default layer status Starts   **************************/
		if($sourcePage=='')
			$sourcePage="LOGIN";
		$profileid=$data["PROFILEID"];
		phoneLayerTracking($profileid,$sourcePage);
		$profileDetails = getProfilePhoneDetails($profileid);
		if($inboundApiForDncStatus===false && $outboundApiStatus===true)
		{
			$mobDncStatus	="Y";
			$landlDncStatus	="Y";
			$altDncStatus	="Y";
			$vNoM=getVirtualNumber($profileid,$profileDetails['MYMOBILE'],$profileDetails['ISD']);
			if(!$profileDetails['MYLANDLINESTD'])
				$profileDetails['MYLANDLINESTD']=$profileDetails['STD'].$profileDetails['MYLANDLINE'];
			$vNoL=getVirtualNumber($profileid,$profileDetails['MYLANDLINESTD'],$profileDetails['ISD']);
			$vNoA=getVirtualNumber($profileid,$profileDetails['ALT_MOBILE'],$profileDetails['ISD']);
		}
		$checkDuplicate=checkIfDuplicate($profileid);
		$vNo = ($vNoM ? $vNoM :($vNoL? $vNoL : $vNoA));
		$tickHideNumbers = ($profileDetails['SHOW_MOBILE']=='C' && $profileDetails['SHOW_LANDLINE']=='C' && $profileDetails['SHOW_ALTERNATE']=='C')?'Y':'N';
		$checkVerified=($profileDetails['MOB_STATUS']=='Y'|| $profileDetails['LANDL_STATUS']=='Y'|| $profileDetails['ALT_STATUS']=='Y')?'Y':'N';
		$UNIQUE = ($checkDuplicate=='N')?'Y':'N';
		$verificationCode = ($checkDuplicate=='Y')? getVerificationCode($profileid):checkVerificationCodeExists($profileid);
		$sql_offer_call="SELECT OFFER_CALLS FROM newjs.JPROFILE_ALERTS WHERE PROFILEID='".$profileid."'";
		$res_offer_call=mysql_query_decide($sql_offer_call);
		$row_offer_call=mysql_fetch_array($res_offer_call);
		$OFFER_CALLS=$row_offer_call['OFFER_CALLS'];
		$smarty->assign("inboundApiForDncStatus",$inboundApiForDncStatus);
		$smarty->assign("outboundApiStatus",$outboundApiStatus);
		$smarty->assign("smsVerificationApiStatus",$smsVerificationApiStatus);
		$smarty->assign("opsVerificationStatus",$opsVerificationStatus);
		$smarty->assign("VIRTUAL_NUMBER",$vNo);
		$smarty->assign("any_verified",$checkVerified);
		$smarty->assign("OFFER_CALLS",$OFFER_CALLS);
		$smarty->assign("tickHideNumbers",$tickHideNumbers);
		$smarty->assign("UNIQUE",$UNIQUE);
		$smarty->assign("SOURCEPAGE",$sourcePage);
		$smarty->assign("MDNC",$mobDncStatus);
		$smarty->assign("LDNC",$landlDncStatus);
		$smarty->assign("ADNC",$altDncStatus);
		$smarty->assign("vcode",$verificationCode);
		$smarty->assign("MOB_STATUS",$profileDetails['MOB_STATUS']);
		$smarty->assign("LANDL_STATUS",$profileDetails['LANDL_STATUS']);
		$smarty->assign("ALT_STATUS",$profileDetails['ALT_STATUS']);
		$smarty->assign("MYMOBILE",$profileDetails['MYMOBILE']);
		$smarty->assign("MYLANDLINE",$profileDetails['MYLANDLINE']);
		$smarty->assign("ALT_MOBILE",$profileDetails['ALT_MOBILE']);
		$smarty->assign("MOBILE_VALID",$profileDetails['MOBILE_VALID']);
		$smarty->assign("LANDLINE_VALID",$profileDetails['LANDLINE_VALID']);
		$smarty->assign("ALTERNATE_VALID",$profileDetails['ALTERNATE_VALID']);
		$smarty->assign("STD",$profileDetails['STD']);
		$smarty->assign("ISD",$profileDetails['ISD']);
		$smarty->display("myjs_verify_phoneno.htm");
		/*********************   Default layer status Ends  ***********************/
	}
?>
