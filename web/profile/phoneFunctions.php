<?php
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
function getOpsPhoneNumber()
{
	return "+91-9560885794";
}

function hideNumbers($profileid,$flag)
{
	if(!in_array($flag,array("N","C","Y")))
		return;
		$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
		$profileid=$profileid;
		$arrFields = array('SHOWPHONE_RES'=>$flag,'SHOWPHONE_MOB'=>$flag);
		$exrtaWhereCond = "";
		$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
      // $sql="UPDATE newjs.JPROFILE SET `SHOWPHONE_RES` = '".$flag."',`SHOWPHONE_MOB` = '".$flag."' WHERE `PROFILEID` = '".$profileid."'";
 //       $res=mysql_query_decide($sql);
        deleteCachedJprofile_ContactDetails($profileid);
        $arrParams = array('SHOWALT_MOBILE'=>$flag);
		$jprofileUpdateObj->updateJPROFILE_CONTACT($profileid, $arrParams);
        //$sqlAlt="UPDATE newjs.JPROFILE_CONTACT SET `SHOWALT_MOBILE` = '".$flag."' WHERE `PROFILEID` = '".$profileid."'";
        //$resAlt=mysql_query_decide($sqlAlt);
}
function entryInDuplcationCheckOnEdit($profileid,$phoneType)
{
        include_once($_SERVER['DOCUMENT_ROOT']."/profile/functions_edit_profile.php");
        $symfonyFilePath=realpath($_SERVER['DOCUMENT_ROOT']."/../");
        include_once($symfonyFilePath."/lib/model/lib/FieldMapLib.class.php");
        include_once($symfonyFilePath."/lib/model/lib/Flag.class.php");
        $dup_flag_value=get_from_duplication_check_fields($profileid);
        if($dup_flag_value){
                if($dup_flag_value[TYPE]=='NEW')
                        $to_not_update_dup=true;
                else
                        $dup_flag_value=$dup_flag_value[FIELDS_TO_BE_CHECKED];
        }
        if(!$to_not_update_dup){
                $dup_flag_value=Flag::setFlag($phoneType,$dup_flag_value,'duplicationFieldsVal');
                insert_in_duplication_check_fields($profileid,'edit',$dup_flag_value);
        }
}
function contact_archive($profileid,$field="",$val="")
{
        if($field=="PHONE_MOB" || $field=="PHONE_RES")
        {
                $sql_sel= "SELECT STD,ISD,PHONE_RES,PHONE_MOB FROM newjs.JPROFILE WHERE activatedKey=1 and PROFILEID='".$profileid."'";
        }
        elseif($field=="PHONE_ALT")
        {
                $sql_sel= "SELECT PROFILEID, ALT_MOBILE, ALT_MOBILE_ISD FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='".$profileid."'";
        }
        $res_sel= mysql_query_decide($sql_sel) or die(mysql_error_js());
        $row_sel= mysql_fetch_assoc($res_sel);
                $date_now=date("Y-m-d H:i:s");
                $ip=FetchClientIP();
                if(strstr($ip, ",")){
                        $ip_new = explode(",",$ip);
                        $ip = $ip_new[1];
                }

                if($field=="PHONE_RES")
                {
                        $ph_row=$row_sel['ISD']."-".$row_sel['STD']."-".$row_sel['PHONE_RES'];
                        if($ph_row!=$val)
                        {
                                $ph_arr=explode("-",$val);
                                if($ph_arr[2]=='')
                                        $val='';
                        }
                        $old_val="";
                        if($row_sel['PHONE_RES'])
                                $old_val =$ph_row;
                }
                elseif($field=="PHONE_MOB")
                {
                        $mob_row=$row_sel['ISD']."-".$row_sel['PHONE_MOB'];
                        if($mob_row!=$val)
                        {
                                $mob_arr=explode("-",$val);
                                if($mob_arr[1]=='')
                                        $val='';
                        }
                        $old_val ="";
                        if($row_sel['PHONE_MOB'])
                                $old_val =$mob_row;
                }
                elseif($field=="PHONE_ALT")
                {
                        $alt_row=$row_sel['ALT_MOBILE_ISD']."-".$row_sel['ALT_MOBILE'];
                        if($alt_row!=$val)///ALT_MOBILE, ALT_MOBILE_ISD
                        {
                                $alt_arr=explode("-",$val);
                                if($alt_arr[1]=='')
                                        $val='';
                        }
                        $old_val ="";
                        if($row_sel['ALT_MOBILE'])
                                $old_val =$alt_row;

                }
                $sql_search="SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='$field'";
                $res_search=mysql_query_decide($sql_search) or die(mysql_error_js());
                if(mysql_num_rows($res_search)>0)
                {
                        $old_val=addslashes(stripslashes($old_val));
                        $val=addslashes(stripslashes($val));
                        $row_search=mysql_fetch_assoc($res_search);
                        $changeid=$row_search['CHANGEID'];
                        $sql_add= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$old_val','$val') ";
                        $res_add= mysql_query_decide($sql_add) or die(mysql_error_js());
               }
               else{
                        $sql_insert= "INSERT INTO newjs.CONTACT_ARCHIVE(PROFILEID,FIELD) VALUES($profileid,'$field')";
                        $res_insert= mysql_query_decide($sql_insert) or die(mysql_error_js());
                        $sql_search="SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='$field'";
                        $res_search=mysql_query_decide($sql_search) or die(mysql_error_js());
                        $row_search=mysql_fetch_assoc($res_search);
                        $changeid=$row_search['CHANGEID'];
                        $sql_add= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$old_val','$val') ";
                        $res_add= mysql_query_decide($sql_add) or die(mysql_error_js());
               }
}
function offerCallSettingsChange($profileid,$flag)
{
	$objUpdate = JProfileUpdateLib::getInstance();
	$objUpdate->updateJPROFILE_ALERTS($profileid,'OFFER_CALLS',$flag);

	/*$sql="UPDATE newjs.JPROFILE_ALERTS SET OFFER_CALLS='".$flag."' WHERE `PROFILEID` = '".$profileid."'";
	$res=mysql_query_decide($sql);*/
}
function checkIfDuplicate($profileid)
{
	$symfonyPhoneFunctions=new SymfonyPhoneFunctions;
	$profiles=$symfonyPhoneFunctions->getProbableDuplicateOfProfileByReason($profileid,"PHONE");
	if($profiles)//marked as probable duplicate with somone
		return 'Y';
	else
	{
		$profiles=$symfonyPhoneFunctions->getProfileDuplicates($profileid);	//this function return all the profiles duplicate to this proifle and the profile itself, in count($profiles) 1 is there for the profile itself in case if it is duplciate
		if(count($profiles)>1)//sure duplicate profile
			return 'Y';
		else
			return 'N';
	}
}
function savePhone($profileid,$phoneType,$phoneNo,$std='',$isd='',$screenflag='',$isdFlag="SAME")
{
        include_once(JsConstants::$docRoot."/commonFiles/flag.php");
	if($phoneType=='M'){
		$phoneTypeFlag="mobile";
		entryInDuplcationCheckOnEdit($profileid,'phone_mob');
		$val=$isd."-".$phoneNo;
		contact_archive($profileid,'PHONE_MOB',$val);
		$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
		$profileid=$profileid;
		$arrFields = array('PHONE_MOB'=>$phoneNo,'PHONE_FLAG'=>'','MOB_STATUS'=>'N','MOD_DT'=>'now()');
		
		//$sql ="update newjs.JPROFILE SET `PHONE_MOB`='".$phoneNo."',`PHONE_FLAG`='',`MOB_STATUS`='N',`MOD_DT`=now() ";
		if($isd!=''){
			//$sql.=",`ISD`='".$isd."'";
			$arrFields['ISD']=$isd;
		}
		if($isdFlag=="DIFF"&& $isd!=''){
			//$sql.=" ,`LANDL_STATUS`='N' ";
			$arrFields['LANDL_STATUS']='N';
		}
		//$sql.=" where `PROFILEID`='".$profileid."'";
		$exrtaWhereCond = "";
		$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
		
		//mysql_query_decide($sql);
		removeFlag("PHONEMOB",$screenflag);
		if($isdFlag=="DIFF" &&$isd!='')
		{
      deleteCachedJprofile_ContactDetails($profileid);
       $arrParams = array('ALT_MOBILE_ISD'=>$isd,'ALT_MOB_STATUS'=>'N');
		$jprofileUpdateObj->updateJPROFILE_CONTACT($profileid, $arrParams);
	            //    $sql ="update newjs.JPROFILE_CONTACT SET `ALT_MOBILE_ISD`='".$isd."',`ALT_MOB_STATUS`='N' where `PROFILEID`='".$profileid."'";
			//$res=mysql_query_decide($sql);
		}
	}
	elseif($phoneType=='L'){
		$phoneTypeFlag="landline";
		entryInDuplcationCheckOnEdit($profileid,'phone_res');
		if($phoneNo!='')
			$phone_std =$std.$phoneNo;	
		if($phoneNo=="")
			$phone_std="";
		$val=$isd."-".$std."-".$phoneNo;
		contact_archive($profileid,'PHONE_RES',$val);
		
		$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
		$profileid=$profileid;
		$arrFields = array('PHONE_RES'=>$phoneNo,'STD'=>$std,'PHONE_FLAG'=>'','LANDL_STATUS'=>'N','PHONE_WITH_STD'=>$phone_std,'MOD_DT'=>'now()');
		
		
		//$sql ="update newjs.JPROFILE SET `PHONE_RES`='".$phoneNo."',`STD`='".$std."',`PHONE_FLAG`='',`LANDL_STATUS`='N',PHONE_WITH_STD='".$phone_std."',`MOD_DT`=now() ";
		if($isd!=''){
			//$sql.=", `ISD`='".$isd."'";
			$arrFields['ISD']=$isd;
		}
		if($isdFlag=="DIFF" && $isd!=''){
			//$sql.=" ,`MOB_STATUS`='N' ";
			$arrFields['MOB_STATUS']='N';
		}
		//$sql.=" where `PROFILEID`='".$profileid."'";
		$exrtaWhereCond = "";
		$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
		//mysql_query_decide($sql);
		removeFlag("PHONERES",$screenflag);
		if($isdFlag=="DIFF" && $isd!='')
		{
      deleteCachedJprofile_ContactDetails($profileid);
      
		 $arrParams = array('ALT_MOBILE_ISD'=>$isd);
		$jprofileUpdateObj->updateJPROFILE_CONTACT($profileid, $arrParams);
			$sql ="update newjs.JPROFILE_CONTACT SET `ALT_MOBILE_ISD`='".$isd."' where `PROFILEID`='".$profileid."'";
			$res=mysql_query_decide($sql);
		}
	}
	elseif($phoneType=='A'){
    deleteCachedJprofile_ContactDetails($profileid);
		$phoneTypeFlag="alternate";
		entryInDuplcationCheckOnEdit($profileid,'alt_mobile');
		$val=$isd."-".$phoneNo;
		contact_archive($profileid,'PHONE_ALT',$val);
		$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
		$profileid=$profileid;
		$arrParams = array('ALT_MOBILE'=>$phoneNo,'ALT_MOB_STATUS'=>'N');
		
              //  $sql ="update newjs.JPROFILE_CONTACT SET `ALT_MOBILE`='".$phoneNo."',`ALT_MOB_STATUS`='N' ";
		if($isd!=''){
			$arrParams['ALT_MOBILE_ISD']=$isd;
			//$sql.=", `ALT_MOBILE_ISD`='".$isd."'";
		}
		$jprofileUpdateObj->updateJPROFILE_CONTACT($profileid, $arrParams);
		//$sql.="where `PROFILEID`='".$profileid."'";
		//$res=mysql_query_decide($sql);
		//already happening in update
		/*if(@mysql_affected_rows($res)<1)
		{
			$sql="INSERT IGNORE INTO `JPROFILE_CONTACT` ( `PROFILEID` , `ALT_MOBILE` , `ALT_MOBILE_ISD` , `SHOWALT_MOBILE` ,`ALT_MOB_STATUS` ) VALUES (".$profileid.", '".$phoneNo."', '".$isd."', 'Y', 'N')";//default show alternate mobile number setting
			mysql_query_decide($sql);
		}*/
		$arrFields = array('PHONE_FLAG'=>'','MOD_DT'=>'now()','HAVE_JCONTACT'=>'Y');
        //        $sql ="update newjs.JPROFILE SET `PHONE_FLAG`='',`MOD_DT`=now(),`HAVE_JCONTACT`='Y' ";
		if($isdFlag=="DIFF" && $isd!=''){
			$arrFields['MOB_STATUS']='N';
			$arrFields['LANDL_STATUS']='N';
			$sql.=" ,MOB_STATUS='N' , LANDL_STATUS='N' ";
		}
		//$sql.=" where `PROFILEID`='$profileid'";
		$exrtaWhereCond = "";
		$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
             //   mysql_query_decide($sql);
	}
	$value = hidePhoneLayer($profileid);
	JsMemcache::getInstance()->set($profileid."_PHONE_VERIFIED",$value);
	$sqlDel="DELETE FROM incentive.INVALID_PHONE WHERE PROFILEID='".$profileid."'";
	mysql_query_decide($sqlDel);
	$action = FTOStateUpdateReason::NUMBER_UNVERIFY;
	SymfonyFTOFunctions::updateFTOState($profileid,$action);
}
function getProfilePhoneDetails($profileid)
{
		$sql ="select PHONE_MOB,PHONE_RES,STD,ISD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,PHONE_WITH_STD,SHOWPHONE_RES ,SHOWPHONE_MOB from newjs.JPROFILE where  activatedKey=1 and PROFILEID='".$profileid."'";
		$res=mysql_query_decide($sql);
		$row=mysql_fetch_array($res);
		$profileDetails['MYMOBILE'] 		=	trim($row['PHONE_MOB']);
		$profileDetails['ISD']		  		=	removeAllSpecialChars($row['ISD']);
		$profileDetails['MYLANDLINESTD']	=	trim($row["PHONE_WITH_STD"]);
		$profileDetails['SHOW_MOBILE']		=	$row['SHOWPHONE_MOB'];
		$profileDetails['SHOW_LANDLINE']	=	$row['SHOWPHONE_RES'];
		$profileDetails['MYLANDLINE']	 	=	trim($row['PHONE_RES']);
		$profileDetails['STD']		 	  	=	ltrim(trim($row['STD']),0);
		if(!$profileDetails['ISD']) 	$profileDetails['ISD'] = '91';
		if($profileDetails['MYMOBILE']!='')
		{
			$profileDetails['MOB_STATUS']	= trim($row["MOB_STATUS"]);
			if($profileDetails['MOB_STATUS']==''|| $profileDetails['MOB_STATUS']=='N')
			{
				$profileDetails['MOB_STATUS']	='N';
				$profileDetails['MOBILE_VALID']	=checkMobileNumber($profileDetails['MYMOBILE'], $profileid,'',$profileDetails['ISD']);
			}
			else
				$profileDetails['MOBILE_VALID']='Y';
		}
		else
		{
			$profileDetails['MOB_STATUS']	='N';
			$profileDetails['MOBILE_VALID']	='N';
		}
		if($profileDetails['MYLANDLINE']!='')
		{
			$profileDetails['LANDL_STATUS']	=trim($row['LANDL_STATUS']);
			if($profileDetails['LANDL_STATUS']==''|| $profileDetails['LANDL_STATUS']=='N')
			{
				$profileDetails['LANDL_STATUS']	='N';
				$profileDetails['LANDLINE_VALID']	=checkLandlineNumber($profileDetails['MYLANDLINE'],$profileDetails['STD'],$profileid,'',$profileDetails['ISD']);
			}
			else
				$profileDetails['LANDLINE_VALID']='Y';
		}
		else
		{
			$profileDetails['LANDL_STATUS']	='N';
			$profileDetails['LANDLINE_VALID']	='N';
		}

		$sqlAlt ="SELECT ALT_MOBILE, ALT_MOB_STATUS,SHOWALT_MOBILE FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='".$profileid."'";
		$resAlt =mysql_query_decide($sqlAlt);
		if($rowAlt =mysql_fetch_array($resAlt))
		{
			$profileDetails['ALT_MOBILE']	=trim ($rowAlt['ALT_MOBILE']);
			if($profileDetails['ALT_MOBILE']!='')
			{
				$profileDetails['ALT_STATUS']	=trim($rowAlt['ALT_MOB_STATUS']);
				if($profileDetails['ALT_STATUS']==''||$profileDetails['ALT_STATUS']=='N')
				{
					$profileDetails['ALT_STATUS']	='N';
					$profileDetails['ALTERNATE_VALID']=checkMobileNumber($profileDetails['ALT_MOBILE'], $profileid,'',$profileDetails['ISD']);
				}
				else
					$profileDetails['ALTERNATE_VALID']='Y';	
			}
			else
			{
				$profileDetails['ALT_STATUS']	='N';
				$profileDetails['ALTERNATE_VALID']='N';
			}
			$profileDetails['SHOW_ALTERNATE']	=$rowAlt['SHOWALT_MOBILE'];
		}
		return $profileDetails;
}

function deleteCachedJprofile_ContactDetails($profileid){
  return;
  $memObject=JsMemcache::getInstance();
  $memObject->delete("JPROFILE_CONTACT_".$profileid);
}
?>
