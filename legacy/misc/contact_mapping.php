<?php
ini_set(max_execution_time,0);
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
ini_set(default_socket_timeout,259200); // 3 daysini_set(log_errors_max_len,0);
$flag_using_php5=1;
include("connect.inc");

$db2=connect_db();
//$db2=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);

/*$ts=time();
$ts-=24*60*60;*/
$ts=$argv[1];
$today=date("Y-m-d",$ts);
$mysqlObj=new Mysql;
$senderID   = array();
$receiverID = array();

/*
$sql="SELECT SENDER,RECEIVER FROM newjs.CONTACTS_1 WHERE newjs.CONTACTS_1.TYPE='I' limit 100";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
	$senderID[] = $row["SENDER"];
	$receiverID[] = $row["RECEIVER"];
}
*/

for($i=0;$i<$noOfActiveServers;$i++)
{
        $tempDbName=$slave_activeServers[$i];
        $myDb=$mysqlObj->connect($tempDbName);
        $sql="SELECT SENDER,RECEIVER FROM newjs.CONTACTS,newjs.PROFILEID_SERVER_MAPPING WHERE newjs.CONTACTS.TIME BETWEEN '$today 00:00:00' AND '$today 23:59:59' AND newjs.CONTACTS.SENDER=newjs.PROFILEID_SERVER_MAPPING.PROFILEID AND newjs.CONTACTS.TYPE='I' AND newjs.PROFILEID_SERVER_MAPPING.SERVERID='$i'";
        $res=$mysqlObj->executeQuery($sql,$myDb);
        while($row=$mysqlObj->fetchAssoc($res))
        {
		$senderID[]   = $row["SENDER"];
		$receiverID[] = $row["RECEIVER"];		
        }
        unset($myDb);
        unset($tempDbName);
}

// Initialization
$tot 		= count($senderID);
$uniq_prof	= array();
$professionArr	= array();
$uniq_income	= array();
$incomeArr	= array();
$uniq_caste	= array();
$casteArr	= array();
$uniq_i_to_c	= array();
$i_to_cArr	= array();
$uniq_i_to_p	= array();
$i_to_pArr	= array();

for($cnt=0; $cnt<$tot; $cnt++)
{
	$profileID 	= "";
	$gender 	= array();
	$profession 	= array();
	$income 	= array();
	$caste 		= array();

	$profileID = $senderID[$cnt].",".$receiverID[$cnt];
	$sql="SELECT PROFILEID,GENDER,OCCUPATION,INCOME,MTONGUE FROM newjs.JPROFILE WHERE PROFILEID in ($profileID)";
	$res=mysql_query($sql,$db2) or logError($sql,$db2);
	while($row=mysql_fetch_array($res))
	{
		$gender[$row['PROFILEID']]       = $row['GENDER'];
		$profession[$row['PROFILEID']] 	 = $row['OCCUPATION'];		
		$income[$row['PROFILEID']]	 = $row['INCOME'];
		$caste[$row['PROFILEID']]        = $row['MTONGUE'];

	}
	$G = $gender[$senderID[$cnt]];
	// profession to profession
	$profVal = field_mapping($profession,$profileID);
	if($profVal){
		$uniq_prof[] = $profVal; 	
		$professionArr[$G][$profVal][] = $profVal;	
	}
	// income to income 
        $incomeVal = field_mapping($income,$profileID);
        if($incomeVal){
		$uniq_income[] = $incomeVal;
	        $incomeArr[$G][$incomeVal][] = $incomeVal;
        }
	// community to community
        $casteVal = field_mapping($caste,$profileID);
        if($casteVal){
		$uniq_caste[] = $casteVal;
             	$casteArr[$G][$casteVal][] = $casteVal;
        }
	// income to community
        $i_to_c_Val = field_mapping($income,$profileID,$caste);
      	if($i_to_c_Val){
		$uniq_i_to_c[] = $i_to_c_Val;
        	$i_to_cArr[$G][$i_to_c_Val][] = $i_to_c_Val;
        }
	// income to profession
        $i_to_p_Val = field_mapping($income,$profileID,$profession);
        if($i_to_p_Val){
		$uniq_i_to_p[] = $i_to_p_Val;	
        	$i_to_pArr[$G][$i_to_p_Val][] = $i_to_p_Val;
        }
}
$professionResult		= resultCount($professionArr,$uniq_prof);
$salaryResult 			= resultCount($incomeArr,$uniq_income);
$casteResult 		     	= resultCount($casteArr,$uniq_caste);
$income_to_community_Result     = resultCount($i_to_cArr,$uniq_i_to_c);
$income_to_profession_Result    = resultCount($i_to_pArr,$uniq_i_to_p);

set_result($professionResult, 'profession',$today);
set_result($salaryResult, 'salary',$today);
set_result($casteResult, 'community',$today);
set_result($income_to_community_Result, 'salary#community',$today);
set_result($income_to_profession_Result, 'salary#profession',$today);

// Functions Defined
function set_result($resultArr, $type, $date)
{
	$cnt_resultArr = count($resultArr);
	for($r=0; $r<$cnt_resultArr; $r++)
	{
		$resultArr_Val = explode("#",$resultArr[$r]);		
		$field = $resultArr_Val[0]; 

		$fieldOthers = explode("-",$field);
		$field1 = $fieldOthers[0];
		$field2 = $fieldOthers[1];	

		$male   = $resultArr_Val[1];
		$female = $resultArr_Val[2];

		insert_Query($male, $female, $field1, $field2, $type,$date);
	}
}
function insert_Query($m_cnt, $f_cnt, $map_field1, $map_field2, $type,$date)
{
	global $db2;
        $sql_insert="INSERT INTO newjs.CONTACTS_MAP(`DATE`,`MALE`,`FEMALE`,`MAP_FIELD_1`,`MAP_FIELD_2`,`TYPE`) VALUES('$date','$m_cnt','$f_cnt','$map_field1','$map_field2','$type')";
        //mysql_query($sql_insert) or logError($sql_insert,$db2);// devServer 
	mysql_query($sql_insert,$db2) or logError($sql_insert,$db2);	
}
function resultCount($array1,$fieldArray)
{
	if( (!is_array($array1)) || (!is_array($fieldArray)) )
		return;
	$fieldResult = array();
	$uniq_fieldArray = array_unique($fieldArray);
	$count =0;
	foreach($uniq_fieldArray as $Fkey=>$Fvalue) 
	{
		$totCnt_M = count($array1['M'][$Fvalue]);
		$totCnt_F = count($array1['F'][$Fvalue]);
		$fieldResult[$count] = $Fvalue."#".$totCnt_M."#".$totCnt_F;
		$count++;
	}
	return $fieldResult;
}
function field_mapping($field1,$profileID,$field2="")
{
	if(!is_array($field1))
		return;
	$id 	= explode(",",$profileID);
	$sender_ID	=$id[0];
	$receiver_ID	=$id[1];
	if($field2){
		if( ($field1[$sender_ID] !=0) && ($field2[$receiver_ID] !=0) ){
			$returnVal = $field1[$sender_ID]."-".$field2[$receiver_ID];
			return $returnVal; 
		}
	}
	else{
		if( ($field1[$sender_ID]!=0) && ($field1[$receiver_ID]!=0) )
			$returnVal = $field1[$sender_ID]."-".$field1[$receiver_ID];
			return $returnVal; 
	}
	return false;	
}
?>
