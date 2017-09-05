<?php

/**
 This task is used to send regular matchalert mailer 
 *@author : Reshu Rajput
 *created on : 20 May 2014 
 */
class RegularMatchalertMailerTask extends sfBaseTask
{
    private $smarty;
    private $mailerName = "MATCHALERT";
    private $limit = 1000;
    const NTDPP_COUNT = 16;
    const TDPP_COUNT = 10;
    const NON_TRENDS_LOGIC=3;
    const TRENDS_LOGIC=2;
    const COMMUNITY_MODEL=4;
  
  protected function configure()
  {
    $this->namespace        = 'mailer';
    $this->name             = 'RegularMatchalertMailer';
    $this->briefDescription = 'regular matchalert mailer';
    $this->detailedDescription = <<<EOF
      The task send matchalert mailer .
      Call it with:

      [php symfony mailer:RegularMatchalertMailer totalScript currentScript] 
EOF;
    $this->addArguments(array(
		new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
                new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
                ));
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
	if(!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);
        
        if(CommonUtility::runFeatureInDaytime(1,8)){
                successfullDie();
        }
	$totalScript = $arguments["totalScript"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
	$LockingService = new LockingService;
        $file = $this->mailerName."_".$totalScript."_".$currentScript.".txt";
        $lock = $LockingService->getFileLock($file,1);
        if(!$lock)
        	successfullDie();
	$mailerServiceObj = new MailerService();
	// match alert configurations
        $fields ="SNO,RECEIVER,USER1,USER2,USER3,USER4,USER5,USER6,USER7,USER8,USER9,USER10,USER11,USER12,USER13,USER14,USER15,USER16,LOGIC_USED,FREQUENCY";
	$receivers = $mailerServiceObj->getMailerReceivers($totalScript,$currentScript,$this->limit,$fields);
	$clicksource = "matchalert1";
	$this->smarty = $mailerServiceObj->getMailerSmarty();
        $countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
        $instanceId = $countObj->getID('MATCHALERT_MAILER');
        $this->smarty->assign('instanceID',$instanceId);
	if(is_array($receivers))
	{            
		$mailerLinks = $mailerServiceObj->getLinks();
		$this->smarty->assign('mailerLinks',$mailerLinks);
		$this->smarty->assign('mailerName',MAILER_COMMON_ENUM::getSenderEnum($this->mailerName)["SENDER"]);
		$widgetArray = Array("autoLogin"=>true,"nameFlag"=>true,"dppFlag"=>false,"membershipFlag"=>true,"openTrackingFlag"=>true,"filterGenderFlag"=>true,"sortPhotoFlag"=>false,"logicLevelFlag"=>true,"googleAppTrackingFlag"=>true,"primaryMailGifFlag"=>true,"alternateEmailSend"=>true,"sortSubscriptionFlag"=>true);

		foreach($receivers as $sno=>$values)
		{
			$pid = $values["RECEIVER"];
			$sno = $values["SNO"];
			$data = $mailerServiceObj->getRecieverDetails($pid,$values,$this->mailerName,$widgetArray);

      if(is_array($data))
			{
                                $stypeMatch = $this->getStype($values["LOGIC_USED"]);
				//Common Parameters required in mailer links
				$data["stypeMatch"] =$stypeMatch."&clicksource=".$clicksource;
                                $dppLink = $mailerLinks['MY_DPP'].$data['commonParamaters']."?From_Mail=Y&EditWhatNew=FocusDpp&stype=".$data['stypeMatch']."&logic_used=".$data.logic;
				$subjectAndBody= $this->getSubjectAndBody($data["USERS"][0],$data["COUNT"],$values["LOGIC_USED"],$pid,$dppLink);                           
                                $data["body"]=$subjectAndBody["body"];
				$data["showDpp"]=$subjectAndBody["showDpp"];
                                
                                if(($values["LOGIC_USED"] == self::NON_TRENDS_LOGIC && $data["COUNT"] < self::NTDPP_COUNT) || ($values["LOGIC_USED"] == self::TRENDS_LOGIC && $data["COUNT"] < self::TDPP_COUNT)){
                                        if($values["LOGIC_USED"] == self::NON_TRENDS_LOGIC)
                                            $minIdealRecords = self::NTDPP_COUNT;
                                        elseif($values["LOGIC_USED"] == self::TRENDS_LOGIC)
                                            $minIdealRecords = self::TDPP_COUNT;
                                        $foundCount = $data["COUNT"];
                                        $data["bodyNote"]="<b>Note</b>: For your best interest, we try to recommend up to $minIdealRecords members matching your Desired Partner Profile every day, but we could find only $foundCount members matching your partner preference. Please broaden your Desired Partner Profile to get more matches on a daily basis.";
                                        $data["showDpp"]=1;
                                }
                                
                                if(($values["LOGIC_USED"] == self::COMMUNITY_MODEL)){
                                    $data["body"].="<a href='".$dppLink."'>click here</a>";
                                }
                                
				$data["surveyLink"]=$subjectAndBody["surveyLink"];
        $data["mailSentDate"] = date("Y-m-d H:i:s");
				$subject ='=?UTF-8?B?' . base64_encode($subjectAndBody["subject"]) . '?='; 
				$this->smarty->assign('data',$data);
				$msg = $this->smarty->fetch(MAILER_COMMON_ENUM::getTemplate($this->mailerName).".tpl");
        $flag = $mailerServiceObj->sendAndVerifyMail($data["RECEIVER"]["EMAILID"],$msg,$subject,$this->mailerName,$pid,$data["RECEIVER"]["ALTERNATEEMAILID"]);
                $this->setMatchAlertNotificationCache($data);
			}
			else
				$flag = "I"; // Invalid users given in database

			$mailerServiceObj->updateSentForUsers($sno,$flag);
			unset($subject);
			unset($mailSent);
			unset($data);
		}
	}
  }
  /**
   * This function returns stpe on the basis of logic level
   * @param int $logicUsed logic level
   * @return string stype based on logic level
   */
  function getStype($logicUsed){
    switch ($logicUsed) {
      case 1:
        return SearchTypesEnums::MatchAlertMailer1;
        break;
      case 2:
        return SearchTypesEnums::MatchAlertMailer2;
        break;
      case 3:
        return SearchTypesEnums::MatchAlertMailer3;
        break;
      case 4:
        return SearchTypesEnums::MatchAlertMailer4;
        break;
      case 5 : 
        return SearchTypesEnums::MatchAlertMailer5;
        break;
      case 7 : 
        return SearchTypesEnums::MatchAlertMailer7;
        break;
      default:
        return SearchTypesEnums::MatchAlertMailer;
        break;
    }
  }
  /**
  This function is to get subject of the mail required as per business
  *@param $name : name of the receiver of the mail
  *@param $count : number of users sent in mail
  *@param $logic : Logic used
  *@param $profileId : Receiver profile Id
  *@return $subject : subject of the mail
  */
  protected function getSubjectAndBody($firstUser,$count,$logic,$profileId,$dppLink="")
  {
	$subject = array();
	$today = date("d M");
        $matchStr = " Matches";
        $these = ' these';
        if($count==1){
                $matchStr = " Match";
                $these = '';
        }
        $dateStr = '';
        $subject["showDpp"]= 0;
	switch($logic)
	{
		case "3": //NT-NT case
			$subject["subject"]= $count." Desired Partner".$matchStr." for today | $today";
                        $subject["body"]=$this->getDppContent($count, $profileId, self::NTDPP_COUNT,$logic);
                        
                        $subject["showDpp"]= 1;
                        $subject["surveyLink"]= 'NT';
			break;
		case "2":// T-NT case 
                        $subject["subject"]= $count." Desired Partner".$matchStr." for today | $today"; 
                        $subject["body"]=$this->getDppContent($count, $profileId, self::TDPP_COUNT,$logic);
                        $subject["showDpp"]= 1;
                        $subject["surveyLink"]= 'NT';
                        break;
		case "1":// T-T case
                        $subject["subject"]= $count.$matchStr." based on your recent activity | $today";
			$subject["body"]="You may send interest to".$these." ".$count.strtolower($matchStr)." based on your recent activity. Your recent activity includes the interests, acceptances and declines sent in the last two months.";
                        $subject["surveyLink"]= 'T';
                        break;
                case "4"://community model case
                        $subject["subject"]= $count.$matchStr." based on activity of people similar to you";
			$subject["body"]="Following are profiles which we have picked based on the activity of people similar to you. Note that some of these profiles may not match your Desired Partner Profile. <br>If you wish to only receive matches as per your Desired Partner Profile, ";
                        break;
                case "5"://relaxed dpp trends case
                        $subject["subject"]= $count.$matchStr." based on your broader Desired Partner Profile";
			$subject["body"]="Shown below are matches based on your broader Desired Partner Profile. We have broadened some of your preferences as your Desired Partner Profile may be very strict. To get matches as per Desired Partner Profile, please ";
                        $subject["showDpp"]= 1;
                        break;
                case "7"://relaxed dpp trends case
                        $subject["subject"]= $count.$matchStr." based on your latest search";
			$subject["body"]="To get more matches strictly based on your Desired Partner Profile, please broaden your <a href='$dppLink'>Desired Partner Profile</a>. Meanwhile, please go through these matches from your latest search, which we have added to your Recommendations.";
                        $subject["showDpp"]= 1;
                        break;
		default :
			 throw  new Exception("No logic send in subjectAndBody() in RegularMatchAlerts task");
			
	}
	return $subject;
  }
  /**
   * 
   * @param type $count mailer count
   * @param type $profileId profileId
   * @param type $valToMatch actual limit for DPP mailer
   * @return type string - mail body
   */
  public function getDppContent($count,$profileId,$valToMatch,$logicLevel){
        $MatchAlerts = new MatchAlerts();
        $LogCount = $MatchAlerts->getProfilesCountOfLogicLevel($profileId,$logicLevel);
        $totalCountData = TwoWayBasedDppAlerts::checkForDppProfile($profileId);
        if($LogCount > $count && !empty($totalCountData) && $totalCountData["CNT"] !=0 ){
                $outOf = "$count out of ".$totalCountData["CNT"]." profiles";
        }else{
                $outOf = $count;
                if($count==1){
                        $outOf .= " profile";
                }else{
                        $outOf .= " profiles";
                }
        }
        $subject="Shown below are $outOf added to your account today, based on your Desired Partner Profile. You may send interest to them.";
        return $subject;
  }
  
  public function setMatchAlertNotificationCache($data){
   
      $receiver = $data["RECEIVER"]["PROFILE"]->getPROFILEID();
      $count = $data["COUNT"];
      $receiverLastLoginDate = $data["RECEIVER"]["PROFILE"]->getLAST_LOGIN_DT();
      $otherProfileid = $data["USERS"][0]->getPROFILEID();
      $otherPicUrl = $this->getValidImage($data["USERS"][0]->getProfilePic120Url());
      $otherPicIosUrl = $this->getValidImage($data["USERS"][0]->getProfilePic450Url());
      $cacheKey = "MA_NOTIFICATION_".$receiver;
      $seperator = "#";
      $preSetCache = JsMemcache::getInstance()->get($cacheKey);
      if($preSetCache){
          $explodedVal = explode($seperator,$preSetCache);
          $count = $count+$explodedVal[0];
          if($this->getValidImage($otherPicUrl) == "D"){
            $otherPicUrl = $explodedVal[2];
            $otherProfileid = $explodedVal[1];
          }
          if($this->getValidImage($otherPicIosUrl) == "D"){
              $otherPicIosUrl = $explodedVal[4];
              $otherProfileid = $explodedVal[1];
          }
      }
      else{
          $body = array("PROFILEID"=>$receiver,"DATE"=>date('Y-m-d'));
          $type = "MA_NOTIFICATION";
          $queueData = array('process' =>'MA_NOTIFICATION',
                            'data'=>array('body'=>$body,'type'=>$type),'redeliveryCount'=>0
                          );
          $producerObj = new JsNotificationProduce();
          $producerObj->sendMessage($queueData);
      }
      $cacheVal = $count.$seperator.$otherProfileid.$seperator.$otherPicUrl.$seperator.$receiverLastLoginDate.$seperator.$otherPicIosUrl;
      $cacheTimeout = MessageQueues::$scheduledNotificationDelayMappingArr["MatchAlertNotification"]*MessageQueues::$notificationDelayMultiplier*12;
      $monitoringKey = "MA_N_".date('Y-m-d');
      if(!JsMemcache::getInstance()->get($monitoringKey)){
          JsMemcache::getInstance()->set($monitoringKey,date('Y-m-d H:i:s'),79200);
      }
      JsMemcache::getInstance()->set($cacheKey,$cacheVal,$cacheTimeout);
  }
  
  public function getValidImage($url){
    $photo = "D";
    if(! (strstr($url, '_vis_') || strstr($url, 'photocomming') || strstr($url, 'filtered') || strstr($url, 'request') || strstr($url, 'photo_coming')) )
        $photo = $url;
    return $photo;
  }

}
