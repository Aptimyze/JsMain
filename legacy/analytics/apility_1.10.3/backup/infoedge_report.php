<?php
//infoedge report.php This script should run every day at 1 pm indian time and should calculate reports for previous day
include('apility.php');

ini_set(max_execution_time,0);
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
ini_set(default_socket_timeout,259200); // 3 days
ini_set(log_errors_max_len,0);

$yesterday  = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
$date = date('Y-m-d',$yesterday);

//die('remove the die to run the script');

$Developer_Token = "zT7sAKIbbXRFNZWe9uIBWA";
$Application_Token = "kIjlB_mJV4hfEQxlUe0S3A";

//jeevansathi starts//
$Email[] = "geetu.ahuja_jsnri@naukri.com";
$Password[] = "geetunri";
$Client_Email[] = "geetu.ahuja_jsnri@naukri.com";
$customerId[] = 7715469394;
$database[]='adwords_jeevansathi';

//dollars
$Email[] = "geetu.ahuja@naukri.com";
$Password[] = "2007_matrimony";
$Client_Email[] = "geetu.ahuja@naukri.com";
$customerId[] = 9662096480;
$database[]='adwords_jeevansathi';

$Email[] = "madhurima.js@naukri.com";
$Password[] = "gr8possibilities";
$Client_Email[] = "madhurima.js@naukri.com";
$customerId[] = 9223213839;
$database[]='adwords_jeevansathi';
//jeevansathi ends//




//naukri starts
$Email[] = "Manjari.chauhan@naukri.com";
$Password[] = "bestjobs";
$Client_Email[] = "Manjari.chauhan@naukri.com";
$customerId[] = 1439047128;
$database[]='adwords_naukri';

$Email[] = "Madhurima.sil@naukri.com";
$Password[] = "bestjobs";
$Client_Email[] = "Madhurima.sil@naukri.com";
$customerId[] = 3921503389;
$database[]='adwords_naukri';

$Email[] = "naukri.new@naukri.com";
$Password[] = "jobs_india";
$Client_Email[] = "naukri.new@naukri.com";
$customerId[] = 7879621245;
$database[]='adwords_naukri';

$Email[] = "Naukri.top@naukri.com";
$Password[] = "job456seek";
$Client_Email[] = "Naukri.top@naukri.com";
$customerId[] = 2633795937;
$database[]='adwords_naukri';
//naukri ends




//99acres.com starts
$Email[] = "Geetu.ahuja@99acres.com";
$Password[] = "123highjumper";
$Client_Email[] = "Geetu.ahuja@99acres.com";
$customerId[] = 9257994868;
$database[]='adwords_99acres';

$Email[] = "praveen.kodur@99acres.com";
$Password[] = "99realestate";
$Client_Email[] = "praveen.kodur@99acres.com";
$customerId[] = 1486549633;
$database[]='adwords_99acres';
// units consumed 16,1330
//99acres.com ends


$db=mysql_connect("localhost","root","") or die ('could not connect to database');

//for($a=0;$a<count($Email);$a++)
for($a=3;$a<4;$a++)
{
        $authenticationContext = new APIlityAuthentication($Email[$a],$Password[$a],$Developer_Token,$Client_Email[$a],$Application_Token);

        mysql_select_db($database[$a]);
}

/*
$a=0;//-------
$database[$a]='adwords_naukri';//-----
$customerId[$a]='7715469394';//----
mysql_select_db($database[$a]);//---
*/


//following is a campaingn report
/*
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

*/

$camapignids_arr=array('5741836','5834656');//-----

