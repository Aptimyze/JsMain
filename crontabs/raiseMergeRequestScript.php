<?php
/* This script is used to raise merge request on both QAsanityReleaseNew and CIRelease. The following parameters are passed:
	1) branch name to be merged
	2) "1" if merge request is to be raised only on QASanityReleaseNew & "2" if it is to be raised for both QASanityReleaseNew and CIRelease. Providing no parameter will assume the default value to be "1"
*/
$branchToMerge = $argv[1];

if($argv[2])
{
	$mergeNumber = $argv[2];
}
else
{
	$mergeNumber = "1";
}


if(!$branchToMerge)
{
	echo "Please provide a branch name to merge";die;
}


$headerArr = array(
	'PRIVATE-TOKEN:YY7g4CeG_tf17jZ4THEi',				
	); //SOLVE PRIVATE TOKEN ISSUE. MAKE ENUMS FOR PRIVATE TOKEN OF ALL

$urlToHit = "http://gitlabweb.infoedge.com/api/v3/projects/Jeevansathi%2FJsMain/merge_requests";

$paramArr = array("target_branch"=>"QASanityReleaseNew","source_branch"=>$branchToMerge,"title"=>"merging ".$branchToMerge." to QAsanityReleaseNew");

sendCurlGETRequest($urlToHit,$paramArr,"",$headerArr,"POST");

if($mergeNumber == "2")
{
	$paramArr2 = array("target_branch"=>"CIRelease","source_branch"=>$branchToMerge,"title"=>"merging ".$branchToMerge." to CIRelease");
sendCurlGETRequest($urlToHit,$paramArr2,"",$headerArr,"POST");
die(" Merge Requests Raised");
}

die ("Merge Request Raised");


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