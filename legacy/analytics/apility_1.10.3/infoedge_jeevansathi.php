<?php
// it takes around 20 lakh units to get static data for all accouts 3 jeevansathi, 4 naukri , 2 99acres

include('apility.php');
include('connect_adwords_db.php');

ini_set(max_execution_time,0);
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
ini_set(default_socket_timeout,259200000);
ini_set(log_errors_max_len,0);

$time_ini = microtime_float();


$Developer_Token = "zT7sAKIbbXRFNZWe9uIBWA";
$Application_Token = "kIjlB_mJV4hfEQxlUe0S3A";

//$db=mysql_connect("localhost","root","") or die ('could not connect to database');
//$db=mysql_connect("localhost:/tmp/mysql.sock","user","CLDLRTa9") or die(mysql_error());

$tables_deleted=0;

//for($a=0;$a<count($Email);$a++)
for($a=0;$a<4;$a++)
{
	if($tables_deleted==0)
	{	
		$sql=" Truncate table $database[$a].Campaign ";
		mysql_query($sql) or die(mysql_error().$sql);
		
		$sql=" Truncate table $database[$a].AdGroup ";
		mysql_query($sql) or die(mysql_error().$sql);
		
		$sql=" Truncate table $database[$a].ImageAd ";
		mysql_query($sql) or die(mysql_error().$sql);
		
		$sql=" Truncate table $database[$a].KeywordCriterion ";
		mysql_query($sql) or die(mysql_error().$sql);
		
		$sql=" Truncate table $database[$a].TextAd ";
		mysql_query($sql) or die(mysql_error().$sql);
		
		$sql=" Truncate table $database[$a].WebsiteCriterion ";
		mysql_query($sql) or die(mysql_error().$sql);
		
		$tables_deleted=1;
	}
	
	$authenticationContext = new APIlityAuthentication($Email[$a],$Password[$a],$Developer_Token,$Client_Email[$a],$Application_Token);
	
	mysql_select_db($database[$a]);

	$allCampaigns = getAllCampaigns(); 

	foreach ($allCampaigns as $campaign) 
	{	
		//$campaign = createCampaignObject(6585896);	//----
		$startDate=explode('+',$campaign->startDate);
		$endDate=explode('+',$campaign->endDate);
		
		if( is_array($campaign->languages) && count($campaign->languages)>0)
			$languages=implode("#",$campaign->languages);
		
		if( is_array($campaign->geoTargets['countries']['countryTargets']['countries']) && count($campaign->geoTargets['countries']['countryTargets']['countries'])>0)
			$countries=implode("#",$campaign->geoTargets['countries']['countryTargets']['countries']);
		
		if( is_array($campaign->geoTargets['countries']['countryTargets']['regions']) && count($campaign->geoTargets['countries']['countryTargets']['regions'])>0)
			$regions=implode("#",$campaign->geoTargets['countries']['countryTargets']['regions']);
		
		if( is_array($campaign->geoTargets['countries']['countryTargets']['metros']) && count($campaign->geoTargets['countries']['countryTargets']['metros'])>0)
			$metros=implode("#",$campaign->geoTargets['countries']['countryTargets']['metros']);
		
		if( is_array($campaign->geoTargets['countries']['countryTargets']['cities']) && count($campaign->geoTargets['countries']['countryTargets']['cities'])>0)
			$cities=implode("#",$campaign->geoTargets['countries']['countryTargets']['cities']);
		
		if( is_array($campaign->geoTargets['countries']['countryTargets']['circles']) && count($campaign->geoTargets['countries']['countryTargets']['circles'])>0)
			$circles=implode("#",$campaign->geoTargets['countries']['countryTargets']['circles']);
		
		$networkTargeting=implode("#",$campaign->networkTargeting);
		
		for($i=0;$i<count($campaign->campaignNegativeKeywordCriteria);$i++)
		{
			$campaignNegativeKeywordCriteria.=$campaign->campaignNegativeKeywordCriteria[$i]['text']."^";
			$campaignNegativeKeywordCriteria.=$campaign->campaignNegativeKeywordCriteria[$i]['type']."#";
		}
		$campaignNegativeKeywordCriteria = substr($campaignNegativeKeywordCriteria, 0, strlen($campaignNegativeKeywordCriteria)-1);
		
		for($i=0;$i<count($campaign->campaignNegativeWebsiteCriteria);$i++)
		{
			$campaignNegativeWebsiteCriteria.=$campaign->campaignNegativeWebsiteCriteria[$i]['url']."#";
		}
		$campaignNegativeWebsiteCriteria = substr($campaignNegativeWebsiteCriteria, 0, strlen($campaignNegativeWebsiteCriteria)-1);

		$sql=" insert into $database[$a].Campaign (id,customerId,name,status,startDate,endDate,dailyBudget,languages,networkTargeting,countries,regions,metros,cities,circles,targetAll,adScheduling_status,budgetOptimizerSettings_bidCeiling,budgetOptimizerSettings_enabled,budgetOptimizerSettings_takeOnOptimizedBids,campaignNegativeKeywordCriteria,campaignNegativeWebsiteCriteria,isEnabledSeparateContentBids,isEnabledOptimizedAdServing) values ('".$campaign->id."' , '".$customerId[$a]."' , '".addslashes($campaign->name)."' , '".$campaign->status."' , '".$startDate[0]."' , '".$endDate[0]."' , '".$campaign->dailyBudget."' , '".addslashes($languages)."' , '".$networkTargeting."' , '".addslashes($countries)."' , '".addslashes($regions)."' , '".addslashes($metros)."' , '".addslashes($cities)."' , '".addslashes($circles)."' , '".$campaign->geoTargets['targetAll']."' , '".$campaign->adScheduling['status']."' , '".$campaign->budgetOptimizerSettings['bidCeiling']."' , '".$campaign->budgetOptimizerSettings['enabled']."' , '".$campaign->budgetOptimizerSettings['takeOnOptimizedBids']."' , '".addslashes($campaignNegativeKeywordCriteria)."' , '".addslashes($campaignNegativeWebsiteCriteria)."' , '".$campaign->isEnabledSeparateContentBids."' , '".$campaign->isEnabledOptimizedAdServing."') "; 
		mysql_query($sql) or die(mysql_error().$sql);
		
		unset($languages);	
		unset($networkTargeting);	
		unset($countries);	
		unset($regions);	
		unset($metros);	
		unset($cities);	
		unset($circles);	
		unset($campaignNegativeKeywordCriteria);	
		unset($campaignNegativeWebsiteCriteria);	


		//$allAdGroups = getAllAdGroups(6585896); //----------------	
		$allAdGroups = $campaign->getAllAdGroups();

		foreach ($allAdGroups as $adGroup)
		{
			//adding data to AdGroup table
			$sql=" insert into $database[$a].AdGroup (id,customerId,maxCpc,maxCpm,maxContentCpc,proxyMaxCpc,name,belongsToCampaignId,status) values ( '".$adGroup->id."' , '".$customerId[$a]."' , '".$adGroup->maxCpc."' , '".$adGroup->maxCpm."' , '".$adGroup->maxContentCpc."' , '".$adGroup->proxyMaxCpc."' , '".addslashes($adGroup->name)."' , '".$adGroup->belongsToCampaignId."' , '".$adGroup->status."') ";
			mysql_query($sql) or die(mysql_error().$sql);
				
			
			//$creatives = getAllAds(array(242452766)); //-----
			$creatives=$adGroup->getAllAds();	
			
			foreach ($creatives as $creative)
			{
				//adding data to Creative/ADs table
				if($creative->adType=='ImageAd')
					$sql=" insert into $database[$a].ImageAd (id,customerId,belongsToAdGroupId,belongsToCampaignId,status,isDisapproved,displayUrl,destinationUrl,name,width,height,imageUrl,thumbnailUrl,mimeType,type) values ( '".$creative->id."' , '".$customerId[$a]."' , '".$creative->belongsToAdGroupId."' , '".$campaign->id."' , '".$creative->status."' , '".$creative->isDisapproved."' , '".addslashes($creative->displayUrl)."' , '".addslashes($creative->destinationUrl)."' , '".addslashes($creative->image['name'])."' , '".$creative->image['width']."' , '".$creative->image['height']."' , '".addslashes($creative->image['imageUrl'])."' , '".addslashes($creative->image['thumbnailUrl'])."' , '".$creative->image['mimeType']."' , '".$creative->image['type']."' ) ";
				else
					$sql=" insert into $database[$a].TextAd (id,customerId,belongsToAdGroupId,belongsToCampaignId,status,isDisapproved,displayUrl,destinationUrl,headline,description1,description2) values ( '".$creative->id."' , '".$customerId[$a]."' , '".$creative->belongsToAdGroupId."' , '".$campaign->id."' ,  '".$creative->status."' , '".$creative->isDisapproved."' , '".addslashes($creative->displayUrl)."' , '".addslashes($creative->destinationUrl)."' , '".addslashes($creative->headline)."' , '".addslashes($creative->description1)."' , '".addslashes($creative->description2)."') ";
					
				mysql_query($sql) or die(mysql_error().$sql);
			}

			
			
			//$criterias = getAllCriteria(229393706);	//----	
			$criterias = $adGroup->getAllCriteria();
			
			foreach($criterias as $criteria)
			{
				//adding data to Criterion table
				if($criteria->criterionType=='Website')
					$sql=" insert into $database[$a].WebsiteCriterion (id,customerId,url,belongsToAdGroupId,belongsToCampaignId,criterionType,isNegative,isPaused,maxCpm,status,language,destinationUrl) values ( '".$criteria->id."' , '".$customerId[$a]."' , '".addslashes($criteria->url)."' , '".$criteria->belongsToAdGroupId."' , '".$campaign->id."' , '".$criteria->criterionType."' , '".$criteria->isNegative."' , '".$criteria->isPaused."' , '".$criteria->maxCpm."' , '".$criteria->status."' , '".$criteria->language."' , '".addslashes($criteria->destinationUrl)."') ";
				else
					$sql=" insert into $database[$a].KeywordCriterion (id,customerId,text,belongsToAdGroupId,belongsToCampaignId,type,criterionType,isNegative,isPaused,maxCpc,minCpc,proxyMaxCpc,status,language,destinationUrl) values ( '".$criteria->id."' , '".$customerId[$a]."' , '".addslashes($criteria->text)."' , '".$criteria->belongsToAdGroupId."' , '".$campaign->id."' , '".$criteria->type."' , '".$criteria->criterionType."' , '".$criteria->isNegative."' , '".$criteria->isPaused."' , '".$criteria->maxCpc."' , '".$criteria->minCpc."' , '".$criteria->proxyMaxCpc."' , '".$criteria->status."' , '".$criteria->language."' , '".addslashes($criteria->destinationUrl)."') ";
				mysql_query($sql) or die(mysql_error().$sql);
			}	
		}
	}
}

$time_end = microtime_float();
$time = $time_end - $time_ini;
echo '<br>time taken ';
echo $time;
echo '<br>';

echo "<br /><b>Overall Consumed Units:</b> ".$soapClients->getOverallConsumedUnits()."<br />";
echo "<br /><b>Overall Performed Operations: </b>".$soapClients->getOverallPerformedOperations()."<br />";
echo "<br /><b>The Response Times of the Last SOAP Requests</b> (Max. ".N_LAST_RESPONSE_TIMES.", Oldest to Youngest):<br />";
foreach($soapClients->getLastResponseTimes() as $lastResponseTime) 
{
	echo "&nbsp;&nbsp;".$lastResponseTime;
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


//update ImageAd as ia , AdGroup as ag set ia.belongsToCampaignId=ag.belongsToCampaignId where ia.belongsToAdGroupId=ag.id;
//Free quota units used:         2,223,527
//Total quota units used:         2,223,527
//Free quota units remaining:         57,776,473
//System-defined quota cap         160,000,000
?>
