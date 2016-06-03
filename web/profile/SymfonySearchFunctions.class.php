<?php
if(!$_SERVER['DOCUMENT_ROOT'])
include_once("config.php");

$symfonyFilePath=$_SERVER['DOCUMENT_ROOT']."/../";
include_once($symfonyFilePath.'/lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php');
include_once($symfonyFilePath.'/config/ProjectConfiguration.class.php');
if(JsConstants::$whichMachine=="local")
	$configuration =ProjectConfiguration::getApplicationConfiguration('jeevansathi', 'dev',true);
elseif(JsConstants::$whichMachine=="test")
	$configuration = ProjectConfiguration::getApplicationConfiguration('jeevansathi', 'test', false);
else
	$configuration =ProjectConfiguration::getApplicationConfiguration('jeevansathi', 'prod', false);

class SymfonySearchFunctions
{
	public static function suggestedAlgoSearch($gender,$religion,$caste,$mtongue,$lage,$hage,$withphoto,$manglik,$mstatus, $similarType = "",$numberOfResults="")
	{ 
		$paramArr["GENDER"] = $gender;
		$paramArr["RELIGION"] = $religion; 
		$paramArr["CASTE"] = $caste;
		$paramArr["MTONGUE"] = $mtongue;
		$paramArr["LAGE"] = $lage;
		$paramArr["HAGE"] = $hage;
		$paramArr["HAVEPHOTO"] = $withphoto;
		$paramArr["MANGLIK"] = $manglik;
		$paramArr["MSTATUS"] = $mstatus;
		$suggestedAlgoCount=SearchConfig::$suggestedAlgoCount;
		if( $similarType == "EOI")
			$suggestedAlgoCount=$numberOfResults;
                $SearchParametersObj = new SearchBasedOnParameters;
		$SearchParametersObj->getSearchCriteria($paramArr);
		$SearchParametersObj->setNoOfResults($suggestedAlgoCount);
		global $data;
		
		if($data["PROFILEID"])
		{
			$SearchUtilityObj =  new SearchUtility;
			$noAwaitingContacts = 1;
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master',$data["PROFILEID"]);
			$SearchUtilityObj->removeProfileFromSearch($SearchParametersObj,'spaceSeperator',$loggedInProfileObj,'',$noAwaitingContacts);
		}

		$SearchServiceObj = new SearchService;
		$respObj = $SearchServiceObj->performSearch($SearchParametersObj,"onlyResults");
		if($respObj->getSearchResultsPidArr() && is_array($respObj->getSearchResultsPidArr()))
			$output = implode(",",$respObj->getSearchResultsPidArr());
		return $output;
	}

	public static function crm_extraDetails_profile_search($gender='',$lage='',$hage='',$religion='',$income='',$caste='',$edu_level_new='')
	{
		$paramArr["GENDER"] = $gender;
		$paramArr["LAGE"] = $lage;
		$paramArr["HAGE"] = $hage;
		$paramArr["RELIGION"] = $religion;
		$paramArr["INCOME"] = $income;
		$paramArr["CASTE"] = $caste;
		$paramArr["EDU_LEVEL_NEW"] = $edu_level_new;
		$SearchParametersObj = new SearchBasedOnParameters;
		$SearchParametersObj->getSearchCriteria($paramArr);
		$SearchServiceObj = new SearchService;
		$respObj = $SearchServiceObj->performSearch($SearchParametersObj,"onlyCount");
		if($respObj->getTotalResults())
			$output = $respObj->getTotalResults();
		return $output;
	}
}
?>
