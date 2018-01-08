<?php
ini_set("max_execution_time",0);
ini_set("memory_limit","128M");
class misRegistrationQualityTask extends sfBaseTask
{
  protected $screenDate =3;
  protected $registrationArray = array();
  protected $CC = array(10,33,19,7,27,30,34,14,28,20,36,12,6,13,37); // core community
  protected $SIC = array(31,16,17,3,25); // south indian community
  protected function configure()
  {
    
    $this->namespace        = 'registration';
    $this->name             = 'misRegistrationQuality';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [misRegistrationQuality|INFO] task does things.
Call it with:
  [php symfony registration:misRegistrationQuality|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array()) {
    error_reporting(0);
    $preDefArray = array('total_reg' => 0, 'F' => 0, 'FMV' => 0, 'FMVCC' => 0, 'M' => 0, 'MMV' => 0, 'MMVCC' => 0, 'screened_SIC' => 0,'SCREENED_CC' => 0,'OTHERS_COMMUNITY' => 0);
    $flag = 1;
    $jprofileObj = new JPROFILE('newjs_slave');
    while($flag)
    {
      $entryDt = $jprofileObj->getLatestEntryDate();
      if($entryDt["ENTRY_DT"] == date("Y-m-d"))
      {
        $flag = 0;
      }
      sleep(60);
    }
    $registerDate = date('Y-m-d', strtotime('- ' . $this->screenDate . ' day'));
    $profiles = $jprofileObj->getProfileQualityRegistationData($registerDate," 00:00:00");
   
    $profile_ids = array();
    $qualityProfiles = array();
    foreach ($profiles as $profile) {
      $profiles_entry_date = date("Y-m-d",strtotime($profile["ENTRY_DT"]));
      if ( $registerDate == $profiles_entry_date) 
      {
        $profile_ids[] = $profile["PROFILEID"];
      }  
      $is_other_community = true;
      $regKey = date('d', strtotime($profile['ENTRY_DT']));
      if (!$profile['SOURCE']) {
        $sourceGroupId = 'BlankSourceGroup';
      } else {
        $sourceGroupId = $profile["SOURCE"];
      }
      if ($profile['SOURCECITY'] == '' || is_null($profile['SOURCECITY'])) {
        $cityRES = 'BlankCITY';
      } else {
        $cityRES = $profile["SOURCECITY"];
      }
      $countryRes = $profile["SOURCE_COUNTRY"];
      $cityRES .= "_".$countryRes;
      if (!array_key_exists($sourceGroupId, $this->registrationArray[$regKey])) {
              $this->registrationArray[$regKey][$sourceGroupId] =array();
      }
      if (!array_key_exists($cityRES, $this->registrationArray[$regKey][$sourceGroupId])) {
        $this->registrationArray[$regKey][$sourceGroupId][$cityRES] = $preDefArray;
        $this->registrationArray[$regKey][$sourceGroupId][$cityRES]['date'] = date('Y-m-d', strtotime($profile['ENTRY_DT']));
      }
      
      
      $this->registrationArray[$regKey][$sourceGroupId][$cityRES]['source_country']  = $countryRes;
      $this->registrationArray[$regKey][$sourceGroupId][$cityRES]['total_reg'] ++;
      if (in_array($profile['MTONGUE'], $this->SIC)){
        $this->registrationArray[$regKey][$sourceGroupId][$cityRES]['screened_SIC'] ++;
        $is_other_community = false;
      }
      $ccStatus = $this->verifyCC($profile['MTONGUE']);

      if (($profile['GENDER'] == 'F' && $profile['AGE'] >= 22) || ($profile['GENDER'] == 'M' && $profile['AGE'] >= 26)) {
        $this->registrationArray[$regKey][$sourceGroupId][$cityRES][$profile['GENDER']] ++;
        $mobVerified = $this->verifyMobile($profile['MV']);
        if($mobVerified == 1){
          $qualityProfiles[] = $profile["PROFILEID"];
          $this->registrationArray[$regKey][$sourceGroupId][$cityRES][$profile['GENDER'].'MV'] ++;
          $this->registrationArray[$regKey][$sourceGroupId][$cityRES][$profile['GENDER'].'MVCC'] += $ccStatus;
        }
      }
      if($ccStatus == 1){
            $is_other_community = false;
            $this->registrationArray[$regKey][$sourceGroupId][$cityRES]['SCREENED_CC'] += $ccStatus;
      }
      
      if ( $is_other_community )
      {
          $this->registrationArray[$regKey][$sourceGroupId][$cityRES]['OTHERS_COMMUNITY'] ++;
      }
      }
    $regQualityObj = new REGISTRATION_QUALITY();
    $regQualityObj->insertQualityRegistration($this->registrationArray);

    $regQualityActivated = new REGISTER_REG_QUALITY_ACTIVATED();
   
    $regQualityActivated->insert($profile_ids,$registerDate); 

    $qualityUpdate = new MIS_CAMPAIGN_KEYWORD_TRACKING(); // update quality column in CAMPAIGN keyword tracking MIS
    $qualityUpdate->updateIsQualityProfile($qualityProfiles);
    
    $this->logSection('data inserted');
  }
  function verifyMobile($MV) {
    if($MV == 'Y'){
      return 1;
    }
    return 0;   
  }
  function verifyCC($MTOUNGE){
    if (in_array($MTOUNGE, $this->CC)){
      return 1;
    }
    return 0;
  }
}
