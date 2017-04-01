<?php
include_once("../sugarcrm/custom/include/language/en_us.lang.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

function get_jprofile_query($viewer="",$viewed="")
{
	global $jprofile_result;
	$sql = "SELECT PROFILEID , SOURCE , SUBSCRIPTION , EMAIL , GENDER , MTONGUE , CASTE , SHOWPHONE_RES , SHOWPHONE_MOB , SHOW_HOROSCOPE , BTIME , CITY_BIRTH , COUNTRY_BIRTH , OCCUPATION , PRIVACY , GET_SMS , COUNTRY_RES , CITY_RES , ACTIVATED , AGE , MOD_DT , SHOW_PARENTS_CONTACT , PARENTS_CONTACT , CONTACT , PINCODE , STD , SHOWADDRESS , PHONE_RES , PHONE_MOB , MESSENGER_ID , MESSENGER_CHANNEL , PHOTOSCREEN , HAVEPHOTO , PHOTO_DISPLAY , USERNAME , HEIGHT , RELATION , MSTATUS , HAVECHILD , MANGLIK , BTYPE , COMPLEXION , DIET , SMOKE , DRINK , RES_STATUS , HANDICAPPED , RELIGION , INCOME , EDU_LEVEL , EDU_LEVEL_NEW , FAMILY_BACK , FAMILYINFO , FAMILY_TYPE , FAMILY_STATUS , FAMILY_VALUES , MOTHER_OCC , T_BROTHER , M_BROTHER ,T_SISTER , M_SISTER , WIFE_WORKING , MARRIED_WORKING , PARENT_CITY_SAME , SUBCASTE , YOURINFO , JOB_INFO , SPOUSE  , FATHER_INFO , SCREENING , GOTHRA , NAKSHATRA , EDUCATION , DTOFBIRTH , SIBLING_INFO , SHOWMESSENGER , DATE(LAST_LOGIN_DT) LAST_LOGIN_DT , CITIZENSHIP , BLOOD_GROUP , WEIGHT , NATURE_HANDICAP , HIV , PHONE_NUMBER_OWNER , PHONE_OWNER_NAME , MOBILE_NUMBER_OWNER , MOBILE_OWNER_NAME , TIME_TO_CALL_START , TIME_TO_CALL_END , WORK_STATUS , RASHI , ANCESTRAL_ORIGIN , HOROSCOPE_MATCH , SPEAK_URDU , INCOMPLETE , ENTRY_DT , ISD FROM newjs.JPROFILE";
	if($viewer && $viewed)
	{
		$sql .=" WHERE PROFILEID IN($viewer,$viewed)";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($row=mysql_fetch_assoc($res))
		{
			$user_type='viewed';
			if($row['PROFILEID']==$viewer)
				$user_type="viewer";
			foreach($row as $key=>$val)
				$jprofile_result["$user_type"]["$key"]=$val;
                }
		if($viewer==$viewed)
			$jprofile_result['viewed']=$jprofile_result['viewer'];
	}
	elseif($viewer || $viewed)
	{
		if($viewer){
			$sql .=" WHERE PROFILEID=$viewer";
			$variable ="viewer";
		}
		if($viewed){
                        $sql .=" WHERE PROFILEID=$viewed";
                        $variable ="viewed";
		}
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_assoc($res);
		if($row)
		{
			foreach($row as $key=>$val)
				$jprofile_result["$variable"]["$key"]=$val;
		}
	}
}

function showContactDetails($viewer_profileid='',$viewed_profileid='')
{
	global $smarty;
	global $NUMBER_OWNER;
	global $jprofile_result;
	global $MESSENGER_CHANNEL;
	global $RELATIONSHIP;

	if($jprofile_result==''){
		unset($jprofile_result);
		get_jprofile_query('',$viewed_profileid);
		global $jprofile_result;
	}

	if($viewer_profileid == $viewed_profileid)
		$PERSON_OWNER=1;
		
	if($PERSON_OWNER){  
		$phone_owner_number=$NUMBER_OWNER[$jprofile_result['viewed']["PHONE_NUMBER_OWNER"]];
		$phone_owner_name=$jprofile_result['viewed']["PHONE_OWNER_NAME"];
		if($jprofile_result["viewed"]["PHONE_RES"]){
			if($jprofile_result["viewed"]["STD"])
				$res_phone=$jprofile_result["viewed"]["STD"]."-".$jprofile_result["viewed"]["PHONE_RES"];
			else
				$res_phone=$jprofile_result["viewed"]["PHONE_RES"];
		}
		$mobile_owner_number=$NUMBER_OWNER[$jprofile_result['viewed']["MOBILE_NUMBER_OWNER"]];
		$mobile_owner_name=$jprofile_result['viewed']["MOBILE_OWNER_NAME"];
		$mob_phone=$jprofile_result["viewed"]["PHONE_MOB"];
                $messenger=$jprofile_result["viewed"]["MESSENGER_ID"];
                if(!strstr($messenger,"@"))
                {
                        if($jprofile_result["viewed"]["MESSENGER_CHANNEL"])
                                $messenger=$messenger."@".$MESSENGER_CHANNEL[$jprofile_result["viewed"]["MESSENGER_CHANNEL"]];
                }
		$smarty->assign("PROFILENAME",$jprofile_result["viewed"]['USERNAME']);
		$smarty->assign("SHOW_MESSENGER",$messenger);
                $smarty->assign("SHOW_ADDRESS",nl2br($jprofile_result["viewed"]["CONTACT"]));
                $smarty->assign("SHOW_PARENTS_ADDRESS",nl2br($jprofile_result["viewed"]["PARENTS_CONTACT"]));
                $smarty->assign("TIME_TO_CALL_START",$jprofile_result["viewed"]['TIME_TO_CALL_START']);
                $smarty->assign("TIME_TO_CALL_END",$jprofile_result["viewed"]['TIME_TO_CALL_END']);
                $smarty->assign("RELATION_NAME",$RELATIONSHIP[$jprofile_result['viewed']['RELATION']]);
	}
	else
	{	
        	if($jprofile_result["viewed"]["SHOWPHONE_RES"]=="Y" && $jprofile_result["viewed"]["PHONE_RES"]!="")
        	{
        	        $phone_owner_number=$NUMBER_OWNER[$jprofile_result['viewed']["PHONE_NUMBER_OWNER"]];
        	        $phone_owner_name=$jprofile_result['viewed']["PHONE_OWNER_NAME"];
                        if($jprofile_result["viewed"]["STD"])
                                $res_phone=$jprofile_result["viewed"]["STD"]."-".$jprofile_result["viewed"]["PHONE_RES"];
                        else
                                $res_phone=$jprofile_result["viewed"]["PHONE_RES"];
        	}
		else
			$res_phone ="";
        	if($jprofile_result["viewed"]["SHOWPHONE_MOB"]=="Y" && $jprofile_result["viewed"]["PHONE_MOB"]!="")
        	{
        	        $mobile_owner_number=$NUMBER_OWNER[$jprofile_result['viewed']["MOBILE_NUMBER_OWNER"]];
        	        $mobile_owner_name=$jprofile_result['viewed']["MOBILE_OWNER_NAME"];
        	        $mob_phone=$jprofile_result["viewed"]["PHONE_MOB"];
        	}
		else
			$mob_phone ="";	
        	if($jprofile_result["viewed"]["CONTACT"]!="" && $jprofile_result["viewed"]["SHOWADDRESS"]=="Y")
        	{
        	        $smarty->assign("SHOW_ADDRESS",nl2br($jprofile_result["viewed"]["CONTACT"]));
        	}
		else
			$smarty->assign("SHOW_ADDRESS","");
        	if($jprofile_result["viewed"]["SHOW_PARENTS_CONTACT"]=="Y" && $jprofile_result["viewed"]["PARENTS_CONTACT"]!="")
        		$smarty->assign("SHOW_PARENTS_ADDRESS",nl2br($jprofile_result["viewed"]["PARENTS_CONTACT"]));
		else
			$smarty->assign("SHOW_PARENTS_ADDRESS","");
	
	        if($res_phone || $mob_phone)
	                if($jprofile_result["viewed"]['TIME_TO_CALL_START'] && $jprofile_result["viewed"]['TIME_TO_CALL_END'])
	                {
	                        $smarty->assign("TIME_TO_CALL_START",$jprofile_result["viewed"]['TIME_TO_CALL_START']);
	                        $smarty->assign("TIME_TO_CALL_END",$jprofile_result["viewed"]['TIME_TO_CALL_END']);
	                }
			else{
				$smarty->assign("TIME_TO_CALL_START","");
				$smarty->assign("TIME_TO_CALL_END","");
			}
        	if($jprofile_result['viewed']['RELATION'])
        	        $smarty->assign("RELATION_NAME",$RELATIONSHIP[$jprofile_result['viewed']['RELATION']]);
		else
			$smarty->assign("RELATION_NAME","");
        	if($jprofile_result["viewed"]["SHOWMESSENGER"]=='Y' && $jprofile_result["viewed"]["MESSENGER_ID"])
        	{
        	        $messenger=$jprofile_result["viewed"]["MESSENGER_ID"];
        	        if(!strstr($messenger,"@"))
        	        {
        	                if($jprofile_result["viewed"]["MESSENGER_CHANNEL"])
        	                        $messenger=$messenger."@".$MESSENGER_CHANNEL[$jprofile_result["viewed"]["MESSENGER_CHANNEL"]];
        	        }
        	        $smarty->assign("SHOW_MESSENGER",$messenger);
        	}
		else
			$smarty->assign("SHOW_MESSENGER","");
	}
        $smarty->assign("PHONE_NO",$res_phone);
        $smarty->assign("SHOW_MOBILE",$mob_phone);
        $smarty->assign("PHONE_PROFILENAME",$phone_owner_name);
        $smarty->assign("PHONE_RELATION_NAME",$phone_owner_number);
        $smarty->assign("MOB_PROFILENAME",$mobile_owner_name);
        $smarty->assign("MOB_RELATION_NAME",$mobile_owner_number);
        $smarty->assign("EMAIL_ID",$jprofile_result["viewed"]["EMAIL"]);
}

        function get_partner_string_from_array($arr,$tablename)
        {
                global $lang;
                if(is_array($arr))
                {
                        $str=implode("','",$arr);
                        if(substr($str,-1)==",")
                        {
                                $wr_dt=print_r($_SERVER,true);
                                $str=substr($str,0,strlen($str)-2);

                        }
                        $sql="select SQL_CACHE distinct LABEL from newjs.$tablename where VALUE in ('$str')";
                        $dropresult=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        while($droprow=mysql_fetch_array($dropresult))
                        {
                                $str1.=$droprow["LABEL"] . ", ";
                        }

                        mysql_free_result($dropresult);

                        return substr($str1,0,-2);
                }
                elseif($lang=="hin")
                        return "मान्य नही";
                else
                        return "   - ";
        }

function display_format_new($str)
{
        if($str)
        {
                $str=trim($str,"'");

                $arr=explode("','",$str);
                return $arr;
        }

}

function checkProfileOwnership($profileid,$name)
{
	$sql="SELECT COUNT(*) AS COUNT FROM Assisted_Product.AP_PROFILE_INFO WHERE SE='$name' and PROFILEID='$profileid'";
        $res=mysql_query_decide($sql) or die("Error while fetching dashboard count   ".mysql_error_js());
        $row=mysql_fetch_assoc($res);
        $row = $row["COUNT"];
	if($row>0)
		return true;
	return false;
}

function sendAdRequestToManager($profileid,$operator,$SITE_URL)
{
	$count =0;
	$adDataArr = getAdRequest_Status($profileid,$operator);
	if($adDataArr['PROFILEID'] && $adDataArr['AD_STATUS']!='DONE')
	{
		$status ='REM';
		$count =$adDataArr['NO_OF_REMINDERS']+1;
	        $sql="UPDATE Assisted_Product.AD_REQUEST_HISTORY SET AD_STATUS='$status',NO_OF_REMINDERS='$count' WHERE PROFILEID='$profileid' AND SE='$operator'";
	        mysql_query_decide($sql) or die("Error while updating AP_DPP_FILTER_ARCHIVE   ".mysql_error_js());
	}
	else	
	{
		$status ='REQ';
		$sql ="INSERT INTO Assisted_Product.AD_REQUEST_HISTORY(`PROFILEID`,`SE`,`AD_STATUS`,`NO_OF_REMINDERS`,`REQUEST_DATE`) VALUES('$profileid','$operator','$status','',now())";
		mysql_query_decide($sql) or die("Error while inserting info in AD_REQUEST_HISTORY  ".mysql_error_js());

		// get count of total request for ad for a particular profile 
	        $sql ="SELECT count(*) AS COUNT FROM Assisted_Product.AD_REQUEST_HISTORY WHERE PROFILEID='$profileid'";
	        $res=mysql_query_decide($sql) or die("Error while fetching dashboard count   ".mysql_error_js());
	        $row=mysql_fetch_assoc($res);
		$count =$row['COUNT'];

	}

	$sql ="SELECT `HEAD_ID` FROM jsadmin.PSWRDS WHERE `USERNAME`='$operator'";
	$res =mysql_query_decide($sql) or die("Error while inserting info in AD_REQUEST_HISTORY  ".mysql_error_js());
	$row=mysql_fetch_assoc($res);
	$headID =$row['HEAD_ID'];

        $sql ="SELECT `EMAIL` FROM jsadmin.PSWRDS WHERE `EMP_ID`='$headID'";  
        $res =mysql_query_decide($sql) or die("Error while inserting info in AD_REQUEST_HISTORY  ".mysql_error_js());         	     $row=mysql_fetch_assoc($res);
        $email =$row['EMAIL'];

	// send email
	$send = ad_emailToManager($profileid,$email,$operator,$count,$status,$SITE_URL);
	if($send)
		return true;
	return false;
}

function getAdRequest_Status($profileid,$operator)
{
	$dataArr =array();
	$sql ="SELECT * FROM Assisted_Product.AD_REQUEST_HISTORY WHERE PROFILEID='$profileid' AND SE='$operator'";
	$res=mysql_query_decide($sql) or die("Error while fetching dashboard count   ".mysql_error_js());
        $row=mysql_fetch_assoc($res);   
	if($row){
		$dataArr['PROFILEID']		=$row['PROFILEID'];
		$dataArr['SE'] 			=$row['SE'];
		$dataArr['AD_STATUS'] 		=$row['AD_STATUS'];
		$dataArr['NO_OF_REMINDERS'] 	=$row['NO_OF_REMINDERS'];
		$dataArr['DATE']		=$row['REQUEST_DATE'];
	}
	return $dataArr;
}

function ad_emailToManager($profileid,$emailID,$SE,$count,$status,$SITE_URL)
{
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

        $sql ="SELECT `USERNAME` FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
        $res=mysql_query_decide($sql) or die("Error while fetching JPROFILE   ".mysql_error_js());
        $row=mysql_fetch_assoc($res);
        $username =$row['USERNAME'];

	if($status=='REM'){
	$subject ="Reminder $count for profile ad for $username";
		$body ="Dear manager,<br> Sales executive $SE feels that service for the profile $username can be boosted by inserting a profile ad for the member. The person had already requested for a profile ad. You can find the member's <a href='$SITE_URL/jsadmin/ap_auto_login.php?username=$SE&profileid=$profileid&auto=profile' >profile here. </a>";
	}
	elseif($status=='REQ'){
		$subject ="Profile ad needs to be inserted for $username";
		$body ="Dear manager,<br> Sales executive $SE feels that service for the profile $username can be boosted by inserting a profile ad for the member. You can find the member's <a href='$SITE_URL/jsadmin/ap_auto_login.php?username=$SE&profileid=$profileid&auto=profile' >profile here.</a>  There has already been $count ad request(s) for this profile";
	}
	$from ="matchpoint@jeevansathi.com";
        $mail =send_email($emailID,$body,$subject,$from);
        if($mail=='1')
                return true;
	return false;
}

function addProfileComments($profileid,$matchid,$operator,$comments="",$role)
{
	$sent_status	="N";	// status to check comments has been sent or not in printout
	$commentsArr = getProfileComments($profileid,$matchid);
	$added_by =$commentsArr['ADDED_BY'];
	if($commentsArr['PROFILEID'] && $commentsArr['MATCH_ID']){
		if($role!='TC')
			$q1 ="COMMENTS='$comments'";
		else
			$q1 ="COMMENTS='$comments',`ADDED_BY`='$role'";

		$sql ="UPDATE Assisted_Product.AP_MATCH_COMMENTS SET ".$q1." WHERE PROFILEID='$profileid' AND MATCH_ID='$matchid'";
		mysql_query_decide($sql) or die("Error while UPDATE Comments in Assisted_Product.AP_MATCH_COMMENTS ".mysql_error_js());
		if($role=='TC' && $added_by==''){
			$consumed='1';
		}
	}
	else{
		if($role!='TC')
			$role='';
		$sql ="INSERT INTO Assisted_Product.AP_MATCH_COMMENTS(`PROFILEID`,`MATCH_ID`,`ADDED_BY`,`ADDED_ON`,`SENT`,`COMMENTS`) VALUES('$profileid','$matchid','$role',now(),'$sent_status','$comments')";
        	mysql_query_decide($sql) or die("Error while inserting info in Assisted_Product.AP_MATCH_COMMENTS ".mysql_error_js());
		$consumed='1';
	}
	//If ap_tc is not selecting close call on interface.
	if($_POST[call_history]=="N")
		$notupdateCallHistory=true;
	if(!$consumed && $_POST[call_history] && CallHistoryStatusCheck($profileid,$matchid,"N") && $profileid!=$matchid)
		$consumed=1;
	// Condition when call is considered as being made
	if($role=='TC' && $comments!='' && ($profileid!=$matchid) && ($consumed) && !$notupdateCallHistory){
		updateCallHistory($profileid,$matchid,$operator);
		logSubmitProfile($matchid,$operator,$role,'DONE');
		// Credits utilized if user has called up the person
		$classObj1 = new Membership;
		$classObj1->consumeCount($profileid,'1');
	}
        //check for only PG comments
        if ($profileid!=$matchid)
        {
          try
          {
            //function call for firing mail on every new changed comment added
            sendApCommentMailer($role,$comments,$commentsArr,$profileid,$matchid); 
          }
          catch (Exception $ex) {
          }
        }
}
function CallHistoryStatusCheck($profileid,$matchid,$status)
{
	if($profileid && $matchid && $status)
	{
        $sql ="select CALL_STATUS from  Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID='$profileid' AND MATCH_ID='$matchid' and CALL_STATUS='$status'";
       $res= mysql_query_decide($sql) or die("Error while inserting info in Assisted_Product.AP_CALL_HISTORY ".mysql_error_js());
	if($row=mysql_fetch_assoc($res))
		return true;
	}
	return false;
     //   moveProfiles($operator,$profileid,'','',array($matchid),'TBC','TBD');
}

function updateCallHistory($profileid,$matchid,$operator)
{
	$sql ="UPDATE Assisted_Product.AP_CALL_HISTORY SET CALL_DATE=now(),CALL_STATUS='Y',TELECALLER='$operator' WHERE PROFILEID='$profileid' AND MATCH_ID='$matchid'";
        mysql_query_decide($sql) or die("Error while inserting info in Assisted_Product.AP_CALL_HISTORY ".mysql_error_js());
	moveProfiles($operator,$profileid,'','',array($matchid),'TBC','TBD');
}

function getProfileComments($profileid,$matchid)
{
	$commentsArr =array();
	$sql ="SELECT * FROM Assisted_Product.AP_MATCH_COMMENTS WHERE PROFILEID='$profileid' AND MATCH_ID='$matchid'";
	$res=mysql_query_decide($sql) or die("Error while fetching dashboard count   ".mysql_error_js());
	$row=mysql_fetch_assoc($res);
	if($row){
		$commentsArr['PROFILEID'] =$row['PROFILEID'];
		$commentsArr['MATCH_ID']  =$row['MATCH_ID'];
		$commentsArr['ADDED_BY']  =$row['ADDED_BY'];
		$commentsArr['ADDED_ON']  =$row['ADDED_ON'];	 
		$commentsArr['SENT'] 	  =$row['SENT'];
		$commentsArr['COMMENTS']  =$row['COMMENTS'];
	}
	return $commentsArr;
}

function getCallerUsers_list($operator,$called_profileid,$profile_start='',$PAGELEN='',$pagination=0)
{
	$profilesArr =array();
	$sql ="SELECT distinct PROFILEID FROM Assisted_Product.AP_CALL_HISTORY WHERE MATCH_ID='$called_profileid' AND (CALL_STATUS!='Y' OR CALL_STATUS!='C')";
	$sql ="SELECT distinct PROFILEID FROM Assisted_Product.AP_CALL_HISTORY WHERE MATCH_ID='$called_profileid' AND CALL_STATUS='N'";
        if($pagination)
	        $sql .=" LIMIT $profile_start,$PAGELEN";
	$res =mysql_query_decide($sql) or die("Error while fetching caller from AP_CALL_HISTORY ".mysql_error_js());	
	$i=0;
        while($row=mysql_fetch_assoc($res))
	{
                $profilesArr[$i]['PROFILEID'] =$row['PROFILEID'];
		$i++;
        }
        return $profilesArr;
}

function getCallerUsers_count($operator,$called_profileid)
{
        $sql ="SELECT count(distinct PROFILEID) AS CNT FROM Assisted_Product.AP_CALL_HISTORY WHERE MATCH_ID='$called_profileid' AND CALL_STATUS='N'";
        $res =mysql_query_decide($sql) or die("Error while caller count from AP_CALL_HISTORY".mysql_error_js());
        $row=mysql_fetch_assoc($res);
        $count =$row['CNT'];
        return $count;
}

function display_resultProfiles($resultprofiles,$start,$matchid,$cid,$pageno,$total_rec,$list,$callreqFlag='')
{
        global $smarty,$IMG_URL,$PHOTO_URL;
        foreach($resultprofiles as $key=>$val)
	{
		if($val["LEAD_ID"])
		{
			$leadsArr[]=$val["LEAD_ID"];
			$idArr[]=$val["LEAD_ID"];
		}
		else
		{
	                $data_3d[$key] =$val;
			$idArr[]=$val["PROFILEID"];
		}
        }
	$profilePicUrls = SymfonyPictureFunctions::getPhotoUrls_nonSymfony($idArr,"SearchPicUrl");	//Symfony Photo Modification 
	if(is_array($data_3d))
	        $resultSet = get_profile_details_all($data_3d,$start);
	if(is_array($leadsArr))
		$resultSet = get_lead_details_all($resultSet,$leadsArr);
	if(is_array($idArr) && $list=='DIS' && $matchid)
		$profileMoveCount=getProfileMoveCount($idArr,$matchid,'DIS');
        for($i=0;$i<count($resultprofiles);$i++)
        {
		$lead=0;
		if($resultprofiles[$i]["LEAD_ID"])
		{
			$lead=1;
			$lead_id=$resultprofiles[$i]["LEAD_ID"];
			$contact_details=$resultSet[$lead_id];
		}
		else
		{
	               $profileid=$resultprofiles[$i]['PROFILEID'];
               	       $contact_details =$resultSet[$profileid];
		}

               if($contact_details['SOURCE']=='ofl_prof')
                       $offline_profile=1;
               else
                       $offline_profile=0;

                $age=$contact_details["AGE"];
                $gothra=trim($contact_details["GOTHRA"]);
                $nakshatra=trim($contact_details["NAKSHATRA"]);
                $height2=$contact_details["HEIGHT"];
                $my_income=$contact_details["INCOME"];
                $gender=$contact_details['GENDER'];
                $myCaste=$contact_details["CASTE"];
                $subcaste=trim($contact_details["SUBCASTE"]);
                $mtongue=$contact_details["MTONGUE"];
                $mtongue_s=$contact_details["MTONGUE_S"];
                $occupation=$contact_details["OCCUPATION"];
                $edu_level=$contact_details["EDUCATION"];
                $residence=$contact_details["RESIDENCE"];
                $religion=$contact_details["RELIGION"];
		
		$small_tag='';
		if($age)
                	$small_tag.="$age,";
		if($height2)
			$small_tag.="$height2,";
		if($religion)
			$small_tag.="$religion";
		if($mtongue_s)
	                $small_tag.="<BR>$mtongue_s,";
		if($myCaste)
			$small_tag.="<BR>$myCaste";
                if($subcaste && isFlagSet("SUBCASTE",$contact_details['SCREENING']))
                        $small_tag.=" ($subcaste)";
                $small_tag.=",<BR>";
                if($nakshatra && $nakshatra!="i don't know" && $nakshatra!="Don't Know")
                        $small_tag.=$nakshatra."<span class=\"no_b\"> (Nakshatra),<BR></span>";
                if($gothra && $gothra!="i don't know" && isFlagSet("GOTHRA",$contact_details['SCREENING']))
                        $small_tag.=$gothra."<span class=\"no_b\"> (Gothra),<BR></span>";
                if($edu_level)
                        $small_tag.="$edu_level, ";
                if($my_income)
                        $small_tag.=$my_income.", " ;
                if($occupation)
                        $small_tag.="<BR> $occupation";
                if($residence)
                        $small_tag.=" in ".$residence;

			if($lead)
			{
				if($gender=='F')
					$my_photo="<div style=\" float:left; margin:0 3px 3px 0; align='left'\"><img src=\"$IMG_URL/profile/images/ic_g_blank_150.gif\" width=\"100\" height=\"133\" border=\"0\"/></div>";
				else
					$my_photo="<div style=\" float:left; margin:0 3px 3px 0; align='left'\"><img src=\"$IMG_URL/profile/images/ic_b_blank_150.gif\" width=\"100\" height=\"133\" border=\"0\"/></div>";
			}
			else
			{
	                        $photochecksum=md5($profileid+5)."i".($profileid+5);
				// Photo image section
				if($contact_details["HAVEPHOTO"]=="Y")
					$havephoto="Y";
				elseif($contact_details["HAVEPHOTO"]=="U")
					$havephoto="U";
				else
					$havephoto="N";
				$image_file=getPhotoImage($havephoto, $gender);
				if($havephoto=='U')
				{
					$my_photo="<div style=\" float:left; margin:0 3px 3px 0; background:url($IMG_URL/profile/ser4_images/$image_file) no-repeat\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"100\" height=\"133\" border=\"0\" ></div>";
				}
				elseif($havephoto=='Y')
				{
					//Symfony Photo Modification - start
					$profilePicUrlArr = $profilePicUrls[$profileid];
					if ($profilePicUrlArr)
					{
						$searchPicUrl = $profilePicUrlArr["SearchPicUrl"];
					}
					else
					{
						$searchPicUrl = null;
					}
					$my_photo="<a class=\"thickbox\"><div style=\" float:left; margin:0 3px 3px 0; background-image:url($searchPicUrl)\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"100\" height=\"133\" border=\"0\" ></div></a>";
					//Symfony Photo Modification - end
				}
				else
				{
					// get no-photo image
					$my_photo="<a class=\"thickbox\" ><div style=\" float:left; margin:0 3px 3px 0; background:url($IMG_URL/profile/ser4_images/$image_file) no-repeat\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"100\" height=\"133\" border=\"0\" ></div></a>";
				}
			}
		if($lead)
		$contacts[]=array("NAME" =>$contact_details["NAME"],
				"LEAD_ID"=>$lead_id,
				"MY_PHOTO"=>$my_photo,
                                "SMALL_TAG"=>$small_tag,
                                "VIEWPROFILE_LINK"=>"ap_viewprofile.php?profileid=$lead_id&matchid=$matchid&offset=$i&j=$pageno&total_rec=$total_rec&user_list=$list&cid=$cid&lead=1&callreqFlag=$callreqFlag",
                                "CHECKBOX_ID"=>$resultprofiles[$i]["CHECKBOX_ID"],
				"DISABLE"=>$resultprofiles[$i]["DISABLE"],
				"MATCH_TYPE"=>$resultprofiles[$i]["MATCH_TYPE"],
				"MOVT_COUNT"=>$profileMoveCount[$lead_id],
				"NOT_IN_DIS"=>$resultprofiles[$i]["NOT_IN_DIS"]
                                );
		else
                $contacts[]=array( "NAME" =>$contact_details["NAME"],
                                        "PROFILEID"=>$profileid,
                                        "MY_PHOTO"=>$my_photo,
                                        "SMALL_TAG"=>$small_tag,
                                        "VIEWPROFILE_LINK"=>"ap_viewprofile.php?profileid=$profileid&matchid=$matchid&offset=$i&j=$pageno&total_rec=$total_rec&user_list=$list&cid=$cid&callreqFlag=$callreqFlag",
					"CHECKBOX_ID"=>$resultprofiles[$i]["CHECKBOX_ID"],
					"DISABLE"=>$resultprofiles[$i]["DISABLE"],
					"MATCH_TYPE"=>$resultprofiles[$i]["MATCH_TYPE"],
					"MOVT_COUNT"=>$profileMoveCount[$profileid],
					"NOT_IN_DIS"=>$resultprofiles[$i]["NOT_IN_DIS"]
                                        );
        }	
        $smarty->assign("CONTACTS_ARR",$contacts);
}

/* IVR- Phone verification check */
function getPhoneValidityCheck($profileidArr)
{
        $pidsArrMob =array();
        $sqlMob ="SELECT PROFILEID from newjs.JPROFILE where PROFILEID IN($profileidArr) AND (MOB_STATUS='Y' OR LANDL_STATUS='Y')";
        $resMob =mysql_query_decide($sqlMob) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlMob,"ShowErrTemplate");
        while($rowMob = mysql_fetch_array($resMob))
        {
                $pidsArrMob[] = $rowMob['PROFILEID'];
        }
        $pidsArr =array_unique($pidsArrMob);
        return $pidsArr;
}
 /* End IVR function */

