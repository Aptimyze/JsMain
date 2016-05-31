<?php
/*
 * this class is used to find dpp of user and send dpp mail to users
 * @author - ankit shukla
 */
class dppReviewMailerFunctions{
    CONST MAIL_ID = "1821";
    /*
     * this functions is used to calculate dpp values and return dpp arr
     * @params - profileid of user
     * $return - array containing dpp values
     */
    public static function getDppForProfile($profileid) {
            $dppKeys = array('LAGE','HAGE','LHEIGHT','HHEIGHT','PARTNER_MTONGUE','PARTNER_RELIGION','PARTNER_CASTE','PARTNER_COUNTRYRES','PARTNER_CITYRES','PARTNER_ELEVEL_NEW','PARTNER_OCC');
            $moreHtml = '...<a href="(LINK)MY_DPP:profileid='.$profileid.'(/LINK)" target="_blank" style="text-decoration:none; color:#14428e;">more</a>';
            $loginProfile = LoggedInProfile::getInstance('',$profileid);
            $loginProfile->getDetail('','','RELIGION');
            $jpartnerObj=new JPartnerDecorated();
            $mysqlObj=new Mysql;
            $myDbName=getProfileDatabaseConnectionName($profileid,'slave',$mysqlObj);
            $myDb=$mysqlObj->connect("$myDbName");
            $jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
            $loginProfile->setJpartner($jpartnerObj);
            $dppArr['casteSectLabel'] = CommonUtility::getCasteOrSectToBeUsed($loginProfile->getRELIGION());
            $jpartnerObj = $loginProfile->getJpartner($jpartnerObj);
            
            //create array
            foreach($dppKeys as $key){
                $functionKeys = "getDecorated".$key;
                $dppArr[$key] = $jpartnerObj->$functionKeys();                
                //add more link to text
                if(strlen($dppArr[$key]) > 56)
                    $dppArr[$key] = substr($dppArr[$key],0,56).$moreHtml;
            }
            
            //income logic
            $l_income = $jpartnerObj->getDecoratedLINCOME();
            $h_income = $jpartnerObj->getDecoratedHINCOME();
            $l_income_dol = $jpartnerObj->getDecoratedLINCOME_DOL();
            $h_income_dol = $jpartnerObj->getDecoratedHINCOME_DOL();
            if($l_income && $l_income!="-" && $h_income && $h_income!='-'){
               $incomeStringRs = $l_income." to ".$h_income;
            }
            if($l_income_dol && $l_income_dol!='-' && $h_income_dol && $h_income_dol!='-'){
               $incomeStringDol = $l_income_dol." to ".$h_income_dol;
            }
            if($incomeStringRs){
                $dppArr['income'] = $incomeStringRs;
                if($incomeStringDol){
                     $dppArr['income'] = $dppArr['income'].", ".$incomeStringDol;
                }
            }
            else
                $dppArr['income'] = $incomeStringDol;
            
            return $dppArr;
            
    }
    
    /*
     * this functions is used to send mail to users
     * @params - profileid of user
     * $return - null
     */
    public static function sendDppReviewMail($value,$instanceId){
            $dppUrl = JsConstants::$siteUrl.'/profile/dpp';
            
            $email_sender = new EmailSender(MailerGroup::DPP_REVIEW, self::MAIL_ID);
            $emailTpl = $email_sender->setProfileId($value);
            $smartyObj = $emailTpl->getSmarty();
            
            //get username for this profile id
            $jProfileObj= JPROFILE::getInstance();
            $uName= $jProfileObj->getUsername($value);
            $smartyObj->assign("username",$uName);
            
            //fetch DPP arr for use in template
            $dppArr = self::getDppForProfile($value);
            
            $smartyObj->assign("dppArr",$dppArr);
            $smartyObj->assign("dppUrl",$dppUrl);
            $smartyObj->assign('instanceID',$instanceId);
            
            //send email
            $email_sender->send();
            
            //get sent status for mail
            $sentStatus = $email_sender->getEmailDeliveryStatus();
            
            //insert entry in mailer log
            $dppMailerLogObj = new PROFILE_DPP_REVIEW_MAILER_LOG();
            $dppMailerLogObj->insertDppReviewMailerEntry($value,$sentStatus,date('Y-m-d'));
        }
     
    
    
}
