<?php
/**
 * Description of EditLogDataPortingTask
 * Cron job for porting existing data in MySql Table to MongoDB Collection
 * <code>
 * To execute : $ symfony oneTimeCron:EditLogDataPorting totalScripts currentScript
 * </code>
 * @author Kunal Verma
 * @created 13th March 2016
 */
class EditLogDataPortingTask extends sfBaseTask
{
  /**
   * To Show Debug Information on console
   */
  private $m_bDebugInfo = false;
  
  /**
   * Array of profile
   * @var Array
   */
  private $m_arrProfilesData = array();
  
  /**
   * Array of Unique Profile
   * @var Array
   */
  private $m_arrUniqueProfiles = array();
  
  /**
   * NO_OF_PROFILES
   */
  const NO_OF_PROFILES = 5000;
  
  /**
   *
   * @var Array of default values 
   */
  private $editLogDefaultArray = array(
    "GENDER"          => '',
    "RELIGION"        => '0',
    "CASTE"           => '0',
    "MANGLIK"         => '',
    "DTOFBIRTH"       => '0000-00-00',
    "MTONGUE"         => '0',
    "OCCUPATION"      => '0',
    "COUNTRY_RES"     => '0',
    "CITY_RES"        => '',
    "HEIGHT"          => '0',
    "EDU_LEVEL"       => '0',
    "EMAIL"           => '',
    "ENTRY_DT"        => '0000-00-00 00:00:00',
    "MOD_DT"          => '0000-00-00 00:00:00',
    "COUNTRY_BIRTH"   => '0',
    "PHOTO_DISPLAY"   => 'A',
    "PHOTOSCREEN"     => '31',
    "NTIMES"          => '0',
    "SUBSCRIPTION_EXPIRY_DT" => '0000-00-00',
    "LAST_LOGIN_DT"   => '0000-00-00',
    "PHOTODATE"       => '0000-00-00 00:00:00',
    "TIMESTAMP"       => '0000-00-00 00:00:00',
    "UDATE"           => '0000-00-00',
    "EDU_LEVEL_NEW"   => '0',
    "SORT_DT"         => '0000-00-00 00:00:00',
    "M_SISTER"        => '0',
    "FAMILY_TYPE"     => '0',
    "FAMILY_STATUS"   => '0',
    "CITIZENSHIP"     => '0',
    "FAMILY_INCOME"   => '0',
    "SECT"            => '0',
    "PG_DEGREE"       => '0',
    "UG_DEGREE"       => '0',
  );
  protected function configure()
  {
    $this->addArguments(array(
			new sfCommandArgument('totalScripts', sfCommandArgument::OPTIONAL, 'TotalScript'),
			new sfCommandArgument('currentScript', sfCommandArgument::OPTIONAL, 'CurrentScript'),
		));
    
    $this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
    
    $this->namespace        = 'oneTimeCron';
    $this->name             = 'EditLogDataPorting';
    $this->briefDescription = 'To port existing data from mysql newjs.EDIT_LOG to mongodb profile.EDIT_LOG';
    $this->detailedDescription = <<<EOF
The [oneTimeCron:EditLogDataPorting|INFO] task runs once to port data 
Call it with:

  [php symfony oneTimeCron:EditLogDataPorting totalScripts currentScript|INFO]
EOF;
  }
  
  /**
   * Main execute function          
   * @param type $arguments
   * @param type $options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
    
    $totalScripts = "1";
    $currentScript = "0";
    
    if(isset($arguments["totalScripts"])){
      $totalScripts = $arguments["totalScripts"]; // total no of scripts
    }
    if(isset($arguments["currentScript"])){
      $currentScript = $arguments["currentScript"]; // current script number
    }
		
    $st_Time = microtime(TRUE);
    //Add your code here
    
    //Get Unique Profiles
    $this->getUniqueProfiles($totalScripts, $currentScript);
    
    do{
      foreach($this->m_arrUniqueProfiles as $key => $val){
                
        //Get Profiles
        $this->getEditLogData($val['PROFILEID']);

        //Process data for Bulk write
        $this->processDataForBlkWrite();
        
        //Unset Each Profile Data
        unset($this->m_arrProfilesData);
      }
      
      //Unset Current Profiles and Get next set of profiles
      unset($this->m_arrUniqueProfiles);
      $this->getUniqueProfiles($totalScripts, $currentScript);
    }while($this->m_arrUniqueProfiles && count($this->m_arrUniqueProfiles));
    
    //Get Script Statistics
    $this->endScript($st_Time);
  }
  
  /**
   * End script 
   * To note statistic of memory and time usages
   * @param : $st_Time [Start Time]
   * @return void
   */
  private function endScript($st_Time = '') {
    $end_time = microtime(TRUE);
    $var = memory_get_usage(true);

    if ($var < 1024)
      $mem = $var . " bytes";
    elseif ($var < 1048576)
      $mem = round($var / 1024, 2) . " kilobytes";
    else
      $mem = round($var / 1048576, 2) . " megabytes";

    if ($this->m_bDebugInfo) {
      $this->logSection('Memory usages : ', $mem);
      $this->logSection('Time taken : ', $end_time - $st_Time);
    }
  }
  
