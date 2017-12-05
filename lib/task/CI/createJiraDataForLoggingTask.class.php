<?php
/*
 *	Author:Sanyam Chopra
 *	This cron fetches details of JIRA's gone Live along with other details and saves the entry on a table
 */

class createJiraDataForLoggingTask extends sfBaseTask{
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
    $this->name                = 'createJiraDataForLogging';
    $this->briefDescription    = 'This cron fetches details of JIRAs gone Live along with other details and saves the entry on a table';
    $this->detailedDescription = <<<EOF
     This cron fetches details of JIRA's gone Live along with other details and saves the entry on a table
     [php symfony CI:createJiraDataForLogging oneTimeActivity] 
EOF;
	$this->addArguments(array(
			new sfCommandArgument('oneTimeActivity', sfCommandArgument::REQUIRED, 'Target Branch')));
  }

  protected function execute($arguments = array(), $options = array())
  {   
    $oneTimeActivity = $arguments["oneTimeActivity"];
    $urlToHit = "http://gitlabweb.infoedge.com/api/v3/projects/Jeevansathi%2FJsMain/repository/tags?"; //url to get tags
    $jiraUrl = "https://jsba99.atlassian.net/rest/api/2/issue/"; //url to get details on Jira issue
    $headerArr = array(
      'PRIVATE-TOKEN:YY7g4CeG_tf17jZ4THEi',       
      ); //token used is of username: vidushi@naukri.com

    $jiraHeaderArr = array(
      'Authorization:Basic dmlkdXNoaUBuYXVrcmkuY29tOnZpZHVzaGkxMjM=',
      'Content-Type:application/json'
      );

    $this->tagArr = array();
    if($oneTimeActivity) //if one time activity to be done then date to be 1stJune
    {
      $comparisonDate = "2017-06-01 00:00:00";  
    }
    else
    {
      $comparisonDate = date("Y-m-d 00:00:00");
    }    
      
    // this gets the tag array
    $response = CommonFunction::sendCurlGETRequest($urlToHit,'',"",$headerArr,"GET");      
    //print_R($response);die;
    //to get tags for the required date
    foreach ($response as $key => $value) 
    {       
      $updatedAtDate = explode(".",$value->commit->committed_date);
      $updatedDate = str_replace("T"," ", $updatedAtDate[0]);
      if($updatedDate > $comparisonDate)
      {          
        $this->tagArr[$value->name] = $value->release->description;         
      }        
    }    
    $jiraArr = array();

    //to take one tag at a time, use the jira's in the desrciption to find data and store in table.
    foreach($this->tagArr as $key=>$value)
    {
      if($value)
      {
        $jiraArr = explode(",",$value);
      }        
      if(is_array($jiraArr) && !empty($jiraArr))
      {
        foreach($jiraArr as $key=>$value)
        {            
          if(strpos($value,"RC@") === false) //RC@ jira's not requried
          {
            $response = CommonFunction::sendCurlGETRequest($jiraUrl.$value,"","",$jiraHeaderArr,"GET");
            
            $this->jiraDetails[$value]["type"]= $response->fields->issuetype->name;
            $this->jiraDetails[$value]["ReleaseName"]= $response->fields->fixVersions[0]->name;
            $this->jiraDetails[$value]["ReleaseDate"]= $response->fields->fixVersions[0]->releaseDate;
            $this->jiraDetails[$value]["StoryPoints"]= $response->fields->customfield_10004;
            $this->jiraDetails[$value]["assignee"]= $response->fields->assignee->name;
            $this->jiraDetails[$value]["summary"]= $response->fields->summary;
            $this->jiraDetails[$value]["label"]= implode(",",$response->fields->labels);
            $this->jiraDetails[$value]["Epic"]= jiraEpicEnums::$epicNames[$response->fields->customfield_10007];
            
            $sprintData = explode(",",$response->fields->customfield_10006[0]);
            if(is_array($sprintData) && !empty($sprintData))
            {
              foreach($sprintData as $k1=>$v1)
              {
                if(strpos($v1,"name")!==false ||strpos($v1,"startDate")!==false || strpos($v1,"endDate")!==false)
                {
                  $splitSprintData = explode("=",$v1);
                  if($splitSprintData[0] == "name")
                  {
                    $this->jiraDetails[$value]["sprint_".$splitSprintData[0]]= $splitSprintData[1];
                  }                
                  else
                  {
                    $splitSprintTime = explode("T",$splitSprintData[1]);
                    $this->jiraDetails[$value]["sprint_".$splitSprintData[0]]= $splitSprintTime[0];
                  }
                }
                unset($sprintData);
                unset($splitSprintData);
                unset($splitSprintTime);
              }
            }
            else
            {
              $this->jiraDetails[$value]["sprint_name"] = "";
              $this->jiraDetails[$value]["sprint_startDate"] = "";
              $this->jiraDetails[$value]["sprint_endDate"] = "";
            }                     
          }            
        }
      }
    }
    $jiraObj = new Jira_JiraDetails("newjs_masterRep");
    $jiraObj->insertRecords($this->jiraDetails);
    unset($jiraObj);
  }  
}
