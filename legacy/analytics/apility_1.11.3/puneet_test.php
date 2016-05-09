<?php
include('apility.php');
include('connect_adwords_db.php');
$Developer_Token = "zT7sAKIbbXRFNZWe9uIBWA";
$Application_Token = "kIjlB_mJV4hfEQxlUe0S3A";

 /*$authenticationContext = new APIlityAuthentication(
          $Email[9],
          $Password[9],
          $Developer_Token,
          $Client_Email[9],
          $Application_Token
        );*/


	/*      version 11 change added */
        $apilityUser = new APIlityUser($Email[1],$Password[1],$Client_Email[1],$Developer_Token,$Application_Token);
        /*      version 11 change added */

        mysql_select_db($database[1]);

  
//print_r(getAccountInfo());
//print_r(getClientsClientAccounts()); 
//print_r(getManagersClientAccounts()); 


//echo getOperationsQuotaThisMonth();  // 160000000 after all usage its still 160000000 on 4oct

//echo getUsageQuotaThisMonth(); //160000000 after all usage its still 160000000 on 4oct

/*$yesterday = gmdate("Y-m-d", mktime(date("H"), date("i"), date("s"), date("m") , date("d") - 1   , date("Y")));
$dayBeforeYesterday = gmdate("Y-m-d", mktime(date("H"), date("i"), date("s"), date("m") , date("d") -2  , date("Y")));
echo getOperationCount($dayBeforeYesterday, $yesterday);*/   
//4 oct morning returned 0 on the first day when i was using it.
//5 oct morning returned 4283 on the second day when i was using it.


/*$yesterday = gmdate("Y-m-d", mktime(date("H"), date("i"), date("s"), date("m") , date("d") -1  , date("Y")));
$dayBeforeYesterday = gmdate("Y-m-d", mktime(date("H"), date("i"), date("s"), date("m") , date("d") - 15 , date("Y")));
echo getUnitCount($dayBeforeYesterday, $yesterday);*/
//4 oct morning return 5, evening returned 4283
//5 oct morning return 4514 , afternoon 4602
//8 oct morning return 11631

//print_r(getUnitCountForClients('2007-10-02', '2007-10-03',array('geetu.ahuja@naukri.com')));   //returned some errors




//*****************************************************************************

/*$campaignObject = createCampaignObject(6585896);
echo $campaignObject->getName();*/

//$allCampaigns = getAllCampaigns(); 
//print_r($allCampaigns);
//took around 10 min

/*$allAdGroups = getAllAdGroups(6585896);  
print_r($allAdGroups);*/ //took around 2 min gettting all ad groups of campaing all

/*$allCriteria = getAllCriteria(283185806); //all criteria of divorcee ad group of campaign all
print_r($allCriteria);*/  

//$allAds = getAllAds(array(283185806));  //all ads of divorcee ad grooup of campaing all
//print_r($allAds);

/*$allAds = getAllAds(array(495170006));  //all ads of  Orkut Malayalam-img ad grooup of campaing   Orkut-img
print_r($allAds);*/

//$allCriteria = getAllCriteria(581283926); // all criteria of adgroup kerala cpc of campaign Site targeted CPC
//print_r($allCriteria);


//*****************************************************************************
// API DOESNOT SUPPORT SITE TARGETTED CAMPAING	

/*$adGroupObject = createAdGroupObject(581283926);
print_r($adGroupObject->getAllCriteria());*/  // The API does not support this type of campaign. error , site targeted adroup tha

/*$allAdGroups = getAllAdGroups(17229686);  
print_r($allAdGroups); //get all ad groups of site targetted campaign same error The API does not support this type of campaign.
$campaignObject = createCampaignObject(17229686);
print_r($campaignObject->getCampaignData());  //get all ad groups of site targetted campaign same error The API does not support this type of campaign.

$allCriteria = getAllCriteria(581283926);
print_r($allCriteria); //get all ad groups of site targetted campaign same error The API does not support this type of campaign.

$allAds = getAllAds(array(581283926));  //all ads of divorcee ad grooup of campaing all
print_r($allAds);*/

//*******************************************************************************

/*$campaignIds = array(171181,5355866);
$campaignObject = getCampaignList($campaignIds);  
print_r($campaignObject);*/

//5335726 campaign id of naukri 1st account
//8894091 campaing id fo 99acres 1st account of conversion optimizer enabled campaign which api does not support
//campaign id of naukri 1st account deleted campaign

