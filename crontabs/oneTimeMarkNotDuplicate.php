<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

ini_set('max_execution_time','0');
ini_set('memory_limit',-1);
chdir(dirname(__FILE__));
include("../config.php");
include("../connect.inc");

include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");


$mysqlObj=new Mysql;

$db=connect_db();
mysql_query("set session wait_timeout=1000",$db);
	
			

			$profile1=new Profile();
			$profile2=new Profile();

			$rawDuplicateObj= new RawDuplicate();
		  	$rawDuplicateObj->setReason(REASON::NONE); 
			$rawDuplicateObj->setIsDuplicate(IS_DUPLICATE::NO); 
			$rawDuplicateObj->addExtension('MARKED_BY','SYSTEM');
	  		$rawDuplicateObj->setScreenAction(SCREEN_ACTION::NONE);
	  		$rawDuplicateObj->addExtension('IDENTIFIED_ON',date('Y-m-d H:i:s'));
	  		$rawDuplicateObj->setComments("None");
		  	$arr=(new DUPLICATE_PROFILE_LOG())->fetchConfirmedDuplicates();
	foreach ($arr as $key => $value) {
		# code...
		  $profile1->getDetail($arr['PROFILE1'],'PROFILEID','PROFILEID,GENDER,ACTIVATED,ENTRY_DT');
		  $profile2->getDetail($arr['PROFILE2'],'PROFILEID','PROFILEID,GENDER,ACTIVATED,ENTRY_DT');
		  if(($profile1->getGENDER() != $profile2->getGENDER()) && ($profile1->getACTIVATED()=='D' || $profile2->getACTIVATED()=='D')  )
		  {

		  	$rawDuplicateObj->setProfile1($profile1->getPROFILEID()); 			
		  	$rawDuplicateObj->setProfile2($profile2->getPROFILEID()); 			
			$ProbableRes=new PROBABLE_DUPLICATES();
			$ProbableRes->removeProbable($rawDuplicateObj);
			$ProbableRes->unsetPriority($rawDuplicateObj);
			DuplicateHandler::DuplicateProfilelog($rawDuplicateObj);
			DuplicateHandler::MarkNotDuplicate($rawDuplicateObj);

			$dupArr[$profile1->getPROFILEID()]= $dupArr[$profile1->getPROFILEID()]!='N' ? 'Y' : 'N';
			$dupArr[$profile2->getPROFILEID()]= $dupArr[$profile2->getPROFILEID()]!='N' ? 'Y' : 'N';


		}	
		else {
			$timeStamp1=JSstrToTime($profile1->getENTRY_DT());
			$timeStamp2=JSstrToTime($profile2->getENTRY_DT());
			
			if($timeStamp1 > $timeStamp2)
			{
				$dupArr[$profile1->getPROFILEID()]= 'N';
			}
			else
				 $dupArr[$profile2->getPROFILEID()]='N';
		}

		$notDuplicateObj=new DUPLICATES_PROFILES();
		foreach ($dupArr as $key => $value) {
			# code...
	  		if($value=='Y')
	  		$notDuplicateObj->removeProfileAsDuplicate($key);

		}




		}