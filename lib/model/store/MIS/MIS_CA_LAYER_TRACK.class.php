<?php
/* this class is used to track clicks on buttons on the critical action layer*/
class MIS_CA_LAYER_TRACK extends TABLE
{
  public function __construct($dbName="") {
    parent::__construct($dbName="");
  }
  /*this function is used to select record in table for today's entry
   * @param profile id,layer which has been opened,button which is clicked
   */
  public function select($profileid) {
    try {
      $sql="SELECT COUNT(*) AS CNT FROM MIS.CA_LAYER_TRACK WHERE PROFILEID=:ID AND ENTRY_DT > DATE_SUB(NOW(), INTERVAL 1 DAY)";
      $res=$this->db->prepare($sql);
      $res->bindValue(":ID",$profileid,PDO::PARAM_INT);
      $res->execute();
      $result = $res->fetch(PDO::FETCH_ASSOC);
      return $result['CNT'];
    } 
    catch(PDOException $e){
		jsException::nonCriticalError("lib/model/store/MIS/MIS_CA_LAYER_TRACK.class.php(3)-->.$sql".$e);
                        return '';
      //throw new jsException($e);
    }
  }
  /*this function is used to insert record in table when a user clicks
   * on any of the two buttons on each partcular day based on type of the layer
   * @param profile id,layer which has been opened,button which is clicked
   */
  public function insert($profileid,$layerType,$button)
  {
    try {
      $date= date("Y-m-d H:i:s");
      $sql1="INSERT IGNORE INTO MIS.CA_LAYER_TRACK(PROFILEID,LAYERID,ENTRY_DT,BUTTON) VALUES (:ID,:LAYER,:DATE,:BUTTON)";
      $res1=$this->db->prepare($sql1);
      $res1->bindValue(":ID",$profileid,PDO::PARAM_INT);
      $res1->bindValue(":LAYER",$layerType,PDO::PARAM_STR);
      $res1->bindValue(":BUTTON",$button,PDO::PARAM_STR);
      $res1->bindValue(":DATE",$date,PDO::PARAM_STR);
      $res1->execute();
      return true;
    } 
    catch(PDOException $e){
		jsException::nonCriticalError("lib/model/store/MIS/MIS_CA_LAYER_TRACK.class.php(3)-->.$sql".$e);
                        return '';
     // throw new jsException($e);
    }
  }
  /*this function is used to insert record in table when a user clicks
   * on any of the two buttons on each partcular day based on type of the layer
   * @param profile id,layer which has been opened,button which is clicked
   */
  public function update($profileid,$layerType,$button)
  {
    try {
      $date= date("Y-m-d H:i:s");
      $sql1="UPDATE MIS.CA_LAYER_TRACK SET BUTTON=:BUTTON WHERE PROFILEID=:ID AND LAYERID=:LAYER ORDER BY SNO DESC LIMIT 1";
      $res1=$this->db->prepare($sql1);
      $res1->bindValue(":ID",$profileid,PDO::PARAM_INT);
      $res1->bindValue(":LAYER",$layerType,PDO::PARAM_STR);
      $res1->bindValue(":BUTTON",$button,PDO::PARAM_STR);
      //$res1->bindValue(":DATE",$date,PDO::PARAM_STR);
      $res1->execute();
    } 
    catch(PDOException $e){
		jsException::nonCriticalError("lib/model/store/MIS/MIS_CA_LAYER_TRACK.class.php(3)-->.$sql".$e);
                        return '';
      //throw new jsException($e);
    }
  }
  /*this function will return the count for no. of times a layer has been shown 
   * in day, how many days
   *@param- profile id,layer id for counting, count for today or total count
   *@return- count integer
   */
  public function getCountLayerDisplay($profileId)
  {
    try {   
      $sql= "SELECT LAYERID, COUNT(LAYERID) AS COUNT, MAX(ENTRY_DT) AS MAX_ENTRY_DT FROM MIS.CA_LAYER_TRACK WHERE PROFILEID = :PROFILE_ID GROUP BY LAYERID ORDER BY ENTRY_DT DESC";
      $prep=$this->db->prepare($sql);
      $prep->bindValue(":PROFILE_ID",$profileId,PDO::PARAM_INT);
      $prep->execute();
       $K=0;
      while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
        $records[$result['LAYERID']]["MAX_ENTRY_DT"] = $result['MAX_ENTRY_DT'];
        $records[$result['LAYERID']]["COUNT"] = $result['COUNT'];
      }
      return $records;
    }
    catch(PDOException $e)
    {
		jsException::nonCriticalError("lib/model/store/MIS/MIS_CA_LAYER_TRACK.class.php(3)-->.$sql".$e);
                        return '';
      //throw new jsException($e);
    }
  }


    public function truncateForUserAndLayer($profileId='',$layerid,$beforeDate)
  {
    try { 
      $sqlDate=$beforeDate ? "AND DATE(`ENTRY_DT`) < :BEFORE_DATE" : "";
      $profileSql=$profileId ? "PROFILEID = :PROFILE_ID AND" : "";
      $sql= "DELETE FROM MIS.CA_LAYER_TRACK WHERE $profileSql LAYERID = :LAYER_ID $sqlDate";
      $prep=$this->db->prepare($sql);
      if($profileId)
      $prep->bindValue(":PROFILE_ID",$profileId,PDO::PARAM_INT);
      $prep->bindValue(":LAYER_ID",$layerid,PDO::PARAM_INT);
      if($beforeDate)
        $prep->bindValue(":BEFORE_DATE",$beforeDate,PDO::PARAM_STR);
      $prep->execute();
      }
    
    catch(PDOException $e)
    {
        mail("palash.chordia@jeevansathi.com,ayush.sethi@jeevansathi.com,nitesh.s@jeevansathi.com","CA Layer :","error in function truncateForUserAndLayer of MIS_CA_LAYER_TRACK.class.php");
    jsException::nonCriticalError("lib/model/store/MIS/MIS_CA_LAYER_TRACK.class.php(3)-->.$sql".$e);
                        return '';
      //throw new jsException($e);
    }
  }
}

