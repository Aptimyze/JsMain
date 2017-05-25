<?php

/* 
 * this task checks for duplicate profiles and fires emails to all those users
 */
class duplicateProfileNotify extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'oneTimeCron';
    $this->name             = 'duplicateProfileNotify';
    $this->briefDescription = 'sends email to the users for notifying them that their profile is duplicate';
    $this->detailedDescription = <<<EOF
      this task takes user ids from table              and sends mail to the users marked as duplicate  
      Call it with:

      [php symfony oneTimeCron:duplicateProfileNotify totalScript currentScript] 
EOF;
    $this->addArguments(array(
            new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
        new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
    ));
                
    $this->addOptions(array(
    new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
  }
  /*
   * this function fetched user id of AP of 1 week before and sends email to those ids
   * which are marked duplicate
   * @param : $arguments array of arguments
   * @param : $options array of options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
    $duplicateProfilesList= new INCENTIVE_NEGATIVE_TREATMENT_LIST("newjs_slave");
    $tempTableRecord= new duplicates_DUPLICATE_PROFILES_MAIL_LOG("newjs_master");
    $afterDate = date('Y-m-d', strtotime('-2 years'));
    //select duplicate ids after above specified dates
    $maxId= $duplicateProfilesList->getMaxId();
    echo "\nMAX ID =".$maxId."\n";
    $userList=$duplicateProfilesList->getAllDuplicateProfiles($afterDate,$arguments["totalScript"],$arguments["currentScript"]);
    foreach ($userList as $key => $value) { 
      duplicateProfilesMail::sendEmailToDuplicateProfiles($value);
      //insert in the log array for resumability
      $tempTableRecord->insert($value);
    }
    $tempTableRecord->delete($arguments["totalScript"],$arguments["currentScript"]);
  }
}

