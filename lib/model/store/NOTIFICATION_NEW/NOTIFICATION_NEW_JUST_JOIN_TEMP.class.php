<?php
class NOTIFICATION_NEW_JUST_JOIN_TEMP extends TABLE
{
    public function __construct($dbname = "")
    {
        $dbname ='notification_master';
        $this->databaseName ='NOTIFICATION_NEW';
        parent::__construct($dbname);
    }

    public function addProfile($profileid,$currentScript=0)
    {
        $istTime = date("Y-m-d", strtotime('+9 hour 30 minutes'));
        $sqlInsert = "INSERT IGNORE INTO  $this->databaseName.JUST_JOIN_TEMP(`PROFILEID`,`SCRIPT_NO`,`ENTRY_DT`) VALUES (:PROFILEID,:SCRIPT_NO,:ENTRY_DT)";
        $resInsert = $this->db->prepare($sqlInsert);
        $resInsert->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
        $resInsert->bindValue(":ENTRY_DT", $istTime, PDO::PARAM_STR); 
        $resInsert->bindValue(":SCRIPT_NO", $currentScript, PDO::PARAM_INT);
        $resInsert->execute();
    }

    public function getProfiles($currentScript=0)
    {
        $sql = "SELECT PROFILEID from $this->databaseName.JUST_JOIN_TEMP WHERE SCRIPT_NO=:SCRIPT_NO";
        $res = $this->db->prepare($sql);
	$res->bindValue(":SCRIPT_NO", $currentScript, PDO::PARAM_INT);
        $res->execute();
        while($result = $res->fetch(PDO::FETCH_ASSOC)){
  		$profilesArr[] = $result['PROFILEID'];
        }
        return $profilesArr;
    }

    public function truncate()
    {
        $sql = "truncate table $this->databaseName.JUST_JOIN_TEMP";
        $res = $this->db->prepare($sql);
        $res->execute();
    }



}
