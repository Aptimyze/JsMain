<?php
/*
 *	Author:Sanyam Chopra
 *	This cron creates version and marks them on jira's going live and also updates the text files accordingly
 */

class createMarkJiraReleaseTask extends sfBaseTask{
	/**
   * 
   * Configuration details for CI:createMarkJiraRelease
   * 
   * @access protected
   * @param none
   */

    const MAILBODY = "Following JIRA's were made LIVE.<br><br><br>";
    const RECEIVER = "sanyam.chopra@jeevansathi.com,sanyam1204@gmail.com";//"esha.jain@jeevansathi.com,kunal.verma@jeevansathi.com,reshu.rajput@jeevansathi.com,manoj.rana@jeevansathi.com,lavesh.rawat@jeevansathi.com,nitesh.sethi@jeevansathi.com,vibhor.garg@jeevansathi.com,vidushi@naukri.com,";    
    const SENDER = "info@jeevansathi.com";
    const SUBJECT = "Jira's Made LIVE";

  protected function configure()
  {
    $this->namespace           = 'CI';
    $this->name                = 'createMarkJiraRelease';
    $this->briefDescription    = 'create and mark jira version and update text files';
    $this->detailedDescription = <<<EOF
     reate and mark jira version and update text file
     [php symfony CI:createMarkJiraRelease targetBranch] 
EOF;
	$this->addArguments(array(
			new sfCommandArgument('targetBranch', sfCommandArgument::REQUIRED, 'Target Branch')));
  }

  protected function execute($arguments = array(), $options = array())
  {   
    $branchName = $arguments["targetBranch"];

    $hotFixBlock = false;
    $releaseBlock = false;
    $groups = array('JSC'=>array("name"=>"JSC","id"=>10013),'JSM'=>array("name"=>"JSM","id"=>10015),'JSI'=>array("name"=>"JSI","id"=>10014),'JTA'=>array("name"=>"JTA","id"=>12400)); //Any other group needs to be added here
    $createVersionUrl = "https://jsba99.atlassian.net/rest/api/2/version";
    $setVersionUrl = "https://jsba99.atlassian.net/rest/api/2/issue/";

//setting defualt time zone
date_default_timezone_set('Asia/Kolkata');

$headerArr = array(
  'Authorization:Basic dmlkdXNoaTp2aWR1c2hp',
  'Content-Type:application/json'
  );
//get tagName which will be the release version
$tageNameFile = "/var/www/CI_Files/tageName.txt";
$tagNameArr = file($tageNameFile , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$tagName = $tagNameArr[0];

if($branchName == "CIRelease")
{
  $parameter = "hotfix";
  $hotFixBlock = true;
  $fileName = "/var/www/CI_Files/CIMergedBranches.txt";
  $CIJiraDetails = "/var/www/CI_Files/CIJiraDetails.txt";
  $jiraDetailsStr = file_get_contents($CIJiraDetails);  
}
elseif($branchName == "QASanityReleaseNew")
{
  $parameter = "release";
  $releaseBlock = true;
  $fileName = "/var/www/CI_Files/QASanityMergedBranches.txt";
  $CIFileName = "/var/www/CI_Files/CIMergedBranches.txt";
  $CIArr = file($CIFileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $QAJiraDetails = "/var/www/CI_Files/QASanitJiraDetails.txt";
  $jiraDetailsStr = file_get_contents($QAJiraDetails);
  $CIJiraDetails = "/var/www/CI_Files/CIJiraDetails.txt";
  $jiraDetailsStr .="<br><br>".file_get_contents($CIJiraDetails);  
}
else
{
  die("Please provide a valid input parameter");
}
$file = file($fileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if(is_array($file) && !empty($file))
{
  if($branchName == "QASanityReleaseNew")
  {
    $releaseJiraArr = $file;
    if(is_array($CIArr) && !empty($CIArr))
    {
      $releaseJira = array_merge($releaseJiraArr,$CIArr);
    }
    else
    {
      $releaseJira = $releaseJiraArr;
    }
    $file = $releaseJira;
  }
  else
  {
    $hotFixJira = $file;
  }

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

    //For Creating Hotfix Versions
  if($parameter == "hotfix" || $parameter == "all")
    createRelease($hotFix,$tagName);

    //For Creating regulat Release Versions
  if($parameter == "release" || $parameter == "all")
    createRelease($release,$tagName);

    //For marking HotFix Versions
  if($parameter == "hotfix" || $parameter == "all")
    markVersion($hotFixJira,$tagName);

    //For marking regular release Versions
  if($parameter == "release" || $parameter == "all")
    markVersion($releaseJira,$tagName);    


    //This is used to send mail after the release versions have been created and marked
    SendMail::send_email(self::RECEIVER,self::MAILBODY.$jiraDetailsStr,self::SUBJECT,self::SENDER);

    /*
    this part is used to ensure that previous data in files is deleted and time is udpated in the time file.
    1) for CIRelease: The mergedBranches file is truncated and the lastReleaseDate file is set to the current time
     2) for QASanityReleaseNew: The mergedBranches file for both CI and QA is truncated and the lastReleaseDate for both is updated.
     */
     if($branchName == "CIRelease")
     {
      $CIFile = fopen("/var/www/CI_Files/CIMergedBranches.txt","w");
      fclose($CIFile);

      $CIDateFile = fopen("/var/www/CI_Files/CIReleaseLastReleaseDate.txt","w+");        
      fwrite($CIDateFile, date("Y-m-d H:i:s"));
      fclose($CIDateFile);
    }
    elseif($branchName == "QASanityReleaseNew")
    {
      $SanityFile = fopen("/var/www/CI_Files/QASanityMergedBranches.txt","w");
      fclose($SanityFile);

      $CIFile = fopen("/var/www/CI_Files/CIMergedBranches.txt","w");
      fclose($CIFile);

      $CIDateFile = fopen("/var/www/CI_Files/CIReleaseLastReleaseDate.txt","w+");        
      fwrite($CIDateFile, date("Y-m-d H:i:s"));
      fclose($CIDateFile);

      $sanityDateFile = fopen("/var/www/CI_Files/QASanityLastReleaseDate.txt","w+");
      fwrite($sanityDateFile,date("Y-m-d H:i:s"));
      fclose($sanityDateFile);
    }
    //print_r(array($hotFix,$release,$hotFixJira,$releaseJira));
  }
}
public function sendCurlGETRequest($urlToHit,$postParams="",$timeout='',$headerArr="",$requestType="")
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
public function markVersion($releaseJira,$tagName)
{
  global $setVersionUrl;
  global $headerArr;
  if(is_array($releaseJira)){
    //Iterate for all the jira ids
    foreach ($releaseJira as $key => $value) {
      //Version name depending on whether it is hotfix or regular release
      $value = substr($value,0,8);
            $versionName = $tagName;
      $url = $setVersionUrl.$value;
      //The required format of params is in this way
      $params = json_encode(array("update"=>array("fixVersions"=>array(array("set"=>array(array("name"=>"$versionName")))))));
      //print_r($params."\n");
      $response = sendCurlPostRequest($url,$params,'',$headerArr,"PUT");
    }
  }
}


public function createRelease($releaseArr,$tagName)
{
  global $groups;
  global $createVersionUrl;
  global $headerArr;
  if(is_array($releaseArr)){
    //Iterate for all the jira ids
    foreach($releaseArr as $key => $val){
      $params = json_encode(array("description"=>"Release",
              "name"=>$tagName,
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
}