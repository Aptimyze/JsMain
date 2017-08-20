<?php

class activateMiniVdOfferTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
     $this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','jeevansathi'),
     ));

    $this->namespace        = 'billing';
    $this->name             = 'activateMiniVdOffer';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [activateMiniVdOffer|INFO] task does things.
Call it with:

  [php symfony activateMiniVdOffer|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
  	ini_set('max_execution_time',0);
  	ini_set('memory_limit',-1);
    sfContext::createInstance($this->configuration);

  	// Main VD process
    $membershipHandlerObj =new MembershipHandler();
    $membershipHandlerObj->addVariableDiscountProfiles();

  	// Mini-vd process
  	$entryDate =date("Y-m-d");
  	$sendMailAlert = true;  
    $VDObj = new VariableDiscount();
    $VDObj->populateRemainingRecordsFromVDTemp($entryDate,$sendMailAlert);
          
    // Extended VD process
    $VDObj->activateExtendedVD($entryDate);
    unset($VDObj); 
  }
}
