<?php
/**
* This taks will decide correct Strategy for the Paid members users. 
*/
class PaidMembersMailerCompletionTask extends sfBaseTask
{
	private $limit = 5000;
	private $limitRec = 16;
	protected function configure()
  	{
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'PaidMailer';
	    $this->name             = 'PaidMembersMailerCompletion';
	    $this->briefDescription = '';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony PaidMailer:PaidMembersMailerCompletion] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);

                ini_set('memory_limit','512M');
                
                $search_PAIDMEMBERS_TO_BE_SENT = new search_PAIDMEMBERS_TO_BE_SENT;
                $totalCount = $search_PAIDMEMBERS_TO_BE_SENT->countMails();
                
                $search_PAID_MEMBERS_MAILER = new search_PAID_MEMBERS_MAILER();
                $cnt = $search_PAID_MEMBERS_MAILER->countMails();
                unset($search_PAIDMEMBERS_TO_BE_SENT);
                unset($search_PAID_MEMBERS_MAILER);
                $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/PaidMembersLogging.txt";
                file_put_contents($fileName, date("Y_m_d", strtotime("now")).':: TOTAL-'.$totalCount.'  :: SENT-'.$cnt."\n", FILE_APPEND);
	}
}
