<?php

/**
  * This file contains functions to find out profiles to be shown in the similar profiles section on detailed profile page.
  * Author : Prinka Wadhwa
**/

include_once "connect.inc";
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once("arrays.php");
include_once("sphinxclusterGlobalarrays.inc");
include_once("search.inc" );
include_once("sphinx_search_function.php");
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
include_once("SymfonySearchFunctions.class.php");
include_once("similarProfilesConfig.php");
include_once(JsConstants::$docRoot."/classes/Memcache.class.php");

global $suggAlgoScoreConst, $suggAlgoNoOfResults, $suggAlgoMinimumNoOfContactsRequired;

$db=connect_db();
$db_2 = connect_737();

/**
  * This function is used to get profileids of similar profiles when a logged-in user views a profile.
  * @param: $viewer - logged-in user who is viewing a profile
  * @param: $viewed - user whose profile is being viewed
  * @param: $viewerGender - gender of the logged-in user who is viewing a profile
  * @param: $viewedGender - gender of the user whose profile is being viewed
  * @return: $final_scores - array of profiles that are similar to the viewed profile
**/
function  getSimilarProfilesForLoggedInCase($viewer,$viewed,$viewedGender,$db, $similarType = "")
{
        global $suggAlgoScoreConst,$suggAlgoNoOfResultsForEOI,$suggAlgoNoOfResults,$suggAlgoNoOfResultsNoOfPages,$suggAlgoMinimumNoOfContactsRequired,$activeServers,$ajax_error,$suggProfAlgo,$db_2;
	
        /**
         * Memchache will store 54 Similar profile IDs in case of EOI confirmation page for a user clicked EOI on a user
         */
        $memcacheObj = new UserMemcache;
        
        if($similarType=="EOI")
                $numberResult=$suggAlgoNoOfResultsForEOI*$suggAlgoNoOfResultsNoOfPages;
        else
                $numberResult=0;
        $memcacheKey.=$viewer . "_" . $viewed . "_" . $numberResult;//viewer id & viewed id as KEY and number of result
        if ($numberResult != 0 && $memcacheObj->getDataFromMem($memcacheKey)) {  //viewer id & viewed id as KEY and number of result 
                $results = $memcacheObj->getDataFromMem($memcacheKey);
                return $results;
        } 
        else {
	
        if ($numberResult != 0)
                        $suggAlgoNoOfResults = $numberResult;
        $databaseName = 'viewSimilar';
	$serverId = $viewer%3;
        if($viewedGender == 'MALE')
		$viewedOppositeGender = 'FEMALE';
	elseif($viewedGender == 'FEMALE')
		$viewedOppositeGender = 'MALE';

	$mysqlObj=new Mysql;
	$myDbName=$activeServers[$serverId];
	$myDb=$mysqlObj->connect("$myDbName");

	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$myDb);

	mysql_select_db("$databaseName",$myDb);


	$sql = "SELECT SQL_CACHE RECEIVER FROM $databaseName.CONTACTS_CACHE_LEVEL1_$viewedGender WHERE SENDER=$viewed ";
	$res=mysql_query($sql,$db_2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db_2);
	while($row=mysql_fetch_assoc($res))
	{
		$contacts_viewed[] = $row['RECEIVER'];
	}

	if(sizeof($contacts_viewed)>=$suggAlgoMinimumNoOfContactsRequired)
	{
		trackSimilarProfilesAlgo('contactsAlgo',$db);
		$suggProfAlgo='contacts';
		$viewedContactsStr = implode(",",$contacts_viewed);
		$contacts1 = getResultSet("RECEIVER",$viewer);
		if(is_array($contacts1))
		{
			foreach($contacts1 as $values)
			{
				$contacts_viewer[$values['RECEIVER']]=1;
			}
		}

		//$contacts2 = getResultSet("SENDER,TYPE",$viewer);
		$contacts2 = getResultSet("SENDER,TYPE","","",$viewer);
		if(is_array($contacts2))
		{
			foreach($contacts2 as $values)
			{
				if($values['TYPE'] == 'I')
					$contacts_viewer[$values['SENDER']]=2;
				else
					$contacts_viewer[$values['SENDER']]=1;
			}
		}

		$sql = "SELECT SQL_CACHE SENDER,RECEIVER,CONSTANT_VALUE FROM $databaseName.CONTACTS_CACHE_LEVEL2_$viewedOppositeGender WHERE SENDER IN ($viewedContactsStr)";
		$res=mysql_query($sql,$db_2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db_2);
		while($row=mysql_fetch_assoc($res))
		{
			$suggestedProf[$row['SENDER']][] = $row['RECEIVER'];
			$constantVal[$row['SENDER']][] = $row['CONSTANT_VALUE'];
		}

		foreach($contacts_viewed as $key=>$val)
		{
			unset($intersect1);
			if(is_array($suggestedProf[$val]) && is_array($contacts_viewer))
			{
				foreach($suggestedProf[$val] as $prof)
					$intersect1[$prof]=1;
				/**
				  * There is a bug in the function array_intersect_key till php version 5.2.4
				  * So using a user defined function common_keys till we upgrade to php version 5.3 on LIVE
				  * The time taken to execute array_intersect key v/s common_keys is (5sec v/s 0.17sec)
				  * http://amiest-devblog.blogspot.com/2008/09/arrayintersectkey-is-terrible.html 
				**/
//				$inter = sizeof(common_keys($intersect1,$contacts_viewer));
				$inter = sizeof(array_intersect_key($intersect1,$contacts_viewer));
			}
			else
			{
				$inter = 0;
			}
			$score_num = $suggAlgoScoreConst + $inter;
			$score_den = sizeof($contacts_viewer) + sizeof($suggestedProf[$val]) - $score_num + $suggAlgoScoreConst;
			$score_den = sqrt($score_den);

			if($score_den != 0)
				$score_viewed[$val] = $score_num / $score_den;
		}

		$ignoredList = getIgnoredProfiles($viewer,$db,1); //function moved to connect_functions.inc

		if(is_array($suggestedProf))
		{
			foreach($suggestedProf as $key=>$value)
			{
				foreach($value as $k=>$v)
				{
					if($contacts_viewer[$v]!=1 && $ignoredList[$v]!=1)
						$scores[$v] = 0;	
				}
			}

			foreach($suggestedProf as $key=>$value)
			{
				foreach($value as $k=>$v)
				{
					if($contacts_viewer[$v]!=1 && $ignoredList[$v]!=1)
					{
						$score = $constantVal[$key][$k]*$score_viewed[$key];
						$scores[$v] += $score;	
					}
				}
			}

			arsort($scores);

			$i=0;
			foreach($scores as $s=>$x)
			{
				if($i++ < $suggAlgoNoOfResults)
					$final_scores[]=$s;
				else
					break;
			}
		} 
		if(sizeof($final_scores) == 0)
			trackContactsAlgoZeroResults($viewer,$viewed,$db);
	}
	else
	{ 
		trackSimilarProfilesAlgo('loggedInSearch',$db);
		global $suggAlgoViewerProfileid,$suggProfAlgo;
		$suggProfAlgo='search';
		$loggedIn=1;
		$includeCaste=2;
		$includeAwaitingContacts=1;
		$suggAlgoViewerProfileid = $viewer;
		$final_scores=getSimilarProfilesFromSearch($loggedIn,$viewed,$viewedGender,$db,$includeCaste,$includeAwaitingContacts, "EOI");
	}
	          if ($numberResult != 0) {
                        global $suggAlgoTimeToStoreResultsInMemcache; 
                        $memcacheObj->setDataToMem($final_scores, $memcacheKey, $suggAlgoTimeToStoreResultsInMemcache); 
                }
                
                return $final_scores;
        }
}

