<?php
/*
This script is used to create the LIVE tag which then goes live and would contain information regarding which JIRA's are merged in the release going live.
Parameter passed : "QASanityReleaseNew" or "CIRelease"
*/

$branchName = $argv[1];

if($branchName == "QASanityReleaseNew")
{
	$SanityMergedFileName = "/var/www/html/branches/branch2/crontabs/QASanityMergedBranches.txt"; //need to change this to the url finally decided.
	//To get files arr by reading the entire file
	$MergedBranchesArr = file($SanityMergedFileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	$tagName = "TAG_RC@".date("Y-m-d_H:i:s");
}
elseif($branchName == "CIRelease")
{
	$CIMergedFileName = "/var/www/html/branches/branch2/crontabs/CIMergedBranches.txt"; //need to change this to the url finally decided.
	$MergedBranchesArr = file($CIMergedFileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$tagName = "TAG_HF@".date("Y-m-d_H:i:s");
}
else
{
	die("Please enter a valid branch name");
}


$urlToHit = "http://gitlabweb.infoedge.com/api/v3/projects/Jeevansathi%2FJsMain/repository/tags?";

$releaseDescription = implode(",", $MergedBranchesArr);

$headerArr = array(
	'PRIVATE-TOKEN:YY7g4CeG_tf17jZ4THEi',				
	);

$paramArr = array("tag_name"=>$tagName,"ref"=>$branchName,"release_description"=>$releaseDescription);

$response = sendCurlGETRequest($urlToHit,$paramArr,"",$headerArr,"POST");

// LOGIC TO FIND TAGS
/*$response = sendCurlGETRequest($urlToHit,'',"",$headerArr,"GET");
$i=0;
foreach ($response as $key => $value) 
{
	if($i<70)
	{
		$tagArr[$value->name]["description"] = $value->release->description;
		$tagArr[$value->name]["dateTime"] = $value->commit->authored_date;
	}
	$i++;	
}*/

//print_R($tagArr);die;
function sendCurlGETRequest($urlToHit,$postParams,$timeout='',$headerArr="",$requestType="")
{    //print_R($postParams);die;
	if(!$timeout)
		$timeout = 50000;
	$ch = curl_init($urlToHit);    
	if($headerArr)
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
	else
		curl_setopt($ch, CURLOPT_HEADER, 0);
	if($postParams)
		curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	if($postParams)
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
	curl_setopt($ch,CURLOPT_NOSIGNAL,1);
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout*10);
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$output = curl_exec($ch);	
	return json_decode($output);
}