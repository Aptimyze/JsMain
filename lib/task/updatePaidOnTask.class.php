<?php

class updatePaidOnTask extends sfBaseTask
{

  protected function configure()
  {
  	$this->addArguments(array(
		new sfCommandArgument('tableName', sfCommandArgument::REQUIRED, 'tableName'),
        new sfCommandArgument('maxCount', sfCommandArgument::REQUIRED, 'maxCount'),        
		));
    $this->namespace        = 'cron';
    $this->name             = 'updatePaidOnTask';
    $this->briefDescription = 'update paid on for searchMale and search female';
    $this->detailedDescription = <<<EOF
    update paid on for searchMale and search female

Call it with:

  [php symfony cron:updatePaidOn tableName maxCount] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));

  }

  protected function execute($arguments = array(), $options = array())
  {
	$tableName = $arguments["tableName"];
	$maxCount = $arguments["maxCount"];  
	$limit = 2000;
	$offset = 0;
	$incrementValue = 2000;

	$memObject=JsMemcache::getInstance();
		$storeObj = new NEWJS_SEARCH_MALE("newjs_slave");
		$storeUpdateObj = new NEWJS_SEARCH_MALE("newjs_masterRep");
	if($tableName == "SearchMale")
	{
		$tbName = "newjs.SEARCH_MALE";
	}
	elseif($tableName == "SearchFemale")
	{	
		$tbName = "newjs.SEARCH_FEMALE";
	}
	for($i=0;$i<$maxCount;$i+= $incrementValue)
	{
		$detailArr = $storeObj->getProfilesForPaidOn($tbName,$offset,$limit);
		foreach($detailArr as $key => $value)
		{
			$dataIsPaid = $memObject->get("FreeToP_".$value);
			if($dataIsPaid !== false && $dataIsPaid != '')
			{
				$storeUpdateObj->updatePaidOn($tableName,$value,$dataIsPaid);
			}
		}
		$offset += $incrementValue;
	}
  }
}
