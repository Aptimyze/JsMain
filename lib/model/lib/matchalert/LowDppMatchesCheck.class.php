<?php
/*This class is used to handle informing user to broaden his dpp due to low dpp matches*/
class LowDppMatchesCheck
{
        private $informTimes = 1;
        public static $mailerFreq = '-1 week';
	public function __construct($dbname='')
	{
		$this->dbname = $dbname;
	}

	/*
	This function is used to check whether to inform user or not
	@param - profileid value, skipped type
	@return - count
	*/
	public function getProfilesWithInformLimitReached($date,$totalScripts,$currentScript)
	{
		$lowMatchesCheckObj = new matchalerts_LowDppMatchalertsCheck();
                $profilesArr = $lowMatchesCheckObj->getProfilesWithInformLimitReached($date,$totalScripts,$currentScript,$this->informTimes);
               
                return $profilesArr;
	}

	/*
        This function deletes all records before a given date for a particular profile
        @param -  profileid,date
        */
	public function deleteBeforeDate($beforeDate)
	{
                $lowMatchesCheckObj = new matchalerts_LowDppMatchalertsCheck();
                $lowMatchesCheckObj->deleteBeforeDate($beforeDate);
        }
        
        /*
        This function inserts a row with no of matches and date
        @param -  profileid,date
        */
	public function insertForProfile($profileId)
	{   
                $currDate = date('Y-m-d H:i:s');
                $lowMatchesCheckObj = new matchalerts_LowDppMatchalertsCheck();
                $lowMatchesCheckObj->insertForProfile($profileId,$currDate);
        }
        
        /*
        This function checks for low results and inserts rows accordingly
        @param -  profileid,date,totalResults
        */
	public function getProfilesWithZeroMatches($totalScript,$currentScript)
	{
                $currDate = date('Y-m-d H:i:s');
                $lowMatchesCheckObj = new matchalerts_LowDppMatchalertsCheck();
                $profilesArr = $lowMatchesCheckObj->getProfilesWithZeroMatches($totalScript,$currentScript,date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 day" )));
                return $profilesArr;
                
        }
        
        /*
        This function checks for low results and inserts rows accordingly
        @param -  profileid,date,totalResults
        */
	public function updateSent($profileid,$sent)
	{
                $lowMatchesCheckObj = new matchalerts_LowDppMatchalertsCheck();
                $profilesArr = $lowMatchesCheckObj->updateSent($profileid,$sent);
                
        }
}

