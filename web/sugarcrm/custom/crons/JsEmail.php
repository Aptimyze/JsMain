<?php
define('sugarEntry',true);
chdir(realpath(dirname(__FILE__))."/../..");
require_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
require_once('../profile/config.php');
//require_once('connect.inc');
require_once('custom/crons/JsMessage.php');
require_once('include/utils/Jsutils.php');
require_once('../../lib/model/lib/FieldMapLib.class.php');
require_once('../../lib/model/lib/search/SearchCommonFunctions.php');
require_once('../../lib/model/lib/CommonUtility.class.php');
require_once('../../lib/model/lib/CommonFunction.class.php');
require_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
require_once('../profile/connect_db.php');
connect_db();
class JsEmail extends JsMessage{
	
	private $smarty;
	private $timeConditionStr;
	function __construct($cronTimeCondition)
	{
		if($cronTimeCondition=="week")
		$this->timeConditionString=$this->createTimeBoundQuery('l.date_entered',array(21,28,35,42,49,56,63,70,77,84,91,98,105,112,119,126,133,140,147,154));
		else
		$this->timeConditionString=$this->createTimeBoundQuery('l.date_entered',array(1,2,4,6,8,10,12,14,16,18));
	}
	
	function sendMessage(){
	global $db;
	$subject="Register on Jeevansathi and see Phone/Email of members you like";
	$from="info@jeevansathi.com";
	$fromName="Jeevansathi Info";
	$reply_to="";
	$timeConditionStr=$this->timeConditionString;
	$lead_id_query="select id as lead_id from sugarcrm.leads as l,sugarcrm.leads_cstm as lc where l.id=lc.id_c AND lc.do_not_email_c='0' AND l.status IN (13,14,15,11,12,17) AND $timeConditionStr AND l.deleted='0' and lc.source_c<>11";
	$result_lead=$db->query($lead_id_query,true); 
	$no_rows=$db->getRowCount($result_lead);
	while($row=$db->fetchByAssoc($result_lead)){
		$lead_id=$row['lead_id'];
		$sql="INSERT IGNORE INTO sugarcrm.auto_mailer (id) VALUES ('$lead_id')";
		$db->query($sql);
		if($db->getAffectedRowCount())
		{
			$lead_query_string="select leads.date_entered as entry_date,leads.first_name as fname, leads.last_name as lname, lc.source_c as lead_source, leads.status, lc.age_c, lc.gender_c, lc.religion_c,lc.caste_c,lc.city_c,lc.enquirer_email_id_c,lc.date_birth_c,lc.mother_tongue_c,leads.campaign_id,js_source_c from sugarcrm.leads, sugarcrm.leads_cstm as lc where leads.id = '$lead_id' AND lc.id_c='$lead_id'";
			$lead_fields=$db->requireSingleRow($lead_query_string);
			$lead_fields['lead_id']=$lead_id;
			$lead_fields['count']=$this->calculateNoOfMatches($lead_fields);
			//If match count is less than 228 then make it default 228 
			if($lead_fields['count']<228)
				$lead_fields['count']=228;
			$messageToSend=$this->createMessage($lead_fields);
			if(empty($lead_fields['enquirer_email_id_c'])){
				$email_sql="select ea.email_address as email from email_addresses as ea, email_addr_bean_rel as eabr where eabr.email_address_id=ea.id AND eabr.bean_id='".$lead_id."' AND ea.invalid_email=0 AND ea.opt_out=0 AND ea.deleted = 0";
				$email=$db->getOne($email_sql);
			}else 
				$email=$lead_fields['enquirer_email_id_c'];
			if($email){
//				echo "<br>$email";
//				echo "<br>$messageToSend";
	 			send_email($email,$messageToSend, $subject,$from,"","","","","","",1,$reply_to,$fromName);
			}
		}
	}
	$sql="SELECT COUNT(*) from sugarcrm.auto_mailer";
	$no_leads=$db->getOne($sql);
	if($no_leads!=$no_rows)
		send_email("nikhil.dhiman@jeevansathi.com,nitesh.s@jeevansathi.com","Problem in auto followup mailer cron $no_leads $no_rows","Auto followup mailer cron didnt run completely");
	}

    
    function createMessage($propertyArr){
	$smarty=new Smarty();
		 global $RELIGIONS,$CASTE_DROP_SMALL,$MTONGUE_DROP_SMALL,$SITE_URL,$IMG_URL,$db;
    $smarty->template_dir=$_SERVER['DOCUMENT_ROOT']."/smarty/templates/mailer/";
    $smarty->compile_dir=$_SERVER['DOCUMENT_ROOT']."/smarty/templates_c/";
    $smarty->compile_check=true;
	// condition for mailer file name based on lead source
	if($propertyArr['lead_source']==4 || $propertyArr['lead_source']==6 || $propertyArr['lead_source']==7)
			$tpl_location="samajNewspaper.tpl";
	else
		$tpl_location="websiteLead.tpl";
	
	//Gender
	if($propertyArr['gender_c']=='M')
		$gender_lead="M";
    else
		$gender_lead="F"; // if we dnt have gender then it is to be treated as female
	
	//city
	if($propertyArr['city_c'])
		$city_lead=$propertyArr['city_c'];
    else
		$city_lead=""; // if we dnt have city then it is to be treated as blank

	if($propertyArr['js_source_c'])
		$source=$propertyArr['js_source_c'];
	else
		$source=fetchLeadSource($propertyArr['lead_source'],$propertyArr['campaign_id'],$db);
	
	//site url
	$smarty->assign('SITE_URL', JsConstants::$siteUrl);
	//img url
	$smarty->assign('IMG_URL', JsConstants::$imgUrl);
		
	if($propertyArr['status']=='24')
		$urlToSend=$SITE_URL."/P/sugarcrm_registration/registration_page1.php?source=$source&record_id=".$propertyArr['lead_id']."&sugar_incomplete=Y&secondary_source=M";
	else
		$urlToSend=$SITE_URL."/P/sugarcrm_registration/registration_page1.php?source=$source&secondary_source=M&record_id=".$propertyArr['lead_id'];
		$preHeader="Register on Jeevansathi and see Phone/Email of members you like";
		$smarty->assign('PREHEADER',$preHeader);
	$smarty->assign('Gender',$gender_lead);
	$smarty->assign('REG_URL',$urlToSend);
	$smarty->assign('FTO_WORTH',1100);
	//popular profiles for suggested matches 
	//foreach($arr as $key=>$val)
	$paramArr["LAGE"]=22;
	$paramArr["HAGE"]=27;
	if($gender_lead=="M")
		$paramArr["GENDER"]="F";
	else
		$paramArr["GENDER"]="M";
	$paramArr["HAVEPHOTO"]="Y";
	$paramArr["SORT_LOGIC"]="P";
	$paramArr["PHOTO_DISPLAY"]="A";
	$paramArr["PRIVACY"]="A";
	
        $searchProfileIds = SearchCommonFunctions::getProfilesBasedOnParams($paramArr,"sugar",1,$SITE_URL);
	    $profileString="'".$searchProfileIds['SEARCH_RESULTS'][0]."','".$searchProfileIds['SEARCH_RESULTS'][1]."','".$searchProfileIds['SEARCH_RESULTS'][2]."','".$searchProfileIds['SEARCH_RESULTS'][3]."'";
	    $pic_urls= SymfonyPictureFunctions::getPhotoUrls_nonSymfony($searchProfileIds['SEARCH_RESULTS'],"SearchPicUrl");

	$suggested_id_query="select PROFILEID,AGE,HEIGHT,CITY_RES,MTONGUE,OCCUPATION,INCOME from newjs.JPROFILE where PROFILEID IN ($profileString)";
	$result_suggested=$db->query($suggested_id_query,true);
	$count=1;
	while($row=$db->fetchByAssoc($result_suggested)){
		 
		$smarty->assign('AGE'.$count,$row['AGE']);
		$smarty->assign('HEIGHT'.$count,FieldMap::getFieldLabel("height",$row['HEIGHT']));
		
		$city=FieldMap::getFieldLabel("city",$row['CITY_RES']);
			if(strlen($city) <= 16)
			$smarty->assign('CITY'.$count,$city);
			else
			$smarty->assign('CITY'.$count,substr($city, 0,14)."...");
		
		$mtongue=FieldMap::getFieldLabel("community",$row['MTONGUE']);
			if(strlen($mtongue) <= 16)
				$smarty->assign('MTONGUE'.$count,$mtongue);
			else
				$smarty->assign('MTONGUE'.$count,substr($mtongue, 0,14)."...");
			
		$occupation=FieldMap::getFieldLabel("occupation",$row['OCCUPATION']);
			if(strlen($occupation) <= 16)
				$smarty->assign('OCCUPATION'.$count,$occupation);
			else
			$smarty->assign('OCCUPATION'.$count,substr($occupation, 0,14)."...");
		$smarty->assign('INCOME'.$count,FieldMap::getFieldLabel("income_level",$row['INCOME']));

		$smarty->assign('searchPicUrl'.$count,$pic_urls[$row['PROFILEID']]["SearchPicUrl"]);
		$profileLink=$SITE_URL."/profile/viewprofile.php?profilechecksum=".CommonFunction::createChecksumForProfile($row['PROFILEID']);
		$smarty->assign('profileLink'.$count,$profileLink);
		$count++;
	}
	$unsubscribe_url=$SITE_URL."/sugarcrm/unsubscribe.php?id=".$propertyArr['lead_id']."&source=lma";
	$smarty->assign('UNSUBSCRIBE_URL',$unsubscribe_url);
	if($city_lead)
	{
		$output = CommonFunction::getJsCenterDetails($city_lead);
		if(is_array($output))
		{
			$smarty->assign('center_flag',1);
			$smarty->assign('AGENT_NAME',$output['AGENT']);
			$smarty->assign('AGENT_MOBILE',$output['MOBILE']);
			$smarty->assign('AGENT_LOCALITY',$output['LOCALITY']);
		}
    		else
    			$smarty->assign('center_flag',0);	
		unset($output);
    	}
	else
	{
		$smarty->assign('center_flag',0);
	}
	
	$smarty->assign('FB_URL','http://www.facebook.com/jeevansathi');
	$smarty->assign('TOLLNO','1800-419-6299');
	$center_locations=$SITE_URL."/profile/contact.php";
	$smarty->assign('CENTER_LOCATIONS',$center_locations);
	$html_code=$smarty->fetch($tpl_location);
	return $html_code;
}

}
?>				
