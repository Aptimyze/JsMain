<?php
class NumberofMatch{
function calculateNoOfMatches($propertyArr){
	global $db;
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
		$casteStringArr=get_all_caste($casteArr[1]);
		if($casteStringArr!=""){
			foreach($casteStringArr as $val)$castes.="$val,";
			$castes=substr($castes,0,-1);
			if(empty($where_query))
				$where_query="CASTE IN ($castes)";
			else
		    $where_query.=" AND CASTE IN ($castes)";
		}else
		   ;	
//			logError("Error in getting all related castes");
		
	}
	if($propertyArr['religion_c']){
			if(empty($where_query))
				$where_query="RELIGION = '".$propertyArr['religion_c']."'";
			else
			$where_query.=" AND RELIGION = '".$propertyArr['religion_c']."'";
		}
	if(empty($where_query))
		$calc_query="select SQL_CACHE COUNT(PROFILEID) from $table_name";
	else $calc_query="select SQL_CACHE COUNT(PROFILEID) from $table_name  where $where_query";
	$count_res=mysql_query_decide($calc_query);
	if($count_res){
		$count_arr=mysql_fetch_array($count_res);
		$count=$count_arr[0];
	    return $count;
	}
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
