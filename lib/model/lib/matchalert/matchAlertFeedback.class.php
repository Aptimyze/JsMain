<?php

//This library is used to call match_alert_feedback store using the data passed on to it by action 
class matchAlertFeedback 
{
	public function __construct($dbname='')
	{
		$this->dbname = $dbname;
	}

	//Function to call matchalert_match_alert_feedback store to insert feedback data
	public function insertMatchAlertFeedback($profileid,$mailSentDate,$stype,$feedbackValue,$feedbackTime)
	{
		$feedbackStoreObj = new MATCHALERT_TRACKING_match_alert_feedback();
		$feedbackStoreObj->	insertMailerFeedback($profileid,$mailSentDate,$stype,$feedbackValue,$feedbackTime);
	}
}