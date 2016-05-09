<?php
define('sugarEntry',true);
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/profile/functions.inc");
include($_SERVER['DOCUMENT_ROOT']."/sugarcrm/custom/crons/housekeepingConfig.php");
include($_SERVER['DOCUMENT_ROOT']."/sugarcrm/include/utils/systemProcessUsersConfig.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

global $partitionsArray;
global $PROFILE_3D;
global $process_user_mapping;

$processUserId=$process_user_mapping["registered_disposition"];
if(!$processUserId)
	$processUserId='1';

$updateTime=date("Y-m-d H:i:s");

$dbSlave=connect_slave();
mysql_query("set session wait_timeout=10000",$dbSlave);

$db=connect_db();
mysql_query("set session wait_timeout=10000",$db);

$profileCompletenessThreshold=80;

$chunk=1000;

$sql="SELECT id,jsprofileid_c,disposition_c FROM sugarcrm.leads JOIN sugarcrm.leads_cstm ON id=id_c WHERE status='26' AND disposition_c IN ('30','29','1','2')";
$res=mysql_query_decide($sql,$dbSlave) or die("Error while fetching leads info ".mysql_error_js());
$count=mysql_num_rows($res);
$totalChunks=ceil($count/$chunk);

for($i=0;$i<$totalChunks;$i++)
{
	$trans=0;
	$skip=$i*$chunk;
	mysql_data_seek($res,$skip);
	unset($profileArr);
	unset($profileInfo);
	unset($profileString);
	unset($updateForPhotoArr);
	unset($checkForCompletenessArr);
	unset($updateForCompletenessArr);
	while(($row=mysql_fetch_assoc($res)) && $trans<$chunk)
	{
		$trans++;
		if($row["jsprofileid_c"])
			$profileArr[$row["disposition_c"]][]=$row["jsprofileid_c"];
	}
	if(is_array($profileArr))
	{
		foreach($profileArr as $disposition=>$profiles)
			$profileString.="'".implode($profiles,"','")."',";
		$profileString=trim($profileString,",");
		$sqlProfileInfo="SELECT PROFILEID,USERNAME,HAVEPHOTO,GENDER,HAVEPHOTO,PHOTO_DISPLAY,RELIGION,EDUCATION,JOB_INFO,WORK_STATUS,BTYPE,COMPLEXION,DIET,SMOKE,DRINK,SHOW_HOROSCOPE,HOROSCOPE_MATCH,MANGLIK,CITY_RES,FAMILYINFO,FAMILY_STATUS,FAMILY_TYPE,FAMILY_BACK,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,PARENT_CITY_SAME,YOURINFO,PHONE_RES,PHONE_MOB,SUBCASTE,CASTE,EDU_LEVEL_NEW,EDU_LEVEL,OCCUPATION,INCOME,PRIVACY,MOB_STATUS,LANDL_STATUS,PHONE_FLAG FROM newjs.JPROFILE WHERE USERNAME IN ($profileString)";
		$resProfileInfo=mysql_query_decide($sqlProfileInfo,$dbSlave) or die("Error while fetching profile info ".mysql_error_js());
		while($rowProfileInfo=mysql_fetch_assoc($resProfileInfo))
			$profileInfo[$rowProfileInfo["USERNAME"]]=$rowProfileInfo;
		foreach($profileArr as $disposition=>$profiles)
		{
			foreach($profiles as $profile)
			{
				$havePhoto=$profileInfo[$profile]["HAVEPHOTO"];
				if($disposition=='30')
				{
					if($havePhoto=='' || $havePhoto=='N')
						$updateForPhotoArr[]=$profile;
				}
				if($havePhoto=='Y')
					$checkForCompletenessArr[]=$profile;
			}
		}
		if(is_array($updateForPhotoArr))
		{
			$updateSql="UPDATE sugarcrm.leads_cstm JOIN sugarcrm.leads ON id=id_c SET disposition_c='1',modified_user_id='$processUserId',date_modified='$updateTime' WHERE jsprofileid_c IN ('".implode($updateForPhotoArr,"','")."') AND status='26' AND disposition_c='30'";
			mysql_query_decide($updateSql,$db) or die("Error while updating db ".mysql_error_js());
		}
		if(is_array($checkForCompletenessArr))
		{
			foreach($checkForCompletenessArr as $profile)
			{
				$profileid=$profileInfo[$profile]["PROFILEID"];
				$PROFILE_3D=$profileInfo[$profile];
				
				$cScoreObject = ProfileCompletionFactory::getInstance(null,null,$profileid);
				$completenessPercent= $cScoreObject->getProfileCompletionScore();
				//$completenessPercent=profile_percent($profileid);
				
				if($completenessPercent<$profileCompletenessThreshold)
					$updateForCompletenessArr[]=$profile;
				elseif($completenessPercent>=$profileCompletenessThreshold)
					$updateForFinallyArr[]=$profile;
			}
			if(is_array($updateForCompletenessArr))
			{
				$updateSql="UPDATE sugarcrm.leads_cstm JOIN sugarcrm.leads ON id=id_c SET disposition_c='2',modified_user_id='$processUserId',date_modified='$updateTime' WHERE jsprofileid_c IN ('".implode($updateForCompletenessArr,"','")."') AND status='26' AND disposition_c IN ('30','1','29','2')";
				mysql_query_decide($updateSql,$db) or die("Error while updating db ".mysql_error_js());
			}
			if(is_array($updateForFinallyArr))
			{
				$updateSql="UPDATE sugarcrm.leads_cstm JOIN sugarcrm.leads ON id=id_c SET disposition_c='31',modified_user_id='$processUserId',date_modified='$updateTime' WHERE jsprofileid_c IN ('".implode($updateForFinallyArr,"','")."') AND status='26' AND disposition_c IN ('30','1','29','2')";
				mysql_query_decide($updateSql,$db) or die("Error while updating db ".mysql_error_js());
			}
		}
	}
}
?>