function get_prev_next_profile($j="",$offset="",$actual_offset="",$total_rec,$show_profile,$user_list,$operator,$profileid,$matchid,$cid)
{
	global $smarty;
	
	if($user_list && $show_profile)
	{
		if(!$j)
			$j=1;
		if(!$actual_offset)
			$actual_offset=($j-1)*12+$offset;

		$next_prev_profileid =next_prev_profileid($actual_offset,$operator,$matchid,$user_list,$show_profile);
		$next_prev_profileidArr =explode("#",$next_prev_profileid);
		$profileid=$next_prev_profileidArr[0];
		if($next_prev_profileidArr[1])
			$lead='1';
		else
			$lead='';

		if($show_profile=="prev")
			$actual_offset=$actual_offset-1;
		elseif($show_profile=="next")
			$actual_offset=$actual_offset+1;	
	}
	if($user_list)
	{
		if(!$actual_offset){
                	if(!$j)
                        	$j=1;
                        $actual_offset=($j-1)*12+$offset;
		}

		$smarty->assign("SHOW_NEXT_PREV",1);
                $smarty->assign("SHOW_PREV",1);
                $smarty->assign("SHOW_NEXT",1);
                if($actual_offset==0){
                        $smarty->assign("SHOW_PREV",0);
		}
                $total_records=$total_rec-1;
                if($actual_offset==$total_records)
                        $smarty->assign("SHOW_NEXT",0);

		$prev_link ="profileid=".$profileid."&matchid=".$matchid."&show_profile=prev&actual_offset=".$actual_offset."&j=".$j."&total_rec=".$total_rec."&user_list=".$user_list."&cid=".$cid;
		$next_link ="profileid=".$profileid."&matchid=".$matchid."&show_profile=next&actual_offset=".$actual_offset."&j=".$j."&total_rec=".$total_rec."&user_list=".$user_list."&cid=".$cid;
		$smarty->assign("prev_link",$prev_link);
		$smarty->assign("next_link",$next_link);

		if($next_prev_profileidArr[1])
			return $profileid."#1";
		else
			return $profileid."#0";
	}
}

