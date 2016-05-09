<?php
require_once JsConstants::$docRoot.'/fto/lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
require_once(JsConstants::$docRoot.'/fto/config/ProjectConfiguration.class.php');
include(dirname(__FILE__).'/../../bootstrap/unit.php');
$configuriation =ProjectConfiguration::getApplicationConfiguration('jeevansathi','prod',false);

$testCasesObj= new testing_states;

$profilesToBeTested=array(350,248,227,221);
$limitExhausted='N';//if exhauting profiles needs to be checked, set it Y

$numberOfTestCases= count($profilesToBeTested);

$t = new lime_test($numberOfTestCases, new lime_output_color());

foreach($profilesToBeTested as $k=>$profileid)
{
	$p1 = new Profile('',$profileid);
        $p1->getDetail("","","PROFILEID,MTONGUE,RELIGION,GENDER,AGE,MOB_STATUS,LANDL_STATUS,HAVEPHOTO");
	$profilesInSubState[$p1->getPROFILE_STATE()->getFTOStates()->getSubState()][]=$profileid;
}
foreach($profilesInSubState as $substate=>$profiles)
{
	$testCasesForSubstate=$testCasesObj->getTestCase($substate,'',$limitExhausted);
	foreach($testCasesForSubstate as $key=>$testCase)
	{
		$profileid=$profilesInSubState[$substate][$key];
		if($profileid)
		{
			$p1= new Profile('',$profileid);
			$p1->getDetail("","","PROFILEID,MTONGUE,RELIGION,GENDER,AGE,MOB_STATUS,LANDL_STATUS,HAVEPHOTO");
			updateAction($p1, $testCase['ACTION']);
			$p1->getDetail("","","PROFILEID,MTONGUE,RELIGION,GENDER,AGE,MOB_STATUS,LANDL_STATUS,HAVEPHOTO");
			$p1->getPROFILE_STATE()->updateFTOState($p1, $testCase['ACTION']);
			$t->is($p1->getPROFILE_STATE()->getFTOStates()->getSubState(),$testCase['NEXT_SUB_STATE'],"Test Id:".$testCase['ID']."\t Profileid:".$profileid."\n");
		}
	}
}
function updateAction(Profile $p1,$action)
{
	$jp=new JPROFILE;
	switch($action)
	{
		case FTOStateUpdateReason::SCREEN:
			break;
		case FTOStateUpdateReason::PHOTO_UPLOAD:
			$jp->edit(array(HAVEPHOTO=>'Y'),$p1->getPROFILEID(),"PROFILEID");
			break;
		case FTOStateUpdateReason::PHOTO_DELETE:
			$jp->edit(array(HAVEPHOTO=>'N'),$p1->getPROFILEID(),"PROFILEID");
			break;
		case FTOStateUpdateReason::NUMBER_VERIFY:
			$jp->edit(array(MOB_STATUS=>'Y'),$p1->getPROFILEID(),"PROFILEID");
			break;
		case FTOStateUpdateReason::NUMBER_UNVERIFY:
			$jp->edit(array(MOB_STATUS=>'N',LANDL_STATUS=>'N'),$p1->getPROFILEID(),"PROFILEID");
			break;
		case FTOStateUpdateReason::EOI_SENT:
			print_r($p1->eoiCount);
			$p1->eoiCount+=1;
			break;
		case FTOStateUpdateReason::ACCEPT_SENT:
			print_r($p1->acceptSent);
			$p1->acceptSent+=1;
			break;
			case FTOStateUpdateReason::ACCEPT_RECEIVED:
			print_r($p1->acceptReceived);
			$p1->acceptReceived+=1;
			break;
	}
}
?>
