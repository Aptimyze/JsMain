<?php


class cronInsertDataIntoAPProfileTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronExecuteDiscountTrackingConsumer
   * 
   * @access protected
   * @param none
  */
  protected function configure()
  {
    $this->namespace           = 'cron';
    $this->name                = 'cronInsertDataIntoAPProfile';
    $this->briefDescription    = 'Get the data from the service_status to ap_profile';
    $this->detailedDescription = <<<EOF
     The [cronexecuteConsumer|INFO] copy the data if not present:
     [php symfony cron:cronInsertDataIntoAPProfileTask] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  /**
   * 
   * Function for executing cron. Executes cron and sets memory and disk alarms for First and Second Server as false
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    //Get the last data from
    $incentive = new incentive_LAST_HANDLED_DATE();
    $purchase = new BILLING_PURCHASES();
    $aprofileInfo = new ASSISTED_PRODUCT_AP_PROFILE_INFO();
    $exclusiveMemberObj = new billing_EXCLUSIVE_MEMBERS();
    $maxDate = $incentive->getHandledDate(18);
    $profileArray = array();
    $exclusiveMemberArray = array();
    $profileArray = $purchase->getExclusiveProfile($maxDate);
    foreach ($profileArray as $key => $value){
        
        $aprofileInfo->insertIntoAPProfileInfo($key,"LIVE",date("Y-m-d H:i:s"),'Y',"default.se");
        //Start JSC-3375
        $exclusiveMemberArray["PROFILEID"] = $value["PROFILEID"];
        $exclusiveMemberArray["ASSIGNED_DT"]="0000-00-00";
        $exclusiveMemberArray["ASSIGNED_TO"]=NULL;
        $exclusiveMemberArray["ASSIGNED"]='N';
        $exclusiveMemberArray["BILLING_DT"]=$value["ENTRY_DT"];
        $exclusiveMemberArray["BILL_ID"]=$value["BILLID"];
        $exclusiveMemberObj->addExclusiveMember($exclusiveMemberArray);
        unset($exclusiveMemberArray);
        //end
        $maxDate = $value;
    }
    $incentive->setHandledDate(18,$maxDate);
  }


}
?>
