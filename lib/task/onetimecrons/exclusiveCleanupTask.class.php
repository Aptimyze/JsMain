<?php

class exclusiveCleanupTask extends sfBaseTask
{
	protected function configure()
	{

	$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','jeevansathi'),
		));

	$this->namespace        = 'oneTimeCron';
	$this->name             = 'exclusiveCleanup';
	$this->briefDescription = 'Updates the Renewal discount percentage for profiles from today till next x days specified in code';
	$this->detailedDescription = <<<EOF
	The [exclusiveCleanup|INFO] task does things.
	Call it with:

	[php symfony oneTimeCron:exclusiveCleanup|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		sfContext::createInstance($this->configuration);

		$exObj = new billing_EXCLUSIVE_MEMBERS();
		$serviceObj = new BILLING_SERVICE_STATUS();

		$checkingDate  	='2017-03-31 00:00:00';
		$fetchDate    	='2016-12-31 00:00:00';
		$dataArr 	=$exObj->getExclusiveMembersList($fetchDate);

		foreach($dataArr as $key=>$val){
			$profileid 	=$val['PROFILEID'];
			$billid 	=$val['BILL_ID'];
			$maxExpiry 	=$serviceObj->getMaxExpirydForBillid($billid);	

			if(strtotime($maxExpiry)<strtotime($checkingDate)){
				$exObj->archiveProfile($billid,$profileid);
				$exObj->deleteExclusiveEntry($billid,$profileid);
			}
		}
	}	
}
