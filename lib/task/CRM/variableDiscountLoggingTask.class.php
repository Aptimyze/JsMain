<?php
/***************************************************************************************************************

* DESCRIPTION   : Cron script, daily scheduled
		: 1. Updates the billing.VARIABLE_DISCOUNT tables to contain only valid discount records and removes the expired records.
		: 2. Update the newjs.ANALYTICS_VARIABLE_DISCOUNT to contain the banner slabs for the profileids for which valid discount exists
*****************************************************************************************************************/

class  variableDiscountLoggingTask extends sfBaseTask{
    protected  function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
        ));
        
        $this->namespace = "vdLogging";
        $this->name = "variableDiscountLoggingTask";
        $this->briefDescription = "";
        $this->detailedDescription = <<<EOF
            The [variableDiscountLoggingTask|INFO] task does things.
            Call it with:[php symfony vdLogging:variableDiscountLoggingTask|INFO]
EOF;
    }
    
    protected function execute($arguments = array(), $options = array())
    {
        ini_set('max_execution_time',0);
        ini_set('memory_limit',-1);
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        $todayDate =date("Y-m-d");

        //************** Variable discount logging process Starts *************
       
	$vdLogObj 		= new billing_VARIABLE_DISCOUNT_LOG();
	$vdOfferDurationLogObj 	= new billing_VARIABLE_DISCOUNT_OFFER_DURATION_LOG();
	$vdObj 			= new billing_VARIABLE_DISCOUNT('newjs_slave');

	$profilesArr 		=$vdObj->getVDProfilesExpiringToday($todayDate);
	if(is_array($profilesArr)){
		foreach($profilesArr as $key=>$dataArr){
			$profileid =$dataArr['PROFILEID'];
			$vdLogObj->insertDataInLog($profileid,$dataArr['DISCOUNT'],$dataArr['SDATE'],$dataArr['EDATE'],$dataArr['ENTRY_DT'],$dataArr['SENT'],$dataArr['SENT_MAIL']);
			$vdOfferDurationLogObj->maintainExpiredDiscounts($profileid,$dataArr['EDATE']);	
		}
	}
        
        unset($vdBackupOneDayObj);
        unset($vdObj);
        unset($vdLogObj);
        unset($vdOfferDurationLogObj);
        
        //*************  Variable discount cleanup process Ends   ****************
    }
}
?>