//following is a adgroup report for campaigns
/*
$xml=getCustomReportJob('XML', 40, 'XML Report',$date,$date, 'Summary', false, array(), array("AveragePosition"
,"AdGroup"
,"AdGroupId"
,"AdGroupStatus"
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
	$sql=" insert into $database[$a].AdGroup_Report (Date,customerId,campaignid,adgroupid,adgroup,agStatus,imps,clicks,ctr,cpc,cpm,cost,pos,conv,convRate,costPerConv,maxCpc,maxCpm) values ('$date' , '$customerId[$a]' , '$row[campaignid]' , '$row[adgroupid]' , '".addslashes($row['adgroup'])."' , '$row[agStatus]' , '$row[imps]' , '$row[clicks]' , '$row[ctr]' , '$row[cpc]' , '$row[cpm]' , '$row[cost]' , '$row[pos]' , '$row[conv]' , '$row[convRate]' , '$row[costPerConv]' , '$row[maxCpc]' , '$row[maxCpm]' )";
	mysql_query($sql) or die(mysql_error().$sql);
}
*/


//following is a keyword report of campaigns identified by campaignids_arr
/*
$xml=getCustomReportJob('XML', 60, 'XML Report',$date,$date, 'Summary', false, array(),
array("AveragePosition"
,"AdGroupId"
,"CampaignId"
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
        $sql=" insert into $database[$a].KeywordCriterion_Report (Date,customerId,campaignid,adgroupid,keywordid,kwSite,kwSiteType,siteKwStatus,kwDestUrl,imps,clicks,ctr,cpc,cpm,cost,pos,conv,convRate,costPerConv,maxCpc,maxCpm) values ('$date' , '$customerId[$a]' , '$row[campaignid]' , '$row[adgroupid]' , '$row[keywordid]' , '".addslashes($row['kwSite'])."' , '$row[kwSiteType]' , '$row[siteKwStatus]' , '$row[kwDestUrl]' , '$row[imps]' , '$row[clicks]' , '$row[ctr]' , '$row[cpc]' , '$row[cpm]' , '$row[cost]' , '$row[pos]' , '$row[conv]' , '$row[convRate]' , '$row[costPerConv]' , '$row[maxCpc]' , '$row[maxCpm]' )";
        mysql_query($sql) or die(mysql_error().$sql);
}
*/



/*
//following is a creative report of campaigns identified by campaignids_arr
$xml=getCustomReportJob('XML', 10, 'XML Report',$date,$date, 'Summary', false, array(),
array("AveragePosition"
,"AdGroupId"
,"CampaignId"
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
        $sql=" insert into $database[$a].Creative_Report (Date,customerId,campaignid,adgroupid,creativeid,creativeType,creativeStatus,creativeDestUrl,imps,clicks,ctr,cpc,cpm,cost,pos,conv,convRate,costPerConv,maxCpc,maxCpm) values ('$date' , '$customerId[$a]' , '$row[campaignid]' , '$row[adgroupid]' , '$row[creativeid]' , '$row[creativeType]' , '$row[creativeStatus]' , '$row[creativeDestUrl]' , '$row[imps]' , '$row[clicks]' , '$row[ctr]' , '$row[cpc]' , '$row[cpm]' , '$row[cost]' , '$row[pos]' , '$row[conv]' , '$row[convRate]' , '$row[costPerConv]' , '$row[maxCpc]' , '$row[maxCpm]' )";
        mysql_query($sql) or die(mysql_error().$sql);
}
*/




/*$sql=" select budget,costPerConv,ctr,convRate from $database[$a].Campaign_Report";
$result=mysql_query($sql) or die(mysql_error().$sql);
while($row=mysql_fetch_array($result))
{
	echo ' row budget before '.$row['budget'];
	$row['budget']=($row['budget']/1000000);
	echo ' row budget after '.$row['budget']. '<Br>';
	
	echo ' row costPerConv before '.$row['costPerConv'];
	$row['costPerConv']=($row['costPerConv']/1000000);
	echo ' row costPerConv after '.$row['costPerConv']. '<Br>';
	
	echo ' row ctr before '.$row['ctr'];
	$row['ctr']=($row['ctr']*100);
	echo ' row ctr after '.$row['ctr']. '<Br>';
	
	echo ' row convrate before '.$row['convRate'];
	$row['convRate']=($row['convRate']*100);
	echo ' row convrate aftre '.$row['convRate']. '<Br>';
}*/


//echo $xml_element->table->rows->row[0]->{@attributes}['campaignid'];	//wrong
//echo $xml_element->table->rows->row[0]['campaignid'];

