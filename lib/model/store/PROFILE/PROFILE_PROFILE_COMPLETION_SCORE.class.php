<?php

/**
 * Description of PROFILE_PROFILE_COMPLETION_SCORE
 * Store Class for CRUD Operation on PROFILE.PROFILE_COMPLETION_SCORE
 * 
 * @author Kunal Verma
 * @created 31st march 2015
 */
class PROFILE_PROFILE_COMPLETION_SCORE extends TABLE {
    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    public function __construct($dbname = "") 
    {
        parent::__construct($dbname);
    }
    
    /*
     * getRecord()
     * Function to retrieve record from the store
     * @param $iProfileID : Integer value ( Profile id )
     * @access public
     * @return Record of provided profileid or FALSE if record does not exist
     * @throws jsException
     */
    public function getRecord($iProfileID)
    {
        if(!is_numeric(intval($iProfileID)) || !$iProfileID)
		{
			throw new jsException("","iProfileID is not numeric in getRecord OF PROFILE_PROFILE_COMPLETION_SCORE.class.php");
		}
        
        try{
            
            $sql = "SELECT SCORE FROM PROFILE.PROFILE_COMPLETION_SCORE WHERE PROFILEID = :PID";
            
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->bindValue(":PID",$iProfileID,PDO::PARAM_INT);
            $pdoStatement->execute();
            
            //If Record Exist
            if($pdoStatement->rowCount())
                return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
            
            return false;//False Means No Record Exist            
        } catch (Exception $ex) {
            throw new jsException($e);
        }
    }
    
    /*
     * replaceRecord()
     * Function to replace/update record 
     * @param $iProfileID : Integer value ( Profile id )
     * @param $iScore : integer value (Profile Completion Score)
     * @access public
     * @return Count of update records
     * @throws jsException
     */
    public function replaceRecord($iProfileID,$iScore)
    {
        if(!is_numeric(intval($iProfileID)) || !$iProfileID)
		{
			throw new jsException("","iProfileID is not numeric in replaceRecord OF PROFILE_PROFILE_COMPLETION_SCORE.class.php");
		}
        
		if(!is_numeric(intval($iScore)) || !$iScore)
		{
			throw new jsException("","iScore is not numeric in replaceRecord OF PROFILE_PROFILE_COMPLETION_SCORE.class.php");
		}
        try{
            
            $sql = "REPLACE INTO PROFILE.PROFILE_COMPLETION_SCORE(PROFILEID,SCORE) VALUES (:PID,:SCORE)";
            
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->bindValue(":PID",$iProfileID,PDO::PARAM_INT);
            $pdoStatement->bindValue(":SCORE",$iScore,PDO::PARAM_INT);
            $pdoStatement->execute();
            
            return $pdoStatement->rowCount();
        } catch (Exception $ex) {
            throw new jsException($e);
        }
    }
    
