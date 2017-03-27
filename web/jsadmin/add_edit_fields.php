<?php
/**
*       Filename        :       add_photo_login.php
*       Included        :       connect.inc
*       Description     :       logs in the operator to add photos for a user
*       Created by      :       Anmol
**/
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
	Modified by 	:	Sriarm Viswanathan
	Modified date	:	30 June 2007
**/
include ("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include ("time.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
global $screen_time;

if(authenticated($cid))
{	
			$social_new_fields['COMPANY_NAME'] = 'Name of Organization';
			$social_new_fields['PROFILE_HANDLER_NAME'] = 'Person handling Profile';
			$social_new_fields['GOTHRA_MATERNAL'] = 'Gothra (Maternal)';
			$social_new_fields['PG_COLLEGE'] = 'PG College';
			$social_new_fields['SCHOOL'] = 'Name of School';
			$social_new_fields['COLLEGE'] = 'Name of College';
			$social_new_fields['OTHER_UG_DEGREE'] = 'Other Graduation Degree';
			$social_new_fields['OTHER_PG_DEGREE'] = 'Other PG Degree';
			$social_new_fields['ALT_MOBILE_OWNER_NAME'] = 'Alternate Mobile Owner';
			$social_new_fields['ALT_MESSENGER_ID'] = 'Alternate Messenger Id';
			$social_new_fields['LINKEDIN_URL'] = 'LinkedIn Url/Id';
			$social_new_fields['FB_URL'] = 'Facebook Url/Id';
			$social_new_fields['BLACKBERRY'] = 'Blackberry Pin';
			$social_new_fields['FAV_FOOD'] = 'Food I Cook';
			$social_new_fields['FAV_TVSHOW'] = 'Favourite TV Show';
			$social_new_fields['FAV_MOVIE'] = 'Favourite Movies';
			$social_new_fields['FAV_BOOK'] = 'Favourite Books';
			$social_new_fields['FAV_VAC_DEST'] = 'Favourite Vacation Destination';
			$smarty->assign("social_new_fields",$social_new_fields);
	if($CMDSubmit)
	{
		$operator_name=getname($cid);

		$sql="select PROFILEID,SUBSCRIPTION,MOD_DT,ACTIVATED,INCOMPLETE from newjs.JPROFILE where USERNAME='$username'";
		$result=mysql_query_decide($sql) or die("$sql"."0".mysql_error_js());
		if(mysql_num_rows($result)>0)
		{
			$myrow=mysql_fetch_array($result);
			$profileid=$myrow['PROFILEID'];
			$RECV_DT=$myrow["MOD_DT"];
			$SUBMIT_DT=newtime($RECV_DT,0,$screen_time,0);
			$subs=$myrow['SUBSCRIPTION'];

			//if profile is in que for screening (i.e it is new and unassigned)
			if($myrow['INCOMPLETE'] == "N")
			{
				$sql_ins = "REPLACE INTO jsadmin.MAIN_ADMIN(PROFILEID, USERNAME, SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, ALLOTED_TO, SUBSCRIPTION_TYPE) VALUES('$profileid','$username','O','$RECV_DT','$SUBMIT_DT',DATE_SUB(NOW(), INTERVAL 30 MINUTE),'','$subs')";
				mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());
			}

			$screen="1099511627775"; //131071; //8191
			if($user_name)
                                $screen=removeflag("USERNAME",$screen);
			if($subcaste)
				$screen=removeflag("SUBCASTE",$screen);	
			if($city_birth)
				$screen=removeflag("CITYBIRTH",$screen);	
			if($gothra)
				$screen=removeflag("GOTHRA",$screen);	
			if($nakshatra)
				$screen=removeflag("NAKSHATRA",$screen);	
			if($messenger)
				$screen=removeflag("MESSENGER",$screen);	
			if($yourinfo)
				$screen=removeflag("YOURINFO",$screen);	
			if($familyinfo)
				$screen=removeflag("FAMILYINFO",$screen);	
			if($spouse)
				$screen=removeflag("SPOUSE",$screen);	
			if($contact)
				$screen=removeflag("CONTACT",$screen);	
			if($education)
				$screen=removeflag("EDUCATION",$screen);	
			if($phone_res)
				$screen=removeflag("PHONERES",$screen);	
			if($phone_mob)
				$screen=removeflag("PHONEMOB",$screen);	
			if($email)
				$screen=removeflag("EMAIL",$screen);
			if($father_info)
				$screen=removeflag("FATHER_INFO",$screen);
			if($sibling_info)
				$screen=removeflag("SIBLING_INFO",$screen);	
			if($parents_contact)
				$screen=removeflag("PARENTS_CONTACT",$screen);
			if($job_info)
				$screen=removeflag("JOB_INFO",$screen);			
			if($fname)
				$screen=removeflag("NAME",$screen);
			if($ancestral_origin)
                                $screen=removeflag("ANCESTRAL_ORIGIN",$screen);		
			if($phone_owner_name)
                                $screen=removeflag("PHONE_OWNER_NAME",$screen);
			if($mobile_owner_name)
                                $screen=removeflag("MOBILE_OWNER_NAME",$screen);
			foreach ($social_new_fields as $key => $value) {
				if($$key)
					$screen=removeflag($key,$screen);
			}
      //$sql_update="update newjs.JPROFILE set SCREENING='$screen' where PROFILEID='$profileid'";
			//mysql_query_decide($sql_update) or die("2".mysql_error_js());

      $objUpdate = JProfileUpdateLib::getInstance();
      $result = $objUpdate->editJPROFILE(array('SCREENING'=>$screen),$profileid,"PROFILEID");
      if(false === $result) {
        die('Mysql error while updating JPROFILE at line 114');
      }
      unset($objUpdate);
      
			$message="This user's profile has been marked for screening successfully.<br>";
			$message.="<a href=\"add_edit_fields.php?cid=$cid&username=$operator_name\">Master Edit another profile</a>";
			$smarty->assign("MSG",$message);
			$smarty->display("jsadmin_msg.tpl");
		}
		else 
		{
			$smarty->assign("OPERATOR_NAME",$operator_name);
			$smarty->assign("USERNAME",$user_name);
			$smarty->assign("SUBCASTE",$subcaste);
			$smarty->assign("CITY_BIRTH",$city_birth);
			$smarty->assign("GOTHRA",$gothra);
			$smarty->assign("NAKSHATRA",$nakshatra);
			$smarty->assign("MESSENGER",$messenger);
			$smarty->assign("YOURINFO",$yourinfo);
			$smarty->assign("FAMILYINFO",$familyinfo);
			$smarty->assign("SPOUSE",$spouse);
			$smarty->assign("CONTACT",$contact);
			$smarty->assign("EDUCATION",$education);
			$smarty->assign("PHONE_RES",$phone_res);
			$smarty->assign("PHONE_MOB",$phone_mob);
			$smarty->assign("EMAIL",$email);
			$smarty->assign("SIBLING_INFO",$sibling_info);
			$smarty->assign("FATHER_INFO",$father_info);
			$smarty->assign("PARENTS_CONTACT",$parents_contact);
			$smarty->assign("JOB_INFO",$job_info);
			$smarty->assign("RELOGIN",1);
			$smarty->assign("CID",$cid);
			$smarty->assign("FNAME",$fname);
			$smarty->assign("ANCESTRAL_ORIGIN",$ancestral_origin);
			$smarty->assign("PHONE_OWNER_NAME",$phone_owner_name);
			$smarty->assign("MOBILE_OWNER_NAME",$mobile_owner_name);
			$smarty->display("add_edit_fields.htm");
		}		
	}
	else 
	{		
		$smarty->assign("username",$username);
		$smarty->assign("OPERATOR_NAME",$operator_name);
		$smarty->assign("CID",$cid);
		$smarty->display("add_edit_fields.htm");
	}
}
else //user timed out
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");
}	
?>
