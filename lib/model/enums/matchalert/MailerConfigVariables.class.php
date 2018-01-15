<?php
//This class has static variables and functions used across matchalerts, kundli alerts and new matches mailers

class MailerConfigVariables
{
	public static $maxAgeDiff=5;		//should be same as configVariables::$maxAgeDiff in web/new_matchalert/configVariables.php
        public static $maxHeightDiff=10;	//should be same as configVariables::$maxHeightDiff in web/new_matchalert/configVariables.php
	public static $trendThreshold=20;	//should be same as configVariables::$trendThreshold in web/new_matchalert/configVariables.php
	public static $strategyNTvsNewLogic=5;
        public static $strategyTvsNewLogic=6;
	public static $maxLimitNewMatchesMails=10;
        public static $newMatchAlertLogic='N';
        public static $oldMatchAlertLogic='O';
        public static $strategyReceiversTVsT = 1;
        public static $strategyReceiversTVsNT = 2;
        public static $strategyReceiversNT = 3;
        public static $communityModelNT = 4;
        public static $relaxedDpp = 5;
        
        
        public static $DppLoggedinWithReverseDppSort = 1; // DPP Loggedin in last 15days with reverse dpp sort
        public static $DppLoggedinWithTrendsScoreSort = 2; // DPP Loggedin in last 15days, (Instead of TRENDS) for User having Trends sort by trends score
        public static $DppNotLoggedinWithLoginDateSort = 3; // DPP not Loggedin in last 15days (Instead of TRENDS) for User having Trends sort by login timestamp
        public static $TrendsLoggedinWithTrendsScoreSort = 4; // TRENDS Loggedin in last 15days sort by trends score
        public static $TrendsNotLoggedinWithLoginDateSort = 5; // TRENDS not Loggedin in last 15days sort by login timestamp
        
	/*
	This function returns the number of days from 01-01-2005 to today. Used in matches generation logic
	@return - no of days
	*/
	public static function getNoOfDays()		
        {
                $today=mktime(0,0,0,date("m"),date("d"),date("Y"));
                $zero=mktime(0,0,0,01,01,2005);
                $gap=($today-$zero)/(24*60*60);
                return $gap;
        }

	/*
	This function returns the number of days from 01-01-2006 to today. Used in mailers sending logic
	@return - no of days
	*/
	public static function getLogicalDate()		
	{
		$today=mktime(0,0,0,date("m"),date("d"),date("Y")); //timestamp for today
		$zero=mktime(0,0,0,01,01,2006); //timestamp for 1 Jan 2006
		$gap=($today-$zero)/(24*60*60); //$gap is the no. of days since 1 Jan 2006.
		return $gap;
	}

	/*
	This function gives the date corresponding to a given number of days from 01-01-2006. It is used to get the exact date on which a mailer was sent
	@return - date in YYYY-MM-DD format
	*/
	public static function decodeLogicalDate($gap)
	{
		$zero=mktime(0,0,0,01,01,2006); //timestamp for 1 Jan 2006
		$gap = ($gap*24*60*60);
		$gap = $gap + $zero;
		$dateString = date("Y-m-d",$gap);
		return $dateString;
	}
}
?>
