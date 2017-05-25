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
                
		/** 
		* truncate tables 
		*/
                $search_PAIDMEMBERS_TO_BE_SENTDDL = new search_PAIDMEMBERS_TO_BE_SENT('newjs_masterDDL');
                $search_PAIDMEMBERS_TO_BE_SENTDDL->truncateTable();
                unset($search_PAIDMEMBERS_TO_BE_SENTDDL);
                $search_PAID_MEMBERS_MAILERDDL = new search_PAID_MEMBERS_MAILER('newjs_masterDDL');
                $search_PAID_MEMBERS_MAILERDDL->truncateTable();
                unset($search_PAID_MEMBERS_MAILERDDL);
                
		$search_PAIDMEMBERS_TO_BE_SENT = new search_PAIDMEMBERS_TO_BE_SENT("newjs_master");
		/* truncate tables */

		$conditionNew = "(ACTIVATED='Y' OR ACTIVATED = 'N') AND ";
		$conditionNew .= "(((jp.MOB_STATUS = 'Y' || jp.LANDL_STATUS = 'Y' || jpc.ALT_MOB_STATUS = 'Y')) && (jp.LAST_LOGIN_DT >= DATE_SUB( now( ) , INTERVAL 15 DAY )))";
                $flag = 1;
                $limtStrt = 0;
                $limtChunk = 2000;
                do{
                        
                        $jprofileObj = NEWJS_JPROFILE::getInstance("newjs_slave");
                        $limitStr = $limtStrt.",".$limtChunk;
                        $limtStrt += $limtChunk;
                        $jprofileData = $jprofileObj->getLastLoggedInData($conditionNew,$limitStr);
                        if(is_array($jprofileData)){
                                $search_PAIDMEMBERS_TO_BE_SENT->populateTables($jprofileData);
                        }
			else
				$flag=0;
                        
                        unset($jprofileObj);
		}while($flag);
	}
}
