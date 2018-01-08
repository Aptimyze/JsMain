<?php
/**
  * This is the control script for getting profiles for the similar profiles section on the detailed profile page.
  * author: Prinka Wadhwa
**/

include_once("algoSuggestedProfiles.php");
include_once("connect.inc");

$db = connect_db();

$data = authenticated();

if($data['PROFILEID'])
	$loggedIn = 1;


if($_GET['viewed'] && $_GET['viewedGender'])
{
	$viewedChecksum = $_GET['viewed'];
	$viewedArr = explode("i",$viewedChecksum);
	$viewed = $viewedArr[1];
	$viewedGender = $_GET['viewedGender'];

	if($viewedGender == 'M')
	{
		$viewedGender = 'MALE';
	}
	elseif($viewedGender == 'F')
	{
		$viewedGender = 'FEMALE';
	}

	if($loggedIn == 1)
	{
		$viewer = $data['PROFILEID'];
		$similarProfileids = getSimilarProfilesForLoggedInCase($viewer,$viewed,$viewedGender,$db);
	}
	else
	{
		$loggedIn = 0;
		if(isset($_COOKIE['ISEARCH']))
		{
			$similarProfileids = getSimilarProfilesForLoggedInCase($_COOKIE['ISEARCH'],$viewed,$viewedGender,$db);
		}
		else
		{
			$includeCaste=0;
			if($_GET['searchid']!='') //$includeCaste = 1 if the user came from search after specifying caste values else $includeCaste=0
			{
				if(!is_numeric($_GET['searchid']))
			        {
					include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
					ValidationHandler::getValidationHandler("","white listing handling : getSimilarProfiles.php");
					return NULL;
				}
				$searchid=$_GET['searchid'];
				$includeCaste = checkIfCasteSpecified($searchid,$db);
			}
			$similarProfileids = getSimilarProfilesForLoggedOutCase($viewed,$viewedGender,$includeCaste,$db);
		}
	}

	if($similarProfileids)
	{
		global $suggProfAlgo;

		foreach($similarProfileids as $id)
		{
			if($id != $viewed && ++$j<=$suggAlgoNoOfResults)
			{
				$ids[]=$id;
			}
		}

		$similarProfileids = implode(",",$ids);

		if($similarProfileids)
		{
			$shuffleResults=1;
			if($suggProfAlgo == 'contacts')
				$shuffleResults = 0;
//			elseif($suggProfAlgo == 'search')
//				$shuffleResults = 1;

			if($loggedIn == 1)
				echo $similarProfilesDetails = getSimilarProfilesDetails($similarProfileids,$db,$loggedIn,$shuffleResults,$viewer);
			else
				echo $similarProfilesDetails = getSimilarProfilesDetails($similarProfileids,$db,$loggedIn,$shuffleResults);
		}
		else
			echo 'noResultsFound';
	}
	else
	{
		//tracking zero results
		if($loggedIn == 1)
			$loginStatus = 'LoggedIn';
		elseif(isset($_COOKIE['ISEARCH']))
			$loginStatus = 'LoggedInThroughCookie';
		elseif($loggedIn == 0)
			$loginStatus = 'LoggedOut';
		trackZeroResults($loginStatus,$viewer,$viewed,$db);
		echo "noResultsFound";
	}
}
else
{
	echo "noResultsFound";
}
?>
