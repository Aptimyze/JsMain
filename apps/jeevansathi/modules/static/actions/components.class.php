<?php
class staticComponents extends sfComponents{
    public function executeCallHelp($request){
        if(($_COOKIE['close_help']==1) || (sfConfig::get('mod_'.$this->getContext()->getModuleName().'_'.$this->getContext()->getActionName().'_enable_call_help')=='off'))
            return sfView::NONE;
        $loginData = $request->getAttribute('loginData');
        $login = $request->getAttribute('login')?1:0;
        $subscription = $loginData['SUBSCRIPTION'];
        $profileid = $loginData['PROFILEID'];
        $paid = 0;
        if(strstr($subscription,"F,D") || strstr($subscription,"D,F") || strstr($subscription,"D") || strstr($subscription,"F"))
            $paid=1;

        $editPage=0;
        if(strstr($request->getParameter('profilechecksum'),$loginData['PROFILEID'])  || $request->getParameter('ownview')==1)
            $editPage=1;
        if($_COOKIE['IS_NRI'])
        {
            $this->IS_NRI=1;
        }
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/MSIE\ 6\.0/i',$u_agent))
        {
            $ub = True;
        }
        $ie_any=false;
        if(preg_match('/MSIE/i',$u_agent))
        {
            $ie_any = true;
        }

        $MESSAGE['/index.php'][0][0]="Need Help to register ?";

        $MESSAGE['/profile/mainmenu.php'][1][0]="Need Help to<br> contact members ?";
        $MESSAGE['/profile/mainmenu.php'][1][1]="Need Help to<br> contact members ?";

        //$MESSAGE['HOME'][1][0]="Find the perfect Jeevansathi. Need Help to register ?";

        $MESSAGE['/search/perform'][0][0]="Find the perfect Jeevansathi.<BR>Need Help to register ?";
        $MESSAGE['/search/perform'][1][0]="Find the perfect Jeevansathi.<br>For Search Tips,";
        $MESSAGE['/search/perform'][1][1]="Find the perfect Jeevansathi.<br>For Search Tips,";

        //Detailed profile page.
        if($editPage==0)
        {
            $MESSAGE['/profile/viewprofile.php'][0][0]="To contact this member";

            $MESSAGE['/profile/viewprofile.php'][1][0]="To contact this member,";
        }
        else
            $MESSAGE['/profile/viewprofile.php'][1][0]="For tips to get more <br>responses on your profile";


        $MESSAGE['/profile/contacts_made_received.php'][1][0]="Need Help to<br>contact members ?";
        $MESSAGE['/symfony_index.php/membership/jspc'][0][0]="Need help on membership options ?";

        $MESSAGE['/symfony_index.php/membership/jspc'][1][0]="Need help on membership options ?";

        $MESSAGE['/profile/payment.php'][0][0]="Need help on <br>payment options ?";
        $MESSAGE['/profile/payment.php'][1][0]="Need help on <br>payment options ?";

        if($_SERVER['REQUEST_URI']=="/membership/jspc"||$_SERVER['REQUEST_URI']=="/membership/jspc")
        {
            $MESSAGE['OTHER'][1][0]="Need Help on membership options ?";
            $MESSAGE['OTHER'][0][0]="Need help on membership options ?";
        }
        else if($_SERVER['REQUEST_URI']=="/membership/jspc")
        {
            $MESSAGE['OTHER'][1][0]="Need Help on payment options ?";
            $MESSAGE['OTHER'][0][0]="Need help on payment options ?";
        }
        else
        {
            $MESSAGE["OTHER"][0][0]="Need Help ?";
            $MESSAGE["OTHER"][1][0]="Need Help ?";
            $MESSAGE["OTHER"][1][1]="Need Help ?";
        }

        $scriptname=$_SERVER['PHP_SELF'];

        $scriptname=str_replace("/P/","/profile/",$scriptname);
        if($MESSAGE[$scriptname])
        {
            $mes=$MESSAGE[$scriptname][$login][$paid];
        }
        else
            $mes=$MESSAGE["OTHER"][0][0];
        if($mes)
        {
            $this->CALL_HELP_MES=$mes;
            $this->is_ie=$ub;
            $this->ie_any=$ie_any;
        }
        else
        {
            return sfView::NONE;
        }
    }
    public function executeMobleftslider($request){
        $this->getProfileObj();
        $memObj=new JMembership();
        list($userType,$expiryDate,$memStatus)=$memObj->getMemUserType(LoggedInProfile::getInstance()->getPROFILEID());
        $contactsLeft = $memObj->getRemainingContactsForUser(LoggedInProfile::getInstance()->getPROFILEID());
        if($userType == memUserType::FREE || $userType == memUserType::ONLY_VAS || $userType == memUserType::EXPIRED_BEYOND_LIMIT)
            $this->memStat = 'F';
        else if($userType == memUserType::EXPIRED_WITHIN_LIMIT || $userType == memUserType::PAID_WITHIN_RENEW  || $contactsLeft == 0)
            $this->memStat = 'R';
        else
            $this->memStat = 'P';
    }
    public function executeMobrightslider($request){
        $this->getProfileObj();
        if(!$this->loggedIn)
            return sfView::NONE;
    }
    public function executeMobheader($request){
        $this->getProfileObj();
        if($this->loggedIn)
        {
            $this->memcacheCountNew=$this->profileMemcacheObj->get("AWAITING_RESPONSE_NEW")+ $this->profileMemcacheObj->get("ACC_ME_NEW")+ $this->profileMemcacheObj->get("MESSAGE_NEW")+$this->profileMemcacheObj->get("PHOTO_REQUEST_NEW")+ $this->profileMemcacheObj->get("HOROSCOPE_NEW") +$this->profileMemcacheObj->get("MATCHALERT");
            $this->memcacheCountTotal=$this->memcacheCountNew+$this->profileMemcacheObj->get("AWAITING_RESPONSE")+$this->profileMemcacheObj->get("ACC_ME")+$this->profileMemcacheObj->get("MESSAGE")+$this->profileMemcacheObj->get("PHOTO_REQUEST")+$this->profileMemcacheObj->get("HOROSCOPE");
        }
    }
    public function executeMobfooter($request){

    }

