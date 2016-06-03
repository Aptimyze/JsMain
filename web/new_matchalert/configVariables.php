<?php
class configVariables
{
	public static function getNoOfDays()
	{
		$today=mktime(0,0,0,date("m"),date("d"),date("Y"));
		$zero=mktime(0,0,0,01,01,2005);
		$gap=($today-$zero)/(24*60*60);
		return $gap;
	}

	public static $use60DaysRelaxOnDay=5;
        public static $maxLimit=10;
        public static $loginDtRelax1=15;
        public static $loginDtRelax2=60;
	public static $maxForwardTrendsLimit=3;
	public static $maxAgeDiff=5;
	public static $maxHeightDiff=10;
	public static $trendThreshold=20;//change to 20
	public static $queryLimitForOptimization=1000;
	public static $queryLimitForOptimizationMax=1000000;//Take any no. (> than max of record in searchtables) 

	//For Tracking Purpose
	public static $strategyNtVsNtLogic=1;
	public static $strategyNtVsTLogic=2;
	public static $strategyTVsNtLogic=3;
	public static $strategyTVsTLogic=4;
	public static $maxLimitDvD=85;
	public static $maxLimitKundli=500;
	public static $kundliMailLimit=5;
	
}
?>