    /*
     * getUncomputedProfiles
     * Based on  Left Join between PROFILE_COMPLETION_SCORE and JPROFILE
     * And Last Login WithIn provided by arguement, if not provided then by default 10 days
     * @param $totalScript
     * @param $currentScript
     * @param $lastLoginWithIn
     * @param $$limitProfiles
     * @return Array of ProfileIds
     * @access public
     */
    public function getUncomputedProfiles($totalScript,$currentScript,$lastLoginWithIn='10 days',$limitProfiles=0)
    {
        if(!is_numeric(intval($totalScript)) || !$totalScript)
		{
			throw new jsException("","totalScript is not numeric in getUncomputedProfiles OF PROFILE_PROFILE_COMPLETION_SCORE.class.php");
		}
        
		if(!is_numeric(intval($currentScript)))
		{
			throw new jsException("","currentScript is not numeric in getUncomputedProfiles OF PROFILE_PROFILE_COMPLETION_SCORE.class.php");
		}
        
        $time = new DateTime();
        $time->sub(date_interval_create_from_date_string($lastLoginWithIn));
        
        try{
            $sql =  <<<SQL
            SELECT J.PROFILEID
            FROM  newjs.`JPROFILE` J
            LEFT JOIN PROFILE.`PROFILE_COMPLETION_SCORE` S
            ON J.PROFILEID=S.PROFILEID
            WHERE DATE(J.LAST_LOGIN_DT)  >=  :LAST_LOGIN_DT 
            AND J.activatedKey=1
            AND J.PROFILEID MOD :T_SCRIPT = :CUR_SCRIPT
            AND S.PROFILEID IS NULL
SQL;
            if($limitProfiles)
                $sql .= ' LIMIT '. $limitProfiles;
            
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->bindValue(":LAST_LOGIN_DT",$time->format('Y-m-d'),PDO::PARAM_STR);
            $pdoStatement->bindValue(":T_SCRIPT",$totalScript,PDO::PARAM_STR);
            $pdoStatement->bindValue(":CUR_SCRIPT",$currentScript,PDO::PARAM_STR);
            $pdoStatement->execute();
            
            return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            throw new jsException($e);
        }
    }
    /*
     * getVerifiedProfiles
     * Based on  Left Join between PROFILE_COMPLETION_SCORE and JPROFILE
     * 
     * @param $verifiedByArray : Array of day 1, day 7 , day 14, day 21, day30 Date and Score should be less than 60 
     * @param $limitProfiles : To Limit Profiles
     * @param $activated : Activated Flag , be default 'Y'
     * @return Array of ProfileIds
     * @access public
     */
    public function getVerifiedProfiles($verifiedByArray,$limitProfiles="",$activated="Y")
    {
      if(!$verifiedByArray || !is_array($verifiedByArray) ||!count($verifiedByArray) || count($verifiedByArray) < 5 )
      {
        throw new jsException("","verifiedBy is not null in getVerifiedProfiles OF PROFILE_PROFILE_COMPLETION_SCORE.class.php");
      }
     
      try{
          $sql =  <<<SQL
            
          SELECT J.PROFILEID,S.SCORE
          FROM  newjs.`JPROFILE` J
          LEFT JOIN PROFILE.`PROFILE_COMPLETION_SCORE` S
          ON J.PROFILEID=S.PROFILEID
          WHERE  
          ( DATE(J.VERIFY_ACTIVATED_DT) =  :VERIFIED_ACTIVATED_DT_DAY 
          OR  DATE(J.VERIFY_ACTIVATED_DT) =  :VERIFIED_ACTIVATED_DT_7_DAY 
          OR  DATE(J.VERIFY_ACTIVATED_DT) =  :VERIFIED_ACTIVATED_DT_14_DAY 
          OR  DATE(J.VERIFY_ACTIVATED_DT) =  :VERIFIED_ACTIVATED_DT_21_DAY 
          OR  DATE(J.VERIFY_ACTIVATED_DT) =  :VERIFIED_ACTIVATED_DT_30_DAY )   
          AND J.activatedKey = 1
          AND J.ACTIVATED = :ACTIVATED
          
SQL;
          
          if($verifiedByArray['SCORE']){
            $sql .= ' AND ( S.SCORE < :SCORE_THRESHOLD  || S.SCORE IS NULL )';
          }
          if($limitProfiles){
            $sql .= ' LIMIT :LIMIT_PRO ' ;         
          }
          
          $pdoStatement = $this->db->prepare($sql);
          $pdoStatement->bindValue(":VERIFIED_ACTIVATED_DT_DAY",$verifiedByArray['TIME_1'],PDO::PARAM_STR);
          $pdoStatement->bindValue(":VERIFIED_ACTIVATED_DT_7_DAY",$verifiedByArray['TIME_7'],PDO::PARAM_STR);
          $pdoStatement->bindValue(":VERIFIED_ACTIVATED_DT_14_DAY",$verifiedByArray['TIME_14'],PDO::PARAM_STR);
          $pdoStatement->bindValue(":VERIFIED_ACTIVATED_DT_21_DAY",$verifiedByArray['TIME_21'],PDO::PARAM_STR);
          $pdoStatement->bindValue(":VERIFIED_ACTIVATED_DT_30_DAY",$verifiedByArray['TIME_30'],PDO::PARAM_STR);
          $pdoStatement->bindValue(":ACTIVATED",$activated,PDO::PARAM_STR);
          
          if($verifiedByArray['SCORE']){
             $pdoStatement->bindValue(":SCORE_THRESHOLD",$verifiedByArray['SCORE'],PDO::PARAM_INT);
          }
          
          if($limitProfiles){
            $pdoStatement->bindValue(":LIMIT_PRO",$limitProfiles,PDO::PARAM_STR);
          }
          $pdoStatement->execute();

          return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
      } catch (Exception $ex) {
          throw new jsException($e);
      }
    }
    
     /*
     * createImproveScoreTable
     * Function to create improve score table, if exist then warnings come 
     * @return Null
     * @access public
     */
    public function createImproveScoreTable(){
      try{
        $sql =  <<<SQL
          CREATE TABLE IF NOT EXISTS PROFILE.`IMPROVE_PROFILE_SCORE`(
            `PROFILEID` int(11) unsigned PRIMARY KEY,
            `SCORE`  int(3) unsigned DEFAULT NULL,
            `STATUS` enum('S','N') DEFAULT 'N'
          )
SQL;
        $pdoStatement = $this->db->prepare($sql);
        $pdoStatement->execute();
      } catch (Exception $ex) {
        throw new jsException($e);
      }
    }
    