//print_r($xml_element->table->rows);

//print_r($xml_element);

/* For each <movie> node, we echo a separate <plot>.
foreach ($xml->movie as $movie) {
   echo $movie->plot, '<br />';
}*/


//following is a campaingn report
/*print_r(getCustomReportJob('XML', 10, 'XML Report', '2007-10-10', '2007-10-10', 'Summary', false, array(), array("AveragePosition"
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
)));*/  

/*
//following is a adgroup report of a particular campaign
print_r(getCustomReportJob('XML', 10, 'XML Report', '2007-10-10', '2007-10-10', 'Summary', false, array(), array("AveragePosition","AdGroup"
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
//,"DailyBudget"	//this shows budget of the campaing so , removing it.
,"Impressions"
),'',array('6608596')));*/

//ouptput parameters of adgroup report contains
//<row campaignid="6608596" campaign="Active" adgroupid="247083916" adgroup="Careers in Insurance" agStatus="Enabled" imps="896" clicks="1" ctr="0.0011160714285714285" cpc="3.47" cpm="3.872767" cost="3.47" pos="4.21875" conv="0" convRate="0.0" costPerConv="0" maxCpc="0" keywordMinCpc="0"/>


/*
//following is a keyword report report of a particular campaign active of naukri
print_r(getCustomReportJob('XML', 10, 'XML Report', '2007-10-10', '2007-10-10', 'Summary', false, array(), array("AveragePosition","AdGroupId"
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
,"Keyword"
,"KeywordId"
,"KeywordDestinationUrl"
,"KeywordStatus"
,"KeywordType"
//,"DailyBudget"	//this shows budget of the campaing so , removing it.
,"Impressions"
),'',array('6608596')));

<row campaignid="6608596" campaign="Active" adgroupid="244815736" keywordid="1352346256" kwSite="bhel careers" kwSiteType="Broad" siteKwStatus="Active" kwDestUrl="default URL" imps="40" clicks="0" ctr="0.0" cpc="0" cpm="0" cost="0" pos="1.7" conv="0" convRate="0.0" costPerConv="0" maxCpc="0" keywordMinCpc="0"/>*/

/* dont use them...
,"MinimumCpc"     //added
,"MaxContentCPC"  //added
,"MaximumCpc" 	//already in report without writing it
,"MaximumCPM"	//not already in reprot,  or can b already in reprot of site targetted , added here sinec cpc campaign
*/



//keyword report for naukri campaign
//print_r(getKeywordReportJob('XML', 20, 'XML Report', '2007-10-10', '2007-10-10', 'Summary','',array(),'',array(6608596)));  
//output <row campaign="Active" adgroup="Exams&amp;Colleges" kwSite="Total - content targeting" kwSiteType="Content" siteKwStatus="" keywordMinCpc="0" maxCpc="0" kwDestUrl="" imps="169" clicks="0" ctr="0.0" cpc="0" cost="0" pos="3.6568047337" cpm="0"/>



//print_r(getAdTextReportJob('XML', 20, 'XML Report', '2007-10-10', '2007-10-10', 'Summary','',array(),'',array('6608596')));  

//print_r(getAdImageReportJob('XML', 20, 'XML Report', '2007-10-10', '2007-10-10', 'Summary','',array(),array('6608596')));  

/*
//following is a ad report report of a particular campaign active of naukri
print_r(getCustomReportJob('XML', 10, 'XML Report', '2007-10-10', '2007-10-10', 'Summary', false, array(), array("AveragePosition","AdGroupId"
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
,"CreativeDestinationUrl"
,"CreativeId"
,"CreativeStatus"
,"CreativeType"
//,"DailyBudget"	//this shows budget of the campaing so , removing it.
,"Impressions"
),'',array('6608596')));
*/

