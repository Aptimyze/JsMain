<?php
// infoedge report.php This script should run every day at 1 pm indian time and should calculate reports for previous da
// script took less than 1 hour to run and used 36,166 units

include('apility.php');
include('connect_adwords_db.php');

ini_set(max_execution_time,0);
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
ini_set(default_socket_timeout,25920000); // 3 days

if(!$date)
{	
	$yesterday  = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
	$date = date('Y-m-d',$yesterday);
}

for($a=5;$a<10;$a++)
{
        //$authenticationContext = new APIlityAuthentication($Email[$a],$Password[$a],$Developer_Token,$Client_Email[$a],$Application_Token);
	
	/*      version 11 change starts */
        $apilityUser = new APIlityUser($Email[$a],$Password[$a],$Client_Email[$a],$Developer_Token,$Application_Token);
        /*      version 11 change ends */

        mysql_select_db($database[$a],$db);

	/*	Following is a campaingn report		*/
	
	/*
	$xml=getCustomReportJob('XML', 20, 'XML Report',$date,$date, 'Summary', false, array(),
	array("AveragePosition"
	,"Campaign"
	,"CampaignStatus"
	,"CampaignId"
	,"Clicks"
	,"ConversionRate"
	,"Conversions"
	,"Cost"
	,"CostPerConversion"
	,"Cpc"
	,"CPM"
	,"Ctr"
	,"DailyBudget"
	,"Impressions"
	));*/
	
	
	/*      version 11 change starts */
	$xml=getCampaignXmlReport('Campaign Report',$date,$date,
	array("AveragePosition"
        ,"Campaign"
        ,"CampaignStatus"
        ,"CampaignId"
        ,"Clicks"
        ,"ConversionRate"
        ,"Conversions"
        ,"Cost"
//	,"CostPerConversion"
        ,"CostPerConverstion"
        ,"CPC"
//	,"CPM"
        ,"CTR"
        ,"DailyBudget"
        ,"Impressions"),
	array('Summary'),
	$campaigns = array(),
	$campaignStatuses = array(),
	$adGroups = array(),
	$adGroupStatuses = array(),
	$keywords = array(),
	$keywordStatuses = array(),
	$adWordsType = '',
	$keywordType = '',
	$isCrossClient = false,
	$clientEmails = array(),
	$includeZeroImpression = false,
	30,
	false
	);
	/*	version 11 change ends	*/

	if(isset($xml))
		$xml_element = new SimpleXMLElement($xml);
	if(isset($xml_element->table->rows[0]->row))
	foreach ($xml_element->table->rows[0]->row as $row) 
	{
		$camapignids_arr[]=$row['campaignid'];
		$sql=" insert into $database[$a].Campaign_Report (Date,customerId,campaignid,campaign,budget,campStatus,imps,clicks,ctr,cpc,cpm,cost,pos,conv,convRate,costPerConv,maxCpc,maxCpm) values ('$date' , '$customerId[$a]' , '$row[campaignid]' , '".addslashes($row['campaign'])."' , '$row[budget]' , '$row[campStatus]' , '$row[imps]' , '$row[clicks]' , '$row[ctr]' , '$row[cpc]' , '$row[cpm]' , '$row[cost]' , '$row[pos]' , '$row[conv]' , '$row[convRate]' , '$row[costPerConv]' , '$row[maxCpc]' , '$row[maxCpm]' )";
		mysql_query($sql) or die(mysql_error1($db,$sql));
	}

	//$camapignids_arr=array('5741836','5834656');//-----

	
	
	
	/*	Following is a adgroup report for campaigns identified by campaignids_arr	*/
	
	/*
	$xml=getCustomReportJob('XML', 20, 'XML Report',$date,$date, 'Summary', false, array(),
	array("AveragePosition"
	,"AdGroup"
	,"AdGroupId"
	,"AdGroupStatus"
	,"Campaign"
	,"CampaignId"
	,"Clicks"
	,"ConversionRate"
	,"Conversions"
	,"Cost"
	,"CostPerConversion"
	,"Cpc"
	,"CPM"
	,"Ctr"
	//,"DailyBudget"        //this shows budget of the campaing so , removing it.
	,"Impressions"
	),'',$camapignids_arr);*/

	
	/*	version 11 change starts	*/
	$xml=getAdGroupXmlReport('Adgroup Report',$date,$date,
	array("AveragePosition"
        ,"AdGroup"
        ,"AdGroupId"
        ,"AdGroupStatus"
        ,"Campaign"
        ,"CampaignId"
        ,"Clicks"
        ,"ConversionRate"
        ,"Conversions"
        ,"Cost"
//	,"CostPerConversion"
        ,"CostPerConverstion"
        ,"CPC"
//	,"CPM"
	,"CTR"
//	,"DailyBudget"
        ,"Impressions"),
	array('Summary'),
	$campaigns = $camapignids_arr,
	$campaignStatuses = array(),
	$adGroups = array(),
	$adGroupStatuses = array(),
	$keywords = array(),
	$keywordStatuses = array(),
	$adWordsType = '',
	$keywordType = '',
	$isCrossClient = false,
	$clientEmails = array(),
	$includeZeroImpression = false,
	30,
	false
	);
	/*	version 11 change ends	*/


	if(isset($xml))
		$xml_element = new SimpleXMLElement($xml);
	if(isset($xml_element->table->rows[0]->row))
	foreach ($xml_element->table->rows[0]->row as $row) 
	{
		$sql=" insert into $database[$a].AdGroup_Report (Date,customerId,campaignid,campaign,adgroupid,adgroup,agStatus,imps,clicks,ctr,cpc,cpm,cost,pos,conv,convRate,costPerConv,maxCpc,maxCpm) values ('$date' , '$customerId[$a]' , '$row[campaignid]' , '".addslashes($row['campaign'])."' , '$row[adgroupid]' , '".addslashes($row['adgroup'])."' , '$row[agStatus]' , '$row[imps]' , '$row[clicks]' , '$row[ctr]' , '$row[cpc]' , '$row[cpm]' , '$row[cost]' , '$row[pos]' , '$row[conv]' , '$row[convRate]' , '$row[costPerConv]' , '$row[maxCpc]' , '$row[maxCpm]' )";
		mysql_query($sql) or die(mysql_error1($db,$sql));
	}


	

	/*	Following is a keyword report of campaigns identified by campaignids_arr	*/
	
	/*
	$xml=getCustomReportJob('XML', 20, 'XML Report',$date,$date, 'Summary', false, array(),
	array("AveragePosition"
	,"AdGroupId"
	,"AdGroup"
	,"CampaignId"
	,"Campaign"	
	,"Clicks"
	,"ConversionRate"
	,"Conversions"
	,"Cost"
	,"CostPerConversion"
	,"Cpc"
	,"CPM"
	,"Ctr"
	,"Keyword"
	,"KeywordId"
	,"KeywordDestinationUrl"
	,"KeywordStatus"
	,"KeywordType"
	//,"DailyBudget"        //this shows budget of the campaing so , removing it.
	,"Impressions"
	),'',$camapignids_arr);*/


	/*	version 11 change starts	*/
	$xml=getKeywordXmlReport('Keyword Report',$date,$date,
	array("AveragePosition"
        ,"AdGroupId"
        ,"AdGroup"
        ,"Campaign"
        ,"CampaignId"
        ,"Clicks"
        ,"ConversionRate"
        ,"Conversions"
        ,"Cost"
//	,"CostPerConversion"
        ,"CostPerConverstion"
        ,"CPC"
//	,"CPM"
        ,"CTR"
        ,"Keyword"
        ,"KeywordId"
        ,"KeywordDestUrlDisplay"
	,"KeywordStatus"
	,"KeywordTypeDisplay"
//	,"DailyBudget"
	,"Impressions"),
	array('Summary'),
	$campaigns = $camapignids_arr,
	$campaignStatuses = array(),
	$adGroups = array(),
	$adGroupStatuses = array(),
	$keywords = array(),
	$keywordStatuses = array(),
	$adWordsType = '',
	$keywordType = '',
	$isCrossClient = false,
	$clientEmails = array(),
	$includeZeroImpression = false,
	30,
	false
	);
	/*	version 11 change ends	*/


	if(isset($xml))
		$xml_element = new SimpleXMLElement($xml);
	if(isset($xml_element->table->rows[0]->row))
	foreach ($xml_element->table->rows[0]->row as $row) 
	{
		$keyword=$row['keyword'];
		if(!$keyword)
			$keyword=$row['site'];
		if(!$keyword)
			$keyword=$row['kwSite'];
		
		$keyword_type=$row['kwSiteType'];
		if(!$keyword_type)
			$keyword_type=$row['siteType'];
		if(!$keyword_type)
			$keyword_type=$row['kwType'];
		
		$keyword_status=$row['siteKwStatus'];
		if(!$keyword_status)
			$keyword_status=$row['kwStatus'];
		if(!$keyword_status)
			$keyword_status=$row['siteStatus'];
		
		$sql=" insert into $database[$a].KeywordCriterion_Report (Date,customerId,campaignid,campaign,adgroupid,adgroup,keywordid,kwSite,kwSiteType,siteKwStatus,kwDestUrl,imps,clicks,ctr,cpc,cpm,cost,pos,conv,convRate,costPerConv,maxCpc,maxCpm) values ('$date' , '$customerId[$a]' , '$row[campaignid]' , '".addslashes($row['campaign'])."' , '$row[adgroupid]' , '".addslashes($row['adgroup'])."' , '$row[keywordid]' , '".addslashes($keyword)."' , '$keyword_type' , '$keyword_status' , '".addslashes($row['kwDestUrl'])."' , '$row[imps]' , '$row[clicks]' , '$row[ctr]' , '$row[cpc]' , '$row[cpm]' , '$row[cost]' , '$row[pos]' , '$row[conv]' , '$row[convRate]' , '$row[costPerConv]' , '$row[maxCpc]' , '$row[maxCpm]' )";
		mysql_query($sql) or die(mysql_error1($db,$sql));
	}



	/*	Following is a Creative report of campaigns identified by campaignids_arr	*/
	
	/*
	$xml=getCustomReportJob('XML', 20, 'XML Report',$date,$date, 'Summary', false, array(),
	array("AveragePosition"
	,"AdGroupId"
	,"AdGroup"
	,"CampaignId"
        ,"Campaign"
	,"Clicks"
	,"ConversionRate"
	,"Conversions"
	,"Cost"
	,"CostPerConversion"
	,"Cpc"
	,"CPM"
	,"Ctr"
	,"CreativeDestinationUrl"
	,"CreativeId"
	,"CreativeStatus"
	,"CreativeType"
	//,"DailyBudget"        //this shows budget of the campaing so , removing it.
	,"Impressions"
	),'',$camapignids_arr);*/


	/*	version 11 change starts	*/
	$xml=getCreativeXmlReport('Creative Report',$date,$date,
	array("AveragePosition"
        ,"AdGroup"
        ,"AdGroupId"
        ,"Campaign"
        ,"CampaignId"
        ,"Clicks"
        ,"ConversionRate"
        ,"Conversions"
        ,"Cost"
//	,"CostPerConversion"
        ,"CostPerConverstion"
        ,"CPC"
//	,"CPM"
        ,"CTR"
        ,"CreativeDestUrl"
        ,"CreativeId"
        ,"CreativeStatus"
        ,"CreativeType"
//	,"DailyBudget"
        ,"Impressions"),
	array('Summary'),
	$campaigns = $camapignids_arr,
	$campaignStatuses = array(),
	$adGroups = array(),
	$adGroupStatuses = array(),
	$keywords = array(),
	$keywordStatuses = array(),
	$adWordsType = '',
	$keywordType = '',
	$isCrossClient = false,
	$clientEmails = array(),
	$includeZeroImpression = false,
	30,
	false
	);
	/*	version 11 change ends	*/
	

	if(isset($xml))
		$xml_element = new SimpleXMLElement($xml);
	if(isset($xml_element->table->rows[0]->row))
	foreach ($xml_element->table->rows[0]->row as $row) 
	{
		$sql=" insert into $database[$a].Creative_Report (Date,customerId,campaignid,campaign,adgroupid,adgroup,creativeid,creativeType,creativeStatus,creativeDestUrl,imps,clicks,ctr,cpc,cpm,cost,pos,conv,convRate,costPerConv,maxCpc,maxCpm) values ('$date' , '$customerId[$a]' , '$row[campaignid]' , '".addslashes($row['campaign'])."' , '$row[adgroupid]' , '".addslashes($row['adgroup'])."' , '$row[creativeid]' , '$row[creativeType]' , '$row[creativeStatus]' , '".addslashes($row['creativeDestUrl'])."' , '$row[imps]' , '$row[clicks]' , '$row[ctr]' , '$row[cpc]' , '$row[cpm]' , '$row[cost]' , '$row[pos]' , '$row[conv]' , '$row[convRate]' , '$row[costPerConv]' , '$row[maxCpc]' , '$row[maxCpm]' )";
		mysql_query($sql) or die(mysql_error1($db,$sql));
	}
	
	unset($xml);
	unset($row);
        unset($xml_element);
        unset($camapignids_arr);
}

/*      version 11 change added */
$soapClients = &APIlityClients::getClients();
/*      version 11 change added */

echo "<br /><b>Overall Consumed Units:</b> ".$soapClients->getOverallConsumedUnits()."<br />";
echo "<br /><b>Overall Performed Operations: </b>".$soapClients->getOverallPerformedOperations()."<br />";

mail("puneet.makkar@jeevansathi.com,neha.verma@jeevansathi.com","Google Adwords naukri Report Done for ".$date," Overall Consumed Units: ".$soapClients->getOverallConsumedUnits()." \n Overall Performed Operations: ".$soapClients->getOverallPerformedOperations());


function mysql_error1($db,$sql)
{
        mail("puneet.makkar@jeevansathi.com,neha.verma@jeevansathi.com","Error in Google Adwords naukri Report",mysql_error($db)." ".$sql);
}



/*
delete from KeywordCriterion_Report where Date='2007-10-29';
delete from AdGroup_Report where Date='2007-10-29';
delete from Campaign_Report where Date='2007-10-29';
delete from Creative_Report where Date='2007-10-29';
*/
?>