    /*
     * dropImproveScoreTable
     * Function to drop improve score table 
     * @return Null
     * @access public
     */
    public function dropImproveScoreTable(){
      try{
        $sql =  <<<SQL
          DROP TABLE IF EXISTS PROFILE.`IMPROVE_PROFILE_SCORE`
SQL;
        $pdoStatement = $this->db->prepare($sql);
        $pdoStatement->execute();
      } catch (Exception $ex) {
        throw new jsException($e);
      }
    }
    
    /*
     * getImproveScoreRecords
     * Based on  Left Join between PROFILE_COMPLETION_SCORE and JPROFILE
     * And VERIFIED_ACTIVATED_DT 
     * @return Array of ProfileIds
     * @access public
     */
    public function getImproveScoreRecords(){
      try{
        $sql =  <<<SQL
        SELECT * FROM PROFILE.`IMPROVE_PROFILE_SCORE` WHERE STATUS = 'N' ;
SQL;
        $pdoStatement = $this->db->prepare($sql);
        $pdoStatement->execute();
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
      } catch (Exception $ex) {
        throw new jsException($e);
      }
    }
    
    /*
     * isImproveScoreTableExist
     * @return Boolean
     * @access public
     */
    public function isImproveScoreTableExist(){
      try{
        $sql =  <<<SQL
        SELECT 1 FROM PROFILE.`IMPROVE_PROFILE_SCORE` LIMIT 1;
SQL;
        $pdoStatement = $this->db->prepare($sql);
        $pdoStatement->execute();
        return true;
      } catch (Exception $ex) {
        return false;
      }
    }
    
    /*
     * storeVerifiedProfiles
     * @param $arrProfiles : Array of profiles to insert in table
     * @return Boolean
     * @access public
     */
    public function storeVerifiedProfiles(&$arrProfiles){
      try{
        $sql = "INSERT IGNORE INTO PROFILE.`IMPROVE_PROFILE_SCORE` (`PROFILEID`,`SCORE`,`STATUS`) VALUES";
        foreach($arrProfiles as $key=>$record){
          $score = $record[SCORE] ? $record[SCORE] : 'NULL';
          $sql .= "( $record[PROFILEID] , $score , 'N' ) ,";
        }
        $sql = substr($sql, 0, strlen($sql)-1 );
        
        $pdoStatement = $this->db->prepare($sql);
        $pdoStatement->execute();
        
      } catch (Exception $ex) {
          throw new jsException($e);
      }
    }
    
    /*
     * setStatusImproveScoreTable
     * Set Status of records
     * @param $arrProfileIDs : Array of profileid
     * @param $status : Status 'S' means mailer Send and 'N' mean mailer not send 
     * @return Boolean
     * @access public
     */
    public function setStatusImproveScoreTable($arrProfileIDs,$status='S'){
      
      try{
        if(!count($arrProfileIDs))
          return;
        
        $szListProfileIds = implode($arrProfileIDs," , ");
        
        $sql = "UPDATE PROFILE.`IMPROVE_PROFILE_SCORE` SET `STATUS`='S' WHERE PROFILEID IN (:LIST_ID)";
        $pdoStatement = $this->db->prepare($sql);
        $pdoStatement->bindValue(":LIST_ID",$szListProfileIds,PDO::PARAM_STR);
        $pdoStatement->execute();
      } catch (Exception $ex) {
        throw new jsException($e);
      }
      
    }
    
    /*
     * this function will fetch those profiles for which pcs is to be updated
     */
    public function getPofilesToBeUpdated($dateBefore,$totalScript,$currentScript){
        if($dateBefore)
        {
            try{
                $sql = "SELECT P.PROFILEID FROM PROFILE.PROFILE_COMPLETION_SCORE P JOIN newjs.JPROFILE J ON J.PROFILEID = P.PROFILEID WHERE DATE(J.LAST_LOGIN_DT) > :LOGIN_DATE AND P.PROFILEID MOD :T_SCRIPT = :CUR_SCRIPT";
                $pdoStatement = $this->db->prepare($sql);
                $pdoStatement->bindValue(":LOGIN_DATE",$dateBefore,PDO::PARAM_STR);
                $pdoStatement->bindValue(":T_SCRIPT",$totalScript,PDO::PARAM_INT);
                $pdoStatement->bindValue(":CUR_SCRIPT",$currentScript,PDO::PARAM_INT);
                $pdoStatement->execute();
                return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
            }
            catch (Exception $ex) {
                    throw new jsException($e);
            }
        }
    }
}
?>