/**
  * This function is used to get profileids of similar profiles when a logged-out user views a profile.
  * @param: $viewed - user whose profile is being viewed
  * @param: $viewedGender - gender of the user whose profile is being viewed
  * @param: $includeCaste - this has value '1' whenever the user has come after specifying caste in the search parameters
  * @param: $db - database object
  * @return: $results - array of profiles that are similar to the viewed profile
**/
function getSimilarProfilesForLoggedOutCase($viewed,$viewedGender,$includeCaste,$db)
{
	global $ajax_error,$suggProfAlgo;
	$ajax_error=2;
	$suggProfAlgo='search';
	$loggedIn=2;
	$includeAwaitingContacts = 0;
	$results = getSimilarProfilesFromSearch($loggedIn,$viewed,$viewedGender,$db,$includeCaste,$includeAwaitingContacts);
	trackSimilarProfilesAlgo('loggedOutSearch',$db);

	return $results;
}

/**
  * This function is used to query and get profileids of similar profiles when a logged-in user views a profile 
  * but the viewed profile has less than 10 contacts (minimum no of contacts required) in $databaseName.CONTACTS_CACHE_LEVEL1_MALE/FEMALE
  * or when a logged-out user is viewing a profile.
  * @param: $loggedIn - has value '1' when the user is logged in or when ISEARCH (cookie) is set and has value '2' when the user is logged out
  * @param: $viewed - user whose profile is being viewed
  * @param: $viewedGender - gender of the user whose profile is being viewed
  * @param: $db - database object
  * @param: $includeCaste - this has value '1' whenever the user has come after specifying caste in the search parameters
  * @return: $results - array of profiles that are similar to the viewed profile
**/
function getSimilarProfilesFromSearch($loggedIn,$viewed,$viewedGender,$db,$includeCaste,$include_awaiting_contacts, $similarType = "")
{
	//global $AGE_GROUP_SUGG_ALGO,$ajax_error,$ALL_HINDI_MTONGUES,$ALL_MARRIED_MSTATUS;
	include(JsConstants::$docRoot."/profile/arrays.php");
	$limitedSearchResults=16;
	
	$sql = "SELECT AGE,GENDER,RELIGION,MTONGUE,MSTATUS,CASTE,INCOME FROM newjs.JPROFILE WHERE PROFILEID=$viewed";
	$res=mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
	if($row=mysql_fetch_assoc($res))
	{
		if($includeCaste == 2)//from logged in case
		{
			if($row['MSTATUS']!='N')
			{
				$includeCaste=0; //dont consider caste for profiles whose MSTATUS != 'N'
			}
		}


		if(in_array($row['MTONGUE'],$ALL_HINDI_MTONGUES))
		{
			$result['MTONGUE']=$ALL_HINDI_MTONGUES; //if mtongue belongs to any hindi community, then change mtongue value to 'all-hindi'
		}
		else
			$result['MTONGUE'][]=$row['MTONGUE']; 

		$result['RELIGION'][]=$row['RELIGION'];

		if($row['GENDER']=='M')
			$age=$AGE_GROUP_SUGG_ALGO["MALE"][$row['AGE']];
		if($row['GENDER']=='F')
			$age=$AGE_GROUP_SUGG_ALGO["FEMALE"][$row['AGE']];

		if(!$age)
		{
			if($age>=36 && $row['GENDER']=='M')
				$result['AGE']=$AGE_GROUP_SUGG_ALGO["MALE"]['MAX'];
			elseif($age>=34 && $row['GENDER']=='F')
				$result['AGE']=$AGE_GROUP_SUGG_ALGO["FEMALE"]['MAX'];
		}
		$age = explode(",",$age);

		$result['LAGE']=$age[0];
		$result['HAGE']=$age[1];

		if($row['MSTATUS']=='N')
			$result['MSTATUS'][]='N';
		else	
			$result['MSTATUS']=$ALL_MARRIED_MSTATUS;

		if(($includeCaste == 1) || ($loggedIn == 1 && $includeCaste == 2 && $result['MSTATUS'][0]=='N'))
		{
			if(is_part_of_a_group($row['CASTE']) == 1)
				$result['CASTE']=explode(",",show_group_members($row['CASTE']));
			else
				$result['CASTE'][]=$row['CASTE'];
		}
		else
			$result['CASTE'][]='0';

		//refer code below: done in order to pass income as a global variable cz passing in the function will get it considered as a hard filter whereas this has to be considered as just a sorting variable
		global $suggAlgoIncomeFilter;
		if($row['GENDER']=='M')
			$suggAlgoIncomeFilter=$row['INCOME'];
		elseif($row['GENDER']=='F')
			$suggAlgoIncomeFilter='';

		if($viewedGender == 'MALE')
			$genderVal = 'M';
		elseif($viewedGender == 'FEMALE')
			$genderVal = 'F';

		$memcacheObj=new UserMemcache;

		$memcacheKey = $genderVal."_".$result['RELIGION'][0]."_".implode("*",$result['CASTE'])."_".implode("*",$result['MTONGUE'])."_".$result['LAGE']."_".$result['HAGE']."_".implode("*",$result['MSTATUS']);
		if($row['GENDER']=='M')
			$memcacheKey.="_".$row['INCOME'];

		if($loggedIn == 2 && $memcacheObj->getDataFromMem($memcacheKey))  //gender_religion_caste_mtongue_lage_hage_mstatus
		{
			$results = $memcacheObj->getDataFromMem($memcacheKey);
			$results = explode(",",$results);
			return $results;
		}
		else
		{
			global $viewSimilarFromProfilePage,$limitedSearchResults,$suggAlgoNoOfResultsForEOI,$suggAlgoNoOfResultsNoOfPages,$skipClusters,$skipRelaxation, $suggAlgoNoOfResultsToBeFetched, $suggAlgoIncludeAwaitingContacts,$suggAlgoLoginStatus;

                         if($similarType=="EOI")
                                $suggAlgoNoOfResultsToBeFetched = $suggAlgoNoOfResultsForEOI*$suggAlgoNoOfResultsNoOfPages;
                        $skipClusters=1;
			$limitedSearchResults=$suggAlgoNoOfResultsToBeFetched;
			$skipRelaxation=1;
			$viewSimilarFromProfilePage=1;
			$suggAlgoIncludeAwaitingContacts=$include_awaiting_contacts;
			$suggAlgoLoginStatus=$loggedIn;
			$db = connect_db();
			$results=SymfonySearchFunctions::suggestedAlgoSearch($genderVal,$result['RELIGION'],$result['CASTE'],$result['MTONGUE'],$result['LAGE'],$result['HAGE'],'Y','',$result['MSTATUS'], $similarType, $limitedSearchResults);
			if($loggedIn == 2)
			{
				global $suggAlgoTimeToStoreResultsInMemcache;
				$memcacheObj->setDataToMem($results,$memcacheKey,$suggAlgoTimeToStoreResultsInMemcache);
			}
		}

		if($results)
			$results = explode(",",$results);

		return $results;
	}
}

