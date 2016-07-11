<?php
/**
* This will populate/truncate the data used for savedSearch. 
*/
class savedSearchPopulateTask extends sfBaseTask
{
	protected function configure()
    {
        $this->addArguments(array(
                        new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
                        new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
        	));

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
        
        $this->namespace = 'savedSearch';
        $this->name = 'savedSearchPopulate';
        $this->briefDescription='';
        $this->detailedDescription = <<<EOF
          Call it with:
          [php symfony savedSearch:savedSearchPopulate totalScripts currentScript]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance ($this->configuration);
        ini_set('memory_limit','512M');

        $totalScripts = $arguments["totalScripts"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
        
        $savedSearchObj = new send_saved_search_mail();
		
		//Truncate table Data       
		$savedSearchObj->truncateSavedSearchData();
		$searchAgentObj = new SEARCH_AGENT();
		$receiverData = $searchAgentObj->insertSavedSearchMailerData($currentScript,$totalScripts);	
    }
}