/*$campaignIds = array(5336026);
$campaignObject = getCampaignList($campaignIds);  
print_r($campaignObject);*/

//***************************************************************************************



//print_r(getAccountReportJob('XML', 20, 'XML Report', '2007-10-04', '2007-10-04', 'Summary'));  

//print_r(getCustomReportJob('CSV', 10, 'CSV Report', '2007-10-04', '2007-10-04', 'Summary', false, array(), array('Campaign', 'Clicks')));  

//print_r(getAdGroupReportJob('XML', 20, 'XML Report', '2007-10-02', '2007-10-02','Summary',false,array(),array(),'SearchOnly',array(8763746)));  // ad reprot for nri campaign for jeevansathi

//print_r(getCustomReportJob('XML', 10, 'XML Report', '2007-10-04', '2007-10-04', 'Summary', false, array(), array('Campaign', 'Clicks','AdWordsType')));  

//$campaignObject = createCampaignObject(6585896);
//print_r($campaignObject->getCampaignData());  

//$campaignObject = createCampaignObject(17223506);
//print_r($campaignObject->getLanguages());  

//$adObject = createAdObject(242452766,978345686);
//echo $adObject->getAdType();  

//$allAds = getAllAds(array(242452766));  //all ads of nri  ad grooup of campaing nri ,text ads + image ads
//echo $allAds[0]->adType;

//$db=mysql_connect("localhost","root","") or die ('cud not connect');
/*$db=mysql_connect("172.16.3.182","user","CLDLRTa9") or die ('cud not connect');
*/
//mysql_select_db("adwords_jeevansathi");
//echo 'puneet is a good boy';

/*
$sql="select * from adwords_jeevansathi.AdGroup";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
print_r($row);*/

//print_r(getCampaignReportJob('XML', 20, 'XML Report', '2007-10-10', '2007-10-10', 'Summary'));


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


///////////////////////////////////////////////////////////////////////////////////////////

// VERSION 11 STARTS

///////////////////////////////////////////////////////////////////////////////////////////

/*
echo getAccountXmlReport(
  'Report Name',
  '2008-03-16',
  '2008-04-16',
  array('AccountName'),
  array('Weekly'),
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

<?xml version="1.0" standalone="yes"?>
<report>
	<table>
	<columns>
		<column name="weekStart"/>
		<column name="account"/>
		<column name="clicks"/>
	</columns>
	<rows>
		<row weekStart="3/16/08 - 3/16/08" account="Account" clicks="3290" cost="0" cpc="0" maxCpc="0" keywordMinCpc="0" cpm="0"/>
		<row weekStart="3/17/08 - 3/23/08" account="Account" clicks="19996" cost="0" cpc="0" maxCpc="0" keywordMinCpc="0" cpm="0"/>
		<row weekStart="3/24/08 - 3/30/08" account="Account" clicks="11897" cost="0" cpc="0" maxCpc="0" keywordMinCpc="0" cpm="0"/>
		<row weekStart="3/31/08 - 4/6/08" account="Account" clicks="10092" cost="0" cpc="0" maxCpc="0" keywordMinCpc="0" cpm="0"/>
		<row weekStart="4/7/08 - 4/13/08" account="Account" clicks="8724" cost="0" cpc="0" maxCpc="0" keywordMinCpc="0" cpm="0"/>
		<row weekStart="4/14/08 - 4/16/08" account="Account" clicks="3239" cost="0" cpc="0" maxCpc="0" keywordMinCpc="0" cpm="0"/>
	</rows>
	</table>

	<totals>
		<subtotal clicks="3290" name="2008-03-10" cost="0" cpc="0" cpm="0"/>
		<subtotal clicks="19996" name="2008-03-17" cost="0" cpc="0" cpm="0"/>
		<subtotal clicks="11897" name="2008-03-24" cost="0" cpc="0" cpm="0"/>
		<subtotal clicks="10092" name="2008-03-31" cost="0" cpc="0" cpm="0"/>
		<subtotal clicks="8724" name="2008-04-07" cost="0" cpc="0" cpm="0"/>
		<subtotal clicks="3239" name="2008-04-14" cost="0" cpc="0" cpm="0"/>
		<grandtotal clicks="57238" cost="0" cpc="0" cpm="0"/>
	</totals>
</report>
*/
////////////////////////////////////////////////////////////////
/*

echo getCampaignXmlReport(
  'Report Name',
  '2008-04-15',
  '2008-04-15',
  array("AveragePosition"
        ,"Campaign"
        ,"CampaignStatus"
        ,"CampaignId"
        ,"Clicks"
        ,"ConversionRate"
        ,"Conversions"
        ,"Cost"
        //,"CostPerConversion"
        ,"CostPerConverstion"
	,"CPC"
        //,"CPM"
        ,"CTR"
        ,"DailyBudget"
        ,"Impressions"),
  array('Daily'),
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

*/
/*
<?xml version="1.0" standalone="yes"?>
<report><table><columns><column name="campaignid"/><column name="campaign"/><column name="budget"/><column name="campStatus"/><column name="imps"/><column name="clicks"/><column name="ctr"/><column name="cpc"/><column name="cost"/><column name="pos"/><column name="conv"/><column name="convRate"/><column name="costPerConv"/></columns><rows><row campaignid="8876311" campaign="current-s" budget="20000000000" campStatus="Active" imps="834" clicks="23" ctr="0.027577937649880094" cpc="16.903913" cost="388.79" pos="2.9892086331" conv="2" convRate="0.08695652173913043" costPerConv="194395000" maxCpc="0" keywordMinCpc="0" cpm="0"/>*/

