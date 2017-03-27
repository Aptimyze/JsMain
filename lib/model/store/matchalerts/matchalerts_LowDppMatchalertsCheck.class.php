<?php

class matchalerts_LowDppMatchalertsCheck extends TABLE
{
	public function __construct($dbname="")
	{
			$dbname = $dbname?$dbname:"matchalerts_slave";
			parent::__construct($dbname);
	}
        
        /*this function returns total number of rows and the most recent date
         * @return - partition number
         */
        public function getProfilesWithInformLimitReached($date,$totalScripts,$currentScript,$informTimes){
            try{
                $sql="SELECT PROFILEID,COUNT(*) AS NUMBER FROM matchalerts.LOW_DPP_MATCHALERTS_CHECK WHERE PROFILEID%:TOTAL_SCRIPT=:SCRIPT AND DATE>=:DATE GROUP BY PROFILEID HAVING NUMBER>=:INFORM_TIMES";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":DATE", $date, PDO::PARAM_STR);
                $prep->bindValue(":INFORM_TIMES", $informTimes, PDO::PARAM_STR);
                $prep->bindValue(":TOTAL_SCRIPT",$totalScripts,PDO::PARAM_INT);
                $prep->bindValue(":SCRIPT",$currentScript,PDO::PARAM_INT);
                $prep->execute();
                while($res = $prep->fetch(PDO::FETCH_ASSOC))
                    $result[]=$res['PROFILEID'];
                return $result;
            }
            catch (PDOException $ex) {
               jsException::nonCriticalError($ex);
            }
        }
        
         /*this inserts a row with number of matches
         * @return - partition number
         */
        public function insertForProfile($profileid,$date){
            try{
                $sql="INSERT INTO matchalerts.LOW_DPP_MATCHALERTS_CHECK(PROFILEID,DATE) VALUES (:PROFILEID,:DATE)";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":DATE", $date, PDO::PARAM_STR);
                $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $prep->execute();
            }
            catch (PDOException $ex) {
                jsException::nonCriticalError($ex);
            }
        }
        
        /*this deletes all rows before a given date
         * @return - partition number
         */
        public function deleteBeforeDate($date){
            try{
                $sql="DELETE FROM matchalerts.LOW_DPP_MATCHALERTS_CHECK WHERE DATE < :DATE";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":DATE", $date, PDO::PARAM_STR);
                $prep->execute();
            }
            catch (PDOException $ex) {
                jsException::nonCriticalError($ex);
            }
        }
        
        /*returns that profileids which have zero results
         * @return -  number of matches
         */
        public function getProfilesWithZeroMatches($totalScript,$currentScript,$date){
            try{
                $sql="SELECT DISTINCT(PROFILEID) FROM matchalerts.LOW_DPP_MATCHALERTS_CHECK WHERE PROFILEID%:TOTAL_SCRIPT=:CURRENT_SCRIPT AND DATE>:DATE AND SENT='N' limit 5000";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":TOTAL_SCRIPT", $totalScript, PDO::PARAM_INT);
                $prep->bindValue(":CURRENT_SCRIPT", $currentScript, PDO::PARAM_INT);
                $prep->bindValue(":DATE", $date, PDO::PARAM_STR);
                $prep->execute();
                while($res = $prep->fetch(PDO::FETCH_ASSOC))
                    $result[]=$res['PROFILEID'];
                return $result;
            }
            catch (PDOException $ex) {
                jsException::nonCriticalError($ex);
            }
        }
        
        /*updates sent status
         * @return -  number of matches
         */
        public function updateSent($profileid,$sent){
            try{
                $sql="UPDATE matchalerts.LOW_DPP_MATCHALERTS_CHECK SET SENT=:STATUS WHERE PROFILEID=:PROFILEID";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $prep->bindValue(":STATUS", $sent, PDO::PARAM_STR);
                $prep->execute();
            }
            catch (PDOException $ex) {
                jsException::nonCriticalError($ex);
            }
        }
        
}