/**
  * This function is used to get details and thumbnails of profileids passed.
  * These details are displayed in the similar profile section on view profile page.
  * @param: $similarProfileids - array of profileids whose details are to be found.
  * @return: $displayContent -  a string containing all the required details and thumbnail urls. 
**/
function getSimilarProfilesDetails($similarProfileids,$db,$loggedIn,$shuffleResults,$viewer='')
{
	global $HEIGHT_DROP, $CITY_DROP, $CITY_INDIA_DROP, $MTONGUE_DROP_SMALL, $CASTE_DROP, $CASTE_DROP_SMALL, $EDUCATION_LEVEL_NEW_DROP, $INCOME_NEW_SUGG_ALGO, $suggAlgoMaxLengthOfEachField,$ajax_error;
	$ajax_error=2;

	$sql = "SELECT PROFILEID,USERNAME,GENDER,AGE,HEIGHT,CITY_RES,MTONGUE,RELIGION,CASTE,EDU_LEVEL_NEW,INCOME,HAVEPHOTO,PRIVACY,PHOTO_DISPLAY FROM newjs.JPROFILE WHERE PROFILEID IN ($similarProfileids) ";

	if($shuffleResults == 1)
		$sql.=" ORDER BY RAND() ";
	else
		$sql.=" ORDER BY FIELD(PROFILEID,$similarProfileids) ";

	$res=mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$mydb);
	while($row=mysql_fetch_assoc($res))
	{
		$initialOrdering[]=$row['PROFILEID'];
		$results[$row['PROFILEID']]['AGE']=$row['AGE'];
		$results[$row['PROFILEID']]['HEIGHT']=$HEIGHT_DROP[$row['HEIGHT']];

		$results[$row['PROFILEID']]['CITY_RES']=$CITY_DROP[$row['CITY_RES']];
		if(!$results[$row['PROFILEID']]['CITY_RES'])
		{
			$results[$row['PROFILEID']]['CITY_RES']=$CITY_INDIA_DROP[$row['CITY_RES']];
		}

		$results[$row['PROFILEID']]['MTONGUE']=$MTONGUE_DROP_SMALL[$row['MTONGUE']];

		if($row['RELIGION'] == '5' || $row['RELIGION'] == '6' || $row['RELIGION'] == '7') //for buddhist, parsi, jewish - show the full caste
		{
			$results[$row['PROFILEID']]['CASTE']=$CASTE_DROP[$row['CASTE']];
		}
		else
		{
			$results[$row['PROFILEID']]['CASTE']=$CASTE_DROP_SMALL[$row['CASTE']];
			if($results[$row['PROFILEID']]['CASTE']=='Others') //for caste = others, show full caste
			{
				$results[$row['PROFILEID']]['CASTE']=$CASTE_DROP[$row['CASTE']];
			}
		}

		$results[$row['PROFILEID']]['EDU_LEVEL']=$EDUCATION_LEVEL_NEW_DROP[$row['EDU_LEVEL_NEW']];

		$results[$row['PROFILEID']]['INCOME']=$INCOME_NEW_SUGG_ALGO[$row['INCOME']];

		$info[$row['PROFILEID']]['GENDER']=$row['GENDER'];
		$info[$row['PROFILEID']]['HAVEPHOTO']=$row['HAVEPHOTO'];
		$info[$row['PROFILEID']]['PRIVACY']=$row['PRIVACY'];
		$info[$row['PROFILEID']]['PHOTO_DISPLAY']=$row['PHOTO_DISPLAY'];

//		$checksum = md5($row['PROFILEID']).'i'.$row['PROFILEID'];
//		$profileUrls[$row['PROFILEID']]="$SITE_URL/profile/viewprofile.php?profilechecksum=".$checksum;
	}

	//start: added for trac 1022 - prioritizing photo profiles among the profiles returned from the similar profiles algo
	foreach($initialOrdering as $id)
	{
		if($info[$id]['PRIVACY']=='F' && $loggedIn == 1 && check_privacy_filtered1($viewer,$id))
		{
			$finalIds['FILTERED'][]=$id;	
		}
		elseif($info[$id]['HAVEPHOTO']=='N' || $info[$id]['HAVEPHOTO']=='' || $info[$id]['HAVEPHOTO']=='U')
		{
			$finalIds['NO_PHOTO'][]=$id;
		}
		elseif($info[$id]['PHOTO_DISPLAY']=='C')
		{
			if($loggedIn==0)
			{
				$finalIds['PHOTO_NOT_VISIBLE'][]=$id;
			}
			elseif($loggedIn == 1)
			{
				$contact_status_new = get_contact_status_dp($id,$viewer);

				if($contact_status_new["R_TYPE"])
					$contact_status = $contact_status_new["R_TYPE"];
				else
					$contact_status = $contact_status_new["TYPE"];

				if(in_array($contact_status,array('I','A','RA')))
				{
					$finalIds['PHOTO_VISIBLE'][]=$id;
				}
				else
				{
					$finalIds['PHOTO_NOT_VISIBLE'][]=$id;
				}
			}
		}
		elseif(($info[$id]['PRIVACY']=='R' || $info[$id]['PRIVACY']=='F') && $loggedIn == 0)
		{
			$finalIds['PHOTO_NOT_VISIBLE'][]=$id;
		}
		elseif($info[$id]['PHOTO_DISPLAY']=='A')
		{
			$finalIds['PHOTO_VISIBLE'][]=$id;
		}
	}
	$order = array('PHOTO_VISIBLE','PHOTO_NOT_VISIBLE','NO_PHOTO','FILTERED');
	foreach($order as $orderVal)
	{
		if(is_array($finalIds[$orderVal]))
		{
			foreach($finalIds[$orderVal] as $idValue)
			{
				$orderedProfileids[]=$idValue;
			}
		}
	}
