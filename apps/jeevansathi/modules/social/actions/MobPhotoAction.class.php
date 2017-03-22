<?php

/**
 * MobPhotoAction
 *
 * @package     : JSMS   
 * @subpackage  : Social
 * @author      : Akash Kumar  
 */
class MobPhotoAction extends sfActions {

        public function execute($request) { 
                $request->setParameter('sourceMobilePhotoAction','1'); // to identify app vs mobile
                if ($request->getParameter("perform") == "mobUploadPhoto") {
                        /* capturing api */
                        ob_start();
                        $mobileLoginObj= new MobileCommon();
                        if(!$mobileLoginObj->isLogin()){
                                echo "LOGOUT";
                                die;
                        }
                        
                        $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                        $profileId = $loggedInProfileObj->getPROFILEID();
						$trackingObj = new pictureUploadTracking();
						$trackingObj->InsertPageTrack($profileId,"action","UPLOAD_PAGE");
                        
                        $request->setParameter('actionName','uploadPhoto');
                        $request->setParameter('moduleName','social');
                        sfContext::getInstance()->getController()->getPresentationFor('social', 'SelfPhotoFunctionalityV1');
                        $uploadPhotoResponse = ob_get_contents();
                        ob_end_clean();
                        /* capturing api */

                        print_r($uploadPhotoResponse);
                        die;
                        $this->setTemplate("");
                }
                if ($request->getParameter("perform") == "mobDeletePhoto") {
                        /* capturing api */
                        ob_start();
                        $mobileLoginObj= new MobileCommon();
                        if(!$mobileLoginObj->isLogin()){
                                echo "LOGOUT";
                                die;
                        }
                        $request->setParameter('actionName','deletePhoto');
                        $request->setParameter('moduleName','social');
                        $request->setParameter('userType','newMobile');
                        sfContext::getInstance()->getController()->getPresentationFor('social', 'SelfPhotoFunctionalityV1');
                        $deletePhotoResponse = ob_get_contents();
                        ob_end_clean();
                        /* capturing api */

                        print_r($deletePhotoResponse);
                        die;
                        $this->setTemplate("");
                }
                if ($request->getParameter("perform") == "mobSetProfilePic") {
                        /* capturing api */
                        ob_start();
                        $request->setParameter('actionName','setProfilePhoto');
                        $request->setParameter('moduleName','social');
                        sfContext::getInstance()->getController()->getPresentationFor('social', 'SelfPhotoFunctionalityV1');
                        $setProfilePhotoResponse = ob_get_contents();
                        ob_end_clean();
                        /* capturing api */

                        print_r($setProfilePhotoResponse);
                        die;
                        $this->setTemplate("");
                }

                $app = MobileCommon::isApp();
                if(!$app){
                        if(MobileCommon::isDesktop()){
                                $app = "D";
                        }elseif(MobileCommon::isNewMobileSite()){
                                $app = "J";
                        }else{
                                $app = "O";
                        }
                }
                $searchKey .= $app."_";
                if(php_sapi_name() === 'cli'){
                        $searchKey .= "CLI_";
                }
                file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/mob.txt",var_export($_SERVER,true)."\napp:".$searchKey."\nperform:".$request->getParameter("perform")."\n\n\n",FILE_APPEND);
		die;
        }
        

}
