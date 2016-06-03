<?php
ini_set("max_execution_time",0);
ini_set("memory_limit","128M");
class misRegistrationQualityTask extends sfBaseTask
{
  protected $screenDate =3;
  protected $registrationArray = array();
  protected $CC = array(10,33,19,7,27,30,34,14,28,20,36,12,6,13); // core community
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
    $preDefArray = array('total_reg' => 0, 'F' => 0, 'FMV' => 0, 'FMVCC' => 0, 'M' => 0, 'MMV' => 0, 'MMVCC' => 0, 'screened_SIC' => 0);
    
    $jprofileObj = new JPROFILE('newjs_slave');
    $registerDate = date('Y-m-d', strtotime('- ' . $this->screenDate . ' day')) . " 00:00:00";
    $profiles = $jprofileObj->getProfileQualityRegistationData($registerDate);
    
    foreach ($profiles as $profile) {
      $regKey = date('d', strtotime($profile['ENTRY_DT']));
      if (!$profile['SOURCE']) {
        $sourceGroupId = 'BlankSourceGroup';
      } else {
        $sourceGroupId = $profile["SOURCE"];
      }
      if (!array_key_exists($sourceGroupId, $this->registrationArray[$regKey])) {
        $this->registrationArray[$regKey][$sourceGroupId] = $preDefArray;
        $this->registrationArray[$regKey][$sourceGroupId]['date'] = date('Y-m-d', strtotime($profile['ENTRY_DT']));
      }
      
      $this->registrationArray[$regKey][$sourceGroupId]['total_reg'] ++;
      if (in_array($profile['MTONGUE'], $this->SIC)){
        $this->registrationArray[$regKey][$sourceGroupId]['screened_SIC'] ++;
      }
      if (($profile['GENDER'] == 'F' && $profile['AGE'] >= 22) || ($profile['GENDER'] == 'M' && $profile['AGE'] >= 26)) {
        $this->registrationArray[$regKey][$sourceGroupId][$profile['GENDER']] ++;
        $mobVerified = $this->verifyMobile($profile['MV']);
        if($mobVerified == 1){
          $this->registrationArray[$regKey][$sourceGroupId][$profile['GENDER'].'MV'] ++;
          $this->registrationArray[$regKey][$sourceGroupId][$profile['GENDER'].'MVCC'] += $this->verifyCC($profile['MTONGUE']);
        }
      }
    }
    $regQualityObj = new REGISTRATION_QUALITY();
    $regQualityObj->insertQualityRegistration($this->registrationArray);
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
