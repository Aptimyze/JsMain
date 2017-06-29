<?php
/**
* This class is used for storing data into match_alert_byLogic_total table which contains the total count of number of people eligible for Match Alerts each day
*/
class MATCHALERT_TRACKING_MATCHALERT_FEEDBACK extends TABLE
{
	public function __construct($dbname='')
	{
		$dbname = $dbname?$dbname:"newjs_master";
		parent::__construct($dbname);
	}

	public function insert($data,$profileId)
	{
		try
		{
                        $fields = "PROFILEID";
                        $bindfields = ":PROFILEID";
			$sql = "INSERT IGNORE into MATCHALERT_TRACKING.MATCHALERT_FEEDBACK (";
                        if(!empty($data["reason1"])){
                                $bindfields .= ",:REASON1";
                                $fields .= ",REASON1";
                        }
                        if(trim($data["txtReason1"]) != ""){
                                $bindfields .= ",:REASON1_TEXT";
                                $fields .= ",REASON1_TEXT";
                        }
                        if(!empty($data["reason2"])){
                                $bindfields .= ",:REASON2";
                                $fields .= ",REASON2";
                        }
                        if(trim($data["txtReason2"]) != ""){
                                $bindfields .= ",:SUGGESTION_TEXT";
                                $fields .= ",SUGGESTION_TEXT";
                        }
                        if(!empty($data["MA_DATE"])){
                                $bindfields .= ",:MA_DATE";
                                $fields .= ",MA_DATE";
                        }
                        if(!empty($data["STYPE"])){
                                $bindfields .= ",:STYPE";
                                $fields .= ",STYPE";
                        }
                        if(!empty($data["feedbackTime"])){
                                $bindfields .= ",:FEEDBACK_ON";
                                $fields .= ",FEEDBACK_ON";
                        }
                        $sql .= $fields.") values (".$bindfields.")";
                        //echo $sql;die;
			$prep = $this->db->prepare($sql);
                        
                        if(!empty($data["reason1"])){
                                $prep->bindValue(":REASON1",implode(",",$data["reason1"]),PDO::PARAM_STR);
                        }
                        if(trim($data["txtReason1"]) != ""){
                                $prep->bindValue(":REASON1_TEXT",$data["txtReason1"],PDO::PARAM_STR);
                        }
                        if(!empty($data["reason2"])){
                                $prep->bindValue(":REASON2",implode(",",$data["reason2"]),PDO::PARAM_STR);
                        }
                        if(trim($data["txtReason2"]) != ""){
                                $prep->bindValue(":SUGGESTION_TEXT",$data["txtReason2"],PDO::PARAM_STR);
                        }
                        if(!empty($data["MA_DATE"])){
                                $prep->bindValue(":MA_DATE",$data["MA_DATE"],PDO::PARAM_STR);
                        }
                        if(!empty($data["STYPE"])){
                                $prep->bindValue(":STYPE",$data["STYPE"],PDO::PARAM_STR);
                        }
                        if(!empty($data["feedbackTime"])){
                                $prep->bindValue(":FEEDBACK_ON",$data["feedbackTime"],PDO::PARAM_STR);
                        }
			$prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
                        $prep->execute();
                        $count = $prep->rowCount();
                        return true;
		}
		catch (PDOException $e)
		{
			//add mail/sms
			jsException::nonCriticalError("Feedback not submitted");
                        return true;
		}
	}

}