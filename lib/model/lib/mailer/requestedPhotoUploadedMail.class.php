<?php
/*
 * this class is used to find dpp of user and send dpp mail to users
 * @author - ankit shukla
 */
class requestedPhotoUploadedMail{
    const mailerName = "UPLOADED_PHOTO";
    /*
     * this functions is used to send mail to users
     * @params - profileid of user
     * $return - null
     */
    public static function sendUploadPhotoMail($profileId,$uploaderId){
            $mailerServiceObj = new MailerService();
            $mailerLinks = $mailerServiceObj->getLinks();
            
            $smarty = $mailerServiceObj->getMailerSmarty();
            $smarty->assign('mailerLinks',$mailerLinks);
            $smarty->assign('mailerName',MAILER_COMMON_ENUM::getSenderEnum(self::mailerName)["SENDER"]);
            $widgetArray = Array("autoLogin"=>true,"nameFlag"=>true,"dppFlag"=>false,"membershipFlag"=>true,"openTrackingFlag"=>false,"filterGenderFlag"=>true,"sortPhotoFlag"=>false,"logicLevelFlag"=>false,"googleAppTrackingFlag"=>false);
            
            $values = array("SNO"=>0,"USER1"=>$uploaderId,"RECEIVER"=>$profileId);
            $data = $mailerServiceObj->getRecieverDetails($profileId,$values,self::mailerName,$widgetArray);
            if(is_array($data))
            {
                $data["stypeMatch"] = "PURM";

                //get username for uploader
                $uploaderUserName= $data[USERS][$uploaderId]->USERNAME;

                $subject= "$uploaderUserName has uploaded photo following your request";
                $data["body"]= "$uploaderUserName has uploaded photo following your request. You may now send an interest to $uploaderUserName, if you haven't already sent one.";
                $subject ='=?UTF-8?B?' . base64_encode($subject) . '?='; 
                $smarty->assign('data',$data);
                //print_r($data);die;

                $msg = $smarty->fetch(MAILER_COMMON_ENUM::getTemplate(self::mailerName).".tpl");
                $flag = $mailerServiceObj->sendAndVerifyMail($data["RECEIVER"]["EMAILID"],$msg,$subject,self::mailerName,$pid);
            }
        }
     
    
    
}
