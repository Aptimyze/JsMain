<?php

//This table tracks the feedback given by users who get match alert mailers
class MATCHALERT_TRACKING_match_alert_feedback extends TABLE 
{
	public function __construct($dbname="")
	{
		$dbname = $dbname?$dbname:"newjs_master"; 
		parent::__construct($dbname);
	}

	//This function is used to insert feedback details to the table
	public function insertMailerFeedback($profileid,$mailSentDate,$stype,$feedbackValue,$feedbackTime)
	{
		try
		{
			$sql = "INSERT IGNORE INTO MATCHALERT_TRACKING.match_alert_feedback(PROFILEID,MAILSENTDATE,STYPE,FEEDBACKVALUE,FEEDBACKTIME) VALUES (:PROFILEID,:MAILSENTDATE,:STYPE,:FEEDBACKVALUE,:FEEDBACKTIME)";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":MAILSENTDATE",$mailSentDate,PDO::PARAM_INT);
			$prep->bindValue(":STYPE",$stype,PDO::PARAM_STR);
			$prep->bindValue(":FEEDBACKVALUE",$feedbackValue,PDO::PARAM_STR);
			$prep->bindValue(":FEEDBACKTIME",$feedbackTime,PDO::PARAM_INT);
			$prep->execute();
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}

}