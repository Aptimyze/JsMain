<?php
/**
 * MIS.RESPONSETRACKING
 * 
 * This class handles all database queries to MIS.RESPONSETRACKING
 * 
 * @package    jeevansathi
 * @author     ESHA JAIN
 * @created    04-09-2013
 */

class MIS_RESPONSETRACKING extends TABLE {
        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database name to which the connection would be made, will be one of the shards in this case
         */

  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }
        /**
         * @fn insert
         * @brief insert data in RESPONSETRACKING
         * @param $contactId : Unquie id for join with CONTACTS table
         * @param $profileid : logged in profileid kept to identify the shard for insertion
         * @param $contactType : ACCEPT/DECLINE
         * @param $trackingString : String to identify the sequence a user followed so as to make the contact
         */

  public function insert($contactId,$profileid,$contactType, $trackingString) {
	if(!$contactId)
		throw new jsException("","contactId not found in NEWJS_RESPONSETRACKING");	
	if(!$profileid)
		throw new jsException("","profileid not found in NEWJS_RESPONSETRACKING");	
	if(!$contactType)
		throw new jsException("","contactType not found in NEWJS_RESPONSETRACKING");	
	if(!isset($trackingString))
		throw new jsException("","trackingString not found in NEWJS_RESPONSETRACKING");	
    try {
      $sql = "INSERT IGNORE INTO MIS.RESPONSETRACKING(CONTACTID, PROFILEID, CONTACT_TYPE, DATE, TRACKING_STRING) VALUES(:CONTACTID, :PROFILEID, :CONTACT_TYPE, :DATE, :TRACKING_STRING)";
      $prep = $this->db->prepare($sql);
      $prep->bindValue(":CONTACTID", $contactId, PDO::PARAM_INT);
      $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
      $prep->bindValue(":CONTACT_TYPE", $contactType, PDO::PARAM_STR);
      $prep->bindValue(":DATE", date("Y-m-d"), PDO::PARAM_STR);
      $prep->bindValue(":TRACKING_STRING", $trackingString, PDO::PARAM_STR);
      $prep->execute();
    }
    catch (PDOException $e) {
      throw new jsException($e);
    }
  
  }
        /**
         * @fn getSummarizedContactTrackingData
         * @brief get data from RESPONSETRACKING
         * @param $date : date for which data is to be fetched
         * @return : returned summarized data of responses for a particular day
         */
  public function getSummarizedContactTrackingData($date)
  {
	if(!$date)
		$date = date('Y-m-d',mktime(0,0,0,date('m'),date("d")-1,date("Y")));
	try
	{
		$sql = "SELECT COUNT(*) AS COUNT,TRACKING_STRING,CONTACT_TYPE FROM MIS.RESPONSETRACKING WHERE DATE=:DATE GROUP BY TRACKING_STRING,CONTACT_TYPE";
		$res = $this->db->prepare($sql);
		$res->bindValue(":DATE",$date, PDO::PARAM_STR);
		$res->execute();
		while($row=$res->fetch(PDO::FETCH_ASSOC))
			$return[$row['TRACKING_STRING']][$row['CONTACT_TYPE']] = $row['COUNT'];
		return $return;

	}
	catch (PDOException $e) {
	      throw new jsException($e);
	}
  }
}
