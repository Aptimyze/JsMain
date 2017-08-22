<?php

/**
  This task is used to send EOi Similar profiles mailer
 * @author : Ankit Shukla
 * created on : 22 August 2017
 */
class EoiSimilarProfilesMailerTask extends sfBaseTask {

    private $smarty;
    private $mailerName = "EOI_SIMILAR_PROFILES";
    private $limit = 1000;

    protected function configure() {
        $this->namespace = 'mailer';
        $this->name = 'EoiSimilarProfilesMailer';
        $this->briefDescription = 'Eoi SimilarProfiles mailer';
        $this->detailedDescription = <<<EOF
      The task send Eoi Similar Profiles Mailer.
      Call it with:

      [php symfony mailer:EoiSimilarProfilesMailer totalScript currentScript] 
EOF;
        $this->addArguments(array(
          new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
          new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
        ));
        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
        ));
    }

    protected function execute($arguments = array(), $options = array()) {
        if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        $totalScript = $arguments["totalScript"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
        $LockingService = new LockingService;
        $file = $this->mailerName . "_" . $totalScript . "_" . $currentScript . ".txt";
        $lock = $LockingService->getFileLock($file, 1);
        if (!$lock)
            successfullDie();
        $mailerServiceObj = new MailerService();
        // match alert configurations
        
        $receivers = $mailerServiceObj->getMailerReceiversViewSimilarProfilesMailer($totalScript, $currentScript, $this->limit, $fields);
        $clicksource = "matchalert1";
        $this->smarty = $mailerServiceObj->getMailerSmarty();

        $countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
        $instanceId = $countObj->getID('MATCHALERT_MAILER');

        $this->smarty->assign('instanceID', $instanceId);
        if (is_array($receivers)) {
            $mailerLinks = $mailerServiceObj->getLinks();
            $this->smarty->assign('mailerLinks', $mailerLinks);
            $this->smarty->assign('mailerName', MAILER_COMMON_ENUM::getSenderEnum($this->mailerName)["SENDER"]);
            $widgetArray = Array("autoLogin" => true, "nameFlag" => true, "dppFlag" => false, "membershipFlag" => true, "openTrackingFlag" => true, "filterGenderFlag" => true, "sortPhotoFlag" => false, "logicLevelFlag" => false, "googleAppTrackingFlag" => false, "primaryMailGifFlag" => true, "alternateEmailSend" => true, "sortSubscriptionFlag" => true);

            foreach ($receivers as $sno => $values) {
                $pid = $values["PROFILEID"];
                $sno = $values["SNO"];
                $noOfEois = $values['INTERESTS_SENT'];
                $typeOfEois = $values['TYPE'];
                $data = $mailerServiceObj->getRecieverDetails($pid, $values, $this->mailerName, $widgetArray);

                if (is_array($data)) {
                    if($typeOfEois == 'A')
                        $stypeMatch = SearchTypesEnums::EOI_SIMILAR_PROFILES_MAIL_ACCEPTED;
                    else
                        $stypeMatch = SearchTypesEnums::EOI_SIMILAR_PROFILES_MAIL_OTHERS;
                    //Common Parameters required in mailer links
                    $data["stypeMatch"] = $stypeMatch . "&clicksource=" . $clicksource;
                    $subjectAndBody = $this->getSubjectAndBody($data["COUNT"],$noOfEois);
                    $data["body"] = $subjectAndBody["body"];
                    $data["surveyLink"] = $subjectAndBody["surveyLink"];
                    $data["mailSentDate"] = date("Y-m-d H:i:s");
                    $subject = '=?UTF-8?B?' . base64_encode($subjectAndBody["subject"]) . '?=';
                    $this->smarty->assign('data', $data);
                    $msg = $this->smarty->fetch(MAILER_COMMON_ENUM::getTemplate($this->mailerName) . ".tpl");
                    $flag = $mailerServiceObj->sendAndVerifyMail($data["RECEIVER"]["EMAILID"], $msg, $subject, $this->mailerName, $pid, $data["RECEIVER"]["ALTERNATEEMAILID"]);
                    
                } else
                    $flag = "I"; // Invalid users given in database
                $mailerServiceObj->updateSentForSimilarProfilesMailUsers($sno, $flag);
                unset($subject);
                unset($mailSent);
                unset($data);
            }
        }
    }

    /**
      This function is to get subject of the mail required as per business
     * @param $name : name of the receiver of the mail
     * @param $count : number of users sent in mail
     * @param $logic : Logic used
     * @param $profileId : Receiver profile Id
     * @return $subject : subject of the mail
     */
    protected function getSubjectAndBody($totalProfiles,$senderCount) {
        $subject = array();
        
        $matchStr = " interests";
        if ($count == 1) {
            $matchStr = " interest";
        }
        $dateStr = '';
        $subject["subject"] = "You sent $senderCount $matchStr last week, now connect with these profiles similar to them .";
        $subject["body"] = "You had sent $senderCount $matchStr last week. You may now Send Interest to these $totalProfiles similar profiles:";
        $subject["surveyLink"] = 'NT';
        return $subject;
    }

}
