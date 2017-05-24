<?php
/**
 * 
 */
class ChatDetail
{
  /**
   * ProfileId of a user
   * @var Integer 
   */
  private $iProfileID = null;
    
  /**
   *
   * @var type 
   */
  private $szProfileDBShard = null;
  
  /**
   *
   * @var type 
   */
  private $szProfileDBMaster = null;
  
  /**
   * 
   */
  public function __construct($iProfileId)
  {
    $this->iProfileID = $iProfileId;
    $this->szProfileDBShard = JsDbSharding::getShardNo($this->iProfileID, true);
    $this->szProfileDBMaster = JsDbSharding::getShardNo($this->iProfileID, false);
  }
  
  /**
   * 
   * @param type $bSlavePrefrence : True for Data from Slave, ekse from Master Db
   * @param type $iOtherProfileID : ProfileId of other gender, by default null in which case all details will be returned for the profile
   */
  public function getDetails($bSlavePrefrence = true, $iOtherProfileID = null)
  {
    $szDbName = $this->szProfileDBShard;
    if(false == $bSlavePrefrence) {
      $szDbName = $this->szProfileDBMaster;
    }
    
    $objStore = new NEWJS_CHAT_LOG($szDbName);
    $arrResult = $objStore->getChatLogForLegal($this->iProfileID);
    
    //GEt Profile Info
    $arrProfileIDs = array();
    if(is_array($arrResult) && count($arrResult)) {
      foreach($arrResult as $row) {
        if(false === in_array($row['SENDER'], $arrProfileIDs)) {
          $arrProfileIDs[] = $row['SENDER'];
        }
        if(false === in_array($row['RECEIVER'], $arrProfileIDs)) {
          $arrProfileIDs[] = $row['RECEIVER'];
        }
      }
      
      $obj = JPROFILE::getInstance('newjs_slave');
      $arrProfiles = $obj->getArray(array('PROFILEID'=> implode(",", $arrProfileIDs)),"","","PROFILEID,USERNAME");
      
      foreach($arrProfiles as $row) {
        $arrMap[$row['PROFILEID']] = $row['USERNAME'];
      }
      
      foreach($arrResult as $k => $row) {
        $arrResult[$k]['SENDER'] = $arrMap[$row['SENDER']];
        $arrResult[$k]['RECEIVER'] = $arrMap[$row['RECEIVER']];
      }
    } else {
      $arrResult = false;
    }
    
    return $arrResult;
  }
      
}
?>