//print_r($initialOrdering);
//print_r($finalIds);
//print_r($orderedProfileids);
	//end: added for trac 1022 - prioritizing photo profiles among the profiles returned from the similar profiles algo

	foreach($orderedProfileids as $profileid)
	{
		$details = $results[$profileid];

		$checksum = md5($profileid).'i'.$profileid;
		$profileUrls[$profileid]="$SITE_URL/profile/viewprofile.php?profilechecksum=".$checksum;

		if($details['AGE'])
		{
			$similarProfiles[$profileid]=$details['AGE'] . " yrs ";
		}

		$similarProfiles = convertToDisplayFormat($similarProfiles,$profileid,$details['HEIGHT'],'(',$suggAlgoMaxLengthOfEachField,$details['AGE'],NULL);

		$similarProfiles = convertToDisplayFormat($similarProfiles,$profileid,$details['CITY_RES'],'/',$suggAlgoMaxLengthOfEachField,$details['AGE'],$details['HEIGHT']);

		$similarProfiles[$profileid].="<br>";

		$similarProfiles = convertToDisplayFormat($similarProfiles,$profileid,$details['MTONGUE'],'/',$suggAlgoMaxLengthOfEachField,NULL,NULL);

		$caste = trim($details['CASTE'],'-');
		$similarProfiles = convertToDisplayFormat($similarProfiles,$profileid,$caste,'/',$suggAlgoMaxLengthOfEachField,$details['MTONGUE'],NULL);

		$similarProfiles[$profileid].="<br>";

		$similarProfiles = convertToDisplayFormat($similarProfiles,$profileid,$details['EDU_LEVEL'],'/',$suggAlgoMaxLengthOfEachField,NULL,NULL);

		$similarProfiles = convertToDisplayFormat($similarProfiles,$profileid,$details['INCOME'],'',$suggAlgoMaxLengthOfEachField,$details['EDU_LEVEL'],NULL);

		$similarProfiles[$profileid] = html_entity_decode($similarProfiles[$profileid]);
	}

	$displayContent = implode("--",$similarProfiles);
	$displayContent.="##";

	//$thumbnailUrls = SymfonyPictureFunctions :: getPhotoUrls_nonSymfonyViewSimilar($similarProfileids,'ThumbailUrl',$db2);
	$thumbnailUrls = SymfonyPictureFunctions :: getPhotoUrls_nonSymfony($similarProfileids,'COALESCE( NULLIF( ProfilePic120Url,"") , ThumbailUrl ) as ThumbailUrl',$db2);

	foreach($similarProfiles as $key=>$value) //done in order to take care of the profiles where a thumbnail url isnt returned.
	{
		if($info[$key]['HAVEPHOTO']=='Y' && $loggedIn == 0 && ($info[$key]['PRIVACY']=='R' || $info[$key]['PRIVACY']=='F' || $info[$key]['PHOTO_DISPLAY']=='C'))
		{
			if($info[$key]['GENDER']=='M')
			{
				$displayContent.="$IMG_URL/profile/ser4_images/login_to_view_photo_sm_b.gif"."--";
			}
			elseif($info[$key]['GENDER']=='F')
			{
				$displayContent.="$IMG_URL/profile/ser4_images/login_to_view_photo_sm_g.gif"."--";
			}
			$profileUrls[$key].="&viewSimilar=1";
		}
		elseif($info[$key]['HAVEPHOTO']=='Y' && $loggedIn == 1 && $info[$key]['PRIVACY']=='F' && check_privacy_filtered1($viewer,$key))
		{
			if($info[$key]['GENDER']=='M')
			{
				$displayContent.="$IMG_URL/profile/ser4_images/pro_fil_sm_b.gif"."--";
			}
			elseif($info[$key]['GENDER']=='F')
			{
				$displayContent.="$IMG_URL/profile/ser4_images/pro_fil_sm_g.gif"."--";
			}
			$profileUrls[$key].="&viewSimilar=1";
		}
		elseif($info[$key]['HAVEPHOTO']=='Y' && $loggedIn == 1 && $info[$key]['PHOTO_DISPLAY']=='C')
		{
			$contact_status_new = get_contact_status_dp($key,$viewer);

			if($contact_status_new["R_TYPE"])
				$contact_status = $contact_status_new["R_TYPE"];
			else
				$contact_status = $contact_status_new["TYPE"];

			if(in_array($contact_status,array('I','A','RA')))
			{
				$displayContent.=$thumbnailUrls[$key]['ThumbailUrl']."--";
			}
			else
			{
				if($info[$key]['GENDER']=='M')
				{
					$displayContent.="$IMG_URL/profile/ser4_images/photo_vis_if_con_acc_sm_b.gif"."--";
				}
				elseif($info[$key]['GENDER']=='F')
				{
					$displayContent.="$IMG_URL/profile/ser4_images/photo_vis_if_con_acc_sm_g.gif"."--";
				}
				$profileUrls[$key].="&viewSimilar=1";
			}
		}
		else
		{
			if($thumbnailUrls[$key]['ThumbailUrl'] && $info[$key]['HAVEPHOTO']=='Y')
			{
				$displayContent.=$thumbnailUrls[$key]['ThumbailUrl']."--";
			}
			elseif($info[$key]['GENDER']=='M')
			{
				if($info[$key]['HAVEPHOTO']=='N' || $info[$key]['HAVEPHOTO']=='')
				{
					$displayContent.="$IMG_URL/profile/images/ic_photo_notavailable_b_60.gif"."--";
				}
				elseif($info[$key]['HAVEPHOTO']=='U')
				{
					$displayContent.="$IMG_URL/profile/images/ph_cmgsoon_sm_b.gif"."--";
				}
				else
				{
					$displayContent.="$IMG_URL/profile/images/ic_photo_notavailable_b_60.gif"."--";
				}
			}
			elseif($info[$key]['GENDER']=='F')
			{
				if($info[$key]['HAVEPHOTO']=='N' || $info[$key]['HAVEPHOTO']=='')
				{
					$displayContent.="$IMG_URL/profile/images/ic_photo_notavailable_g_60.gif"."--";
				}
				elseif($info[$key]['HAVEPHOTO']=='U')
				{
					$displayContent.="$IMG_URL/profile/images/ph_cmgsoon_sm_g.gif"."--";
				}
				else
				{
					$displayContent.="$IMG_URL/profile/images/ic_photo_notavailable_g_60.gif"."--";
				}
			}
		}
	}

	$displayContent.="##";
	$displayContent.= implode("--",$profileUrls);
	$displayContent=str_ireplace("####","",$displayContent);
	
	return $displayContent;
}

