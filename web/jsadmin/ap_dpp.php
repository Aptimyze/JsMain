<?php
$flag_using_php5=1;
include("connect.inc");
include_once("ap_common.php");
include_once("ap_dpp_common.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/connect_dd.inc");

$db=connect_db();

if(authenticated($cid))
{
	$role=fetchRole($cid);
	$name=getname($cid);
	if($skip)
	{
		if($new)
			$status='NQA';
		else
			$status='RQA';
		if(checkAssigned($editedProfile,$status,$name,$role))
		{
			$sql="REPLACE INTO Assisted_Product.AP_QA_SKIPPED_RECORDS(PROFILEID,STATUS,SKIPPED_BY,SKIPPED_ON) VALUES('$editedProfile','$status','$name',NOW())";
			$res=mysql_query_decide($sql) or die("Error while skipping records  ".mysql_error_js());
			if(mysql_affected_rows_js())
			{
				deleteTemporaryDPP($editedProfile,$name);
				$sql="DELETE FROM Assisted_Product.AP_QUEUE WHERE ASSIGNED_TO='$name' AND ASSIGNED_FOR='$status' AND PROFILEID='$editedProfile'";
				$res=mysql_query_decide($sql) or die("Error while deleting entry for skipping   ".mysql_error_js());
				if($outOfQueue)
		                        header("Location: ".$SITE_URL."/jsadmin/ap_pull_profile.php?cid=".$cid);
			}
			else
				$skip='';
		}
	}
	if($filter && $editID && $editedProfile)
	{
		$updateString=$filter."_FILTER='$value'";
		updateTemporaryDPP($updateString,$editedProfile,$name);
	}
	if($editID && !$skip)
	{
		$presentStatus=checkDPPCurrentStatus($editID,$editedProfile);
		if($role=='SE')
		{
			$flag=0;
			$res=checkNewDPP($editID,$editedProfile);
			if(@mysql_num_rows($res))
			{
				deleteTemporaryDPP($editedProfile,$name);
			}
			else
			{
				$flag=1;
			}
			$profile=$editedProfile;
		}
		if($role=='SE' && $action && $flag)
		{
			if(!checkAssigned($editedProfile,'',$name,$role))
				die("Profile is not assigned to you");
			$new=isProfileNew($editedProfile);
			if($presentStatus=='SE')
			{
				if($action=='QA')
				{
					if($new)
						$newStatus='NQA';
					else
						$newStatus='RQA';
					$comments=addslashes(stripslashes(urldecode($postedComments)));
					$sql="SELECT * FROM Assisted_Product.AP_TEMP_DPP WHERE PROFILEID='$editedProfile' AND CREATED_BY='$name'";
					$res=mysql_query_decide($sql) or die("Error while fetching temporary DPP   ".mysql_error_js());
					if($row=mysql_fetch_assoc($res))
					{
						createDPP($row,$editedProfile,$name,$role,$newStatus,$editID,$presentStatus,'OBS',$online,$dppCreatedBy,$comments,'',1);
						deleteTemporaryDPP($editedProfile,$name);
					}

				}
				elseif($action=='LIVE')
				{
					deleteTemporaryDPP($editedProfile,$name);
					makeDPPLive($editedProfile,$editID,$name,$dppCreatedBy,$online,$presentStatus);
					if($new)
						makeProfileLive($editedProfile);
				}
			}
			elseif($presentStatus=='LIVE')
			{
				if($action=='QA')
				{
					$newStatus='RQA';
					$comments=addslashes(stripslashes(urldecode($postedComments)));
					$sql="SELECT * FROM Assisted_Product.AP_TEMP_DPP WHERE PROFILEID='$editedProfile' AND CREATED_BY='$name'";
					$res=mysql_query_decide($sql) or die("Error while fetching temporary DPP   ".mysql_error_js());
					if($row=mysql_fetch_assoc($res))
					{
						createDPP($row,$editedProfile,$name,$role,$newStatus,$editID,$presentStatus,'',$online,$dppCreatedBy,$comments,'',1);
						deleteTemporaryDPP($editedProfile,$name);
					}
				}
			}
			elseif($presentStatus=='RQA' || $presentStatus=='NQA')
			{
				if($action=='QA')
				{
					$sql="SELECT DATE FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE PROFILEID='$editedProfile' AND DPP_ID='$editID'";
					$res=mysql_query_decide($sql) or die("Error while comparing dpp dates   ".mysql_error_js());
					$row=mysql_fetch_assoc($res);
					$date1=$row["DATE"];
				
					$sql="SELECT COUNT(*) AS COUNT FROM Assisted_Product.AP_TEMP_DPP WHERE PROFILEID='$editedProfile' AND CREATED_BY='$name' AND DATE>='$date1'";
					$res=mysql_query_decide($sql) or die("Error while comparing dpp dates  ".mysql_error_js());
					$row=mysql_fetch_assoc($res);
					if($row["COUNT"])
					{
						if($new)
							$newStatus='NQA';
						else
							$newStatus='RQA';
						/*if(checkAssigned($editedProfile,$newStatus,'','QA'))
						{*/
							$comments=addslashes(stripslashes(urldecode($postedComments)));
							$sql="SELECT * FROM Assisted_Product.AP_TEMP_DPP WHERE PROFILEID='$editedProfile' AND CREATED_BY='$name'";
							$res=mysql_query_decide($sql) or die("Error while fetching temporary DPP   ".mysql_error_js());
							if($row=mysql_fetch_assoc($res))
							{
								createDPP($row,$editedProfile,$name,$role,$newStatus,$editID,$presentStatus,'OBS',$online,$dppCreatedBy,$comments,'',1);
								deleteTemporaryDPP($editedProfile,$name);
							}
	
					}
					else
					{
						deleteTemporaryDPP($editedProfile,$name);	
					}
				}
			}
		}
		elseif($role=='QA')
		{
			if($new)
				$loggedFor='NQA';
			else
				$loggedFor='RQA';
			if(!checkAssigned($editedProfile,$loggedFor,$name,$role) && !$pulledProfile)
			{
				if($outOfQueue)
					die("Please pull another profile from <a href=\"$SITE_URL/jsadmin/ap_pull_profile.php?cid=$cid\">here</a>");
				else
					die("Please pull another profile by clicking <a href=\"$SITE_URL/jsadmin/ap_dpp.php?cid=$cid&new=$new\">here</a>");	
			}
			
			$res=checkNewDPP($editID,$editedProfile);
			if(@mysql_num_rows($res))
			{
				deleteTemporaryDPP($editedProfile,$name);
				$row=mysql_fetch_assoc($res);
				if($row["STATUS"]=='NQA' && $new)
					$update=1;
				elseif($row["STATUS"]=='RQA' && !$new)
					$update=1;
				else
					$delete=1;
			}
			else
			{	
				if($new && $presentStatus!='NQA')
					$delete=1;
				if(!$new && $presentStatus!='RQA')
					$delete=1;
			}
			if($pulledProfile)
                        {
                                $update=0;
                                $delete=0;
                        }
			if($delete)
			{
				$sql="DELETE FROM Assisted_Product.AP_QUEUE WHERE ASSIGNED_TO='$name' AND PROFILEID='$editedProfile' AND ASSIGNED_FOR='$loggedFor'";
				mysql_query_decide($sql) or die("Error while deleting entry from queue   ".mysql_error_js());
				if($outOfQueue)
                                {
                                        if(!$pulledProfile)
                                                die("Some error has occurred. Please select profile from here <a href=\"$SITE_URL/jsadmin/ap_pull_profile.php?cid=$cid\">here</a>");

                                }
                                else
                                        die("Please pull another profile by clicking <a href=\"$SITE_URL/jsadmin/ap_dpp.php?cid=$cid&new=$new\">here</a>");
			}
			elseif($update)
			{
				if(!$pulledProfile)
                                {
                                        $sql="UPDATE Assisted_Product.AP_QUEUE SET ASSIGN_TIME=NOW() WHERE PROFILEID='$editedProfile' AND ASSIGNED_TO='$name' AND ASSIGNED_FOR='$loggedFor'";
                                        mysql_query_decide($sql) or die("Error while updating queue   ".mysql_error_js());
                                }
			}
			elseif($action)
			{
				if($action=="SE")
				{
					$comments=addslashes(stripslashes(urldecode($postedComments)));
					$sql="SELECT * FROM Assisted_Product.AP_TEMP_DPP WHERE PROFILEID='$editedProfile' AND CREATED_BY='$name'";
					$res=mysql_query_decide($sql) or die("Error while fetching temporary DPP   ".mysql_error_js());
					if($row=mysql_fetch_assoc($res))
					{
						createDPP($row,$editedProfile,$name,$role,'SE',$editID,$presentStatus,'OBS',$online,$dppCreatedBy,$comments);
						deleteTemporaryDPP($editedProfile,$name);
					}
				}
				elseif($action=="LIVE")
				{
					deleteTemporaryDPP($editedProfile,$name);
					makeDPPLive($editedProfile,$editID,$name,$dppCreatedBy,$online,$presentStatus);
					if($new)
						makeProfileLive($editedProfile);
				}
				if(!$pulledProfile)
					logSubmitProfile($editedProfile,$name,$loggedFor,'DONE');
				if($outOfQueue && !$pulledProfile)
                                        header("Location: ".$SITE_URL."/jsadmin/ap_pull_profile.php?cid=".$cid);
			}
		}
	}
	if($pulledProfile)
                $profile=$pulledProfile;
	if(!$profile)
	{
		$row=fetchNextProfile($role,$name,$new);
		$profile=$row["PROFILEID"];
	}
	if($profile)
	{
		if($role=='SE')
		{
			if(!checkAssigned($profile,'',$name,$role))
				die("Profile is not assigned to you");
			$new=isProfileNew($profile);
		}
		if(!$editID)
			deleteTemporaryDPP($profile,$name);
		
		$sqlID="SELECT COUNT(*) AS COUNT FROM Assisted_Product.AP_TEMP_DPP WHERE PROFILEID='$profile' AND CREATED_BY='$name'";
                $resID=mysql_query_decide($sqlID) or die("Error while checking entry in temporary dpp   ".mysql_error_js());
                $rowID=mysql_fetch_assoc($resID);
		if($rowID["COUNT"])
			$showEdited=1;
		else
			$showEdited=0;
		if($role=="DIS")
			$dppHistory[]=fetchCurrentDPP($profile);
		else
			$dppHistory=fetchDPPHistory($profile,$name,$showEdited);
	
		displayDPP($dppHistory);
		$sql="SELECT GENDER,USERNAME,SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$profile'";
		$res=mysql_query_decide($sql) or die("Error while fetching username   ".mysql_error_js());
		$row=mysql_fetch_assoc($res);
		$smarty->assign("PROFILEID",$profile);
		$smarty->assign("USERNAME",$row["USERNAME"]);
		$smarty->assign("GENDER",$row["GENDER"]);
		$smarty->assign("SUBSCRIPTION",$row["SUBSCRIPTION"]);
		
		if(!$rowID["COUNT"])
		{	
			$lastIndex=count($dppHistory)-1;
			foreach($dppHistory as $key=>$value)
			{
				if($key==$lastIndex)
				{
                                        $value[CHILDREN] = preg_replace("/'/", '', $value[CHILDREN]);
					$sql="REPLACE INTO Assisted_Product.AP_TEMP_DPP(GENDER,CHILDREN,LAGE,HAGE,LHEIGHT,HHEIGHT,HANDICAPPED,CASTE_MTONGUE,PARTNER_BTYPE,PARTNER_CASTE,PARTNER_CITYRES,PARTNER_COUNTRYRES,PARTNER_DIET,PARTNER_DRINK,PARTNER_ELEVEL_NEW,PARTNER_INCOME,PARTNER_MANGLIK,PARTNER_MSTATUS,PARTNER_MTONGUE,PARTNER_NRI_COSMO,PARTNER_OCC,PARTNER_RELATION,PARTNER_RES_STATUS,PARTNER_SMOKE,PARTNER_COMP,PARTNER_RELIGION,PARTNER_NAKSHATRA,NHANDICAPPED,AGE_FILTER,MSTATUS_FILTER,RELIGION_FILTER,CASTE_FILTER,COUNTRY_RES_FILTER,CITY_RES_FILTER,MTONGUE_FILTER,INCOME_FILTER,DATE,CREATED_BY,PROFILEID,LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL) VALUES('$value[GENDER]','$value[CHILDREN]','$value[LAGE]','$value[HAGE]','$value[LHEIGHT]','$value[HHEIGHT]',\"$value[HANDICAPPED]\",\"$value[CASTE_MTONGUE]\",\"$value[PARTNER_BTYPE]\",\"$value[PARTNER_CASTE]\",\"$value[PARTNER_CITYRES]\",\"$value[PARTNER_COUNTRYRES]\",\"$value[PARTNER_DIET]\",\"$value[PARTNER_DRINK]\",\"$value[PARTNER_ELEVEL_NEW]\",\"$value[PARTNER_INCOME]\",\"$value[PARTNER_MANGLIK]\",\"$value[PARTNER_MSTATUS]\",\"$value[PARTNER_MTONGUE]\",\"$value[PARTNER_NRI_COSMO]\",\"$value[PARTNER_OCC]\",\"$value[PARTNER_RELATION]\",\"$value[PARTNER_RES_STATUS]\",\"$value[PARTNER_SMOKE]\",\"$value[PARTNER_COMP]\",\"$value[PARTNER_RELIGION]\",\"$value[PARTNER_NAKSHATRA]\",\"$value[NHANDICAPPED]\",\"$value[AGE_FILTER]\",\"$value[MSTATUS_FILTER]\",\"$value[RELIGION_FILTER]\",\"$value[CASTE_FILTER]\",\"$value[COUNTRY_RES_FILTER]\",\"$value[CITY_RES_FILTER]\",\"$value[MTONGUE_FILTER]\",\"$value[INCOME_FILTER]\",NOW(),'$name','$profile',\"$value[LINCOME]\",\"$value[HINCOME]\",\"$value[LINCOME_DOL]\",\"$value[HINCOME_DOL]\")";
					mysql_query_decide($sql) or die("Error while inserting into temp dpp   ".mysql_error_js());
				}
			}
		}
		
		if($role=='QA')
		{
			$matches=getNumberOfTempDPPMatches($profile,$name);
			$smarty->assign("numberOfMatches",$matches);
		}
		$smarty->assign("editedProfile",$profile);
		$matchPointString="matchPoint=1&matchPointPID=$profile&matchPointName=$row[USERNAME]&matchPointSub=$row[SUBSCRIPTION]&matchPointOperator=$name&matchPointCID=$cid&matchPointNew=$new&matchPointPulledProfile=$pulledProfile&outOfQueue=$outOfQueue";
		$smarty->assign("matchPointString",$matchPointString);
		if($outOfQueue)
                        $smarty->assign("outOfQueue",$outOfQueue);
                if($pulledProfile)
                        $smarty->assign("pulledProfile",$pulledProfile);
	}
	else
		die("No profiles in pool");
	if($role=='QA' || $role=='SE')
		$smarty->assign("EDIT",1);
	$smarty->assign("operator",$name);
	$smarty->assign("cid",$cid);
	$smarty->assign("new",$new);
	$smarty->assign("ROLE",$role);
	if($role!='QA')
	{
		$listArr=array('SL','FIL','TBD','DIS','TBC');
		$profileArr=array($profile);
		$countArr=getNumberInList($profileArr,$listArr);
	}
	fetchLeftPanelLinks($role,$cid,$profile,$new,"DPP",$countArr);
	$smarty->display("ap_dpp.htm");
}
else
{
	$msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
