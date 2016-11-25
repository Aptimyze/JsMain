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
        $query          ='select PHONE FROM DNC.DNC_LIST;';
	//$sql ="SELECT PHONE FROM DNC.DNC_LIST limit 10 INTO OUTFILE ".$sourceFile." LINES TERMINATED BY '\n'";

	// Dir. structure
	$filename ='dnc_'.date('dmY').'.csv';
	$sourceDir = JsConstants::$docRoot.'/uploads/csv_files/dnc/'.$filename;
	$destDir = JsConstants::$docRoot.'/uploads/csv_files/fpdialer/';


	// dump command	
	$command ='/usr/local/mysql_php/bin/mysql -u'.$user.' -p'.$password.' -h'.$dns.' -P'.$port.' -e "'.$query.'" >'.$sourceDir;	
	passthru($command);
	die('test');
	
        //Copy dnc data to shared dir.(fpdialer) 
	usleep(3000000);
	passthru("cp $sourceDir $destDir", $return_var);
	if($return_var){
		$message ='ERROR: DNC-Data csv not copied on fpdialer';
	}
	else{
		$message ='SUCCESS: DNC-Data csv copied on fpdialer';
	}
	mail("manoj.rana@naukri.com,dheeraj.negi@naukri.com","$message","","From:JeevansathiCrm@jeevansathi.com");
  }
}
