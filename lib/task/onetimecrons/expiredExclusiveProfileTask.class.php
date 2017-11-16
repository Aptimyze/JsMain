<?php

class expiredExclusiveProfileTask extends sfBaseTask
{

    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'jeevansathi')
        ));
        
        $this->namespace = 'oneTimeCron';
        $this->name = 'expiredExclusiveProfile';
        $this->briefDescription = 'This will delete the legacy data of exclusive profile which are expired';
        $this->detailedDescription = <<<EOF
	The [expiredExclusiveProfile|INFO] task does things.
	Call it with:

	[php symfony oneTimeCron:expiredExclusiveProfile|INFO]
EOF;
    }

    // Function will either delete the expired data or if not expire will update the billid
    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);
        $exObj = new billing_EXCLUSIVE_MEMBERS();
        $serviceObj = new BILLING_SERVICE_STATUS();
        $exServicingObj = new billing_EXCLUSIVE_SERVICING();
        $exLegacyArray = $exServicingObj->getData();
        foreach ($exLegacyArray as $key => $value) {
            $profileId = $value["CLIENT_ID"];
            $legacyBillid = $value["BILLID"];
            $id=$value["ID"];
            $result = $serviceObj->getMaxExpiryExclusive($profileId);
            if($result!=null){
                $billid = $result["BILLID"];
                $expiryDate = $result["EXP_DT"];
                $expiryDate = strtotime($expiryDate);
                $currentDate = date("Y-m-d");
                $currentDate = strtotime($currentDate);
                if ($expiryDate < $currentDate) {
                    $exServicingObj->removeExclusiveClientEntry($profileId,"",$legacyBillid);
                } else {
                    if ($legacyBillid == 0 || $legacyBillid=="")
                        $exServicingObj->updateBillingDate($id,$billid);
                }
                unset($result);
            }
        }
    }
}