//*********************************************************************
/*
//following is a website report for site targetted campaing of 99acres
print_r(getCustomReportJob('XML', 10, 'XML Report', '2007-10-10', '2007-10-10', 'Summary', false, array(), array("AdGroupId"
,"AdGroup"
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
,"Website"
//,"DailyBudget"        //this shows budget of the campaing so , removing it.
,"Impressions"
),'',array('8276061')));
/*
<?xml version="1.0" standalone="yes"?>
<report><table><columns><column name="campaignid"/><column name="campaign"/><column name="adgroupid"/><column name="adgroup"/><column name="site"/><column name="imps"/><column name="clicks"/><column name="ctr"/><column name="cpc"/><column name="cpm"/><column name="cost"/><column name="conv"/><column name="convRate"/><column name="costPerConv"/></columns><rows>

<row campaignid="8276061" campaign="site-target" adgroupid="259154571" adgroup="realty" site="bihartimes.com" imps="18" clicks="0" ctr="0.0" cpc="0" cpm="0" cost="0" conv="0" convRate="0.0" costPerConv="0" maxCpc="0" keywordMinCpc="0"/>

<row campaignid="8276061" campaign="site-target" adgroupid="259154571" adgroup="realty" site="maplandia.com" imps="234" clicks="3" ctr="0.01282051282051282" cpc="1.906666" cpm="24.444444" cost="5.72" conv="0" convRate="0.0" costPerConv="0" maxCpc="0" keywordMinCpc="0"/>

</rows></table><totals><grandtotal imps="49846" clicks="84" ctr="0.0016851903863900814" cpc="4.953095" cpm="8.346908" cost="416.06" conv="11" convRate="0.13095238095238096" costPerConv="37823636"/></totals></report>

<row campaignid="8276061" campaign="site-target" adgroupid="259154571" adgroup="realty" site="jobstreet.com" imps="30" clicks="0" ctr="0.0" cpc="0" cpm="0" cost="0" conv="0" convRate="0.0" costPerConv="0" maxCpc="0" keywordMinCpc="0"/

<totals><grandtotal imps="49846" clicks="84" ctr="0.0016851903863900814" cpc="4.953095" cpm="8.346908" cost="416.06" conv="11" convRate="0.13095238095238096" costPerConv="37823636"/></totals></report>
*/

//***************************************************************************************//


/*
print_r(getCustomReportJob('XML', 10, 'XML Report', '2007-10-10', '2007-10-10', 'Summary', false, array(), array(
"Campaign"
,"CampaignId"
,"AdGroupId"
,"Cpc"
,"CPM"
,"VisibleUrl"
,"Website"
//,"DailyBudget"	//this shows budget of the campaing so , removing it.
,"Impressions"
),'',array('8276061')));

/*
out put of site targetted campaing of 99 acres.com
<?xml version="1.0" standalone="yes"?>
<report><table><columns><column name="campaignid"/><column name="campaign"/><column name="adgroupid"/><column name="site"/><column name="creativeVisUrl"/><column name="imps"/><column name="cpc"/><column name="cpm"/></columns><rows><row campaignid="8276061" campaign="site-target" adgroupid="259154571" site="bihartimes.com" creativeVisUrl="99acres.com" imps="9" cpc="0" cpm="0" cost="0" maxCpc="0" keywordMinCpc="0"/><row campaignid="8276061" campaign="site-target" adgroupid="259154571" site="bihartimes.com" creativeVisUrl="99acres.com" imps="5" cpc="0" cpm="0" cost="0" maxCpc="0" keywordMinCpc="0"/><row campaignid="8276061" campaign="site-target" adgroupid="259154571" site="bihartimes.com" creativeVisUrl="99acres.com" imps="4" cpc="0" cpm="0" cost="0" maxCpc="0" keywordMinCpc="0"/><row campaignid="8276061" campaign="site-target" adgroupid="259154571" site="buzzle.com" creativeVisUrl="99acres.com" imps="1" cpc="0" cpm="0" cost="0" maxCpc="0" keywordMinCpc="0"/><row campaignid="8276061" campaign="site-target" adgroupid="259154571" site="findarticles.com" creativeVisUrl="99acres.com" imps="1" cpc="0" cpm="0" cost="0" maxCpc="0" keywordMinCpc="0"/>
*/

