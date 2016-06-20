<?
/*
This php script populates data from VARIABLE_DISCOUNT_TEMP to VARIABLE_DISCOUNT and VARIABLE_DISCOUNT_OFFER_DURATION tables
*/

class populateVDEntriesFromTempTableTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for billing:populateVDEntriesFromTempTable
   * 
   * @access protected
   * @param none
  */
  protected function configure()
  {
    $this->namespace           = 'billing';
    $this->name                = 'populateVDEntriesFromTempTable';
    $this->briefDescription    = 'populates data from VARIABLE_DISCOUNT_TEMP to VARIABLE_DISCOUNT and VARIABLE_DISCOUNT_OFFER_DURATION tables';
    $this->detailedDescription = <<<EOF
     The [populateVDEntriesFromTempTable|INFO] populates data from VARIABLE_DISCOUNT_TEMP to VARIABLE_DISCOUNT and VARIABLE_DISCOUNT_OFFER_DURATION tables:
     [php symfony billing:populateVDEntriesFromTempTable] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  /**
   * 
   * Function for executing cron.
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    ini_set('max_execution_time',0);
    ini_set('memory_limit',-1);
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
    $sendMailAlert = true;    //send mail alert in case of cron failure
    $entryDate =date("Y-m-d");
    $limit = uploadVD::$RECORDS_SELECTED_PER_TRANSFER;

    //update previous VD records
    $VDObj = new VariableDiscount();
    //$VDObj->updatePreviousVDRecords($entryDate);  Not Required 

    //populate new records from temp table to main VD tables
    $VDObj->populateRecordsFromVDTemp($entryDate,$limit,$sendMailAlert);
    unset($VDObj);

  }
}
?>
