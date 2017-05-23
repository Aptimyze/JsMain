<?php

class MIS_VCD_TRACKING extends TABLE
{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
	* This function updates the table MIS.VCD_TRACKING
	* whenever a free user try to view user's contact details and does not have privilage. 
	**/
	public function insertTracking($viewer,$viewed,$channel,$type,$viewed_sub,$viewer_sub) 
	{
		if(!$viewer || !$viewed)
			throw new jsException("","PROFILEID IS BLANK IN MIS_VCD_TRACKING.class.php");
		try
		{
			$date=date("Y-m-d");
			$sql = "INSERT IGNORE INTO MIS.VCD_ATTEMP_TRACKING(VIEWER,VIEWED,VIEWED_SUBSCRIPTION,VIEWER_SUBSCRIPTION,`DATE`,CHANNEL,CONTACT_TYPE) VALUES(:VIEWER,:VIEWED,:VIEWED_SUBSCRIPTION,:VIEWER_SUBSCRIPTION,:DATE1,:CHANNEL,:TYPE)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VIEWED", $viewed, PDO::PARAM_INT);
			$res->bindValue(":VIEWER", $viewer, PDO::PARAM_INT);
			$res->bindValue(":DATE1", $date, PDO::PARAM_STR);
			$res->bindValue(":CHANNEL", $channel, PDO::PARAM_STR);
			$res->bindValue(":TYPE",$type,PDO::PARAM_STR);
			$res->bindValue(":VIEWED_SUBSCRIPTION",$viewed_sub,PDO::PARAM_STR);
			$res->bindValue(":VIEWER_SUBSCRIPTION",$viewer_sub,PDO::PARAM_STR);
			$res->execute();
			return $this->db->lastInsertId(); 
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
        public function getContactAttemptProfiles($profileId,$condition){
                if(!$profileId)
			throw new jsException("","PROFILEID IS BLANK IN MIS_VCD_TRACKING.class.php");
		try
		{
                        $date=date("Y-m-d",strtotime('-3 months'));
                        $sql = "SELECT VIEWER ,DATE as SENT_DATE FROM MIS.VCD_ATTEMP_TRACKING WHERE DATE > '$date' AND VIEWED = :VIEWED";
                        if(!empty($condition)){
                          foreach($condition as $key=>$value){
                            $sql .= " AND ".$key." IN (".$value.")";
                          }
                        }
                        $sql .= ' ORDER BY DATE DESC LIMIT 0,400';
			$res = $this->db->prepare($sql);
			$res->bindValue(":VIEWED", $profileId, PDO::PARAM_INT);
                        $res->execute();
                        $profileIdArr = array();
                        while($profile=$res->fetch(PDO::FETCH_ASSOC)){
                                $profileIdArr[$profile['VIEWER']] =$profile['SENT_DATE'];
                        }
                        return $profileIdArr;
                } catch (PDOException $e) {
                        throw new jsException($e);
                }
        }
}
 
?>
