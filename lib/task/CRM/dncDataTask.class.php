<?php

class dncDataTask extends sfBaseTask
{
  protected function configure()
  {
     $this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','operations'),
     ));

    $this->namespace        = 'dnc';
    $this->name             = 'dncData';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [dncData|INFO] task does things.
Call it with:

  [php symfony dncData|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        ini_set('max_execution_time',0);
        ini_set('memory_limit',-1);
        sfContext::createInstance($this->configuration);

	// Connection
        $user           =MysqlDbConstants::$dnc['USER'];
        $password       =MysqlDbConstants::$dnc['PASS'];
        $dns            =MysqlDbConstants::$dnc['HOST'];
        $port           =MysqlDbConstants::$dnc['PORT'];

        // Dir. structure
        $filename ='dnc_'.date('dmY').'.csv';
        $sourceDir = JsConstants::$docRoot.'/uploads/csv_files/dnc/'.$filename;
        $destDir = JsConstants::$docRoot.'/uploads/csv_files/fpdialer/dnc_dump.csv';

	$dncListObj =new dnc_DNC_LIST();
	$count =$dncListObj->fetchDncCount();

	$loopCnt        =10000000;
	$totLoop 	=intval($count/$loopCnt)+1;
	$startLimit	=0;

	for($i=0; $i<$totLoop; $i++){

	        $query          ="select PHONE FROM DNC.DNC_LIST limit $startLimit, $loopCnt;";
		$startLimit	+=$loopCnt;

		// dump command	
		$command ='/usr/local/mysql_php/bin/mysql -s -u'.$user.' -p'.$password.' -h'.$dns.' -P'.$port.' -e "'.$query.'" >>'.$sourceDir;	
		passthru($command);
	}	
	usleep(3000000);
	//Copy dnc data to shared dir.(fpdialer)
	//$totCsvCnt =passthru("wc -l < $sourceDir");

	passthru("cp $sourceDir $destDir", $return_var);
	if($return_var){
		$message ="ERROR: DNC-Data csv not copied on fpdialer";
	}
	else{
		$message ="SUCCESS: DNC-Data ($count) csv copied on fpdialer";
	}
	mail("manoj.rana@naukri.com,dheeraj.negi@naukri.com","$message","","From:JeevansathiCrm@jeevansathi.com");
  }
}
