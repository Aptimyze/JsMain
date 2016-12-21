<?php
/*
 *	Author:Esha Jain
 */

class removeOtherCasteDppTask extends sfBaseTask
{
	protected function configure()
	{
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
            ));

	    $this->namespace        = 'oneTimeCron';
	    $this->name             = 'removeOtherCasteDpp';
	    $this->briefDescription = 'remove other caste from dpp';
	    $this->detailedDescription = <<<EOF
	remove other caste from newjs.JPARTNER
	   Call it with:
	   [php symfony oneTimeCron:removeOtherCasteDpp]
EOF;
	}
	protected function execute($arguments = array(), $options = array())
	{	
        	if (!sfContext::hasInstance())
        		sfContext::createInstance($this->configuration);
		$limit = 1000;
		for($shard=1;$shard<=3;$shard++)
		{
			$jpartnerSlaveObj = new newjs_JPARTNER("shard".$shard."_slave");
			$jpartnerMasterObj = new newjs_JPARTNER("shard".$shard."_master");
			foreach(DPPConstants::$removeCasteFromDppArr as $k=>$p_caste)
			{
				$offset = 0;
				while(1)
				{
					unset($results);
					$results = $jpartnerSlaveObj->selectPartnerCaste($p_caste,$offset,$limit);
					if(!is_array($results))
						break;
					else
					{
						foreach($results as $x=>$y)
						{
							$newCaste = '';
							$newCaste = $this->getNewCaste($y);
							$jpartnerMasterObj->updateCaste($y['PROFILEID'],$newCaste,$y['PARTNER_CASTE']);
						}
					}
					$offset+=$limit;
				}
			}
			unset($jpartnerSlaveObj);
			unset($jpartnerMasterObj);
		}
	}
	public function getNewCaste($data)
	{
		$casteArr = explode(",",str_replace("'", "",$data['PARTNER_CASTE']));
		$flipArr = array_flip($casteArr);
		foreach(DPPConstants::$removeCasteFromDppArr as $k=>$p_caste)
		{
			unset($casteArr[$flipArr[$p_caste]]);
		}
		if(is_array($casteArr) && count($casteArr)>0)
			return "'".implode("','",$casteArr)."'";
		else
			return;
	}
}