  /**
   * getEditLogData for a single profile
   *  
   */
  private function getEditLogData($iProfileID)
  {
    $storeObj = new StoreTable('newjs_master');
    
    $dbObject = $storeObj->getDBObject();
    $sql ="SELECT * FROM newjs.EDIT_LOG WHERE PROFILEID = :PID ORDER BY MOD_DT ASC";
    
    $pdoStatement = $dbObject->prepare($sql);
    $pdoStatement->bindValue(":PID",$iProfileID,PDO::PARAM_INT);
    $pdoStatement->execute();
    
    if ($pdoStatement->rowCount()) {
      $this->m_arrProfilesData = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
      $this->m_arrProfilesData = null;
    }
    unset($storeObj);
  }
  
  /**
   * processDataForBlkWrite
   */
  private function processDataForBlkWrite()
  {
    //No data exist to process so exit
    if(null === $this->m_arrProfilesData) {
      return ;
    }
    $registrationData = $this->m_arrProfilesData[0];
    $arrSkip = array('MOD_DT','IPADD');
    $arrOut = array();
    
    for ($itr=1;$itr<count($this->m_arrProfilesData);++$itr) {
      $defaultValues = array_intersect($this->m_arrProfilesData[$itr], $this->editLogDefaultArray);
      $arrUpdatedFields = array_diff_key($this->m_arrProfilesData[$itr], $defaultValues);
      
      foreach($arrUpdatedFields as $key=>$value) {
        if(in_array($key, $arrSkip)) {
          continue;
        }
        
        $preValue = $this->getPreviousValue($itr, $key);
        $preValue = iconv('ISO-8859-1', 'UTF-8//IGNORE', $preValue);
        $arrUpdatedFields[$key]= $preValue;
        //$arrUpdatedFields[$key]= $this->getPreviousValue($itr, $key);
      }
      
      if(false === isset($arrUpdatedFields['PROFILEID'])) {
        $arrUpdatedFields['PROFILEID'] = $this->m_arrProfilesData[$itr]['PROFILEID'];
      }
      $arrOut[] = $arrUpdatedFields;
    }

    if (count($arrOut)){
      $mongoStore = new PROFILE_EDIT_LOG();
      $mongoStore->removeData($registrationData['PROFILEID']);
      $bStatus = $mongoStore->insertMany($arrOut);
      
      if(false !== $bStatus){
      //Update Porting Status
        $this->setPortingStatus($registrationData['PROFILEID']);
      }else{
        $this->setPortingStatus($registrationData['PROFILEID'],'E');
      }
      unset($arrOut);
      unset($mongoStore);
    }
    else if (count($this->m_arrProfilesData) == 1) {
      //Update Porting Status
      $this->setPortingStatus($registrationData['PROFILEID']);
    }
    
  }
  
  /**
   * getPreviousValue
   * @param type $currIndex
   * @param type $fieldKey
   * @return type
   */
  private function getPreviousValue($currIndex,$fieldKey)
  {
    //No data exist to process so exit
    if(null === $this->m_arrProfilesData) {
      return ;
    }
    
    $preValue = $this->m_arrProfilesData[0][$fieldKey]; //Value at registration time
    
    $itr = $currIndex-1;
    while($itr >=0)
    {
      if (array_key_exists($fieldKey, $this->editLogDefaultArray) &&
        $this->m_arrProfilesData[$itr][$fieldKey] == $this->editLogDefaultArray[$fieldKey]
       ){
        $itr = $itr - 1;
        continue;
      }
      
      if($this->m_arrProfilesData[$itr][$fieldKey]) {
        $preValue = $this->m_arrProfilesData[$itr][$fieldKey];
        break;
      }
      $itr = $itr - 1;
    }
    return $preValue;
  }
  
  /**
   * getUniqueProfiles
   */
  private function getUniqueProfiles($totalScripts, $currentScript)
  {
    $storeObj = new StoreTable('newjs_master');
    
    $dbObject = $storeObj->getDBObject();
    $sql ="SELECT PROFILEID FROM `PROFILE`.`EDIT_LOG_UNIQUE_IDS` WHERE PROFILEID%:TSCRIPT = :CSCRIPT AND PORTING_DONE=:PORT ORDER BY PROFILEID ASC LIMIT :LIMIT";
    
    $pdoStatement = $dbObject->prepare($sql);
    $pdoStatement->bindValue(":PORT",'N',PDO::PARAM_STR);
    $pdoStatement->bindValue(":LIMIT",self::NO_OF_PROFILES,PDO::PARAM_INT);
    $pdoStatement->bindValue(":TSCRIPT",$totalScripts,PDO::PARAM_INT);
    $pdoStatement->bindValue(":CSCRIPT",$currentScript,PDO::PARAM_INT);
    $pdoStatement->execute();
    
    if ($pdoStatement->rowCount()) {
      $this->m_arrUniqueProfiles = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
      $this->m_arrUniqueProfiles = null;
    }
    unset($storeObj);
  }
  
  /**
   * 
   * @param type $iProfileID
   * @param type $status
   * @return boolean
   */
  private function setPortingStatus($iProfileID, $status='Y')
  {
    $storeObj = new StoreTable('newjs_master');
    
    $dbObject = $storeObj->getDBObject();
    $sql ="UPDATE `PROFILE`.`EDIT_LOG_UNIQUE_IDS` SET PORTING_DONE=:STATUS WHERE PROFILEID=:PID";
    
    $pdoStatement = $dbObject->prepare($sql);
    
    $pdoStatement->bindValue(":PID",$iProfileID,PDO::PARAM_INT);
    $pdoStatement->bindValue(":STATUS",$status,PDO::PARAM_STR);
    $pdoStatement->execute();
    
    if ($pdoStatement->rowCount()) {
      return true;
    }
    unset($storeObj);
    return false;
  }
}