////////////////////////////////////////////////////////////////

/*
echo getAdGroupXmlReport(
  'Report Name',
  '2008-04-15',
  '2008-04-15',
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
        //,"CostPerConversion"
        ,"CostPerConverstion"
        ,"CPC"
        //,"CPM"
        ,"CTR"
        //,"DailyBudget"
        ,"Impressions"),
  array('Summary'),
  $campaigns = array('8969611','29492011'),
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
*/

/*
<?xml version="1.0" standalone="yes"?>
<report><table><columns><column name="date"/><column name="campaignid"/><column name="campaign"/><column name="adgroupid"/><column name="adgroup"/><column name="budget"/><column name="agStatus"/><column name="imps"/><column name="clicks"/><column name="ctr"/><column name="cpc"/><column name="cost"/><column name="pos"/><column name="conv"/><column name="convRate"/><column name="costPerConv"/></columns><rows><row date="2008-04-15" campaignid="8969611" campaign="till i drop -c" adgroupid="244245031" adgroup="A to Z Keywords-C" budget="20000000000" agStatus="Enabled" imps="31035" clicks="51" ctr="0.0016433059449009182" cpc="5.430196" cost="276.94" pos="1.6503302723" conv="4" convRate="0.0784313725490196" costPerConv="69235000" maxCpc="0" keywordMinCpc="0" cpm="0"/>
*/

//////////////////////////////////////////////////////////////////////

/*
echo getCreativeXmlReport(
  'Report Name',
  '2008-04-15',
  '2008-04-15',
  array("AveragePosition"
        ,"AdGroup"
        ,"AdGroupId"
        ,"Campaign"
        ,"CampaignId"
        ,"Clicks"
        ,"ConversionRate"
        ,"Conversions"
        ,"Cost"
        //,"CostPerConversion"
        ,"CostPerConverstion"
        ,"CPC"
        //,"CPM"
        ,"CTR"
	,"CreativeDestUrl"
        ,"CreativeId"
        ,"CreativeStatus"
        ,"CreativeType"
	//,"DailyBudget"
        ,"Impressions"),
  array('Summary'),
  $campaigns = array('8969611'),
  $campaignStatuses = array(),
  $adGroups = array('244248871'),
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

<?xml version="1.0" standalone="yes"?>
<report><table><columns><column name="campaignid"/><column name="campaign"/><column name="adgroupid"/><column name="adgroup"/><column name="creativeid"/><column name="creativeType"/><column name="creativeStatus"/><column name="creativeDestUrl"/><column name="imps"/><column name="clicks"/><column name="ctr"/><column name="cpc"/><column name="cost"/><column name="pos"/><column name="conv"/><column name="convRate"/><column name="costPerConv"/></columns><rows><row campaignid="8969611" campaign="till i drop -c" adgroupid="244245031" adgroup="A to Z Keywords-C" creativeid="1268634871" creativeType="image" creativeStatus="Enabled" creativeDestUrl="http://www.jeevansathi.com/index.php?source=go_4girls" imps="925" clicks="1" ctr="0.001081081081081081" cpc="3.69" cost="3.69" pos="1.1135135135" conv="0" convRate="0.0" costPerConv="0" maxCpc="0" keywordMinCpc="0" cpm="0"/>


*/

