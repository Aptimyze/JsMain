<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

define('sugarEntry',true);
$path=$_SERVER[DOCUMENT_ROOT];
require_once("$path/profile/connect.inc");
chdir("$path/sugarcrm");
require_once("$path/sugarcrm/include/entryPoint.php");
require_once("$path/sugarcrm/include/utils/Jsde_duplicate.php");
require_once("$path/sugarcrm/include/utils/Jsutils.php");
require_once("$path/sugarcrm/include/utils/JsToLeadFieldMapping.php");
class IncompleteProfiles{
	var $duplicate;
	var $db_js;
	function IncompleteProfiles(){
		global $current_user;
		$current_user = new User();
		$current_user->getSystemUser();
		$db = connect_db();
		$db_slave = connect_slave();	
		$this->duplicate=new Duplicate($db);
		$this->duplicateSlave = new Duplicate($db_slave);
		$this->db_js=$db_slave;
		mysql_select_db("sugarcrm",$this->db_js);
		mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$this->db_js);
	}
/** get profile Ids that are not in sugar and update profile id that are already in sugarcrm
 * @param String Start time when Profile became incomplete 
 * @param String End time when Profile became incomplete
 * @return Array of ids which are not in Sugar Lead database
 * */
	function getIdsThatAreNotInSugar($start,$end){
		$sql="select PROFILEID,EMAIL, PHONE_MOB, PHONE_RES,USERNAME,STD from newjs.JPROFILE where MOD_DT<='$start' AND MOD_DT>'$end' AND ACTIVATED <> 'D' AND INCOMPLETE='Y'";
		$result=mysql_query_decide($sql,$this->db_js);
		$freshArr=array();
		while($row=mysql_fetch_array($result)){
			//If lead already exist with corresponding email and contact number, then update profileid field of the lead
		//	if(false){
			if($lead_id_Arr=$this->duplicateSlave->getDuplicateLeadId($row['EMAIL'],$row['PHONE_MOB'],$row['STD'],$row['PHONE_RES'], false)){
				foreach($lead_id_Arr as $lead_id)
				addProfileId($lead_id,$row['USERNAME'],$this->duplicate->db);
			}else{
				//Get the profile data relevant to Sugar LEAD
				array_push($freshArr,$row['PROFILEID']);
			}
		}
		return $freshArr;
}
/**
 *  @return Array of Profile's fields relevant to Lead Form.
 *  @param string jeevansathi profile id.
 *
 *  */
	function getProfileDetailsForSugar($profileid){
		$select=$this->createSelect();
		$sql="select $select from newjs.JPROFILE where PROFILEID='$profileid' and ACTIVATED<> 'D'";
		$sql1="select HOBBY from newjs.JHOBBY where PROFILEID='$profileid'";
		$sql2="select PLACE_BIRTH from newjs.ASTRO_DETAILS where PROFILEID='$profileid'";
		$profile_res=$this->duplicateSlave->db->requireSingleRow($sql);
		$profile_res1=$this->duplicateSlave->db->requireSingleRow($sql1);
		$profile_res2=$this->duplicateSlave->db->requireSingleRow($sql2);
		$sql="select NAME from incentive.NAME_OF_USER where PROFILEID='$profileid'";
		$res=$this->duplicateSlave->db->requireSingleRow($sql);
		if($profile_res1)
			$profile_res=array_merge($profile_res,$profile_res1);
		if($profile_res2)
			$profile_res=array_merge($profile_res,$profile_res2);
		$profile_res['NAME_OF_USER']=$res['NAME'];
		return $profile_res;
	}
	/**
	 * It will create select statement using columns listed in jsleadProfileMapping.php
	 * @returns String that is to be appended in query string that will fetch data from JPROFILE
	 * */
	function createSelect(){
		global $JPROFILECOLMS;
		foreach($JPROFILECOLMS as $column){
			$select.="$column, ";
		}
		return substr($select,0,-2);
	}	
	function convertProfileToLead($profileData){
		global $PROFILETOLEAD;
		$resArr=array();
		foreach($profileData as $key => $value){
			if(!empty($value)){
			if(array_key_exists($key,$PROFILETOLEAD))
				$resArr[$PROFILETOLEAD[$key]]=$value;	
			}
		}
		if(!empty($profileData['CITY_RES']))
			$resArr['city_c']=$profileData['CITY_RES'];
		else if(!empty($profileData['COUNTRY_RES']))
			$resArr['city_c']=$profileData['COUNTRY_RES'];
			$resArr['caste_c']=$profileData['RELIGION']."_".$profileData['CASTE'];
			$resArr['email1']=$profileData['EMAIL'];
			//Name of lead to be either his name or mobile/landline number
			if($profileData['NAME_OF_USER']!='')
				$last_name=$profileData['NAME_OF_USER'];
			else{
			if($profileData['PHONE_MOB']!='')
				$last_name=$profileData['PHONE_MOB'];
			else 
				if($profileData['PHONE_RES'])
					$last_name="0".$profileData['STD']."-".$profileData['PHONE_RES'];
			}
			$resArr['last_name']=$last_name;

			if($profileData['HAVEPHOTO']=='U' || $profileData['HAVEPHOTO']=='Y')
				$resArr['have_photo_c']=1;
			else 
				$resArr['have_photo_c']=0;
			//Make std field blank if landline not available
			if($profileData['STD']){
				if(!$profileData['PHONE_RES'])
					$resArr['std_c']='';
			}
			$resArr['js_source_c']=$profileData['SOURCE'];
			
			if($profileData['SOURCE'] && $profileData['SOURCE']!="unknown"){
				$sql_source="select GROUPNAME from MIS.SOURCE where SourceID='".$profileData['SOURCE']."'";
				$res_source=mysql_query_decide($sql_source,$db_js) or send_email("jaiswal.amit@jeevansathi.com","Problem in problem related to fetching source group","Problem in reglead to sugarlead cron");
				$row_source=mysql_fetch_assoc($res_source);
				if(strpos($row_source['GROUPNAME'],"SEO") !==false || strpos($row_source['GROUPNAME'],"jeevansathi") !==false || strpos($row_source['GROUPNAME'],"unknown") !==false )
					$resArr['source_c']=18;
				elseif(strpos($row_source['GROUPNAME'],"mobiledirect") !==false)
					$resArr['source_c']=20;
				else
					$resArr['source_c']=19;
			}
			else
				$resArr['source_c']=18;

			//Manglik is not mapped properly so handle it here. M in JPROFILE is Y in sugarcrm.
			//
			if($profileData['MANGLIK']=='M')
				$resArr['manglik_c']='Y';
			return $resArr;
		}
	function createLead($leadData){
		$db_to_update=connect_db();
                mysql_select_db("sugarcrm",$db_to_update);
		$jsLead=new Lead();
		foreach($leadData as $key =>$value)
			$jsLead->$key=$value;
		$jsLead->modified_user_id='1';
		$jsLead->created_by='1';
		$jsLead->assigned_user_id='1';
		$jsLead->status='24';
		//disposition value added by Sadaf
		$jsLead->disposition_c='23';
		$jsLead->opt_in_c='1';
		$jsLead->save();
	}
	function createLeadsFromProfileIds($pids){
		if(count($pids)){
			foreach($pids as $pid){
				$profileData=$this->getProfileDetailsForSugar($pid);
				$profileToLead=$this->convertProfileToLead($profileData);
				$this->createLead($profileToLead);
			}
		}
	}

}