/**
  * This function finds out intersection of keys of 2 arrays.
  * There is a bug in the function array_intersect_key till php version 5.2.4
  * So using a user defined function common_keys till we upgrade to php version 5.3 on LIVE
  * The time taken to execute array_intersect key v/s common_keys is (5sec v/s 0.17sec)
  * http://amiest-devblog.blogspot.com/2008/09/arrayintersectkey-is-terrible.html 
**/
function common_keys($a, $b) 
{
	$res = array();
	foreach ($a as $key => $val) 
	{
		if (isset($b[$key]))
			$res[] = $key;
	}
	return $res;
}

/**
  * This function returns 1 of a search including caste as a parameter else it returns NULL
  * @param - $searchId - id of the search for which the above result is to be fetched
  * @param - $db - database connection
**/
function checkIfCasteSpecified($searchId,$db)
{
	global $ajax_error;
	$ajax_error=2;

	$sql = "SELECT COUNT(*) AS CNT FROM newjs.SEARCHQUERY WHERE ID=$searchId AND CASTE<>''";
	$res=mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
	while($row=mysql_fetch_assoc($res))
	{
		if($row['CNT'])
		{
			$includeCaste=1;
		}
	}
	return $includeCaste;
}

/**
  * This function is used to insert an entry into the table MIS.SIMILAR_PROFILES_ZERO_RESULTS whenever the similar profiles algo returns zero results.
  * @param - $loginStatus - check whether the user is loggedIn/loggedOut/loggenInThroughCookie
  * @param - $viewer - user whose viewing a profile
  * @param - $viewed - user whose profile is being viewed
**/
function trackZeroResults($loginStatus,$viewer,$viewed,$db)
{
	global $ajax_error;
	$ajax_error=2;

	$sql = "INSERT IGNORE INTO MIS.SIMILAR_PROFILES_ZERO_RESULTS VALUES('$loginStatus','$viewer','$viewed',CURDATE())";
	mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
	//mysql_query($sql,$db) or die("\n\n".mysql_error($db) . "error in $sql");
}

