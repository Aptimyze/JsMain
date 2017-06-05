<?php

class deActivationDiscountOfferTask extends sfBaseTask
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
    $this->name             = 'deActivationDiscountOffer';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [deActivationDiscountOffer|INFO] task does things.
Call it with:

  [php symfony deActivationDiscountOffer|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        sfContext::createInstance($this->configuration);
        $membershipHandlerObj =new MembershipHandler();

        $discountType =discountType::FESTIVE_DISCOUNT;
        $membershipHandlerObj->deActivateDiscountOffer($discountType);

  }
}
