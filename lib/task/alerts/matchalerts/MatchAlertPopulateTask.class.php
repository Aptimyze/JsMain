<?php
/**
* This will populate/truncate the data used for matchalerts. 
*/
class MatchAlertPopulateTask extends sfBaseTask
{
	protected function configure()
  	{
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'alert';
	    $this->name             = 'MatchAlertPopulate';
	    $this->briefDescription = '';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony alert:MatchAlertPopulate] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);


		$matchalerts_MAILER = new matchalerts_MAILER;
		/** 
		* start the process one all mailers have been fired 
		*/		
		$flag=1;
		while($flag)
		{
			$cnt = $matchalerts_MAILER->countNotSentMails();
			if($cnt==0)
				$flag=0;
			else
			{
				sleep(300);
			}
		}

	        /**
		*daily monitoring
		*/
		$php5 = JsConstants::$php5path;
                $cronDocRoot = JsConstants::$cronDocRoot;
                passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring MATCHALERT_MAILER");
                passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring MATCHALERT_MAILER#INSERT");

		/** 
		* truncate tables 
		*/
		$matchalerts_MATCHALERTS_TO_BE_SENT = new matchalerts_MATCHALERTS_TO_BE_SENT;
		$matchalerts_MATCHALERTS_TO_BE_SENT->truncateTable();

		$matchalerts_MAILER->truncateTable();

		$matchalerts_LOG_TEMP = new matchalerts_LOG_TEMP;
		$matchalerts_LOG_TEMP->truncateTable();
		$matchAlertsLowTrendsObj =  new matchalerts_LowTrendsMatchalertsCheck();
		$matchAlertsLowTrendsObj->truncateTable();
		/* truncate tables */
                
                //delete one month old entries
                $beforeDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
                $lowMatchesCheckObj = new LowDppMatchesCheck();
                $lowMatchesCheckObj->deleteBeforeDate($beforeDate);


		/* populate logic */
		$interval="1 MONTH";
                $intervalAddCon = "";
                $day_of_week=date("w");
                if($day_of_week == 5){
                        $intervalAddCon = "|| (LAST_LOGIN_DT BETWEEN date( now( ) - INTERVAL 3 MONTH ) AND date( now( ) - INTERVAL 1 MONTH ) && (jp.PROFILEID % 4=0 || jp.PROFILEID % 4=1))";
                }elseif($day_of_week == 6){
                        $intervalAddCon = "|| (LAST_LOGIN_DT BETWEEN date( now( ) - INTERVAL 3 MONTH ) AND date( now( ) - INTERVAL 1 MONTH ) && (jp.PROFILEID % 4=2 || jp.PROFILEID % 4=3))";
                }
		/*$day_of_week=date("w");
		if( in_array($day_of_week,array('1','3','5')))
		{
			$conditionNew = "PERSONAL_MATCHES in ('A','O') AND ";
		}
		else
		{
			$conditionNew = "PERSONAL_MATCHES='A' AND ";
		}*/
		$conditionNew = "(ACTIVATED='Y' OR ACTIVATED = 'N') AND ";
		$conditionNew .= "(((jp.ENTRY_DT >= DATE_SUB( now( ) , INTERVAL 15 DAY )) || (jp.ENTRY_DT < DATE_SUB( now( ) , INTERVAL 15 DAY )) && (jp.MOB_STATUS = 'Y' || jp.LANDL_STATUS = 'Y' || jpc.ALT_MOB_STATUS = 'Y')) && ((jp.LAST_LOGIN_DT >= DATE_SUB( now( ) , INTERVAL $interval) $intervalAddCon)))";
		$matchalerts_MATCHALERTS_TO_BE_SENT->populateTables($conditionNew);
                
                /*
                 * Update HASTRENDS column
                 */
                $matchalerts_MATCHALERTS_TO_BE_SENT->updateTrends();
                $matchalerts_MATCHALERTS_TO_BE_SENT->resetTrendsIfOldLogicSet();
                $matchalerts_MATCHALERTS_TO_BE_SENT->insertFromTempTable();
                $matchalerts_MATCHALERTS_TO_BE_SENT->truncateTempTable();
	}
}
