<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/*****************************************************************************************************************************
Author : Sadaf Alam
Description : To crawl detailview of profiles on competition sites
******************************************************************************************************************************/

include_once("CrawlerCommon.php");

$action='detail_view';
if(!$action)
	exit;

$siteId=$_SERVER['argv'][1];
startProcess(2,$siteId);

global $maxDetailViewsPerUser;
global $errorReporting;
global $debugCounter;

if(!$maxDetailViewsPerUser)
	$maxDetailViewsPerUser=40;

if($action=='detail_view')
	$profilesArr=CrawlerCompetitionProfile::getProfilesForCrawlingDetail($siteId);
$siteArr=CrawlerSite::getActiveSites($siteId);
if(is_array($profilesArr))
{
	$searchIdArr=array();
	foreach($profilesArr as $profile)
	{
		$searchId=$profile->getSEARCH_ID();
		if(!in_array($searchId,$searchIdArr))
			$searchIdArr[]=$profile->getSEARCH_ID();
	}
	$searchId_communityIdArr=CrawlerPriorityCommunity::getCommunityIdFromSearchId($searchIdArr);
	$communityIdArr=array();
	if(is_array($searchId_communityIdArr))
	{
		foreach($searchId_communityIdArr as $searchId=>$communityId)
		{
			if(!in_array($communityId,$communityIdArr))
				$communityIdArr[]=$communityId;
		}
		$communityArr=CrawlerPriorityCommunity::getCommunitiesFromId($communityIdArr);
		$count[$communityId]=0;
	}
	foreach($profilesArr as $profile)
	{
		$siteId=$profile->getSITE_ID();
		$searchId=$profile->getSEARCH_ID();
		$communityId=$searchId_communityIdArr[$searchId];
		if($communityId && $siteId)
		{
			/*$count=count($siteProfiles[$siteId][$communityId]);
			if($count<5)*/
			if($count[$communityId]<$maxDetailViewsPerUser)
			{
				$siteProfiles[$siteId][$communityId][]=$profile;
				$count[$communityId]=$count[$communityId]+1;
			}
		}	
	}
	unset($profilesArr);
}
else
{
	endProcess(2,$siteId);
	exit;
}
if(is_array($siteArr))
{
	foreach($siteArr as $crawlerSiteObj)
	{
		$crawlerSiteObj->setActionSequence($action);
		$actionSequence=$crawlerSiteObj->getActionSequence();
		if(is_array($actionSequence))
		{
			$siteId=$crawlerSiteObj->getSITE_ID();
			foreach($communityArr as $communityId=>$crawlerPriorityCommunityObj)
			{
				/*$userGender='';
				if($profileGender=='M')
					$userGender='F';
				elseif($profileGender=='F')
					$userGender='M';*/
				$i=0;
				$noUser='';
				while($i<count($siteProfiles[$siteId][$communityId]) && !$noUser)
				{
					$crawlerURLObjArr=array();
					$crawlerCompetitionProfileArr=array();
					$debugCounter++;
					foreach($actionSequence as $do)
					{
						$paid=0;
						if($do=='login' || $do=='paid_login')
						{
							if($do=='paid_login')
								$paid=1;
							$religion=$crawlerPriorityCommunityObj->getRELIGION();
							$mtongue=$crawlerPriorityCommunityObj->getMTONGUE();
							$profilesGender=$crawlerPriorityCommunityObj->getGENDER();
							if($profilesGender=='F')
								$userGender='M';
							elseif($profilesGender=='M')
								$userGender='F';
							$lage=$crawlerPriorityCommunityObj->getLAGE();
							$hage=$crawlerPriorityCommunityObj->getHAGE();
							$ageArr=getUserAge($lage,$hage,$profilesGender);
							$crawlerUserObj=new CrawlerUser('',$crawlerSiteObj->getSITE_ID(),$action,$paid,$userGender,$religion,$mtongue,$ageArr["LAGE"],$ageArr["HAGE"]);
							$accountId=$crawlerUserObj->getACCOUNT_ID();
							if(!$accountId)
							{
								$siteId=$crawlerSiteObj->getSITE_ID();
								$errorReporting["NO_USER"][$siteId][]=$communityId;
								$noUser=1;
								break;
							}
						}
						if($do=='detail_view')
							$userViewed=0;
						do
						{
							if($do=='detail_view')
							{
								$crawlerCompetitionProfileObj=$siteProfiles[$siteId][$communityId][$i];
								$index=$crawlerCompetitionProfileObj->getCOMPETITION_ID();
							}
							else
								unset($index);
							$crawlerURLObj=new CrawlerURL($crawlerSiteObj->getSITE_ID(),$do);
							$parameters=$crawlerURLObj->getURLParameters();
							if(is_array($parameters))
							{
								foreach($parameters as $parameter)
								{
									$objectName='';
									if(!$parameter["VALUE"] && $parameter["PARENT_CLASS"])
									{
										$objectName=$parameter["PARENT_CLASS"]."Obj";
										$objectsRequired[$objectName]=$$objectName;
									}
								}
							}
							$crawlerURLObj->setURLParametersValues($objectsRequired);
							$crawlerURLObj->formCrawlURL();
							if($index)
								$crawlerURLObjArr[$index]=$crawlerURLObj;
							else
								$crawlerURLObjArr[]=$crawlerURLObj;
							unset($crawlerURLObj);
							if($do=='detail_view')
							{
								$userViewed++;
								$i++;
							}
						}
						while($do=='detail_view' && $userViewed<$maxDetailViewsPerUser && $i<count($siteProfiles[$siteId][$communityId]));
					}//end of foreach for action sequence

					if(is_array($crawlerURLObjArr))
					{
						$crawlerObj=new Crawler($crawlerSiteObj,$crawlerURLObjArr,'',$crawlerUserObj,$action);
						$done=$crawlerObj->crawl();
						unset($crawlerURLObj);
					}

				}//end of while loop for count of profiles
			}//end of community loop
		}//end of if for action sequence
		else
			echo "no action sequence for site ".$crawlerSiteObj->getSITE_ID();
	}//end of site loop
	if(is_array($errorReporting) && count($errorReporting))
		generateErrorReport($action);
}
endProcess(2,$siteId);
?>
