<?php

/**
 * 
 */
class NEWJS_NEW_DELETED_PROFILE_LOG extends TABLE
{

  /**
   * @fn __construct
   * @brief Constructor function
   * @param $dbName - Database to which the connection would be made
   */
  public function __construct($dbname = "")
  {
    parent::__construct($dbname);
  }
  
  public function getProfileEligibleForHouskeeping($deleteBefore = "3 months",$deleteAfter = "1 days")
  {
    try{
      $time = new DateTime();
      $time->sub(date_interval_create_from_date_string($deleteBefore));
      $ltDate = $time->format('Y-m-d H:i:s');

      $time->sub(date_interval_create_from_date_string($deleteAfter));
      $gtDate = $time->format('Y-m-d H:i:s');
      
      //TODO : Add Houskeeping 
      $sql = "SELECT DISTINCT PROFILEID FROM newjs.NEW_DELETED_PROFILE_LOG WHERE DATE < :LT_DATE AND DATE >= :GT_DATE " ;
      $pdoStatement = $this->db->prepare($sql);

      $pdoStatement->bindValue(":LT_DATE", $ltDate, PDO::PARAM_STR);
      $pdoStatement->bindValue(":GT_DATE", $gtDate, PDO::PARAM_STR);

      $pdoStatement->execute();

      return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }catch(Exception $ex) {
      $today = date('Y-m-d H:i:s');
      throw new jsException($ex, "Failed on $today");
    }
   
  }

}
?>