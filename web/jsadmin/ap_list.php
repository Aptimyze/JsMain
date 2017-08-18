<?php
$flag_using_php5=1;
include("connect.inc");
include_once("ap_common.php");
include_once("ap_functions.php");
include_once("display_common.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/arrays.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/contact.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/cmr.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/thumb_identification_array.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");

$db=connect_db();

global $listMainArray;
global $contactsCountArray;
global $leadsCountArray;
global $CITY_DROP;
global $useSlave;
$useSlave=1;

if(authenticated($cid))
{
	if(!$j)
		$j=1;
	$PAGELEN=12;
	$pagination=1;
	$name=getname($cid);
	$role=fetchRole($cid);
	if($submitProfile==1 && $profileid)
	{
		logSubmitProfile($profileid,$name,'DIS','DONE');
		$submittedProfile=$profileid;
		$profileid='';
		if($outOfQueue)
			header("Location: ".$SITE_URL."/jsadmin/ap_pull_profile.php?cid=".$cid);
	}
	if($role=='DIS' && !$profileid && $list=='TBD')
	{
		$cityArr=getDispatcherCities($name);
		if(is_array($cityArr))
			$cities=implode("','",$cityArr);
		else
			$cities='';
		$profileRow=fetchNextProfile($role,$name,'',$cities,$submittedProfile);
		$db=connect_db();
		$profileid=$profileRow["PROFILEID"];
		if(!$profileid)
			die("No more profiles in pool");
	}
	if(!$list || !$profileid)
		die("Some error has occurred. Please try again");

	if(checkAssigned($profileid,'',$name,$role))
	{
		if($action)
		{
			if(is_array($profileSelected) && count($profileSelected))
			{
				if($TBD)
					$desFolder='TBD';
				elseif($REM)
					$desFolder='REM';
				elseif($DIS)
					$desFolder='DIS';
				elseif($TBC)
					$desFolder='TBC';
				elseif($ORIG)
					$desFolder='ORIG';
				if($desFolder)
				{
					foreach($profileSelected as $key=>$value)
					{
						if(substr($value,0,4)=="CALL")
							$callsArr[]=trim($value,"CALL_");
						elseif(substr($value,0,4)=="LEAD")
							$leadsArr[]=trim($value,"LEAD_");
						if(is_numeric($value))
							$contactProfiles[]=$value;
					}
					if($sourceFolder=='TBD' && $desFolder!='DIS')
						unset($callsArr);
					if($desFolder=='REM')
						unset($callsArr);
					moveProfiles($name,$profileid,$contactProfiles,$leadsArr,$callsArr,$list,$desFolder);
				}
			}
		}	
		$sqlName="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
		$resName=mysql_query_decide($sqlName) or die("Error while fetching username   ".mysql_error_js());
		$rowName=mysql_fetch_assoc($resName);
		$username=$rowName["USERNAME"];

		$profileArray=array($profileid);
		$countArray=getNumberInList($profileArray,$listMainArray);
		if($categorySearch)
		{
			if($categorySearch)
                        {
				if(!$lage)
					$lage=$searchedLage;
                                $details["lage"]=$lage;
				if(!$hage)
					$hage=$searchedHage;
                                $details["hage"]=$hage;
				if(!$mstatus)
					$mstatus=$searchedMstatus;
                                $details["mstatus"]=$mstatus;
				if(!$city_Res)
					$city_Res=$searchedCity_res;
                                $details["city_Res"]=$city_Res;
				if(!$havephoto)
					$havephoto=$searchedHavephoto;
                                $details["havephoto"]=$havephoto;
				if(!$caste)
					$caste=$searchedCaste;
                                $details["caste"]=$caste;
				if(!$mtongue)
					$mtongue=$searchedMtongue;
                                $details["mtongue"]=$mtongue;
				if(!$match_type)
					$match_type=$searchedMatchtype;
				$details["match_type"]=$match_type;
                        }
			$shortlistedProfilesArray=getList($profileid,'SL','','',$username);
			$details["ALL_MATCHES"]=$shortlistedProfilesArray;
			$details["ARC_SAX"]=array();
			$details["NUDGES"]=array();	
			$details=getCategorySearchResults($details);
			if(is_array($details["ALLOW_PROFILES"]) && count($details["ALLOW_PROFILES"]))
			{
				foreach($details["ALLOW_PROFILES"] as $key=>$value)
				{
					$allProfilesArray[]=$details["PROFILE_DETAILS"][$value];
				}
			}
			if(is_array($details["LEADS"]) && count($details["LEADS"]))
			{
				foreach($details["LEADS"] as $key=>$value)
				{
					$allProfilesArray[]=$details["LEAD_DETAILS"][$value];
				}
			}
			if(is_array($allProfilesArray) && count($allProfilesArray))
			{
				$shortlistAvailable=1;
				$count=count($allProfilesArray);
			}
		}
		elseif($setSearch)
		{
			$count=getNumberOfSearchedProfiles($profileid,'SET',$setDate);
		}
		else
		{
			$count=$countArray[$profileid][$list];
			$contactArray=$contactsCountArray[$profileid];
			$leadArray=$leadsCountArray[$profileid];
		}
		if($count)
		{
			$profile_start=($j)*$PAGELEN-$PAGELEN;
			if($profile_start>=$count)
			{
				$j=1;
				$profile_start=0;
			}
			if($profile_start+11<$count)
				$profile_end=$profile_start+11;
			else
				$profile_end=$count-1;
			if($setSearch)
				$profilesArray=getSearchedProfiles($profileid,'SET',$setDate,1,$profile_start,$PAGELEN);
			elseif($categorySearch)
			{
				for($i=$profile_start;$i<=$profile_end;$i++)
					$profilesArray[]=$allProfilesArray[$i];
			}
			else
				$profilesArray=getList($profileid,$list,$contactArray,$leadArray,$username,$profile_start,$PAGELEN,1);
			display_resultProfiles($profilesArray,0,$profileid,$cid,$j,$count,$list,'');
			if($list=='SL')
			{
				if($shortlistAvailable)
					$shortlistedProfilesArray=$allProfilesArray;
				else
					$shortlistedProfilesArray=getList($profileid,'SL','','',$username);
				$details["self_profileid"]=$profileid;
				$details["ALL_MATCHES"]=$shortlistedProfilesArray;
				populateSearchBar($details);
			}
			pagination($j,$count,$PAGELEN,"");
			getButtons($list,$role);
		}
		if($list=='SL')
			$pageDetail["LEADS"][]=$value["LEAD_ID"];
                                                $pageDetail["LEAD_DETAILS"][$value["LEAD_ID"]]=$value;

		if($setSearch)
			$searchType='SET';
		elseif($categorySearch)
			$searchType='CAT';
		getTitle($list,$count,$searchType,$setDate,$username);	
		fetchLeftPanelLinks($role,$cid,$profileid,'',$list,$countArray);
		if($list=='DIS')
			fetchCourierSet($profileid);
		$curPage="ap_list.php?cid=$cid&profileid=$profileid&list=$list";
		$smarty->assign("CUR_PAGE",$curPage);
		$smarty->assign("cid",$cid);
		$smarty->assign("profileid",$profileid);
		$smarty->assign("list",$list);
		$smarty->assign("j",$j);
		if($setSearch)
		{
			$smarty->assign("setSearch",1);
			$smarty->assign("setDate",$setDate);
		}
		if($categorySearch)
			$smarty->assign("categorySearch",1);
	
		// Added for print
		$smarty->assign("MATCH_TYPE",$match_type);
                $smarty->assign("username",$username);
                $smarty->assign("role",$role);
                // End
		$smarty->display("ap_list.htm");
	}
	else
		die("Profile is not assigned to you");
}
else
{
	$msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
