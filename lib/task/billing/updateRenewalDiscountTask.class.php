<?php

class updateRenewalDiscountTask extends sfBaseTask
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
    $this->name             = 'updateRenewalDiscount';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [updateRenewalDiscount|INFO] task does things.
Call it with:

  [php symfony updateRenewalDiscount|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        sfContext::createInstance($this->configuration);
	$expiryDt = date("Y-m-d", time()+29*24*60*60);
	$startDt  = date("Y-m-d");
	$discountExpiryDt = date("Y-m-d",strtotime("$expiryDt +10 days"));

        $ssObj = new BILLING_SERVICE_STATUS();
        $res = $ssObj->getRenewalProfiles($expiryDt);

        $memHandlerObj = new MembershipHandler();
        $rdObj = new billing_RENEWAL_DISCOUNT();
	$rdLogObj =new billing_RENEWAL_DISCOUNT_LOG();
        $rdObj->removedExpiredProfiles();

	if(count($res)>0){
	        foreach($res as $key=>$profileid){
		       	$discount = $memHandlerObj->calculateVariableRenewalDiscount($profileid);        
		       	$rdObj->insert($profileid, $discount, $expiryDt);
			$rdLogObj->insert($profileid, $discount,$startDt, $discountExpiryDt);
	        }
	}
  }
}
