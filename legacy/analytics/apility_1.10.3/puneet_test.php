<?php
include('apility.php');
include('connect_adwords_db.php');
$Developer_Token = "zT7sAKIbbXRFNZWe9uIBWA";
$Application_Token = "kIjlB_mJV4hfEQxlUe0S3A";

 $authenticationContext = new APIlityAuthentication(
          $Email[0],
          $Password[0],
          $Developer_Token,
          $Client_Email[0],
          $Application_Token
        );


print_r($authenticationContext);  
print_r(getAccountInfo());
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

/*$allCampaigns = getAllCampaigns(); 
print_r($allCampaigns);*/ //took around 10 min

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
//$db=mysql_connect("172.16.3.182","user","CLDLRTa9") or die ('cud not connect');
//mysql_select_db("adwords_jeevansathi");
echo 'puneet is a good boy';

/*
$sql="select * from adwords_jeevansathi.AdGroup";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
print_r($row);
*/

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


/*current used unit 15434
time taken for jeevansathis 1st account8096.98006177

Overall Consumed Units: 199784

Overall Performed Operations: 199784

The Response Times of the Last SOAP Requests (Max. 10, Oldest to Youngest):
  853  850  557  971  301  957  810  1196  160  877*/

echo "<br /><b>Overall Consumed Units:</b> ".$soapClients->getOverallConsumedUnits()."<br />";
echo "<br /><b>Overall Performed Operations: </b>".$soapClients->getOverallPerformedOperations()."<br />";
?>