function next_prev_profileid($actual_offset,$operator="",$matchid="",$user_list="",$show_profile)
{
	$profilesArray =array();
	$user_list =trim($user_list);
	$actual_offset+=1;
	if($show_profile=='prev')
		$actual_offset=$actual_offset-2;

	if($user_list=='CALLERS')
	{
		$profileidsArr= getCallerUsers_list($operator,$matchid);
		$profileid =$profileidsArr[$actual_offset]['PROFILEID'];
	}
	else
	{
        	$sqlName="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$matchid'";
        	$resName=mysql_query_decide($sqlName) or die("Error while fetching username   ".mysql_error_js());
        	$rowName=mysql_fetch_assoc($resName);
        	$username=$rowName["USERNAME"];
		$profileidsArr=getList($matchid,$user_list,'','',$username,'','','');
		if($profileidsArr[$actual_offset]['LEAD_ID'])
			$profileid =$profileidsArr[$actual_offset]['LEAD_ID']."#1";
		else
			$profileid =$profileidsArr[$actual_offset]['PROFILEID']."#0";
	}
	return $profileid;
}

function check_ex_form_detail($profileid)
{
        $sql ="SELECT COUNT(*) as count FROM Assisted_Product.AP_CALL_HISTORY as `call`,Assisted_Product.AP_EFORM_DETAILS as `form` WHERE form.PROFILEID='$profileid' AND call.MATCH_ID=form.PROFILEID";
        $res =mysql_query_decide($sql) or die("Error while fetching dashboard count   ".mysql_error_js());
	$row =mysql_fetch_array($res);
	if($row['count'] >0)
		return true;
	return false;
}

