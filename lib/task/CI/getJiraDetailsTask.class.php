<?php
/*
 *	Author:Sanyam Chopra
 *	This cron fetches jira details for already calculated list of jira's that we merged into CIRelease/QASanityReleaseNew as per the parameter passed
 */

class getJiraDetailsTask extends sfBaseTask{
	/**
   * 
   * Configuration details for CI:getMergedBranches
   * 
   * @access protected
   * @param none
   */
  protected function configure()
  {
    $this->namespace           = 'CI';
    $this->name                = 'getJiraDetails';
    $this->briefDescription    = 'get details about JIRA ids that are already saved in a file';
    $this->detailedDescription = <<<EOF
     get details about JIRA ids that are already saved in a file
     [php symfony CI:getJiraDetails targetBranch] 
EOF;
	$this->addArguments(array(
			new sfCommandArgument('targetBranch', sfCommandArgument::REQUIRED, 'Target Branch')));
  }

  protected function execute($arguments = array(), $options = array())
  {   
    $targetBranch = $arguments["targetBranch"];

    if($targetBranch == "QASanityReleaseNew")
    {
      $FileName = "/var/www/CI_Files/QASanityMergedBranches.txt";
      $jiraDetailsFileName = "/var/www/CI_Files/QASanitJiraDetails.txt";
    }
    elseif($targetBranch == "CIRelease")
    {
      $FileName = "/var/www/CI_Files/CIMergedBranches.txt";
      $jiraDetailsFileName = "/var/www/CI_Files/CIJiraDetails.txt";
    }
    else
    {
      echo("Please enter a valid Branch name");die;
    }

  //To get files arr by reading the entire file
  $FilesArr = file($FileName , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

  $headerArr = array(
        'Authorization:Basic dmlkdXNoaUBuYXVrcmkuY29tOnZpZHVzaGkxMjM=',
        'Content-Type:application/json'
        );

  $jiraDescriptionStr = "";
  $setVersionUrl = "https://jsba99.atlassian.net/rest/api/2/issue/";

  if(is_array($FilesArr) && !empty($FilesArr))
  {
    foreach($FilesArr as $key=>$value)
    {
      $response = CommonFunction::sendCurlGETRequest($setVersionUrl.$value,"","",$headerArr,"GET");
      $jiraDescriptionStr .= $value." : ".$response->fields->summary."<br>Assignee: ".$response->fields->assignee->name."<br><br>";
    }

    //to write the last released branch into a file(filename)
    if($file = fopen($jiraDetailsFileName, "w+"))
    {
      fwrite($file, $jiraDescriptionStr);
    }

    die("Data saved to file ");
  }
  else
  {
    echo("Files array was blank");die;
  }
}
}