////////////////////////////////////////////////////////////////////////


echo getKeywordXmlReport(
  'Report Name',
  '2008-04-15',
  '2008-04-15',
  array("AveragePosition"
        ,"AdGroupId"
        ,"AdGroup"
	,"Campaign"
        ,"CampaignId"
        ,"Clicks"
        ,"ConversionRate"
        ,"Conversions"
        ,"Cost"
        //,"CostPerConversion"
        ,"CostPerConverstion"
        ,"CPC"
        //,"CPM"
        ,"CTR"
	,"Keyword"
        ,"KeywordId"
        ,"KeywordDestUrlDisplay"
        ,"KeywordStatus"
        ,"KeywordTypeDisplay"
	//,"DailyBudget"
        ,"Impressions"),
  array('Summary'),
  $campaigns = array('24872671'),
  $campaignStatuses = array(),
  $adGroups = array('499686451'),
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

/*
following output was when i didnt entered any campaing or adroup id in keyword reprot
<?xml version="1.0" standalone="yes"?>
<report><table><columns><column name="campaignid"/><column name="campaign"/><column name="keywordid"/><column name="kwSite"/><column name="kwSiteType"/><column name="siteKwStatus"/><column name="kwDestUrl"/><column name="campStatus"/><column name="imps"/><column name="clicks"/><column name="ctr"/><column name="cpc"/><column name="cost"/><column name="pos"/><column name="conv"/><column name="convRate"/><column name="costPerConv"/></columns><rows>

	<row campaignid="8876311" campaign="current-s" keywordid="11884331" kwSite="indian matrimonial" kwSiteType="Broad" siteKwStatus="Active" kwDestUrl="http://www.jeevansathi.com/index.php?source=go%5fnri&amp;adnetwork=google&amp;account=jeevan%20saathi%20nri&amp;campaign=current%2ds&amp;adgroup=matrimonials%2ds&amp;keyword=indian%20matrimonial&amp;match=broad&amp;lmd=26%2dmar%2d08" campStatus="Active" imps="80" clicks="5" ctr="0.0625" cpc="15.12" cost="75.6" pos="3.475" conv="0" convRate="0.0" costPerConv="0" maxCpc="0" keywordMinCpc="0" cpm="0"/>

	<row campaignid="24872671" campaign="site targetted cpc nri" keywordid="1945016971" kwSite="hindu.com" kwSiteType="WebSite" siteKwStatus="Active" kwDestUrl="http://www.jeevansathi.com/index.php?source=go_hindu&amp;adnetwork=google&amp;account=jeevan%20saathi%20nri&amp;campaign=site%20targetted%20cpc%20nri&amp;adgroup=hindu%20cpc%20nri&amp;keyword=hindu%2ecom&amp;match=placement&amp;lmd=06%2dmar%2d08" campStatus="Active" imps="26" clicks="0" ctr="0.0" cpc="0" cost="0" pos="5.0" conv="0" convRate="0.0" costPerConv="0" maxCpc="0" keywordMinCpc="0" cpm="0"/>
*/

/*
following ouptut was when addinc campaing id + adroup id to keyword report
<?xml version="1.0" standalone="yes"?>
<report><table><columns><column name="campaignid"/><column name="campaign"/><column name="adgroupid"/><column name="adgroup"/><column name="keywordid"/><column name="kwSite"/><column name="kwSiteType"/><column name="siteKwStatus"/><column name="kwDestUrl"/><column name="imps"/><column name="clicks"/><column name="ctr"/><column name="cpc"/><column name="cost"/><column name="pos"/><column name="conv"/><column name="convRate"/><column name="costPerConv"/></columns><rows><row campaignid="8876311" campaign="current-s" adgroupid="241599091" adgroup="Matrimonials-S" keywordid="11884331" kwSite="indian matrimonial" kwSiteType="Broad" siteKwStatus="Active" kwDestUrl="http://www.jeevansathi.com/index.php?source=go%5fnri&amp;adnetwork=google&amp;account=jeevan%20saathi%20nri&amp;campaign=current%2ds&amp;adgroup=matrimonials%2ds&amp;keyword=indian%20matrimonial&amp;match=broad&amp;lmd=26%2dmar%2d08" imps="80" clicks="5" ctr="0.0625" cpc="15.12" cost="75.6" pos="3.475" conv="0" convRate="0.0" costPerConv="0" maxCpc="0" keywordMinCpc="0" cpm="0"/><row campaignid="8876311" campaign="current-s" adgroupid="241599091" adgroup="Matrimonials-S" keywordid="18888811" kwSite="matrimonials indian" kwSiteType="Broad" siteKwStatus="Active" kwDestUrl="http://www.jeevansathi.com/index.php?source=go%5fnri&amp;adnetwork=google&amp;account=jeevan%20saathi%20nri&amp;campaign=current%2ds&amp;adgroup=matrimonials%2ds&amp;keyword=matrimonials%20indian&amp;match=broad&amp;lmd=24%2dmar%2d08" imps="22" clicks="0" ctr="0.0" cpc="0" cost="0" pos="2.1818181818" conv="0" convRate="0.0" costPerConv="0" maxCpc="0" keywordMinCpc="0" cpm="0"/>
*/

/*
keyword report only using campaing id
<?xml version="1.0" standalone="yes"?>
<report><table><columns><column name="campaignid"/><column name="campaign"/><column name="adgroupid"/><column name="adgroup"/><column name="keywordid"/><column name="kwSite"/><column name="kwSiteType"/><column name="siteKwStatus"/><column name="kwDestUrl"/><column name="imps"/><column name="clicks"/><column name="ctr"/><column name="cpc"/><column name="cost"/><column name="pos"/><column name="conv"/><column name="convRate"/><column name="costPerConv"/></columns><rows>

<row campaignid="8876311" campaign="current-s" adgroupid="241599091" adgroup="Matrimonials-S" keywordid="11884331" kwSite="indian matrimonial" kwSiteType="Broad" siteKwStatus="Active" kwDestUrl="http://www.jeevansathi.com/index.php?source=go%5fnri&amp;adnetwork=google&amp;account=jeevan%20saathi%20nri&amp;campaign=current%2ds&amp;adgroup=matrimonials%2ds&amp;keyword=indian%20matrimonial&amp;match=broad&amp;lmd=26%2dmar%2d08" imps="80" clicks="5" ctr="0.0625" cpc="15.12" cost="75.6" pos="3.475" conv="0" convRate="0.0" costPerConv="0" maxCpc="0" keywordMinCpc="0" cpm="0"/>
*/

/*
site targetted cpc campaign
<row campaignid="24872671" campaign="site targetted cpc nri" adgroupid="499686451" adgroup="Punjabi CPC NRI" keywordid="1955205511" kwSite="pz10.com" kwSiteType="WebSite" siteKwStatus="Active" kwDestUrl="http://www.jeevansathi.com/index.php?source=go_pnj&amp;adnetwork=google&amp;account=jeevan%20saathi%20nri&amp;campaign=site%20targetted%20cpc%20nri&amp;adgroup=punjabi%20cpc%20nri&amp;keyword=pz10%2ecom&amp;match=placement&amp;lmd=06%2dmar%2d08" imps="5678" clicks="4" ctr="7.044734061289186E-4" cpc="6.9575" cost="27.83" pos="2.5353997887" conv="1" convRate="0.25" costPerConv="27830000" maxCpc="0" keywordMinCpc="0" cpm="0"/>
*/


/*
//adgroup of 2nd jeevansathi account till i drop -c 
//$campaign = createCampaignObject(8969611);

$adGroup = createAdGroupObject(499688611);

//$criterias = getAllCriteria(244245031); //----  
$criterias = $adGroup->getAllCriteria();      //++++

echo 'All criterias of first ad group';
print_r($criterias);
echo '<br>';
echo '<br>';
echo '<br>';
*/



/*current used unit 15434
time taken for jeevansathis 1st account8096.98006177

Overall Consumed Units: 199784

Overall Performed Operations: 199784

The Response Times of the Last SOAP Requests (Max. 10, Oldest to Youngest):
  853  850  557  971  301  957  810  1196  160  877*/

/* DEPRICIATED IN VERSION 11
echo "<br /><b>Overall Consumed Units:</b> ".$soapClients->getOverallConsumedUnits()."<br />";
echo "<br /><b>Overall Performed Operations: </b>".$soapClients->getOverallPerformedOperations()."<br />";
*/

?>

