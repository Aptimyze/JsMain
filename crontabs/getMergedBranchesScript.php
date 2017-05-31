<?php
/*
	This script is created to get the branhes merged on given number of days from a given target branch. We are uing three parameters currently in running the script.	
	1) target branch (QASanityReleaseNew,CIRelease)	
	2) pathName (e.g.: /var/www/testjs12/)
	It will calculate the number of branches merged and store it in a file.
*/

//arguments provided in the script
$targetBranch = $argv[1];
$pathName = $argv[2];

$urlToHit = "http://gitlabweb.infoedge.com/api/v3/projects/Jeevansathi%2FJsMain/merge_requests?per_page=50&state=merged";

$headerArr = array(
	'PRIVATE-TOKEN:YY7g4CeG_tf17jZ4THEi',				
	); //Token used is of the username : vidushi@naukri.com

$SanityMergedFileName = $pathName."/crontabs/QASanityMergedBranches.txt";

$CIMergedFileName = $pathName."/crontabs/CIMergedBranches.txt";

//last released branch name is stored in this file
$lastReleasedBranchFileName = $pathName."/crontabs/lastReleasedBranch.txt"; 


//To get files arr by reading the entire file
$SanityFilesArr = file($SanityMergedFileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);


$CIFilesArr = file($CIMergedFileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$QASanityReleaseBranchesArr = array();
$CIBranchesArr = array();
$releaseBranch = "";
$targetBranchQA = "QASanityReleaseNew";
$targetBranchCI = "CIRelease";
$currentDateTime = date("Y-m-d H:i:s");

if($targetBranch == $targetBranchCI)
{	
	$CIlastReleaseDateFileName = $pathName."/crontabs/CIReleaseLastReleaseDate.txt";
	$CILastReleaseDateArr = file($CIlastReleaseDateFileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	$CILastReleaseDate = $CILastReleaseDateArr[0];
	
	//considering that CIRelease goes live very frequently, the pagination limit has been reduced and kept to the default 20.
	$urlToHit = "http://gitlabweb.infoedge.com/api/v3/projects/Jeevansathi%2FJsMain/merge_requests?state=merged";

	$response = sendCurlGETRequest($urlToHit,'',"",$headerArr,"GET");

	foreach($response as $key=>$value)
	{
		$updatedAtDate = explode(".",$value->updated_at);
		$updatedDate = str_replace("T"," ", $updatedAtDate[0]);
		
		if($value->target_branch == $targetBranchCI && ($updatedDate > $CILastReleaseDate && $updatedDate < $currentDateTime))
		{
			$CIBranchesArr[$value->source_branch] = $value->updated_at;
		}
		unset($updatedAtDate);
	}

	//Adding CIRelease JIRA IDs to file
	if($file = fopen($CIMergedFileName, "a"))
	{
		if(is_array($CIBranchesArr) && !empty($CIBranchesArr))
		{
			foreach($CIBranchesArr as $key=>$value)
			{
				if(!in_array($key, $CIFilesArr))
				{
					fwrite($file, $key."\n");
				}
			}
		}
	}
	die("script finished execution for CIRelease");
}
elseif($targetBranch == $targetBranchQA)
{
	$SanitylastReleaseDateFileName = $pathName."/crontabs/QASanityLastReleaseDate.txt";

	$sanityLastReleaseDateArr = file($SanitylastReleaseDateFileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	$sanityLastReleaseDate = $sanityLastReleaseDateArr[0];
	$response = sendCurlGETRequest($urlToHit,'',"",$headerArr,"GET");
	foreach($response as $key=>$value)
	{
		$updatedAtDate = explode(".",$value->updated_at);
		$updatedDate = str_replace("T"," ", $updatedAtDate[0]);

	//echo("updateDate:".$updatedDate."\n greater than requiredDate:".$sanityLastReleaseDate."\n less than current date:".$currentDateTime);die;

		if($updatedDate > $sanityLastReleaseDate && $updatedDate < $currentDateTime)
		{	
			if($value->target_branch == $targetBranchQA)
			{
				$QASanityReleaseBranchesArr[$value->source_branch] = $value->updated_at;	
			}
			elseif($value->target_branch == $targetBranchCI)
			{
				$CIBranchesArr[$value->source_branch] = $value->updated_at;
			}

		}
		unset($updatedAtDate);
	}
	
	//loop to remove same branches in CI and QASanity and also to remove RC branch which was merged back to QASanityReleaseNew
	if(is_array($QASanityReleaseBranchesArr) && !empty($QASanityReleaseBranchesArr))
	{
		foreach($QASanityReleaseBranchesArr as $key=>$value)
		{
			if($CIBranchesArr[$key])
			{		
				unset($QASanityReleaseBranchesArr[$key]);		//removed those from QASanity arr which existes both in CIRelease and QASanity.
			}
			if(strpos($key,"RC@")!==false)
			{
				unset($QASanityReleaseBranchesArr[$key]); //removed RC@<date> branch which was back merged to Sanity hence had not gone live
			} 
		}
	}

	//Loop on CIRELEASE to check if there was an RC brach merged.
	if(is_array($CIBranchesArr) && !empty($CIBranchesArr))
	{
		foreach($CIBranchesArr as $key=>$value)
		{
			if("RC@" == substr($key,0,3))
			{
				$releaseBranch = $key; //check to see where it has to be used.
				//unset($CIBranchesArr[$key]); //check if it needs to be unset
			}
		}
	}

	//to write the last released branch into a file(filename)
	if($file = fopen($lastReleasedBranchFileName, "w+")) //changed the mode form "a" to "w+". Check again
	{
		fwrite($file, $releaseBranch."\n");
	}

//Adding QASanity JIRA IDs to file
	if($file = fopen($SanityMergedFileName, "a"))
	{
		if(is_array($QASanityReleaseBranchesArr) && !empty($QASanityReleaseBranchesArr))
		{
			foreach($QASanityReleaseBranchesArr as $key=>$value)
			{
				if(!in_array($key, $SanityFilesArr))
				{
					fwrite($file, $key."\n");
				}
			}
		}
	}

	die("script finished execution for QASanityReleaseNew");
}
else
{
	die("Please enter a valid branch name");
}
function sendCurlGETRequest($urlToHit,$postParams,$timeout='',$headerArr="",$requestType="")
{
    //print_r($urlToHit);die;
	if(!$timeout)
		$timeout = 50000;
	$ch = curl_init($urlToHit);    
	if($headerArr)
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
	else
		curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
	curl_setopt($ch,CURLOPT_NOSIGNAL,1);
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout*10);
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$output = curl_exec($ch);
	return json_decode($output);
}