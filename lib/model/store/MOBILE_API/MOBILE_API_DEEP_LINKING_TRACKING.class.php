<?php

class MOBILE_API_DEEP_LINKING_TRACKING extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

        //This function inserts the viewer and viewed profileId along with the date and fetches the auto increment tracking id and returns it
        
        public function setTrackingData($profileId,$loggedInProfileId,$flag="N")
        {
                $date= date("Y-m-d H:i:s");
        	try
        	{
        		$sql="INSERT INTO MOBILE_API.DEEP_LINKING_TRACKING(PROFILEID,LOGGEDIN_PID,DATETIME,FLAG) VALUES(:PROFILEID,:LOGGEDIN_PID,:DATETIME,:FLAG)";
        		$res=$this->db->prepare($sql);
        		$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $res->bindValue(":LOGGEDIN_PID", $loggedInProfileId, PDO::PARAM_INT);
                        $res->bindValue(":DATETIME", $date, PDO::PARAM_STR);
                        $res->bindValue(":FLAG", $flag, PDO::PARAM_STR);
        		$res->execute();
                        $trackId = $this->db->lastInsertId();
                        return $trackId;
        	}
        	catch(PDOException $e)
			{	
				throw new jsException($e);
			}
        }

        //This function updates the flag if the redirected page for the same viewer and viewed profileId has been opened successfully.
        
        public function upadteTrackingData($profileId,$trackingId,$viewerProfileId,$flag)
        {       
                try
                {
                        $sql="UPDATE MOBILE_API.DEEP_LINKING_TRACKING SET FLAG=:FLAG WHERE ID=:TRACKINGID AND PROFILEID=:PROFILEID AND LOGGEDIN_PID=:VIEWER_PROFILEID";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $res->bindValue(":VIEWER_PROFILEID", $viewerProfileId, PDO::PARAM_INT);
                        $res->bindValue(":TRACKINGID", $trackingId, PDO::PARAM_INT);
                        $res->bindValue(":FLAG", $flag, PDO::PARAM_STR);
                        $res->execute();
                        return $res->rowCount();
                }
                catch(PDOException $e)
                {       
                        throw new jsException($e);
                }
        }

}
?>