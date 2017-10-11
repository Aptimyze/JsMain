<?php

/************************************************************************************************************************
*    DESCRIPTION        : Current 9 queries on JPROFILE are running which extracts data of viewer and viewed profile.
			  Now this query is limited to 2 , by declaring global array jprofile_result which strore info of
			  both viewed and viewer profile.
*    CREATED BY         : lavesh
***********************************************************************************************************************/

function limiting_jprofile_query($viewer="",$viewed="",$for_edit_master=0)
//viewprofile
{
	global $jprofile_result;
	if($viewer && $viewed)
	{
		//include_once("ntimes_function.php");
		$sql="SELECT PROFILEID , SOURCE , SUBSCRIPTION , EMAIL , GENDER , MTONGUE , CASTE , SHOWPHONE_RES , SHOWPHONE_MOB , SHOW_HOROSCOPE , BTIME , CITY_BIRTH , COUNTRY_BIRTH , OCCUPATION , PRIVACY , GET_SMS , COUNTRY_RES , CITY_RES , ACTIVATED , AGE , MOD_DT , SHOW_PARENTS_CONTACT , PARENTS_CONTACT , CONTACT , PINCODE , STD , SHOWADDRESS , PHONE_RES , PHONE_MOB , MESSENGER_ID , MESSENGER_CHANNEL , PHOTOSCREEN , HAVEPHOTO , PHOTO_DISPLAY , USERNAME , HEIGHT , RELATION , MSTATUS , HAVECHILD , MANGLIK , BTYPE , COMPLEXION , DIET , SMOKE , DRINK , RES_STATUS , HANDICAPPED , RELIGION , INCOME , EDU_LEVEL , EDU_LEVEL_NEW , FAMILY_BACK , FAMILYINFO , FAMILY_TYPE , FAMILY_STATUS , FAMILY_VALUES , MOTHER_OCC , T_BROTHER , M_BROTHER ,T_SISTER , M_SISTER , WIFE_WORKING , MARRIED_WORKING , PARENT_CITY_SAME , SUBCASTE , YOURINFO , JOB_INFO , SPOUSE  , FATHER_INFO , SCREENING , GOTHRA , NAKSHATRA , EDUCATION , DTOFBIRTH , SIBLING_INFO , SHOWMESSENGER , LAST_LOGIN_DT , CITIZENSHIP , BLOOD_GROUP , WEIGHT , NATURE_HANDICAP , HIV , PHONE_NUMBER_OWNER , PHONE_OWNER_NAME , MOBILE_NUMBER_OWNER , MOBILE_OWNER_NAME , TIME_TO_CALL_START , TIME_TO_CALL_END , WORK_STATUS , RASHI , ANCESTRAL_ORIGIN , HOROSCOPE_MATCH , SPEAK_URDU , INCOMPLETE , ENTRY_DT , ISD , MOB_STATUS , LANDL_STATUS , PHONE_FLAG FROM newjs.JPROFILE WHERE PROFILEID IN($viewer,$viewed)";
		if($for_edit_master)
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		else
			$res=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($row=mysql_fetch_assoc($res))
		{
			$user_type='viewed';
			if($row['PROFILEID']==$viewer)
				$user_type="viewer";

			//$ntimes = ntimes_count($row['PROFILEID'],"SELECT");
			//$jprofile_result[$user_type]["NTIMES"]=$ntimes;
			foreach($row as $key=>$val)
				$jprofile_result["$user_type"]["$key"]=$val;
                }
		if($viewer==$viewed)
			$jprofile_result['viewed']=$jprofile_result['viewer'];
			
		
	}
	else
	{
		if($viewer)
		{
				$sql="SELECT PROFILEID , SOURCE , SUBSCRIPTION , EMAIL , GENDER , MTONGUE , CASTE , SHOWPHONE_RES , SHOWPHONE_MOB , SHOW_HOROSCOPE , BTIME , CITY_BIRTH , COUNTRY_BIRTH , OCCUPATION , PRIVACY , GET_SMS , COUNTRY_RES , CITY_RES , ACTIVATED , AGE , MOD_DT , SHOW_PARENTS_CONTACT , PARENTS_CONTACT , CONTACT , PINCODE , STD , SHOWADDRESS , PHONE_RES , PHONE_MOB , MESSENGER_ID , MESSENGER_CHANNEL , PHOTOSCREEN , HAVEPHOTO , PHOTO_DISPLAY , USERNAME , HEIGHT , RELATION , MSTATUS , HAVECHILD , MANGLIK , BTYPE , COMPLEXION , DIET , SMOKE , DRINK , RES_STATUS , HANDICAPPED , RELIGION , INCOME , EDU_LEVEL , EDU_LEVEL_NEW , FAMILY_BACK , FAMILYINFO , FAMILY_TYPE , FAMILY_STATUS , FAMILY_VALUES , MOTHER_OCC , T_BROTHER , M_BROTHER ,T_SISTER , M_SISTER , WIFE_WORKING , MARRIED_WORKING , PARENT_CITY_SAME , SUBCASTE , YOURINFO , JOB_INFO , SPOUSE  , FATHER_INFO , SCREENING , GOTHRA , NAKSHATRA , EDUCATION , DTOFBIRTH , SIBLING_INFO , SHOWMESSENGER , LAST_LOGIN_DT , CITIZENSHIP , BLOOD_GROUP , WEIGHT , NATURE_HANDICAP , HIV , PHONE_NUMBER_OWNER , PHONE_OWNER_NAME , MOBILE_NUMBER_OWNER , MOBILE_OWNER_NAME , TIME_TO_CALL_START , TIME_TO_CALL_END , WORK_STATUS , RASHI , ANCESTRAL_ORIGIN , HOROSCOPE_MATCH , SPEAK_URDU , INCOMPLETE , ENTRY_DT , ISD , MOB_STATUS , LANDL_STATUS , PHONE_FLAG FROM newjs.JPROFILE WHERE PROFILEID=$viewer";
				if($for_edit_master)
					$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				else
					$res=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$row=mysql_fetch_assoc($res);
				if($row)
				{
					foreach($row as $key=>$val)
						$jprofile_result["viewer"]["$key"]=$val;
				}
		}	

		if($viewed)
		{
			//include_once("ntimes_function.php");
			//$ntimes = ntimes_count($viewed,"SELECT");

			$sql="SELECT PROFILEID , SOURCE , SUBSCRIPTION , EMAIL , GENDER , MTONGUE , CASTE , SHOWPHONE_RES , SHOWPHONE_MOB , SHOW_HOROSCOPE , BTIME , CITY_BIRTH , COUNTRY_BIRTH , OCCUPATION , PRIVACY , GET_SMS , COUNTRY_RES , CITY_RES , ACTIVATED , AGE , MOD_DT , SHOW_PARENTS_CONTACT , PARENTS_CONTACT , CONTACT , PINCODE , STD , SHOWADDRESS , PHONE_RES , PHONE_MOB , MESSENGER_ID , MESSENGER_CHANNEL , PHOTOSCREEN , HAVEPHOTO , PHOTO_DISPLAY , USERNAME , HEIGHT , RELATION , MSTATUS , HAVECHILD , MANGLIK , BTYPE , COMPLEXION , DIET , SMOKE , DRINK , RES_STATUS , HANDICAPPED , RELIGION , INCOME , EDU_LEVEL , EDU_LEVEL_NEW , FAMILY_BACK , FAMILYINFO , FAMILY_TYPE , FAMILY_STATUS , FAMILY_VALUES , MOTHER_OCC , T_BROTHER , M_BROTHER ,T_SISTER , M_SISTER , WIFE_WORKING , MARRIED_WORKING , PARENT_CITY_SAME , SUBCASTE , YOURINFO , JOB_INFO , SPOUSE  , FATHER_INFO , SCREENING , GOTHRA , NAKSHATRA , EDUCATION , DTOFBIRTH , SIBLING_INFO , SHOWMESSENGER , LAST_LOGIN_DT , CITIZENSHIP , BLOOD_GROUP , WEIGHT , NATURE_HANDICAP , HIV , PHONE_NUMBER_OWNER , PHONE_OWNER_NAME , MOBILE_NUMBER_OWNER , MOBILE_OWNER_NAME , TIME_TO_CALL_START , TIME_TO_CALL_END , WORK_STATUS , RASHI , ANCESTRAL_ORIGIN , HOROSCOPE_MATCH , SPEAK_URDU , INCOMPLETE , ENTRY_DT , ISD , MOB_STATUS , LANDL_STATUS , PHONE_FLAG FROM newjs.JPROFILE  WHERE PROFILEID=$viewed";
			if($for_edit_master)
				$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			else
				$res=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($res);
			if($row)
			{
				foreach($row as $key=>$val)
					$jprofile_result["viewed"]["$key"]=$val;
				//$jprofile_result["viewed"]["NTIMES"]=$ntimes;
			}

		}
		else// if no profile is found for this profileid, show error message
		{
			if (function_exists('showProfileError_DP')) 
				showProfileError_DP();
			else
				 showProfileError();
		}
	}
}

