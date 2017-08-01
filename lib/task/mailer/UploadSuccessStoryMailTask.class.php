<?php
/* This task is used to send success story mailer
 *@author : Ayush Chauhan
 *created on : 31 July 2017
 */

class UploadSuccessStoryMailTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
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

        foreach ($profilesInfo as $key => $value) {
            $top8Mailer = new EmailSender(MailerGroup::TOP8, '1859');
            $tpl = $top8Mailer->setProfileId($key);
            $tpl->getSmarty()->assign("userName",$value);
            $tpl->getSmarty()->assign("SITE_URL","trunk.jeev.com");
            $tpl->setSubject($subject);
            $top8Mailer->send();
        }
    }

    protected function getProfilesInfo(){
        $profileDelReasonObj = new NEWJS_PROFILE_DEL_REASON();
        $dates[] = date('Y-m-d',strtotime(' -1 day'));
        $dates[] = date('Y-m-d',strtotime(' -7 days'));
        $dates[] = date('Y-m-d',strtotime(' -14 days'));
        $dates[] = date('Y-m-d',strtotime(' -30 days'));

        $profilesDeleted = array();

        foreach ($dates as $key => $value) {
            $tempRes = $profileDelReasonObj->getProfilesForSuccesStory($value);
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
        print_r($result);
        return $result;
    }
}

?>