<?php
define('sugarEntry',true);
chdir(realpath(dirname(__FILE__))."/../..");
require_once('include/entryPoint.php');
require_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
abstract class JsMessage{
	function __construct(){
	}
function createTimeBoundQuery($columnName,$dayArr){
	$days=implode(",",$dayArr);
	$sql="DATEDIFF(NOW(),if($columnName>'2011-03-30',$columnName,'2011-03-30')) IN ($days)";
/*	$unixTime=date("Y-m-d");
	$boundaryTimeArr=array();
	foreach($dayArr as $day){
		$temp=JSstrToTime("-$day day", JSstrToTime($unixTime));
		$boundaryTimeArr[$day]=date("Y-m-d",$temp);
	}
	if(!count($boundaryTimeArr)){
		//log error
		die("Error in calculating time boundary");
	}
	foreach($boundaryTimeArr as $key=>$adate){
		$start="$adate 00:00:00";
		$end="$adate 23:59:59";
		$timeConditionStr.="($columnName >= str_to_date('".$start."','%Y-%m-%d %H:%i:%s') AND $columnName <= str_to_date('".$end."', '%Y-%m-%d %H:%i:%s')) OR ";
	}*/
	return $sql;
}
abstract function createMessage($propertyArr);
abstract function sendMessage();
function calculateNoOfMatches($propertyArr){
	global $db;
//	print_r($propertyArr);echo "<br>";
	if($propertyArr['date_birth_c'])
		$age=$this->getAge($propertyArr['date_birth_c']);
	else
		$age=$propertyArr['age_c'];
	$table_name=($propertyArr['gender_c']=='M')?'newjs.SEARCH_FEMALE':'newjs.SEARCH_MALE';
	if($age){
		if($age>=18 && $age<=70){
		switch($propertyArr['gender_c']){
		case 'M': $where_query="$table_name.AGE <= ".$age;
		break;
		case 'F': $where_query="$table_name.AGE >= ".$age;
		break;
		}
		}
	}
	if($propertyArr['caste_c']){
		$casteArr=explode("_",$propertyArr['caste_c']);
		
		$casteStringArr=$this->get_all_caste($casteArr[1]);
		if($casteStringArr!=""){
			foreach($casteStringArr as $val)$castes.="$val,";
			$castes=substr($castes,0,-1);
			if(empty($where_query))
				$where_query="CASTE IN ($castes)";
			else
		    $where_query.=" AND CASTE IN ($castes)";
		} 
		
	}
	if($propertyArr['religion_c']){
			if(empty($where_query))
				$where_query="RELIGION = '".$propertyArr['religion_c']."'";
			else
			$where_query.=" AND RELIGION = '".$propertyArr['religion_c']."'";
		}
	if(empty($where_query))
		$calc_query="select SQL_CACHE COUNT(*) from $table_name";
	else $calc_query="select SQL_CACHE COUNT(*) from $table_name  where $where_query";
//	echo "$calc_query<br>";
	    $count=$db->getOne($calc_query);
	    return $count;

}
function get_all_caste($caste){
	global $db;
		if($caste=="1" || $caste=="148" ||$caste=="153")
			$Caste_arr="";
		else{
			//REVAMP JS_DB_CASTE
			    include_once("../profile/connect_db.php");
			    $db1=connect_slave();
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
        		$Caste_arr = get_all_caste_revamp_js_db($caste,$db1,1);
        		//REVAMP JS_DB_CASTE
		}
	if(is_array($Caste_arr))
		return array_unique($Caste_arr);
	else 
		return "";
}
function createCompleteMessage($lead_id, $lead_query_string){
	global $db;
	$result_fields=$db->requireSingleRow($lead_query_string);
    $result_fields['lead_id']=$lead_id;
    $result_fields['count']=$this->calculateNoOfMatches($result_fields);
	if($result_fields['count']<228)
		$result_fields['count']=228;
    $messageToSend=$this->createMessage($result_fields);
	return $messageToSend;
}
function getAge($bdate) 
{
		$bdate_arr=explode("-",$bdate);
		$year=$bdate_arr[0];
		$month=$bdate_arr[1];
		$day=$bdate_arr[2];
	    $iAge = date('Y') - $year;
		if(date('m') < $month) 
		{
		   return --$iAge;
		} 
		elseif(date('m') == $month) 
		{
		   if(date('d') < $day) 
		   {
		      return $iAge - 1;
		   } 
		   else 
		   {
		      return $iAge;
		   }
		 } 
	    else 
	    {
	       return $iAge;
	    }
}  
}
