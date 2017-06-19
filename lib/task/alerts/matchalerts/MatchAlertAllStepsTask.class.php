<?php
/**
* This will populate/truncate the data used for matchalerts. 
*/
class MatchAlertAllStepsTask extends sfBaseTask
{
	private $noOfInstance = 9;
	protected function configure()
  	{
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'alert';
	    $this->name             = 'MatchAlertAllSteps';
	    $this->briefDescription = '';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony alert:MatchAlertAllSteps] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);

		$php5 = JsConstants::$php5path;
                $cronDocRoot = JsConstants::$cronDocRoot;
                passthru("$php5 $cronDocRoot/symfony alert:MatchAlertPopulate");
		for($i=0;$i<$this->noOfInstance;$i++)
		{
	                passthru("$php5 $cronDocRoot/symfony alert:MatchAlertCalculation $this->noOfInstance $i >>".JsConstants::$alertDocRoot."/new_matchalert/logerror.txt &");
		}
     
	}
}