// This function gets the PROFILEID corresponding to the LEAD_ID
function get_UserProfileid($id="")
{
	$db = connect_slave();
        $sql="SELECT `jsprofileid_c` AS `USERNAME` from sugarcrm.leads_cstm where id_c='$id'";     
        $resName=mysql_query_decide($sql,$db) or die("Error while fetching username   ".mysql_error_js());
        $rowName=mysql_fetch_assoc($resName);
        $username=$rowName["USERNAME"];
        $username =trim($username);

        if($username){
                $sql="SELECT `PROFILEID` FROM newjs.JPROFILE WHERE `USERNAME`='$username'";
                $resid=mysql_query_decide($sql,$db) or die("Error while fetching username   ".mysql_error_js());
                $row=mysql_fetch_assoc($resid);
                $profileid=$row["PROFILEID"];
        }
        if($profileid)
                return $profileid;
        return false;
}


function leadDetails($id="")
{
	$leadData_arr =array();
	$db = connect_slave();
	$sql ="SELECT leads_cstm.type_c as newspaper,leads_cstm.edition_date_c as edition_date,leads.lead_source as source,leads.assistant as assistant from sugarcrm.leads,sugarcrm.leads_cstm where leads.id=leads_cstm.id_c AND leads.id='$id'";
	$res =mysql_query_decide($sql,$db) or die("Error while fetching username   ".mysql_error_js());	
	$row=mysql_fetch_assoc($res);
	$edition_date 	=$row['edition_date'];
	$source 	=$row['source'];
	$assistant 	=$row['assistant'];				
	
	if($source=='1')
		$source_name ="Telephone";
	elseif($source=='2')
		$source_name ="Walk-in";
	elseif($source =='3')
		$source_name ="Web";
	elseif($source =='4'){
		$source_name =$row['newspaper'];
		$source_name=$GLOBALS['app_list_strings']['type_lead'][$source_name];
	}
	elseif($source =='5')
		$source_name ="Email";

	$sql ="SELECT `id`,`filename` from sugarcrm.notes where parent_id='$id'";
	$res =mysql_query_decide($sql,$db) or die("Error while fetching username   ".mysql_error_js());
	$row=mysql_fetch_assoc($res);
	$filename   =$row['filename']; 
	$file_id    =$row['id'];	

	$leadData_arr =	array("SOURCE"=>$source,"SOURCE_NAME"=>$source_name,"EDITION_DT"=>$edition_date,"ASSISTANT"=>$assistant,"FILENAME"=>$filename,"file_id"=>$file_id);
	return $leadData_arr;
}

