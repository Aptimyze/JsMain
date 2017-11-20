<?php
class GEOIP_SCRAPPERIPS extends TABLE 
{
		/**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */
        public function __construct($dbname = "") {
                parent::__construct($dbname);
        }

        public function insertTrackingData($ip,$profileId,$email,$date)
        {
        	try
            {
                $sql = "INSERT INTO  GeoIP.ScraperIPs(IP,PROFILEID,EMAIL) VALUES (:IP, :PROFILEID,  :EMAIL)";
                $pdoStatement = $this->db->prepare($sql);
                
                $pdoStatement->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
                $pdoStatement->bindValue(":EMAIL",$email,PDO::PARAM_STR);
                $pdoStatement->bindValue(":IP",$ip,PDO::PARAM_STR);
                $res = $pdoStatement->execute();
                return $res;
            }
            catch (Exception $ex) 
            {
               jsException::nonCriticalError($e);
            }
        }
	
}
