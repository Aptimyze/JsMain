<?php

/* 
 * this cron creates sends sms to the AP contacts every saturday
 * for how many interests have been sent on their behalf
 */

class responseBoosterNotifications extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'smsNotification';
    $this->name             = 'responseBoosterNotifications';
    $this->briefDescription = 'sends sms to the AP contacts every saturday
for how many interests have been sent on their behalf';
    $this->detailedDescription = <<<EOF
      this task takes contacts from table Assisted_Product.AUTOMATED_CONTACTS_TRACKING and counts number of interests sent to contacts on behalf of this user 
      Call it with:

      [php symfony smsNotification:responseBoosterNotifications totalScript currentScript] 
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
   * this function fetched user id of AP of 1 week before and sends sms to those ids
   * with their interests count
   * @param - $arguments- array of arguments, $options - array of options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
    $sendSmsUserList= new ASSISTED_PRODUCT_AUTOMATED_CONTACTS_TRACKING("newjs_slave");
    $tempTableRecord= new ASSISTED_PRODUCT_AUTOMATED_CONTACTS_LOG("newjs_master");
    include_once(sfConfig::get("sf_web_dir")."/classes/SmsAir2Web.class.php");
    include_once(sfConfig::get("sf_web_dir")."/classes/SMSLib.class.php");
    //for sms sending
    $this->sendSmsObj= new SmsAir2Web();
    //for getting short url
    $this->smsLibObj= new SMSLib();
    $longUrl = JsConstants::$siteUrl."/inbox/6/1";
    $afterDate = date('Y-m-d', strtotime('-7 days'));
    $endDate = date('Y-m-d');
    //select sender ids between above specified dates
    $userList = $sendSmsUserList->selectByDate($afterDate,$endDate, $arguments["totalScript"],$arguments["currentScript"]);
    $jProfileObj=JPROFILE::getInstance();
    foreach ($userList as $key => $value) {
      //get phone no, activated status, sms subscription 
      $profileData = $jProfileObj->get($key,"PROFILEID","PHONE_MOB,SERVICE_MESSAGES");
      $mobPhone= $profileData[PHONE_MOB];
      $shortUrl= $this->smsLibObj->getShortURL($longUrl, $key);
      $messageTxt= "Jeevansathi has sent ".$value." interests this week on your behalf. You may receive accepts/calls in response. To see the list, visit ".$shortUrl;
      //insert in the log array for resumability
      $tempTableRecord->insert($key);
      //check for deactivated users and unsubscribed ones
      if ($profileData[ACTIVATED] !='D' && $profileData[SERVICE_MESSAGES] !='U') {
        $generatedXml=$this->sendSmsObj->generateXml($key, $mobPhone, $messageTxt); 
        $this->sendSmsObj->send($generatedXml,''); 
      }
    }
    $tempTableRecord->delete($arguments["totalScript"],$arguments["currentScript"]);
  }
}
