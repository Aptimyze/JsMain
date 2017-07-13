<?php

/*
 * Author: Esha Jain
 * This task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
 */

class KnwlarityvnoTableCleanupTask extends sfBaseTask
{
 const NO_OF_DAYS=20;
  protected function configure()
  {

    $this->namespace        = 'cleanup';
    $this->name             = 'KnwlarityvnoTableCleanup';
	$this->date = date('Y-m-d',mktime(0,0,0,date('m'),date("d")-self::NO_OF_DAYS,date("Y")));// daily cron date
    $this->briefDescription = 'cleanup of Knwlarityvno Table in newjs except for the profiles logged in after date passed as parameter';
    $this->detailedDescription = <<<EOF
      The [KnwlarityvnoTableCleanup|INFO] task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
      Call it with:

      [php symfony cleanup:KnwlarityvnoTableCleanup] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
    ini_set('memory_limit','1024M');
                ini_set('max_execution_time', 0);
        if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);

  	$knwlarityvnoObj = new newjs_KNWLARITYVNO;
  	$knwlarityvnoObj->deleteInvalidData();
    $totalChunks=100;
    for($chunk=0;$chunk<$totalChunks;$chunk++)
    {
      	$profiles = $knwlarityvnoObj->getProfilesInTable($totalChunks,$chunk);
        if(count($profiles)>0)
      	{
          $profilesDeleted = $this->getProfilesLoggedInBeforeDate($profiles);
          unset($profiles);
      	   if(is_array($profilesDeleted))
      		  $knwlarityvnoObj->deleteVnoForProfiles($profilesDeleted);
          unset($profilesDeleted);
        }
    }
  }
  private function getProfilesLoggedInBeforeDate($profiles)
  {

	$jprofileObj = new JPROFILE("newjs_slave");
	$profileDetails = array();
	$parameterLess['LAST_LOGIN_DT']=$this->date;
	$parameterEqual['PROFILEID']=implode(",",$profiles);
	$profileDetails = $jprofileObj->getArray($parameterEqual,'',"",'PROFILEID',$parameterLess);
	if(is_array($profileDetails))
	{
		$profilesLoggedInBeforeDate = array();
		foreach($profileDetails as $k=>$v)
			$profilesLoggedInBeforeDate[] = $v['PROFILEID'];
		return $profilesLoggedInBeforeDate;
	}
  }
}
