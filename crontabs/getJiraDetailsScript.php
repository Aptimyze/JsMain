<?php
//This script reads the JIRA IDs from the QASanityMergedBranches.txt and CIMergedBranches.txt
/* the arguments passed in this script are:
	1) $targetBranch : QASanityReleaseNew/CIRelease
	2)$pathName : /var/www/testjs09
*/

$targetBranch = $argv[1];
$pathName = $argv[2];

if($targetBranch == "QASanityReleaseNew")
{
	$FileName = $pathName."/crontabs/QASanityMergedBranches.txt";
}
elseif($targetBranch == "CIRelease")
{
	$FileName = $pathName."/crontabs/CIMergedBranches.txt";
}
else
{
	echo("Please enter a valid Branch name");die;
}

//To get files arr by reading the entire file
$FilesArr = file($FileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);


$headerArr = array(
				'Authorization:Basic dmlkdXNoaTp2aWR1c2hp',
				'Content-Type:application/json'
				);

$jiraDescriptionArr = array();

$setVersionUrl = "https://jsba99.atlassian.net/rest/api/2/issue/";

if(is_array($FilesArr) && !empty($FilesArr))
{
	foreach($SanityFilesArr as $key=>$value)
	{
		$response = sendCurlGETRequest($setVersionUrl.$value,"","",$headerArr,"GET");
		$jiraDescriptionArr[$value]["status"] = $response->fields->status->name;
		$jiraDescriptionArr[$value]["assignee"] = $response->fields->assignee->name;
		$jiraDescriptionArr[$value]["description"] = $response->fields->summary;	
	}
	print_R($jiraDescriptionArr);die;
}
else
{
	echo("Files array was blank");die;
}
function sendCurlGETRequest($urlToHit,$postParams="",$timeout='',$headerArr="",$requestType="")
{
    //print_r($urlToHit);die;
	if(!$timeout)
		$timeout = 50000;
	$ch = curl_init($urlToHit);    
	if($headerArr)
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
	else
		curl_setopt($ch, CURLOPT_HEADER, 0);
	/*if($postParams)
		curl_setopt($ch, CURLOPT_POST, 1);*/
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	/*if($postParams)
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);*/
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