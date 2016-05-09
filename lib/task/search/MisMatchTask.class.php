<?php
/*
 * Author: Ankit Shukla 
 * This task takes all the profiles from search_male and search_female table
 * and matches their gender with gender in Jprofile table and deletes all the
 * mismatched profiles and also profiles with negative flag in 
 * incentive.NEGATIVE_TREATMENT_LIST
 */

class GenderMisMatchTask extends sfBaseTask
{
  protected function configure() {
    $this->namespace        = 'search';
    $this->name             = 'MisMatch';
    $this->briefDescription = 'remove profiles with discrepancy in search_male,female table';
    $this->detailedDescription = <<<EOF
Call it with:
[php symfony search:MisMatch]
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
}
  protected function execute($arguments = array(), $options = array()) {
    if(!sfContext::hasInstance()) {
      sfContext::createInstance($this->configuration);
    }                    
    $searchMaleMasterObj = new NEWJS_SEARCH_MALE("newjs_master");
    $searchMaleSlaveObj = new NEWJS_SEARCH_MALE("newjs_slave");
    $searchFemaleMasterObj = new NEWJS_SEARCH_FEMALE("newjs_master");
    $searchFemaleSlaveObj = new NEWJS_SEARCH_FEMALE("newjs_slave");
    $GenderMaleProfiles=$searchMaleSlaveObj->getProfilesGenderDiscrepancy();
    if ($GenderMaleProfiles) {
      $searchMaleMasterObj->deleteProfilesGenderNFlag($GenderMaleProfiles);
    }
    $NFlagMaleProfiles=$searchMaleSlaveObj->getProfilesNegativeFlag();
    if ($NFlagMaleProfiles) {
      $searchMaleMasterObj->deleteProfilesGenderNFlag($NFlagMaleProfiles);
    }
    $GenderFemaleProfiles=$searchFemaleSlaveObj->getProfilesGenderDiscrepancy();
    if ($GenderFemaleProfiles) {
      $searchFemaleMasterObj->deleteProfilesGenderNFlag($GenderFemaleProfiles);
    }
    $NFlagFemaleProfiles=$searchFemaleSlaveObj->getProfilesNegativeFlag();
    if ($NFlagFemaleProfiles) {
      $searchFemaleMasterObj->deleteProfilesGenderNFlag($NFlagFemaleProfiles);
    }
  }
}