// function to get lead contact details
function leadContactDetails($id="")
{
	global $smarty;
	$db = connect_slave();
        $sql ="SELECT `phone_mobile`,`phone_home`,`primary_address_street`,`primary_address_city`,`primary_address_state`,`primary_address_country`,`primary_address_postalcode` from sugarcrm.leads where id='$id'";
        $res =mysql_query_decide($sql,$db) or die("Error while fetching lead contact details   ".mysql_error_js());
        $row=mysql_fetch_assoc($res);
        $show_mobile   		=$row['phone_mobile'];
        $show_phone   		=$row['phone_home'];
        $primary_address_street	=$row['primary_address_street'];
	$primary_address_city   =$row['primary_address_city'];
	$primary_address_state	=$row['primary_address_state'];
	$primary_address_country=$row['primary_address_country'];
	$primary_address_code	=$row['primary_address_postalcode'];
	if($primary_address_street)
		$address1 .=$primary_address_street; 		
	if($primary_address_city)
		$address1 .=" , ".$primary_address_city;
	if($primary_address_state)
		$address1 .=" , ".$primary_address_state;
	if($primary_address_country)
		$address1 .=" , ".$primary_address_country;
	if($primary_address_code)
		$address1 .=" , ".$primary_address_code;

	$sql1 ="SELECT eadd.email_address as EMAIL from sugarcrm.email_addr_bean_rel as emap,sugarcrm.email_addresses as eadd where emap.bean_id='$id' AND emap.bean_module='Leads' AND emap.email_address_id=eadd.id";
	$res1 =mysql_query_decide($sql1,$db) or die("Error while fetching lead contact details   ".mysql_error_js());
	$row1=mysql_fetch_assoc($res1);	
	$email_id =$row['EMAIL'];	

	$smarty->assign("SHOW_MOBILE",$show_mobile);	
	$smarty->assign("PHONE_NO",$show_phone);
	$smarty->assign("EMAIL_ID",$email_id);
	$smarty->assign("SHOW_ADDRESS",$address1);
}

