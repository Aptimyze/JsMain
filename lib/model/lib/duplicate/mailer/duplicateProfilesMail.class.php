<?php

/* 
 * class to fire email based on profiles that are duplicate
 */

class duplicateProfilesMail 
{
  /*
   * this function fires email to the users email id
   * @param : $profileId profileid to which email has to be sent
   */
  public static function sendEmailToDuplicateProfiles($profileId) {
    $duplicateMailer=new EmailSender(MailerGroup::DUPLICATE_PROFILES,1791);
    $emailTpl=$duplicateMailer->setProfileId($profileId); 
    $smartyObj = $emailTpl->getSmarty();
    //get username for this profile id
    $jProfileObj= JPROFILE::getInstance();
    $uName= $jProfileObj->getUsername($profileId);
    $smartyObj->assign("user_name",$uName);
    $smartyObj->assign("profileid",$profileId);
    $duplicateMailer->send();
  }
}
