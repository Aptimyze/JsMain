<?php

/*
 * Author: Prinka Wadhwa
 * This task gets all the profiles for which duplication check needs to be done and runs duplication checks for these profiles.
*/

class cronFtoMisTask extends sfBaseTask
{
  protected function configure()
  {

//$this->addArguments(array(
//	new sfCommandArgument('profileType', sfCommandArgument::OPTIONAL, 'My argument', 'NEW'),
//	));

$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));


    $this->namespace        = 'cron';
    $this->name             = 'cronFtoMis';
    $this->briefDescription = 'populates MIS tables in the database named FTO';
    $this->detailedDescription = <<<EOF
The [cronDuplication|INFO] task populates MIS tables in the database named FTO.
Call it with:

  [php symfony cron:cronFtoMis] 
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
	$profileType = $arguments["profileType"]; // NEW / EDIT

        $fp=fopen('/tmp/ftoMisCron' . ".lock","w+");

        if($fp)
        {
                $gotlock=flock($fp,LOCK_EX + LOCK_NB);
                if(!$gotlock)
                {
                        echo "cannot get lock. exiting";
                        fclose($fp);
                        exit;
                }
        }
        else
        {
                echo "cannot get lock. exiting";
                exit;
        }

  }
}