/* function for colouring the row in the dashboard
 * GREEN: 	live profiles 
 * RED: 	QA done and send back to SE (SE action pending) 
 * GREEN: 	QA done without any changes but profile not live yet 
 * YELLOW: 	QA not done (QA action is pending). 
 * WHITE: 	otherwise cases
*/
function getProfileStatus($profileArray)
{
	if(!is_array($profileArray))
		return array();
	$profileStatus =array();

	// Check for RED colour				
	$pidStr =implode(",",$profileArray);
	$sql ="SELECT distinct(PROFILEID) FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE where `STATUS`='SE' AND `PROFILEID` IN($pidStr) order by `DATE` desc";
       	$res=mysql_query_decide($sql) or die("Error while fetching from AP_DPP_FILTER_ARCHIVE   ".mysql_error_js());
       	while($row=mysql_fetch_assoc($res))
	{
		$profileid=$row['PROFILEID'];
		if($profileid)
			$profileStatus[$profileid] ='red';
	}
	$newprofileArray =array();
	foreach($profileArray as $key=>$val){
		$colorExist = $profileStatus[$val];
		if($colorExist=='')
			$newprofileArray[]=$val;
	}
	if(empty($newprofileArray))
		return $profileStatus;
	$pidStr =implode(",",$newprofileArray);

	// Check for YELLOW colour
	$sql ="(SELECT distinct(PROFILEID) FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE where (`STATUS`='NQA' OR `STATUS`='RQA') AND `PROFILEID` IN($pidStr)) UNION (SELECT distinct(PROFILEID) FROM Assisted_Product.AP_PROFILE_INFO where `STATUS`='BILLED' AND `PROFILEID` IN($pidStr)) order by 'DATE' desc ";
       	$res=mysql_query_decide($sql) or die("Error while fetching from AP_DPP_FILTER_ARCHIVE,AP_PROFILE_INFO".mysql_error_js());
       	while($row=mysql_fetch_assoc($res))
	{
       		$profileid=$row["PROFILEID"];
		if($profileid)
			$profileStatus[$profileid] ='yellow';
	}
        $newprofileArray1 =array();
        foreach($newprofileArray as $key=>$val){
        	$colorExist = $profileStatus[$val];
                if($colorExist=='')
                	$newprofileArray1[]=$val;
        }
	if(empty($newprofileArray1))
		return $profileStatus;
        $pidStr =implode(",",$newprofileArray1);

        // Check for GREEN colour
	$sql ="SELECT distinct(PROFILEID) FROM Assisted_Product.AP_QUEUE_LOG where `STATUS`='DONE' AND ((`ASSIGNED_FOR`='RQA') OR (`ASSIGNED_FOR`='NQA')) AND `PROFILEID` IN($pidStr) order by `SUBMIT_TIME` desc";
        $res=mysql_query_decide($sql) or die("Error while fetching from AP_QUEUE_LOG ".mysql_error_js());
        while($row=mysql_fetch_assoc($res))
        {
                $profileid=$row["PROFILEID"];
                if($profileid)
                        $profileStatus[$profileid] ='green';
        }
        $newprofileArray2 =array();
        foreach($newprofileArray1 as $key=>$val){
                $colorExist = $profileStatus[$val];
                if($colorExist=='')
                        $newprofileArray2[]=$val;
        }
        if(empty($newprofileArray2))
                return $profileStatus;
        $pidStr =implode(",",$newprofileArray2);

	// Check for GREEN colour
	$sql ="SELECT `PROFILEID` FROM Assisted_Product.AP_PROFILE_INFO where `STATUS`='LIVE' AND `PROFILEID` IN($pidStr)";
	$res=mysql_query_decide($sql) or die("Error while fetching from AP_PROFILE_INFO for NOT QA   ".mysql_error_js());
	while($row=mysql_fetch_assoc($res))
	{
		$profileid=$row["PROFILEID"];
		if($profileid)
			$profileStatus[$profileid] ='green';
	}

	// Check for WHITE colour (all the left profiles)
        foreach($newprofileArray2 as $key=>$val){
	        $colorExist = $profileStatus[$val];
                if($colorExist=='')
			$profileStatus[$val] ='white';
        }                               
	return $profileStatus;
}

