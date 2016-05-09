<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/*****************************************************************************************************************************
Author : Sadaf Alam
Description : To crawl search results on competition sites based on listed priority communities
******************************************************************************************************************************/

include_once("CrawlerCommon.php");

$siteId=$_SERVER['argv'][1];
startProcess(1,$siteId);

$action='search';

global $errorReporting;
$debugCounter=0;

$communityArr=CrawlerPriorityCommunity::getCommunitiesForCrawlingSearch($siteId); //returns search criteria
$siteArr=CrawlerSite::getActiveSites($siteId); //get site to be crawled

if(is_array($siteArr) && is_array($communityArr))
{
	foreach($siteArr as $crawlerSiteObj)
	{
		$crawlerSiteObj->setActionSequence($action);
		$actionSequence=$crawlerSiteObj->getActionSequence();
		if(is_array($actionSequence))
		{
			foreach($communityArr as $crawlerPriorityCommunityObj)
			{
				$debugCounter++;
				$crawlerURLObjArr='';
				$searchGender='';
				$userGender='';
				$searchGender=$crawlerPriorityCommunityObj->getGENDER();		
				if($searchGender=='M')
					$userGender='F';
				else
					$userGender='M';
				foreach($actionSequence as $do)
				{
					$paid=0;
					$objectsRequired='';
					if($do=='login' || $do=='paid_login')
					{
						if($do=='paid_login')
							$paid=1;
						$crawlerUserObj=new CrawlerUser('',$crawlerSiteObj->getSITE_ID(),$action, $paid,$userGender); //get user to be used for crawling //order of arguments modified by prinka (BM)
						if(!$crawlerUserObj->getACCOUNT_ID())
						{
							$siteId=$crawlerSiteObj->getSITE_ID();
							$communityId=$crawlerPriorityCommunityObj->getCOMMUNITY_ID();
							$errorReporting["NO_USER"][$siteId][$communityId]=1;	
							break;
						}
					}
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
					$crawlerURLObjArr[]=$crawlerURLObj;
					unset($crawlerURLObj);
				}

				//var_dump($crawlerURLObjArr);
				if(is_array($crawlerURLObjArr))
				{
					$crawlerObj=new Crawler($crawlerSiteObj,$crawlerURLObjArr,$crawlerPriorityCommunityObj,$crawlerUserObj,$action);
					$done=$crawlerObj->crawl();
				}
//break; //added by prinka for testing purpose
			}
		}
	}
	if(is_array($errorReporting) && count($errorReporting))
                generateErrorReport($action);
}
endProcess(1,$siteId);
?>