function limiting_contact_jprofile_query($sender_profileid,$receiver_profileid)
{
	global $jprofile_contact;
	$sql="SELECT PROFILEID , GENDER , USERNAME , SERVICE_MESSAGES , EMAIL , ACTIVATED, PHOTO_DISPLAY, HAVEPHOTO, PRIVACY from newjs.JPROFILE where PROFILEID in ('$receiver_profileid','$sender_profileid')";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($row=mysql_fetch_array($res))
	{
		$profileid=$row["PROFILEID"];
		$jprofile_contact[$profileid]["USERNAME"]=$row["USERNAME"];
		$jprofile_contact[$profileid]["GENDER"]=$row["GENDER"];
		$jprofile_contact[$profileid]["SERVICE_MESSAGES"]=$row["SERVICE_MESSAGES"];
		$jprofile_contact[$profileid]["EMAIL"]=$row["EMAIL"];
		$jprofile_contact[$profileid]["ACTIVATED"]=$row["ACTIVATED"];
		$jprofile_contact[$profileid]["PHOTO_DISPLAY"]=$row["PHOTO_DISPLAY"];
		$jprofile_contact[$profileid]["HAVEPHOTO"]=$row["HAVEPHOTO"];
		$jprofile_contact[$profileid]["PRIVACY"]=$row["PRIVACY"];
	}
	//print_r($jprofile_contact);
}

?>
