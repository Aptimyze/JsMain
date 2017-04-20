<?php
/**
* This will populate/truncate the data used for paid members users. 
*/
class PaidMembersPopulateTask extends sfBaseTask
{
	protected function configure()
  	{
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'PaidMailer';
	    $this->name             = 'PaidMembersPopulate';
	    $this->briefDescription = '';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony PaidMailer:PaidMembersPopulate] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);


		$search_PAID_MEMBERS_MAILER = new search_PAID_MEMBERS_MAILER();
		/** 
		* start the process one all mailers have been fired 
		*/		
		$flag=0;
		while($flag)
		{
			$cnt = $search_PAID_MEMBERS_MAILER->countNotSentMails();
			if($cnt==0)
				$flag=0;
			else
			{
				sleep(300);
			}
		}

		/** 
		* truncate tables 
		*/
		$search_PAIDMEMBERS_TO_BE_SENT = new search_PAIDMEMBERS_TO_BE_SENT;
		$search_PAIDMEMBERS_TO_BE_SENT->truncateTable();

		$search_PAID_MEMBERS_MAILER->truncateTable();
		/* truncate tables */


		/* populate logic */
		$interval="6 MONTH";
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
		$conditionNew .= "(((jp.ENTRY_DT >= DATE_SUB( now( ) , INTERVAL 15 DAY )) || (jp.ENTRY_DT < DATE_SUB( now( ) , INTERVAL 15 DAY )) && (jp.MOB_STATUS = 'Y' || jp.LANDL_STATUS = 'Y' || jpc.ALT_MOB_STATUS = 'Y')) && (jp.LAST_LOGIN_DT >= DATE_SUB( now( ) , INTERVAL 3 MONTH )))";
		$search_PAIDMEMBERS_TO_BE_SENT->populateTables($conditionNew);
	}
}