    public function executeNewMobileSiteHamburger($request)
    {

        if($request->getParameter('blockOldConnection500'))
            $this->loggedIn=0;
        else
            $this->getProfileObj();
        $this->translateURL = JsConstants::$hindiTranslateURL;
        if($this->loggedIn)
        {
            $this->loginProfile=LoggedInProfile::getInstance();
            $this->loginProfile->getDetail($this->loginProfile->getPROFILEID(),"PROFILEID","*");
            $justJoinedMemcacheCount=$this->profileMemcacheObj->get('JUST_JOINED_MATCHES');
            $this->justJoinedCount=$justJoinedMemcacheCount;
            $this->mtongue = $this->loginProfile->getMTONGUE();

            $this->profilePic = $this->loginProfile->getHAVEPHOTO();
            if (empty($this->profilePic))
                $this->profilePic="N";
            if($this->profilePic!="N"){
                $pictureServiceObj=new PictureService($this->loginProfile);
                $profilePicObj = $pictureServiceObj->getProfilePic();
                if($profilePicObj){
                    if($this->profilePic=='U')
                        $picUrl = $profilePicObj->getThumbail96Url();
                    else
                        $picUrl = $profilePicObj->getProfilePic120Url();
                    $photoArray = PictureFunctions::mapUrlToMessageInfoArr($picUrl,'ThumbailUrl','',$this->gender);
                    $this->ProfilePicUrl = $photoArray['url'];
                }

            }
            else
            {
                if($this->loginProfile->getGENDER()=="F")
                    $this->ProfilePicUrl="/images/jsms/commonImg/3_4_NoFemalePhoto.jpg";
                else
                    $this->ProfilePicUrl="/images/jsms/commonImg/3_4_NoMalePhoto.jpg";
            }
            $memHandlerObj = new MembershipHandler();
            $data2 = $memHandlerObj->fetchHamburgerMessage($request);
            $this->MembershipMessage = $data2['hamburger_message'];
            $request->setParameter("perform","count");
            ob_start();
            $jsonData = sfContext::getInstance()->getController()->getPresentationFor("search", "saveSearchCallV1");
            $output = ob_get_contents();
            ob_end_clean();
            $savedSearchCountData = json_decode($output,true);
            $this->savedSearchCount = $savedSearchCountData['saveDetails']['count'];
        }
    }

    private function getProfileObj()
    {
        $this->pObj = LoggedInProfile::getInstance();
        $this->loggedIn=1;
        if(($this->pObj->getPROFILEID()))
        {
            $this->profileMemcacheObj=new ProfileMemcacheService($this->pObj);
        }
        else
            $this->loggedIn=0;
    }
}
?>