/**
  * This function is used to track the no of hits sent to each similar profiles algo in 1 day.
  * @param - $algo - name of the algo
  * @param - $db - database connection
**/
function trackSimilarProfilesAlgo($algo,$db)
{
	global $ajax_error;
	$ajax_error=2;

	$sql = "UPDATE MIS.TRACK_SIMILAR_PROFILES_ALGO SET COUNT=COUNT+1 WHERE SIMILAR_PROFILE_ALGO='$algo' AND DATE=CURDATE()";
	mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
	if(mysql_affected_rows($db)==0)
	{
		$sql2 = "INSERT INTO MIS.TRACK_SIMILAR_PROFILES_ALGO VALUES('$algo',1,CURDATE())";
		mysql_query($sql2,$db) or die("\n\n".mysql_error($db) . "error in $sql2");
	}
}

/**
  * This function manipulates a value and returns it in the form in which it is to be displayed in the similar profiles section.

  * @param - $similarProfiles - array - contains the values in the same form as they are to be displayed
  * @param - $profileid - profileid for which the value is manipulated
  * @param - $detailVal - value which has to be manipulated
  * @param - $explodeChar - char by which the string is to be split
  * @param - $suggAlgoMaxLengthOfEachField - max length of the string to be returned
  * @return - $similarProfiles array - contains the values in the same form as they are to be displayed
**/
function convertToDisplayFormat($similarProfiles,$profileid,$detailVal,$explodeChar,$suggAlgoMaxLengthOfEachField,$displayField1=NULL,$displayField2=NULL)
{
	if($detailVal)
	{
		if($explodeChar == '/')
		{
			$displayVal = explode("/",$detailVal);
			if(strlen($displayVal[0]) > $suggAlgoMaxLengthOfEachField)
			{
				$displayVal[0]=substr($displayVal[0],0,$suggAlgoMaxLengthOfEachField - 3)."...";
			}
		}
		elseif($explodeChar == '(')
		{
			$displayVal = explode("(",$detailVal);
		}
		else
		{
			$displayVal[0] = $detailVal;
		}
		if($displayField1 || $displayField2)
		{
			$similarProfiles[$profileid].=",".$displayVal[0];
		}
		else
		{
			$similarProfiles[$profileid].=$displayVal[0];
		}
	}

	return $similarProfiles;
}

/**
  * This function is used to insert an entry into the table MIS.CONTACTS_ALGO_ZERO_RESULTS whenever the similar profiles algo returns zero results.
  * @param - $viewer - user whose viewing a profile
  * @param - $viewed - user whose profile is being viewed
  * @param - $db - database object
**/
function trackContactsAlgoZeroResults($viewer,$viewed,$db)
{
	global $ajax_error;
	$ajax_error=2;

	$sql = "INSERT INTO MIS.CONTACTS_ALGO_ZERO_RESULTS VALUES('$viewer','$viewed',CURDATE())";
	mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
}

?>
