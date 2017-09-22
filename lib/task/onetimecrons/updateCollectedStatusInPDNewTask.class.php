<?php

class updateCollectedStatusInPDNewTask extends sfBaseTask
{
	protected function configure()
	{

	$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','jeevansathi'),
		));

	$this->namespace        = 'oneTimeCron';
	$this->name             = 'updateCollectedStatusInPDNew';
	$this->briefDescription = 'Update COLLECTED, COLLECTED_BY, COLLECTION_DATE in PAYMENT_DETAIL_NEW for entries after 1st March';
	$this->detailedDescription = <<<EOF
	The [updateCollectedStatusInPDNew|INFO] task does things.
	Call it with:

	[php symfony oneTimeCron:updateCollectedStatusInPDNew|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
        if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
		
        $startDate = "2017-04-01 00:00:00";
        $paymentDetailObj = new BILLING_PAYMENT_DETAIL("newjs_slave");
        $records = $paymentDetailObj->getDesiredRecordsAfterDate($startDate);
        
        $paymentDetailNewObj = new billing_PAYMENT_DETAIL_NEW();
        print_r("Total records:");
        print_r(count($records));
        foreach($records as $key => $value){
            unset($params);
            $params["RECEIPTID"]       = $value["RECEIPTID"];
            $params["COLLECTED"]       = $value["COLLECTED"];
            $params["COLLECTED_BY"]    = $value["COLLECTED_BY"];
            $params["COLLECTION_DATE"] = $value["COLLECTION_DATE"];
            $paymentDetailNewObj->updateCollectionRecords($params);
        }
        print_r("Done!");
	}
}
