<?php
class onlineUserCleanupTask extends sfBaseTask
{
  protected function configure()
  {
     $this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','operations'),
     ));
    $this->namespace        = 'cleanup';
    $this->name             = 'onlineUserCleanup';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [onlineUserCleanup|INFO] task does things.
Call it with:
  [php symfony onlineUserCleanup|INFO]
EOF;
  }


  protected function execute($arguments = array(), $options = array())
  {
        if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);

		$dateTime1 ='11';
		$dateTime2 ='22';
		$dateTime =date("H");
		$redisOnline =true;
		if(($dateTime>=$dateTime1) && ($dateTime<$dateTime2))
			$redisOnline =false;
		if($redisOnline){
                	$jsCommonObj =new JsCommon();
                	$profilesArr =$jsCommonObj->removeOfflineProfiles();
		}
  }
}
