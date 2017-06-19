<?php
class JPayment extends Membership
{
    /**
     * @return mixed
     */
    public function getBanks()
    {
        $bankObj = new billing_BANK('newjs_slave');
        $bank    = $bankObj->getName();
        return $bank;
    }

    /**
     * @param $city_value
     * @return mixed
     */
    public function getBranches($city_value)
    {
        $newjsContactObj = new NEWJS_CONTACT_US('newjs_slave');
        $near_branches   = $newjsContactObj->fetchBranches($city_value);
        return $near_branches;
    }

    /**
     * @param $city_value
     * @return mixed
     */
    public function getChangeBranches($city_value)
    {
        $newjsContactObj = new NEWJS_CONTACT_US('newjs_slave');
        $near_branches   = $newjsContactObj->fetchBranches($city_value, true);
        return $near_branches;
    }

    /**
     * @param $cityArr
     * @return mixed
     */
    public function getChangeBranchesArr($cityArr)
    {
        $newjsContactObj = new NEWJS_CONTACT_US('newjs_slave');
        $near_branches   = $newjsContactObj->fetchBranches($cityArr, true);
        return $near_branches;
    }

    /**
     * @param $profileid
     * @return mixed
     */
    public function getCityRes($profileid)
    {
        $jprofileObj = new JPROFILE();
        $profileDet = $jprofileObj->get($profileid,'PROFILEID','CITY_RES');
        $cityRes    = $profileDet['CITY_RES'];
        return $cityRes;
    }

    /**
     * @param $chequePickup
     * @return mixed
     */
    public function getNearBycities($chequePickup = null)
    {
        $incBraCitObj = new incentive_BRANCH_CITY('newjs_slave');
        $near_ar = $incBraCitObj->fetchNearBycities($chequePickup);
        return $near_ar;
    }

    /**
     * @return mixed
     */
    public function getStates()
    {
        $newjsContactObj = new NEWJS_CONTACT_US('newjs_slave');
        $STATES          = $newjsContactObj->fetchStates();
        return $STATES;
    }

    /**
     * @param $profileid
     * @return mixed
     */
    public function get_nearest_branches($profileid)
    {
        $profileObj = LoggedInProfile::getInstance('newjs_slave', $profileid);
        $cityRes    = $profileObj->getCITY_RES();
        if ($cityRes && !empty($cityRes) && $cityRes != '') {
            $near_branches = $this->getBranches($cityRes);
        }
        return $near_branches;
    }
}
