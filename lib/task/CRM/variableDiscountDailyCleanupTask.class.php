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
        
        $vdBackupOneDayObj = new billing_VARIABLE_DISCOUNT_BACKUP_1DAY('newjs_masterDDL');
        
        $vdBackupOneDayObj->truncate();
        $vdBackupOneDayObj->insertDataFromVariableDiscount();
        
        //Send VD Impact Report
        $vdImpactObj = new VariableDiscount();
        $vdImpactObj->generateVDImpactReport();
        
        $vdObj = new billing_VARIABLE_DISCOUNT();
        //$vdObjSelSlave = new billing_VARIABLE_DISCOUNT('newjs_slave');
        
        // Only edate check is added to include current available discounts and future discounts
        /* Future discounts : those discounts whose start date is greater than todays date */
        
        // Maintains log for the expired discounts
        $vdLogObj = new billing_VARIABLE_DISCOUNT_LOG();
        $vdLogObj->insertDataFromVariableDiscountBackup1Day($todayDate);
        
        //$toBeDeletedProfiles = $vdObjSelSlave->selectToBeDeletedProfilesWhoseVariableDiscountIsEndingYesterday();
        //$newjsTempSMSDetObj = new newjs_TEMP_SMS_DETAIL();
        //$newjsTempSMSDetObj->deletePreviousVdEntries($toBeDeletedProfiles);
        $vdObj->deleteVariableDiscountEndingYesterday();

        
        // Maintain records from VARIABLE_DISCOUNT_OFFER_DURATION for the expired discounts in VARIABLE_DISCOUNT_OFFER_DURATION_LOG
        $vdOfferDurationLogObj = new billing_VARIABLE_DISCOUNT_OFFER_DURATION_LOG();
	$todaysDate =date("Y-m-d");
        $vdOfferDurationLogObj->maintainExpiredDiscounts($todaysDate);
        
        // Delete records from VARIABLE_DISCOUNT_OFFER_DURATION for the expired discounts
        $vdOfferDurationObj = new billing_VARIABLE_DISCOUNT_OFFER_DURATION();
        $vdOfferDurationObj->deleteExpiredDiscount($todayDate);

	$vdDiscountTemp =new billing_VARIABLE_DISCOUNT_TEMP();
	$vdDiscountTemp->deleteExpiredMiniVdDiscount($todayDate);	
        
        unset($vdBackupOneDayObj);
        unset($vdObj);
        unset($vdLogObj);
        unset($vdImpactObj);
        unset($vdOfferDurationLogObj);
        unset($vdOfferDurationObj);
        
        //*************  Variable discount cleanup process Ends   ****************
    }
}
?>
