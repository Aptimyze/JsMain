<?php

class updateDppIncomeDataTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
			));

		$this->namespace        = 'oneTimeCron';
		$this->name             = 'updateDppIncomeData';
		$this->briefDescription = 'cron to update dpp income';
		$this->detailedDescription = <<<EOF
		Call it with:

		[php symfony oneTimeCron:updateDppIncomeData]
EOF;
	}
	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);

		ini_set('memory_limit','512M');
		$limit = 2000;
		$offset = 0;
		$incrementValue = 2000;
		$profileIdStr  = "";
		$hincome = "19";
		for($activeServerId=0;$activeServerId<=2;$activeServerId++)
		{
			$shardSlave = JsDbSharding::getShardDbName($activeServerId,1);
			$shardMaster = JsDbSharding::getShardDbName($activeServerId);
			$jpartnerSlaveObj = new newjs_JPARTNER($shardSlave);
			$jpartnerMasterObj = new newjs_JPARTNER($shardMaster);
			while(1)
			{
				$profileData = $jpartnerSlaveObj->getDppDataForProfiles($limit,$offset);
				if(count($profileData) == 0)
				{
					break;
				}				
				foreach($profileData as $key=>$value)
				{
					if($value["HINCOME"]!=19 && ($value["HINCOME"] == $value["LINCOME"]+1)) //change != to ==
					{
						$rArr["minIR"] = $value["LINCOME"];
						$rArr["maxIR"] = $hincome;
						$incomeMapObj = new IncomeMapping($rArr);
						$incomeMapArr = $incomeMapObj->incomeMapping();
						$Income = $incomeMapArr['istr'];						
						$jpartnerMasterObj->updateIncomeValueForProfile($value["PROFILEID"],$hincome,$Income,$value["HINCOME"]);			
						$currentTime = date("Y-m-d H:i:s");
						unset($incomeMapObj);
						if($value["GENDER"] == "M")
						{							
							$searchFemaleObj = new NEWJS_SEARCH_FEMALE();
							$searchFemaleObj->updateModifiedTime($value["PROFILEID"],$currentTime);
							unset($searchFemaleObj);
						}
						elseif($value["GENDER"] == "F")
						{
							$searchMaleObj = new NEWJS_SEARCH_MALE();
							$searchMaleObj->updateModifiedTime($value["PROFILEID"],$currentTime);
							unset($searchMaleObj);
						}
					}				
				}				
				$offset +=$incrementValue;
				unset($profileData);
			}
			$offset = 0;
			unset($jpartnerSlaveObj);
			unset($jpartnerMasterObj);
		}
	}
}