// function to get the executives assigned to Manager
function getMng_EmployeeNames($manager,$role)
{
        $sql ="SELECT `EMP_ID` FROM jsadmin.PSWRDS WHERE `USERNAME`='$manager'";
        $res =mysql_query_decide($sql) or die("Error while inserting info in AD_REQUEST_HISTORY  ".mysql_error_js());
        $row=mysql_fetch_assoc($res);
        $EMP_ID =$row['EMP_ID'];

        $sql ="SELECT `USERNAME`,`PRIVILAGE` FROM jsadmin.PSWRDS WHERE `HEAD_ID`='$EMP_ID'";
        $res =mysql_query_decide($sql) or die("Error while inserting info in AD_REQUEST_HISTORY  ".mysql_error_js());
	$i=0;
        while($row=mysql_fetch_assoc($res))
	{
		$privilage =$row['PRIVILAGE'];
		$priv=explode("+",$privilage);
		if(in_array("$role",$priv)){
	        	$usernameArr[$i] =$row['USERNAME'];
			$i++;
		}
	}
	return $usernameArr;
}

function calledProfiles($profileArray)
{
        if(!is_array($profileArray))
                return array();
        $profiles= implode(",",$profileArray);
        $sql="SELECT COUNT(*) AS COUNT,c.PROFILEID FROM Assisted_Product.AP_CALL_HISTORY AS c,Assisted_Product.AP_SERVICE_TABLE AS s WHERE c.PROFILEID IN($profiles) AND c.CALL_STATUS='Y' AND c.CALL_DATE > DATE_SUB(s.NEXT_SERVICE_DATE, INTERVAL 15 DAY) AND c.PROFILEID=s.PROFILEID GROUP BY c.PROFILEID";
        $res=mysql_query_decide($sql);
        while($row=mysql_fetch_assoc($res))
                $valueArray[$row["PROFILEID"]]["TBC"]=$row["COUNT"];
        return $valueArray;
}

