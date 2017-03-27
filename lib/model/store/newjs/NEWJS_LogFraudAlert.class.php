<?php
class NEWJS_LogFraudAlert extends TABLE
{
        public function __construct($dbname="")
        {
		$dbname=$dbname?$dbname:"211_connect";
		parent::__construct($dbname);
        }

	/**
	  * 
	**/

        public function ProfileNewlyRegister ($Interval)
        {
                try
                {

                	$sql = "SELECT J.PROFILEID, J.EMAIL from newjs.JPROFILE AS J LEFT JOIN newjs.LogFraudAlert AS F ON J.PROFILEID=F.PROFILEID WHERE J.ENTRY_DT > DATE_SUB(CURDATE(), INTERVAL :INTERVAL DAY) AND F.PROFILEID IS NULL";
                	
                	$prep = $this->db->prepare($sql);
                	$prep->bindValue(":INTERVAL", $Interval, PDO::PARAM_INT);
                	$prep->execute();
                	while($row = $prep->fetch(PDO::FETCH_ASSOC))
                	{
						$profilemail[$row['PROFILEID']] =$row['EMAIL'];	                	
					}
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return $profilemail;

        }

        public function ProfilesActivated ($totalScript,$currentScript,$OneTimeInterval)
        {
                try
                {

                	$sql = "SELECT J.PROFILEID,J.EMAIL from newjs.JPROFILE AS J LEFT JOIN newjs.LogFraudAlert AS F ON J.PROFILEID=F.PROFILEID WHERE DATE(J.LAST_LOGIN_DT) > DATE_SUB(CURDATE(), INTERVAL :OneTimeInterval DAY) AND J.`PROFILEID`%:totalScript=:currentScript AND J.activatedKey ='1' AND J.ACTIVATED <> 'D' AND F.PROFILEID IS NULL";
                	$prep = $this->db->prepare($sql);
                	$prep->bindValue(":OneTimeInterval", $OneTimeInterval, PDO::PARAM_INT);
                	$prep->bindValue(":currentScript", $currentScript, PDO::PARAM_INT);
                	$prep->bindValue(":totalScript", $totalScript, PDO::PARAM_INT);                	
                	$prep->execute();
                	while($row = $prep->fetch(PDO::FETCH_ASSOC))
						$profilemail[$row['PROFILEID']] =$row['EMAIL'];
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return $profilemail;
        }

        public function InsertStatusAlert ($profileIdMailSent)
        {
                try
                {
						$sql = "INSERT INTO `LogFraudAlert` (PROFILEID,STATUS) VALUES (:PROFILEID,'Y')";
						$res = $this->db->prepare($sql);
			            $res->bindValue(":PROFILEID", $profileIdMailSent, PDO::PARAM_INT);
                		$res->execute();    
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

}
?>
