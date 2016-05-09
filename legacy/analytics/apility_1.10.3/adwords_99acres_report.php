<?php
// infoedge report.php This script should run every day at 1 pm indian time and should calculate reports for previous da
// script took less than 1 hour to run and used 36,166 units
// before running this scrip units consumed 2223527
// after running this scrip 2259693

//die('remove die to run the script');

include('apility.php');
include('connect_adwords_db.php');

ini_set(max_execution_time,0);
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
ini_set(default_socket_timeout,25920000); // 3 days
//ini_set(log_errors_max_len,0);

if(!$date)
{	
	$yesterday  = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
	$date = date('Y-m-d',$yesterday);
}

//echo $date;
//die('bye');

$Developer_Token = "zT7sAKIbbXRFNZWe9uIBWA";
$Application_Token = "kIjlB_mJV4hfEQxlUe0S3A";

//$db=mysql_connect("localhost","root","") or die ('could not connect to database');
//$db=mysql_connect("localhost:/tmp/mysql.sock","user","CLDLRTa9") or die(mysql_error());

//for($a=0;$a<9;$a++)
for($a=3;$a<5;$a++)
{
        $authenticationContext = new APIlityAuthentication($Email[$a],$Password[$a],$Developer_Token,$Client_Email[$a],$Application_Token);

        mysql_select_db($database[$a]);


	//following is a campaingn report
	$xml=getCustomReportJob('XML', 20, 'XML Report',$date,$date, 'Summary', false, array(), array("AveragePosition"
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
	));


	if(isset($xml))
		$xml_element = new SimpleXMLElement($xml);
	if(isset($xml_element->table->rows[0]->row))
	foreach ($xml_element->table->rows[0]->row as $row) 
	{
		$camapignids_arr[]=$row['campaignid'];
		$sql=" insert into $database[$a].Campaign_Report (Date,customerId,campaignid,campaign,budget,campStatus,imps,clicks,ctr,cpc,cpm,cost,pos,conv,convRate,costPerConv,maxCpc,maxCpm) values ('$date' , '$customerId[$a]' , '$row[campaignid]' , '".addslashes($row['campaign'])."' , '$row[budget]' , '$row[campStatus]' , '$row[imps]' , '$row[clicks]' , '$row[ctr]' , '$row[cpc]' , '$row[cpm]' , '$row[cost]' , '$row[pos]' , '$row[conv]' , '$row[convRate]' , '$row[costPerConv]' , '$row[maxCpc]' , '$row[maxCpm]' )";
		mysql_query($sql) or die(mysql_error().$sql);
	}


	//$camapignids_arr=array('5741836','5834656');//-----

	//following is a adgroup report for campaigns
	$xml=getCustomReportJob('XML', 20, 'XML Report',$date,$date, 'Summary', false, array(), array("AveragePosition"
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
	),'',$camapignids_arr); 


	if(isset($xml))
		$xml_element = new SimpleXMLElement($xml);
	if(isset($xml_element->table->rows[0]->row))
	foreach ($xml_element->table->rows[0]->row as $row) 
	{
		$sql=" insert into $database[$a].AdGroup_Report (Date,customerId,campaignid,campaign,adgroupid,adgroup,agStatus,imps,clicks,ctr,cpc,cpm,cost,pos,conv,convRate,costPerConv,maxCpc,maxCpm) values ('$date' , '$customerId[$a]' , '$row[campaignid]' , '".addslashes($row['campaign'])."' , '$row[adgroupid]' , '".addslashes($row['adgroup'])."' , '$row[agStatus]' , '$row[imps]' , '$row[clicks]' , '$row[ctr]' , '$row[cpc]' , '$row[cpm]' , '$row[cost]' , '$row[pos]' , '$row[conv]' , '$row[convRate]' , '$row[costPerConv]' , '$row[maxCpc]' , '$row[maxCpm]' )";
		mysql_query($sql) or die(mysql_error().$sql);
	}


	//following is a keyword report of campaigns identified by campaignids_arr
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
	),'',$camapignids_arr);


	if(isset($xml))
		$xml_element = new SimpleXMLElement($xml);
	if(isset($xml_element->table->rows[0]->row))
	foreach ($xml_element->table->rows[0]->row as $row) 
	{
		$keyword=$row['kwSite'];
		$keyword_type=$row['kwSiteType'];
		$keyword_status=$row['siteKwStatus'];
		if(!$keyword)
			$keyword=$row['keyword'];
		if(!$keyword_type)
			$keyword_type=$row['kwType'];
		if(!$keyword_status)
			$keyword_status=$row['kwStatus'];
		
		$sql=" insert into $database[$a].KeywordCriterion_Report (Date,customerId,campaignid,campaign,adgroupid,adgroup,keywordid,kwSite,kwSiteType,siteKwStatus,kwDestUrl,imps,clicks,ctr,cpc,cpm,cost,pos,conv,convRate,costPerConv,maxCpc,maxCpm) values ('$date' , '$customerId[$a]' , '$row[campaignid]' , '".addslashes($row['campaign'])."' , '$row[adgroupid]' , '".addslashes($row['adgroup'])."' , '$row[keywordid]' , '".addslashes($keyword)."' , '$keyword_type' , '$keyword_status' , '".addslashes($row['kwDestUrl'])."' , '$row[imps]' , '$row[clicks]' , '$row[ctr]' , '$row[cpc]' , '$row[cpm]' , '$row[cost]' , '$row[pos]' , '$row[conv]' , '$row[convRate]' , '$row[costPerConv]' , '$row[maxCpc]' , '$row[maxCpm]' )";
		mysql_query($sql) or die(mysql_error().$sql);
	}



	//following is a creative report of campaigns identified by campaignids_arr
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
	),'',$camapignids_arr);

	if(isset($xml))
		$xml_element = new SimpleXMLElement($xml);
	if(isset($xml_element->table->rows[0]->row))
	foreach ($xml_element->table->rows[0]->row as $row) 
	{
		$sql=" insert into $database[$a].Creative_Report (Date,customerId,campaignid,campaign,adgroupid,adgroup,creativeid,creativeType,creativeStatus,creativeDestUrl,imps,clicks,ctr,cpc,cpm,cost,pos,conv,convRate,costPerConv,maxCpc,maxCpm) values ('$date' , '$customerId[$a]' , '$row[campaignid]' , '".addslashes($row['campaign'])."' , '$row[adgroupid]' , '".addslashes($row['adgroup'])."' , '$row[creativeid]' , '$row[creativeType]' , '$row[creativeStatus]' , '".addslashes($row['creativeDestUrl'])."' , '$row[imps]' , '$row[clicks]' , '$row[ctr]' , '$row[cpc]' , '$row[cpm]' , '$row[cost]' , '$row[pos]' , '$row[conv]' , '$row[convRate]' , '$row[costPerConv]' , '$row[maxCpc]' , '$row[maxCpm]' )";
		mysql_query($sql) or die(mysql_error().$sql);
	}
	
	unset($xml);
	unset($row);
        unset($xml_element);
        unset($camapignids_arr);
}


echo "<br /><b>Overall Consumed Units:</b> ".$soapClients->getOverallConsumedUnits()."<br />";
echo "<br /><b>Overall Performed Operations: </b>".$soapClients->getOverallPerformedOperations()."<br />";

/*
delete from KeywordCriterion_Report where Date='2007-10-29';
delete from AdGroup_Report where Date='2007-10-29';
delete from Campaign_Report where Date='2007-10-29';
delete from Creative_Report where Date='2007-10-29';
*/
?>

