<?php
/* This task is used to send success story mailer
 *@author : Ayush Chauhan
 *created on : 31 July 2017
 */

class UploadSuccessStoryMailTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','jeevansathi'),
        ));

        $this->namespace        = 'mailer';
        $this->name             = 'uploadSuccessStoryMail';
        $this->briefDescription = 'Upload Success Story Email';
        $this->detailedDescription = <<<EOF
		The [uploadSuccessStoryMail|INFO] task does things.
		Call it with:
		[php symfony mailer:uploadSuccessStoryMail|INFO]
EOF;
    }

    /**
     * Executes the current task.
     *
     * @param array $arguments An array of arguments
     * @param array $options An array of options
     *
     * @return integer 0 if everything went fine, or an error code
     */
    protected function execute($arguments = array(), $options = array()) {
        if(!sfContext::hasInstance()){
            sfContext::createInstance($this->configuration);
        }

        $profilesInfo = $this->getProfilesInfo();
        $subject = "Submit your success story and get a free gift!";

        foreach ($profilesInfo as $profileId => $userName) {
            $mailerId = AddStory::getEncryptedMailerId($profileId);
//            $mailerId = "5061310c154ec1696d7b3e3bcf07979a|i|7";
            $mailerId = urlencode($mailerId);
            $top8Mailer = new EmailSender(MailerGroup::TOP8, '1859');
            $tpl = $top8Mailer->setProfileId($profileId);
            $tpl->getSmarty()->assign("userName",$userName);
            if($mailerId)
                $tpl->getSmarty()->assign("mailerId",$mailerId);
            else
                continue;
            $tpl->getSmarty()->assign("fromMailer","true");
            $tpl->setSubject($subject);
            $jprofile = new JPROFILE();
            $row = $jprofile->get($profileId, "PROFILEID", "EMAIL");
            if($mailerId)
                $top8Mailer->send($row["EMAIL"]);
        }
    }

    protected function getProfilesInfo(){
        $profileDelReasonObj = new NEWJS_PROFILE_DEL_REASON();
        $dates[] = date('Y-m-d',strtotime(' -1 day'));
        $dates[] = date('Y-m-d',strtotime(' -7 days'));
        $dates[] = date('Y-m-d',strtotime(' -14 days'));
        $dates[] = date('Y-m-d',strtotime(' -30 days'));

        $profilesDeleted = array();

        foreach ($dates as $index => $date) {
            $tempRes = $profileDelReasonObj->getProfilesForSuccesStory($date);
            if(is_array($tempRes))
                $profilesDeleted+= $tempRes;
        }
        $result = array();
        if(!empty($profilesDeleted)){
            $successStoriesObj = new NEWJS_SUCCESS_STORIES();
            $successStoriesUploaded = $successStoriesObj->checkIfSuccessStoriesUploaded($profilesDeleted);
            if(is_array($successStoriesUploaded))
                $result = array_diff($profilesDeleted,$successStoriesUploaded);
            else
                $result = $profilesDeleted;
        }
        return $result;
    }
}

?>