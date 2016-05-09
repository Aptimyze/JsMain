<?php

class newMatchAlertFailDetectTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
    // $this->addOptions(array(
    //   new sfCommandOption('my_option', null, sfCommandOption::PARAMETER_REQUIRED, 'My option'),
    // ));

    $this->namespace        = 'mailer';
    $this->name             = 'newMatchAlertFailDetectTask';
    $this->briefDescription = 'New Match alert fail detection task';
    $this->detailedDescription = <<<EOF
The [newMatchAlertFailDetectTask|INFO] task does things.
Call it with:

  [php symfony mailer:newMatchAlertFailDetectTask|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $countObject=new new_matches_emails_RECEIVER();      				           // Object for checking number of email of various services(like gmail,yahoo etc)
			$count=$countObject->getCountWaiting();	
		
                        $myFile = sfConfig::get("sf_web_dir")."/uploads/SearchLogs/newMatchDetect.txt";

                        $fh = fopen($myFile, 'r');
                        $record = fgets($fh);
                        fclose($fh);

                        $fh = fopen($myFile, 'w');
                        fwrite($fh, $count);
                        fclose($fh);
                
                if($count==$record && $count!=0)	
                {       $from="alert@jeevansathi.com";
                        mail("lavesh.rawat@gmail.com,reshurajput@gmail.com,akashkumardtu@gmail.com","New Match Alert Task failed","New Match Alert Task failed<br>Last Count-".$count,"From: $from\n");
                           // ALERT
                }
                
  }
}