// function manipulates the datetime format ,return array(0=>date,1=>time)
function datetime_format($dateTime)
{
        $dateTime       =trim($dateTime);
        $arr = explode(" ",$dateTime);
        $date =$arr['0'];
        if($date){
                $dateArr        =explode("-",$date);   
                $dateTimestamp  = mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr["0"]);
                $date           = date("dS M Y",$dateTimestamp);
        }
        $time =$arr['1'];
        if($time){
                $timeArr        =explode(":",$time);
                $Timestamp      = mktime($timeArr[0],$timeArr[1],0);
                $time           = date("g.i A",$Timestamp);
        }
	$dateTimeArr =$date." ".$time; 	
        return $dateTimeArr;
}

function sendApCommentMailer($szRole,$szComments,$arrPreviousComments,$iProfileId,$iMatchId)
{
    
    $iMailID = 1790;
    //Base Condition, Role should be TC
    if('TC' != $szRole)
    {
        return ;
    }
    
    if( strlen($szComments)  && 
        (!$arrPreviousComments['COMMENTS'] || 
         ($arrPreviousComments['COMMENTS'] && strtolower($szComments) != strtolower($arrPreviousComments['COMMENTS']))
        )    
      )
    {
        $sql="SELECT `USERNAME`,`PROFILEID` FROM newjs.JPROFILE WHERE `PROFILEID`='$iProfileId' OR `PROFILEID`='$iMatchId'";
        $res=mysql_query_decide($sql);
        while($row=mysql_fetch_assoc($res))
	{
            if($row[PROFILEID]==$iProfileId)
                $pg=$row[USERNAME];
            else
                $pog=$row[USERNAME];
        }
        //Fire AP Comment Mailer
        $apMailer=new EmailSender(MailerGroup::AP_COMMENTS,$iMailID);
        $emailTpl=$apMailer->setProfileId($iProfileId); 
        $smartyObj = $emailTpl->getSmarty();
        $smartyObj->assign("pog_id",$pog);
        $smartyObj->assign("pg_id",$pg);
        $smartyObj->assign("TC_COMMENTS",$szComments);
        $apMailer->send();
    }    
}
?>

