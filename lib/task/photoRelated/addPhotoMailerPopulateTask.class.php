<?php
/**
* This will populate/truncate the sending mailer to prompt users to add photos 
*/
class addPhotoMailerPopulateTask extends sfBaseTask
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
        
        $this->namespace = 'photoRelated';
        $this->name = 'addPhotoMailerPopulate';
        $this->briefDescription='';
        $this->detailedDescription = <<<EOF
          Call it with:
          [php symfony photoRelated:addPhotoMailerPopulate totalScripts currentScript]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance ($this->configuration);
        ini_set('memory_limit','256M');

        $totalScripts = $arguments["totalScripts"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
        
        $photoMailerObj = new PICTURE_ADD_PHOTO_MAILER("newjs_masterRep");
    
    //Truncate table Data       
    $photoMailerDDLObj = new PICTURE_ADD_PHOTO_MAILER('newjs_masterDDL');
    $photoMailerDDLObj->truncatePhotoMailerData();

    //date condition of users to whom mail is to be sent.
    $dateConditionArr = array(date('Y-m-d', strtotime("-4 day")),date('Y-m-d', strtotime("-11 day")),date('Y-m-d', strtotime("-18 day")),date('Y-m-d', strtotime("-25 day")),date('Y-m-d', strtotime("-32 day")),date('Y-m-d', strtotime("-39 day")));

        //select from slave
    $jprofileObj = new JPROFILE("newjs_slave");
    $receiverData = $jprofileObj->getProfileForNoPhotoMailer($dateConditionArr);
        unset($jprofileObj);
        
        if(is_array($receiverData))
        {
            //insert to master
            $insertDataObj = new PICTURE_ADD_PHOTO_MAILER("newjs_master");
            $insertDataObj->insertnoPhotoMailerData($receiverData);
            unset($insertDataObj);
        }
    }
}
