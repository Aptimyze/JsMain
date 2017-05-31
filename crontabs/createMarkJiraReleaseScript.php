<?php
/*
Script for creating versions and marking release in Jira
Assumptions:
The script loops line by line to the merging output
The content of the block 
"MISSING FROM QASanityReleaseNew........"" to "----------------------"
and
"MISSING FROM CIRelease........"" to "----------------------"
is considered into hotfix and regular release respectively.
For each block The first text within [] is taken to be the JIRA ids of the Jiras that are merged
Each distinct group is used to create the version for corresponding block
and for each JIRA id then the API is hit to mark release.

In case a new group is added add it to the array $groups
Change the fileName variable to the name of the file from which data is to be read
$hotFix      : Array containg keys of distinct groups for which the release is to be made for hot fix
$release     : Array containign keys of distinct groups for which the release is to be made for regular release
$hotFixJira  : Array of all JIRA ids for hotfix
$releaseJira : Array of all JIRA ids for regular release
Three params:
hotfix / release/ all
*/
//$parameter = $argv[1];
$branchName = $argv[1]; //This specifies whether the branch to be taken into account is QASanityReleaseNew or CIRelease
$missingFromQASanityPattern  = "MISSING FROM QASanityReleaseNew";
$missingFromCIReleasePattern = "MISSING FROM CIRelease";
$breakDelimieter = "---------------------------------------------------------------------";
$hotFixBlock = false;
$releaseBlock = false;
$groups = array('JSC'=>array("name"=>"JSC","id"=>10013),'JSM'=>array("name"=>"JSM","id"=>10015),'JSI'=>array("name"=>"JSI","id"=>10014)); //Any other group needs to be added here
$createVersionUrl = "https://jsba99.atlassian.net/rest/api/2/version";
$setVersionUrl = "https://jsba99.atlassian.net/rest/api/2/issue/";

$headerArr = array(
				'Authorization:Basic dmlkdXNoaTp2aWR1c2hp',
				'Content-Type:application/json'
				);

if($branchName == "CIRelease")
{
    $parameter = "hotFix";
    $hotFixBlock = true;
    $fileName = "/var/www/html/branches/branch2/crontabs/CIMergedBranches.txt";
}
elseif($branchName == "QASanityReleaseNew")
{
    $parameter = "release";
    $releaseBlock = true;
    $fileName = "/var/www/html/branches/branch2/crontabs/QASanityMergedBranches.txt";
}
else
{
    die("Please provide a valid input parameter");
}
$file = file($fileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if($branchName == "QASanityReleaseNew")
{
    $releaseJira = $file;
}
else
{
    $hotFixJira = $file;
}

if(is_array($file) && !empty($file))
{
    foreach($file as $key=>$value)
    {        
        if($hotFixBlock == true)
        {                          
            $projectName = substr($value,0,3);
            $hotFix[$projectName] = "1";
        }

        if($releaseBlock == true)
        {                                    
            $projectName = substr($value,0,3);
            $release[$projectName] = "1";
        }
    }

    // //For Creating Hotfix Versions
    // if($parameter == "hotfix" || $parameter == "all")
    //     createRelease($hotFix,"HF");

    // //For Creating regulat Release Versions
    // if($parameter == "release" || $parameter == "all")
    //     createRelease($release,"RC");

    // //For marking HotFix Versions
    // if($parameter == "hotfix" || $parameter == "all")
    //     markVersion($hotFixJira,"HF");

    // //For marking regular release Versions
    // if($parameter == "release" || $parameter == "all")
    //     markVersion($releaseJira,"RC");    

    
    //this part is used to ensure that previous data in files is deleted and time is udpated in the time file.
    /*1) for CIRelease: The mergedBranches file is truncated and the lastReleaseDate file is set to the current time
     2) for QASanityReleaseNew: The mergedBranches file for both CI and QA is truncated and the lastReleaseDate for both is updated.*/
    if($branchName == "CIRelease")
    {
        $CIFile = fopen("/var/www/html/branches/branch2/crontabs/CIMergedBranches.txt","w");
        fclose($CIFile);

        $CIDateFile = fopen("/var/www/html/branches/branch2/crontabs/CIReleaseLastReleaseDate.txt","w+");        
        fwrite($CIDateFile, date("Y-m-d H:i:s"));
        fclose($CIDateFile);
    }
    elseif($branchName == "QASanityReleaseNew")
    {
        $SanityFile = fopen("/var/www/html/branches/branch2/crontabs/QASanityMergedBranches.txt","w");
        fclose($SanityFile);

        $CIFile = fopen("/var/www/html/branches/branch2/crontabs/CIMergedBranches.txt","w");
        fclose($CIFile);

        $CIDateFile = fopen("/var/www/html/branches/branch2/crontabs/CIReleaseLastReleaseDate.txt","w+");        
        fwrite($CIDateFile, date("Y-m-d H:i:s"));
        fclose($CIDateFile);

        $sanityDateFile = fopen("/var/www/html/branches/branch2/crontabs/QASanityLastReleaseDate.txt","w+");
        fwrite($sanityDateFile,date("Y-m-d H:i:s"));
        fclose($sanityDateFile);
    }
    //print_r(array($hotFix,$release,$hotFixJira,$releaseJira));
}


function markVersion($releaseJira,$releaseText){
	global $setVersionUrl;
	global $headerArr;
	if(is_array($releaseJira)){
		//Iterate for all the jira ids
		foreach ($releaseJira as $key => $value) {
			//Version name depending on whether it is hotfix or regular release
			$versionName = "$releaseText@".date('d')."-".date('m')."-".date('y');
			$url = $setVersionUrl.$value;
			//The required format of params is in this way
			$params = json_encode(array("update"=>array("fixVersions"=>array(array("set"=>array(array("name"=>"$versionName")))))));
			//print_r($params."\n");
			$response = sendCurlPostRequest($url,$params,'',$headerArr,"PUT");
		}
	}
}


function createRelease($releaseArr,$releaseText){
	global $groups;
	global $createVersionUrl;
	global $headerArr;
	if(is_array($releaseArr)){
		//Iterate for all the jira ids
		foreach($releaseArr as $key => $val){
			$params = json_encode(array("description"=>"Release",
							"name"=>"$releaseText@".date('d')."-".date('m')."-".date('y'),
							"archived"=> false,
							"released"=> false,
							"releaseDate"=> date('Y-m-d'),
							"project"=> $groups[$key]['name'],
							"projectId"=> $groups[$key]['id']));
			//print_r($params."\n");
			$response = sendCurlPostRequest($createVersionUrl,$params,'',$headerArr);
		}
    }
}
function sendCurlPostRequest($urlToHit,$postParams,$timeout='',$headerArr="",$requestType="")
{
    //print_r($urlToHit);
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
    if($requestType == "PUT")
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
    curl_setopt($ch,CURLOPT_NOSIGNAL,1);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout*10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch);
    //print_r($output);
    return $output;
}
?>