/*
//output for keywoard targetted camaping of naukri 6608596
<?xml version="1.0" standalone="yes"?>
<report><table><columns><column name="campaignid"/><column name="campaign"/><column name="site"/><column name="creativeVisUrl"/><column name="imps"/><column name="cpc"/><column name="cpm"/></columns><rows><row campaignid="6608596" campaign="Active" site="Total - content targeting" creativeVisUrl="Naukri.com" imps="12501" cpc="2.700769" cpm="2.808575" cost="0" maxCpc="0" keywordMinCpc="0"/>

<row campaignid="6608596" campaign="Active" site="Total - content targeting" creativeVisUrl="Naukri.com" imps="62" cpc="0" cpm="0" cost="0" maxCpc="0" keywordMinCpc="0"/><row campaignid="6608596" campaign="Active" site="Total - content targeting" creativeVisUrl="Naukri.com" imps="726" cpc="0" cpm="0" cost="0" maxCpc="0" keywordMinCpc="0"/>

<row campaignid="6608596" campaign="Active" site="a college" creativeVisUrl="Naukri.com" imps="2" cpc="0" cpm="0" cost="0" maxCpc="0" keywordMinCpc="0"/><row campaignid="6608596" campaign="Active" site="a+ exam" creativeVisUrl="Naukri.com" imps="3" cpc="0" cpm="0" cost="0" maxCpc="0" keywordMinCpc="0"/>
/rows></table><totals><grandtotal imps="64105" cpc="3.809268" cpm="9.745261" cost="0"/></totals></report>
<br /><b>Overall Consumed Units:</b> 1005<br /><br /><b>Overall Performed Operations: </b>6<br />
*/

/*current used unit 15434
time taken for jeevansathis 1st account8096.98006177
//$allCampaigns = getAllCampaigns(); 
//print_r($allCampaigns); //took around 10 min

/*
print_r(getCustomReportJob('XML', 10, 'XML Report', '2007-10-04', '2007-10-04', 'Summary', false, array(), array("AccountName","AdGroup"
,"AdGroupId"
,"AdGroupStatus"
,"AdWordsType"
,"AverageConversionValue"
,"AveragePosition"
,"BottomPosition"
,"Campaign"
,"CampaignEndDate"
,"CampaignId"
,"CampaignStatus"
,"Clicks"
,"ConversionRate"
,"Conversions"
,"ConversionValuePerClick"
,"ConversionValuePerCost"
,"Cost"
,"CostPerConversion"
,"CostPerTransaction"
,"Cpc"
,"CPM"
,"CreativeDestinationUrl"
,"CreativeId"
,"CreativeStatus"
,"CreativeType"
,"Ctr"
,"CustomerTimeZone"
,"DailyBudget"
,"DefaultConversionCount"
,"DefaultConversionValue"
,"DescriptionLine1"
,"DescriptionLine2"
,"DescriptionLine3"
,"DestinationUrl"
,"ImageAdName"
,"ImageHostingKey"
,"Impressions"
,"Keyword"
,"KeywordId"
,"KeywordDestinationUrl"
,"KeywordStatus"
,"KeywordType"
,"LeadCount"
,"LeadValue"
,"MinimumCpc"
,"MaxContentCPC"
,"MaximumCpc"
,"MaximumCPM"
,"PageViewCount"
,"PageViewValue"
,"SaleCount"
,"SaleValue"
,"SignupCount"
,"SignupValue"
,"TopPosition"
,"TotalConversionValue"
,"Transactions"
,"VisibleUrl"
,"Website" ),'',array('5810216')));  
*/


/*current used unit 15434
time taken for jeevansathis 1st account8096.98006177

Overall Consumed Units: 199784

Overall Performed Operations: 199784

The Response Times of the Last SOAP Requests (Max. 10, Oldest to Youngest):
  853  850  557  971  301  957  810  1196  160  877*/

echo "<br /><b>Overall Consumed Units:</b> ".$soapClients->getOverallConsumedUnits()."<br />";
echo "<br /><b>Overall Performed Operations: </b>".$soapClients->getOverallPerformedOperations()."<br />";
?>

