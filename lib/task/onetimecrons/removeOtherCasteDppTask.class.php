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
		for($shard=1;$shard<=3;$shard++)
		{
			foreach(DPPConstants::$removeCasteFromDppArr as $k=>$p_caste)
			{
				while(1)
				{sleep(5);
					$jpartnerSlaveObj = new newjs_JPARTNER("shard".$shard."_slave");
					unset($results);
					$results = $jpartnerSlaveObj->selectPartnerCaste($p_caste);
					if(!is_array($results))
						break;
					else
					{
						$jpartnerMasterObj = new newjs_JPARTNER("shard".$shard."_master");
						foreach($results as $x=>$y)
						{
							$newCaste = '';
							$newCaste = $this->getNewCaste($y);
							$jpartnerMasterObj->updateCaste($y['PROFILEID'],$newCaste);
						}
						unset($jpartnerMasterObj);
					}
				}
			}
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
