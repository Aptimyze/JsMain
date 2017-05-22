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
$parameter = $argv[1];
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

$fileName = "ci.txt";

if ($file = fopen($fileName, "r")) {
    while(!feof($file)) {
        $line = fgets($file);
        //Check to determine the first block of hotfix. Here hotFixBlock variable is set to true and it remains true until the break delimeter is excountered
        if(strpos($line,$missingFromQASanityPattern) !== FALSE){
        	$hotFixBlock = true;
        }

        //Check to determine the second block of regular release. Here releaseBlock variable is set to true and it remains true until the break delimeter is excountered
        if(strpos($line,$missingFromCIReleasePattern) !== FALSE){
        	$releaseBlock = true;
        }

        if($hotFixBlock == true){
        	//This code is executed if the line being iterated currently is in the hotfix block
        	unset($result);
        	//Get the Jira Projects for which the release is to be created in $result[1] and specific jira ids in $result[0]
        	$result = getJiraProjectAndIds($line);
        	if(is_array($result)){
	        	$hotFixJira[] = $result[0];
	        	$hotFix[$result[1]] = "1";
	        }
        }

        if($releaseBlock == true){
        	//This code is executed if the line being iterated currently is in the regular release block
        	unset($result);
        	//Get the Jira Projects for which the release is to be created in $result[1] and specific jira ids in $result[0]
        	$result = getJiraProjectAndIds($line);
        	if(is_array($result)){
	        	$releaseJira[] = $result[0];
	        	$release[$result[1]] = "1";
	        }
        }

        //Check for when the break delimeter is encountered and the $hotFixBlock and $releaseBlock is set to false
        if(strpos($line,$breakDelimieter) !== FALSE){
        	$hotFixBlock = false;
        	$releaseBlock = false;
        }
    }

    //For Creating Hotfix Versions
    if($parameter == "hotfix" || $parameter == "all")
        createRelease($hotFix,"HF");

	//For Creating regulat Release Versions
    if($parameter == "release" || $parameter == "all")
        createRelease($release,"RC");

    //For marking HotFix Versions
    if($parameter == "hotfix" || $parameter == "all")
        markVersion($hotFixJira,"HF");

	//For marking regular release Versions
    if($parameter == "release" || $parameter == "all")
        markVersion($releaseJira,"RC");    

    //print_r(array($hotFix,$release,$hotFixJira,$releaseJira));
    fclose($file);
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

function getJiraProjectAndIds($line){
	global $groups;
	unset($matches);
	//This preg matches the first text encountered in square brackets i.e. []
	if(preg_match("/\[([^\]]*)\]/", $line, $matches)){
		//Sub string for first three letters to get the project
		$subString = substr($line, 1,3);
		//Check whether the text in square brackets is actually a jira id or some other text
    	if(array_key_exists($subString, $groups)){
    		//Adding it to two different arrays. One for unique jira ids and the other to get the project
    		//$result[0] = $matches[1];
            $result[0] = substr($matches[1], 0,8);
    		$result[1] = $subString;
    	}
	}
	return $result;
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