<?php

class requestDeletionMisTask extends sfBaseTask
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

    $this->namespace        = '';
    $this->name             = 'requestDeletionMis';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [requestDeletionMis|INFO] task does things.
Call it with:

  [php symfony requestDeletionMis]
EOF;
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
        ));
  }
  protected function execute($arguments = array(), $options = array())
  {  
    if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
    $requestDelLogObj = new MIS_REQUEST_DELETIONS_LOG();
    $userDidNotDelete = $requestDelLogObj->getAllUsersRequestedDeletion();  
    $userDidNotDeleteStr = implode(',', $userDidNotDelete);
    $usernameObj = NEWJS_JPROFILE::getInstance('newjs_slave');
    $reportArray= $usernameObj-> getArray(array('PROFILEID' => $userDidNotDeleteStr),'','','USERNAME,EMAIL,PHONE_MOB,ISD','','','','','','','',"ACTIVATED != 'D'");
    $data="USERNAMES,PHONE NUMBER,EMAIL";
 
     if(is_array($reportArray))
    {
      foreach ($reportArray as $key => $value) 
      {
      $data.="\r\n".$value['USERNAME'].",".$value['ISD'].$value['PHONE_MOB'].",".$value['EMAIL'];
      }
    }
    $todayDate = (new DateTime())->format('Y-m-d');
   // print_r($data); die('aaa');
    SendMail::send_email('anant.gupta@naukri.com,mithun.s@jeevansathi.com',"Please find the attached CSV file.","Report for Users who did not delete their profile even after receiving a request to delete","noreply@jeevansathi.com",'','',$data,'','requestDelete_'.$todayDate.".csv");
    // add your code here
  }
}
