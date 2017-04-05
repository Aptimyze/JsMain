<?php

/**
 * DeleteRetrieveHouseKeepingTask
 * Housekeeping Task for moving data from three months delete table to corresponding delete tables
 * @author Kunal Verma <kunal.verma@jeevansathi.com>
 * @date 5th March 2017
 */
class DeleteRetrieveHouseKeepingTask extends sfBaseTask
{
  /*
   * Debug Info
   * @access private
   * @var Boolean
   */

  private $bDebugInfo = true;

  /**
   *
   * @var array
   */
  private $arrTables = array(
    //Tables in Main DB
    "main" => array(
      array("db"=>"newjs", "table"=>"DELETED_BOOKMARKS", "suffix"=>"_ELIGIBLE_FOR_RET", "where"=>array("BOOKMARKER", "BOOKMARKEE")),
      array("db"=>"newjs", "table"=>"DELETED_IGNORE_PROFILE", "suffix"=>"_ELIGIBLE_FOR_RET", "where"=>array("PROFILEID", "IGNORED_PROFILEID")),
      array("db"=>"jsadmin", "table"=>"DELETED_OFFLINE_MATCHES", "suffix"=>"_ELIGIBLE_FOR_RET", "where"=>array("PROFILEID", "MATCH_ID")),
      array("db"=>"jsadmin", "table"=>"DELETED_OFFLINE_NUDGE_LOG", "suffix"=>"_ELIGIBLE_FOR_RET", "where"=>array("SENDER", "RECEIVER")),
      array("db"=>"jsadmin", "table"=>"DELETED_VIEW_CONTACTS_LOG", "suffix"=>"_ELIGIBLE_FOR_RET", "where"=>array("VIEWER", "VIEWED")),
    ),
    //Tables in Shards DB 
    "shard" => array(
      array("db"=>"newjs", "table"=>"DELETED_HOROSCOPE_REQUEST", "suffix"=>"_ELIGIBLE_FOR_RET", "where"=>array("PROFILEID","PROFILEID_REQUEST_BY")),
      array("db"=>"newjs", "table"=>"DELETED_PHOTO_REQUEST", "suffix"=>"_ELIGIBLE_FOR_RET", "where"=>array("PROFILEID","PROFILEID_REQ_BY")),
      array("db"=>"newjs", "table"=>"DELETED_MESSAGE_LOG", "suffix"=>"_ELIGIBLE_FOR_RET", "where"=>array("SENDER","RECEIVER")),
      array("db"=>"newjs", "table"=>"DELETED_MESSAGES", "suffix"=>"_ELIGIBLE_FOR_RET", "where"=>array("SENDER","RECEIVER")),
      array("db"=>"newjs", "table"=>"DELETED_EOI_VIEWED_LOG", "suffix"=>"_ELIGIBLE_FOR_RET", "where"=>array("VIEWER","VIEWED")),
      array("db"=>"newjs", "table"=>"DELETED_PROFILE_CONTACTS", "suffix"=>"_ELIGIBLE_FOR_RET", "where"=>array("SENDER","RECEIVER")),
    ),   
  );
  
  /**
   *
   * @var array
   */
  private $arrProfiles = null;
  
  /*
   * Configure function 
   */
  protected function configure()
  {
    //Command line arguements       
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
    ));
    
    $this->namespace = 'DeleteRetrieve';
    $this->name = 'HousekeepingThreeMonth';
    $this->briefDescription = 'Cron job for ffor moving data from three months delete table to corresponding delete tables.';
    $this->detailedDescription = <<<EOF
        The [DeleteRetrieve:Housekeeping|INFO] task does things.
            
        Call it with:

        [php symfony DeleteRetrieve:HousekeepingThreeMonth]
EOF;
  }

  /**
   * 
   * @param type $arguments
   * @param type $options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if(!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
       
    //Get Profiles
    $this->getProfiles();
    
    //Run HouseKeeping on Profiles
    $this->houseKeeper();
  }
  
  /**
   * 
   */
  private function getProfiles()
  {
    $this->logSection("Info : Getting profiles","...");
    
    $deleteLogObj = new NEWJS_NEW_DELETED_PROFILE_LOG("newjs_slave");
    $arrProfiles = $deleteLogObj->getProfileEligibleForHouskeeping("4 months", "1 months");
    
    $this->arrProfiles = array();
    
    if(is_array($arrProfiles) && count($arrProfiles)) {
      foreach($arrProfiles as $profile) {
        $this->arrProfiles[] = $profile['PROFILEID'];
      }
    }
    
    if($this->bDebugInfo)
    {
      $this->logSection("Info : Number of profiles ", count($this->arrProfiles));
    }
  }
  
  private function houseKeeper()
  {
    if( 0 === count($this->arrProfiles)) {
      return ;
    }
    
    foreach($this->arrProfiles as $iProfileID) {
      $this->moveData($iProfileID);die(X);
    }
  }
  
  /**
   * 
   * @param type $iProfileID
   */
  private function moveData($iProfileID)
  {
    $arrStoreObj = array();
    $arrStoreObj["main"] = new StoreTable("newjs_master");
    $arrStoreObj["shard1"] = new StoreTable("shard1_master");
    $arrStoreObj["shard2"] = new StoreTable("shard2_master");
    $arrStoreObj["shard3"] = new StoreTable("shard3_master");
    
    try {
      foreach($this->arrTables as $key => $tableArray) {
        if($key == "main") {
          $dbObj = $arrStoreObj["main"];
          //Start Transcation
          $dbObj->getDBObject()->beginTransaction();
          //$this->moveLogic($tableArray, $dbObj, $iProfileID);
        } else {
          $arrShard = array("shard1", "shard2", "shard3");
          foreach($arrShard as $shardConnection) {
            $dbObj = $arrStoreObj[$shardConnection];
            
            //Start Transcation
            $dbObj->getDBObject()->beginTransaction();
            //$this->moveLogic($tableArray, $dbObj, $iProfileID);
          }
        }
      }
      //Commit
      foreach($arrStoreObj as $conn) {
        $conn->getDBObject()->commit();
      }
      
    } catch (Exception $ex) {
      //Rollback
      foreach($arrStoreObj as $conn) {
        $conn->getDBObject()->rollback();
      }
    }
  }
  
  private function moveLogic($tableArray, $dbObj, $iProfileID)
  {
     foreach($tableArray as $tableConfig) {
        $dataBaseObj = $dbObj->getDBObject();
        
        $dbName = $tableConfig["db"];
        $toTableName = $tableConfig["table"];
        $fromTableName = $tableConfig["table"] + $tableConfig["suffix"];
        $arrWhere = $tableConfig["where"];
        
        foreach($arrWhere as $where) {
          try{
            //Insert 
            $sql = "INSERT IGNORE INTO $dbName.$toTableName SELECT * FROM $dbName.$fromTableName WHERE $where = :PID";
            $pdoStatement = $dbObject->prepare($sql);
            $pdoStatement->bindValue(":PID", $iProfileID,PDO::PARAM_INT);
            $pdoStatement->execute();
            
            //Delete
            $sql = "DELETE FROM $dbName.$fromTableName WHERE $where = :PID";
            $pdoStatement = $dbObject->prepare($sql);
            $pdoStatement->bindValue(":PID", $iProfileID,PDO::PARAM_INT);
            $pdoStatement->execute();
            
            
          } catch (Exception $ex) {
            throw $ex;
          }
        }
      }
  }
}