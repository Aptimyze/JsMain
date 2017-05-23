<?php
/***************************************************************************************************************

* DESCRIPTION   : Cron script, daily scheduled
		: 1. Updates the billing.VARIABLE_DISCOUNT tables to contain only valid discount records and removes the expired records.
		: 2. Update the newjs.ANALYTICS_VARIABLE_DISCOUNT to contain the banner slabs for the profileids for which valid discount exists
*****************************************************************************************************************/

class variableDiscountDailyCleanupTask extends sfBaseTask{
    protected  function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
        ));
        
        $this->namespace = "vdCleanup";
        $this->name = "variableDiscountDailyCleanupTask";
        $this->briefDescription = "";
        $this->detailedDescription = <<<EOF
            The [variableDiscountDailyCleanupTask|INFO] task does things.
            Call it with:[php symfony vdCleanup:variableDiscountDailyCleanupTask|INFO]
EOF;
    }
    
    protected function execute($arguments = array(), $options = array())
    {
        ini_set('max_execution_time',0);
        ini_set('memory_limit',-1);
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        $todayDate =date("Y-m-d");
        //************** Variable discount cleanup process Starts *************
        
	$vdObj = new billing_VARIABLE_DISCOUNT();

        // Truncate temporary table and insert data into it
        $vdBackupOneDayObj = new billing_VARIABLE_DISCOUNT_BACKUP_1DAY('newjs_master');
        $vdBackupOneDayObj->truncate();
        $vdBackupOneDayObj->insertDataFromVariableDiscount($todayDate);

       	//Send VD Impact Report
       	$vdImpactObj = new VariableDiscount();
       	$vdImpactObj->generateVDImpactReport();
        
       	// Maintains log for the expired discounts
	$vdObj->deleteVariableDiscountEndingYesterday();

       	// Delete records from VARIABLE_DISCOUNT_OFFER_DURATION for the expired discounts
       	$vdOfferDurationObj = new billing_VARIABLE_DISCOUNT_OFFER_DURATION();
       	$vdOfferDurationObj->deleteExpiredDiscount($todayDate);

	$vdDiscountTemp =new billing_VARIABLE_DISCOUNT_TEMP();
	$vdDiscountTemp->deleteExpiredMiniVdDiscount($todayDate);	
        
       	unset($vdBackupOneDayObj);
       	unset($vdObj);
       	unset($vdImpactObj);
       	unset($vdOfferDurationObj);
        
        //*************  Variable discount cleanup process Ends   ****************
    }
}
?>
