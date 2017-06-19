<?php
/*
This class is used to insert data in AUTOMATED_CONTACTS_TRACKING table in Assisted_Product database
*/
class ASSISTED_PRODUCT_AUTOMATED_CONTACTS_TRACKING extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	This function inserts the full data corresponding to a profileid and matchid
	@param 1) profileId 2) matchId
	@return 
	*/
	public function insertIntoAutoContactsTracking($profileId,$matchId)
	{
		if($profileId && $matchId)
		{
			try
			{
				$sql = "INSERT IGNORE INTO Assisted_Product.AUTOMATED_CONTACTS_TRACKING(SENDER,RECEIVER,DATE,LOGIC) VALUES(:PROFILEID,:MATCHID,NOW(),'2')";
				$res = $this->db->prepare($sql);
				$res->bindValue(":MATCHID", $matchId, PDO::PARAM_INT);
				$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
				$res->execute();
				
			}
			catch(PDOException $e)
			{
				throw new jsException($e);
			}
		}
		else
		{
			throw new jsException("","PROFILEID OR MATCHID IS BLANK IN insertIntoAutoContactsTracking() OF ASSISTED_PRODUCT_AUTOMATED_CONTACTS_TRACKING.class.php");
		}
		
	}
  /*
   * this function selects those records from the table which have date greater than variable afterDate
   * @param- $afterDate-date after which records have to be fetched, $totalScript- modulus divisor,$currentScript- modulus remainder
   * @return- array of profileid and interest count
   */
  public function selectByDate($afterDate,$endDate,$totalScript,$currentScript) {
    if ($afterDate && $endDate) {
      try {
        $sql = "SELECT A.SENDER,COUNT(RECEIVER) AS CNT FROM Assisted_Product.AUTOMATED_CONTACTS_TRACKING AS A LEFT JOIN Assisted_Product.AUTOMATED_CONTACTS_LOG AS T ON A.SENDER=T.SENDER_ID WHERE DATE >= :AFTER_DATE AND DATE < :END_DATE AND A.SENDER%:totalScript=:currentScript AND T.SENDER_ID IS NULL GROUP BY A.SENDER";
        $prep = $this->db->prepare($sql);
        $prep->bindValue(":AFTER_DATE",$afterDate, PDO::PARAM_STR);
        $prep->bindValue(":END_DATE",$endDate, PDO::PARAM_STR);
        $prep->bindValue(":totalScript", $totalScript, PDO::PARAM_INT);
        $prep->bindValue(":currentScript", $currentScript, PDO::PARAM_INT);
        $prep->execute();
        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
	  $userProfile[$row['SENDER']] =$row['CNT'];
        }
      } 
      catch (PDOException $e) {
        throw new jsException($e);
      } 
      return $userProfile;
    }
  }  
  /*this functions deletes records which have date before a passed date
   * @param- $beforeDate- date before which records have to be deleted
   */
  public function DeleteRecordsBeforeDate($beforeDate) {
    if ($beforeDate) {
      try {
        $sql = "Delete from Assisted_Product.AUTOMATED_CONTACTS_TRACKING where DATE < :BEFORE_DATE";
        $prep = $this->db->prepare($sql);
        $prep->bindValue(":BEFORE_DATE",$beforeDate, PDO::PARAM_STR);
        $prep->execute();
      } 
      catch (PDOException $e) {
        throw new jsException($e);
      } 
    }
  }  
  /*this functions selects records which have date after a passed date
   * @param- $afterDate- date after which records have to be fetched
   */
  public function getCountAfterDate($afterDate) {
    if ($afterDate) {
      try {
        $sql = "SELECT COUNT(*) AS CNT from Assisted_Product.AUTOMATED_CONTACTS_TRACKING where DATE > :AFTER_DATE";
        $prep = $this->db->prepare($sql);
        $prep->bindValue(":AFTER_DATE",$afterDate, PDO::PARAM_STR);
        $prep->execute();
        $row = $prep->fetch(PDO::FETCH_ASSOC);
        return $row[CNT];
      } 
      catch (PDOException $e) {
        throw new jsException($e);
      } 
    }
  }  
}
?>
