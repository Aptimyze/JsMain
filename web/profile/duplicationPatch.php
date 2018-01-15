<?php
include_once("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/functions_edit_profile.php");
$symfonyFilePath=realpath($_SERVER['DOCUMENT_ROOT']."/../");
include_once($symfonyFilePath."/lib/model/lib/FieldMapLib.class.php");
include_once($symfonyFilePath."/lib/model/lib/Flag.class.php");
$db_sl = connect_slave();
$db = connect_db();
$sqlExc = "SELECT PROFILE1,PROFILE2 FROM  duplicates.DUPLICATE_PROFILE_LOG WHERE entry_date >  '2013-09-04' AND screened_action =  'IN' AND comments LIKE  '%OPS%bi%' AND reason =  'PHONE'";
$resExc = mysql_query($sqlExc,$db) or die($sqlExc);
while($rowExc = mysql_fetch_array($resExc))
{
	$profileExc[$rowExc['PROFILE1']][] = $rowExc['PROFILE2'];
	$excludedProfile1[] = $rowExc['PROFILE1'];
}
for($i=0;$i<1;$i++)
{
$profileDetails = array();
$sql = "SELECT * FROM duplicates.DUPLICATE_PROFILE_LOG WHERE ENTRY_DATE > '2013-09-04' AND REASON = 'PHONE' AND COMMENTS REGEXP 'Parameters__LANDLINE:.{0,5}$'";
$res = mysql_query($sql,$db) or die($sql);
while($row = mysql_fetch_array($res))
{	if(in_array($row['PROFILE1'],$excludedProfile1) && in_array($row['PROFILE2'],$profileExc[$row['PROFILE1']]))
		continue;
	$profileDetails[$row['PROFILE1']][] = $row['PROFILE2'];
}
if(is_array($profileDetails))
{
	foreach($profileDetails as $profile1=>$profileArray)
	{
		$dup_flag_value = array();
		$to_not_update_dup=false;
		$dup_flag_value=get_from_duplication_check_fields($profile1);
		if($dup_flag_value)
		{
			if($dup_flag_value[TYPE]=='NEW')
				$to_not_update_dup=true;
			else
				$dup_flag_value=$dup_flag_value['FIELDS_TO_BE_CHECKED'];
		}
		if(!$to_not_update_dup)
		{
			$dup_flag_value=Flag::setFlag('phone_res',$dup_flag_value,'duplicationFieldsVal');
			insert_in_duplication_check_fields($profile1,'edit',$dup_flag_value);
		}
		$strProfile2 = "";
		foreach($profileArray as $k=> $profile2)
		{
			$profileid = "";
			$dup_flag_value = array();
			$to_not_update_dup = false;
			/*insert in checl fields*/
			$profileid = $profile2;
			$dup_flag_value=get_from_duplication_check_fields($profileid);
			if($dup_flag_value)
			{
				if($dup_flag_value[TYPE]=='NEW')
					$to_not_update_dup=true;
				else
					$dup_flag_value=$dup_flag_value[FIELDS_TO_BE_CHECKED];
			}
			if(!$to_not_update_dup)
			{
				$dup_flag_value=Flag::setFlag('phone_mob',$dup_flag_value,'duplicationFieldsVal');
				$dup_flag_value=Flag::setFlag('phone_res',$dup_flag_value,'duplicationFieldsVal');
				$dup_flag_value=Flag::setFlag('alt_mobile',$dup_flag_value,'duplicationFieldsVal');
				insert_in_duplication_check_fields($profileid,'edit',$dup_flag_value);
			}
			/*\\insert in checl fields*/
			if($strProfile2!='')
				$strProfile2 .= ",";
			$strProfile2.="'".$profile2."'";
		}
echo		$sqlRemove = "DELETE FROM duplicates.PROBABLE_DUPLICATES WHERE PROFILE1 = '".$profile1."' AND PROFILE2 IN (".$strProfile2.") AND REASON='PHONE' AND ENTRY_DATE > '2013-09-04 00:00:00'";
echo "\n";
		$resRemove = mysql_query($sqlRemove,$db) or die($sqlRemove);
		$sqlRemoveLog = "DELETE FROM duplicates.DUPLICATE_PROFILE_LOG WHERE `PROFILE1` = '".$profile1."' AND `PROFILE2` IN (".$strProfile2.") AND REASON='PHONE' AND `ENTRY_DATE` > '2013-09-04' AND COMMENTS REGEXP 'Parameters__LANDLINE:.{0,5}$'";
		$resRemoveLog = mysql_query($sqlRemoveLog,$db) or die($sqlRemoveLog);
	}
}
}
?>
