<?php
/**
 * MIS.SUMMARY_RESPONSE_TRACKING
 * 
 * This class handles all database queries to MIS.SUMMARY_RESPONSE_TRACKING
 * 
 * @package    jeevansathi
 * @author     ESHA JAIN
 * @created    04-09-2013
 */

class MIS_SUMMARY_RESPONSE_TRACKING extends TABLE {

  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }
        /**
         * @fn insert
         * @brief insert summarized data of responses single entry at a time
         * @param $contactType : ACCEPT/DECLINE
         * @param $trackingString : String to identify the sequence a user followed so as to make the contact
         * @param $count : count
         */

  public function insert($contactType,$trackingString,$count=0) {
	if(!$contactType)
		throw new jsException("","contactType not found in MIS_CONTACT_TRACKING");	
	if(!$trackingString)
		throw new jsException("","trackingString not found in MIS_CONTACT_TRACKING");	
    try {
      $sql = "INSERT IGNORE INTO MIS.SUMMARY_RESPONSE_TRACKING(CONTACT_TYPE, TRACKING_STRING, COUNT, DATE) VALUES(:CONTACT_TYPE, :TRACKING_STRING, :COUNT, :DATE)";
      $prep = $this->db->prepare($sql);
      $prep->bindValue(":CONTACT_TYPE", $contactType, PDO::PARAM_STR);
      $prep->bindValue(":TRACKING_STRING", $trackingString, PDO::PARAM_STR);
      $prep->bindValue(":COUNT", $count, PDO::PARAM_INT);
      $prep->bindValue(":DATE", date("Y-m-d"), PDO::PARAM_STR);
      $prep->execute();
    }
    catch (PDOException $e) {
      throw new jsException($e);
    }
  
  }
        /**
         * @fn storeSummarizedTrackingData
         * @brief insert summarized data of responses from uses via diffrent sources collected all in a single query
         */
  public function storeSummarizedContactTrackingData($trackingData,$date)
  {
	if(!is_array($trackingData))
                throw new jsException("","trackingData not found in MIS_CONTACT_TRACKING");  
	$sql='';
	$sql = "INSERT IGNORE INTO MIS.SUMMARY_RESPONSE_TRACKING(CONTACT_TYPE, TRACKING_STRING, COUNT, DATE) VALUES ";
	$countArrays = count($trackingData);
	$sqlStr = '';
	for($i=0;$i<$countArrays;$i++)
	{	if($i!=0)
			$sqlStr.=" , ";
		$sqlStr.= " (:CONTACT_TYPE".$i.", :TRACKING_STRING".$i.", :COUNT".$i.", :DATE) ";
	}
	$sql.=$sqlStr;
	$prep = $this->db->prepare($sql);
	$i = 0;
	foreach($trackingData as $k=>$v)
	{
		$prep->bindValue(":CONTACT_TYPE".$i, $v['CONTACT_TYPE'], PDO::PARAM_STR);
		$prep->bindValue(":TRACKING_STRING".$i, $v['TRACKING_STRING'], PDO::PARAM_STR);
		$prep->bindValue(":COUNT".$i, $v['COUNT'], PDO::PARAM_INT);
		$i++;
	}
      $prep->bindValue(":DATE", $date, PDO::PARAM_STR);
      $prep->execute();

  }
        /**
         * @fn getData
         * @brief gets data for mis
         * @param $date : date for which data is to be fetched
         * @return : returned summarized data of responses for a particular day
         */

  public function getData($date1,$date2)
  {
	try
	{
		if(!$date1)
			throw new jsException("","date not found in MIS_CONTACT_TRACKING");
		if(!$date2)
			throw new jsException("","date not found in MIS_CONTACT_TRACKING");
		$sql = "SELECT * FROM MIS.SUMMARY_RESPONSE_TRACKING WHERE DATE BETWEEN :DATE1 AND :DATE2";
		$prep = $this->db->prepare($sql);
		$prep->bindValue(":DATE1", $date1, PDO::PARAM_STR);
		$prep->bindValue(":DATE2", $date2, PDO::PARAM_STR);
		$prep->execute();
		while($row=$prep->fetch(PDO::FETCH_ASSOC))
			$return[] = $row;
		return $return;
	}
        catch (PDOException $e) {
              throw new jsException($e);
        }
  }

		/**
         * @fn deleteSummarizedContactTrackingData
         * @brief delete data from mis to ensure entries of duplicate data in case of rerun of cron 
         * @param $date : date for which data is to be deleted and entered afterwards
		 * @return : returned void
         */
public function deleteSummarizedContactTrackingData($date)
	{
		try
		{
			if(!$date)
				throw new jsException("","date not found in MIS_CONTACT_TRACKING delete function");
			$sql = "DELETE FROM MIS.SUMMARY_RESPONSE_TRACKING WHERE DATE=:DATE";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":DATE", $date, PDO::PARAM_STR);
			$prep->execute();
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}


}
