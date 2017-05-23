<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/*****************************************************************************************************************************
Author : Sadaf Alam
Description : To crawl detailview of profiles on competition sites
******************************************************************************************************************************/

include_once("CrawlerCommon.php");

$action='contact_detail_view';
if(!$action)
	exit;

$siteId=$_SERVER['argv'][1];
startProcess(4,$siteId);


global $maxContactViewsPerUser;
global $errorReporting;
global $debugCounter;

if(!$maxContactViewsPerUser)
	$maxContactViewsPerUser=20;

if($action=='contact_detail_view')
	$profilesArr=CrawlerCompetitionProfile::getProfilesForCrawlingContactDetail($siteId);
$siteArr=CrawlerSite::getActiveSites($siteId);
if(is_array($profilesArr))
{
	$competitionIdArr=array();
	foreach($profilesArr as $profile)
		$competitionIdArr[]=$profile->getCOMPETITION_ID();
	$accountId_competitionIdArr=CrawlerCompetitionProfile::getAccountIdFromCompetitionId($competitionIdArr);
	$accountIdArr=array();
	if(is_array($accountId_competitionIdArr))
	{
		foreach($accountId_competitionIdArr as $competitionId=>$accountId)
		{
			if(!in_array($accountId,$accountIdArr))
				$accountIdArr[]=$accountId;
		}
		$accountArr=CrawlerUser::getUsersFromId($accountIdArr);
	}
	foreach($profilesArr as $profile)
	{
		$siteId=$profile->getSITE_ID();
		$competitionId=$profile->getCOMPETITION_ID();
		$accountId=$accountId_competitionIdArr[$competitionId];
		if($accountId && $siteId)
		{
			$count=count($siteProfiles[$siteId][$accountId]);
			if($count<$maxContactViewsPerUser)
				$siteProfiles[$siteId][$accountId][]=$profile;
		}	
	}
	unset($profilesArr);
}
else
{
	endProcess(4,$siteId);
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
			if(count($siteProfiles[$siteId]))
			{
				foreach($siteProfiles[$siteId] as $accountId=>$profiles)
				{
					/*$userGender='';
					if($profileGender=='M')
						$userGender='F';
					elseif($profileGender=='F')
						$userGender='M';*/
					$crawlerURLObjArr=array();
					$debugCounter++;
					foreach($actionSequence as $do)
					{
						$paid=0;
						if($do=='login' || $do=='paid_login')
						{
							if($do=='paid_login')
								$paid=1;
							$crawlerUserObj=$accountArr[$accountId];
							$accountId=$crawlerUserObj->getACCOUNT_ID();
							if(!$accountId)
							{
								$siteId=$crawlerSiteObj->getSITE_ID();
								$errorReporting["CHECK_USER"][$siteId][]=$accountId;
								break;
							}
							$userCanView=$crawlerUserObj->getNoOfCanViewContacts();
							if($userCanView<1)
							{
								$errorReporting["USER_CREDITS_OVER"][$siteId][]=$accountId;
								break;
							}
						}
						if($do=='detail_view' || $do=='contact_detail_view')
							$userViewed=0;
						$i=0;
						do
						{
							if($do=='detail_view' || $do=='contact_detail_view')
							{
								$crawlerCompetitionProfileObj=$siteProfiles[$siteId][$accountId][$i];
								$index=$crawlerCompetitionProfileObj->getCOMPETITION_ID();
							}
							else
								unset($index);
							$crawlerURLObj=new CrawlerURL($crawlerSiteObj->getSITE_ID(),$do,$crawlerUserObj->getBmCommunity());//community name required to be passed (for BM)
//							$crawlerURLObj=new CrawlerURL($crawlerSiteObj->getSITE_ID(),$do);
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
							if($do=='detail_view' || $do=='contact_detail_view')
							{
								$userViewed++;
								if($userViewed==$userCanView)
									$errorReporting["USER_CREDITS_OVER"][$siteId][]=$accountId;
								$i++;
							}
						}
						while($do=='contact_detail_view' && $userViewed<$userCanView && $i<count($siteProfiles[$siteId][$accountId]));
					}//end of foreach for action sequence
					if(is_array($crawlerURLObjArr))
					{
						$crawlerObj=new Crawler($crawlerSiteObj,$crawlerURLObjArr,'',$crawlerUserObj,$action);
						$done=$crawlerObj->crawl();
						unset($crawlerURLObj);
					}
				}//end of loop for one account
			}//end of if for checking if profiles exist for a site
		}//end of if for action sequence
		else
			echo "no action sequence for site ".$crawlerSiteObj->getSITE_ID();
	}//end of site loop
	if(is_array($errorReporting) && count($errorReporting))
		generateErrorReport($action);
}
endProcess(4,$siteId);
?>
