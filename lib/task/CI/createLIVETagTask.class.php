<?php
/*
 *	Author:Sanyam Chopra
 *	This cron is used to create TAG for making the code live
 */

class createLIVETagTask extends sfBaseTask{
	/**
   * 
   * Configuration details for CI:createLIVETag
   * 
   * @access protected
   * @param none
   */
  protected function configure()
  {
    $this->namespace           = 'CI';
    $this->name                = 'createLIVETag';
    $this->briefDescription    = 'create tag to make LIVE';
    $this->detailedDescription = <<<EOF
     create tag to make live
     [php symfony CI:createLIVETag targetBranch] 
EOF;
	$this->addArguments(array(
			new sfCommandArgument('targetBranch', sfCommandArgument::REQUIRED, 'Target Branch')));
  }

  protected function execute($arguments = array(), $options = array())
  {
    $branchName = $arguments["targetBranch"];

    //setting defualt time zone
    date_default_timezone_set('Asia/Kolkata');

    if($branchName == "QASanityReleaseNew")
    {
      $SanityMergedFileName = "/var/www/CI_Files/QASanityMergedBranches.txt"; 
      $CIMergedFileName =  "/var/www/CI_Files/CIMergedBranches.txt";
      $CIMergedBranchesArr = file($CIMergedFileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

      if(is_array($CIMergedBranchesArr) && !empty($CIMergedBranchesArr))
      {
        $CIMergedBranches = implode(",", $CIMergedBranchesArr); 
      }

    //To get files arr by reading the entire file
      $MergedBranchesArr = file($SanityMergedFileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
    elseif($branchName == "CIRelease")
    {
      $CIMergedFileName =  "/var/www/CI_Files/CIMergedBranches.txt"; 

      $MergedBranchesArr = file($CIMergedFileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
    else
    {
      die("Please enter a valid branch name");
    }


    $urlToHit = "http://gitlabweb.infoedge.com/api/v3/projects/Jeevansathi%2FJsMain/repository/tags?";

    $tagName = "JSR@".date("YmdHi"); //tagName of the format(JSR@yearMonthDateHourMinutes)

    //to write the tagName into a file(filename)
    if($file = fopen("/var/www/CI_Files/tageName.txt", "w+")) //changed the mode form "a" to "w+". Check again
    {
      fwrite($file, $tagName."\n");
    }

  $releaseDescription = implode(",", $MergedBranchesArr);

  if($branchName == "QASanityReleaseNew")
  {
    if($CIMergedBranches)
    {
      $releaseDescription.=",".$CIMergedBranches;
    }
  }

  $headerArr = array(
    'PRIVATE-TOKEN:YY7g4CeG_tf17jZ4THEi',       
  ); //token used is of username: vidushi@naukri.com

  $paramArr = array("tag_name"=>$tagName,"ref"=>"CIRelease","release_description"=>$releaseDescription); //ref should be CIRelease


  $response = sendCurlGETRequest($urlToHit,$paramArr,"",$headerArr,"POST");
  print_R($response->name);die;
  }

  public function sendCurlGETRequest($urlToHit,$postParams,$timeout='',$headerArr="",$requestType="")
  {    
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
}