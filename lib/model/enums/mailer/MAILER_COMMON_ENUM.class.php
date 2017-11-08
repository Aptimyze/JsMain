<?php
/*
 This class is used to have common enums for different mailers like there sender details, 
 * templates, membership tracking and users feild mapping from database tables
 *@author : Reshu Rajput
 *created on : 20 Jun 2014 
 */

class MAILER_COMMON_ENUM
{
        static public $mailerSenders = array();
	// Fail limit of the mails with mail sent, beyond this a mail will be fired from mailer service class
	static public $MAIL_FAIL_LIMIT = 100;
	static public $template = array();
	static public $membershipTracking = array();	
        static public $userFieldLabel = array();
	static public $googlePlayTracking = array();
	static public $iTunesTracking = array();
	
	/*This function is used to initialize different enums of different mailers
	*/
	static public function init()
        {
		self::$mailerSenders["MATCHALERT"]["SENDER"]="matchalert@jeevansathi.com";
		self::$mailerSenders["MATCHALERT"]["ALIAS"]="Jeevansathi Matches";
		self::$mailerSenders["NEW_MATCHES"]["SENDER"]="matchalert@jeevansathi.com";
                self::$mailerSenders["NEW_MATCHES"]["ALIAS"]="Jeevansathi Matches";
		self::$mailerSenders["VISITORALERT"]["SENDER"]="info@jeevansathi.com";
                self::$mailerSenders["VISITORALERT"]["ALIAS"]="Jeevansathi Info";
                self::$mailerSenders["SAVED_SEARCH"]["SENDER"]="matchalert@jeevansathi.com";
                self::$mailerSenders["SAVED_SEARCH"]["ALIAS"]="Jeevansathi Matches";
                self::$mailerSenders["KUNDLI_ALERTS"]["SENDER"]="matchalert@jeevansathi.com";
                self::$mailerSenders["KUNDLI_ALERTS"]["ALIAS"]="Jeevansathi Matches";
                self::$mailerSenders["CONTACTVIEWERS"]["ALIAS"]="Jeevansathi Alerts";
                self::$mailerSenders["CONTACTVIEWERS"]["SENDER"]="contacts@jeevansathi.com";
                self::$mailerSenders["UPLOADED_PHOTO"]["SENDER"]="contacts@jeevansathi.com";
                self::$mailerSenders["UPLOADED_PHOTO"]["ALIAS"]="Jeevansathi Contacts";
                self::$mailerSenders["FEATURED_PROFILE"]["SENDER"]="membership@jeevansathi.com";
                self::$mailerSenders["FEATURED_PROFILE"]["ALIAS"]="Jeevansathi Membership";
                self::$mailerSenders["ZERO_MATCHALERT"]["SENDER"]="info@jeevansathi.com";
                self::$mailerSenders["ZERO_MATCHALERT"]["ALIAS"]="Jeevansathi Info";
                self::$mailerSenders["PAID_MEMBERS_MAILER"]["SENDER"]="matchalert@jeevansathi.com";
                self::$mailerSenders["PAID_MEMBERS_MAILER"]["ALIAS"]="Jeevansathi Matches";
                 self::$mailerSenders["ADD_PHOTO_MAILER"]["SENDER"]="info@jeevansathi.com";
                self::$mailerSenders["ADD_PHOTO_MAILER"]["ALIAS"]="Jeevansathi Info";
                self::$mailerSenders["EXCLUSIVE_MATCH_MAIL"]["SENDER"]="info@jeevansathi.com";
                self::$mailerSenders["EXCLUSIVE_MATCH_MAIL"]["ALIAS"]="Jeevansathi Info";
                
                self::$mailerSenders["PENDING_INTEREST_MAIL"]["SENDER"]="info@jeevansathi.com";
                self::$mailerSenders["PENDING_INTEREST_MAIL"]["ALIAS"]="Jeevansathi Info";
                
                self::$mailerSenders["EXCLUSIVE_PROPOSAL_MAIL"]["SENDER"]="info@jeevansathi.com";
                self::$mailerSenders["EXCLUSIVE_PROPOSAL_MAIL"]["ALIAS"]="Jeevansathi Info";
                self::$mailerSenders["EOI_SIMILAR_PROFILES"]["SENDER"]="matchalert@jeevansathi.com";
                self::$mailerSenders["EOI_SIMILAR_PROFILES"]["ALIAS"]="Jeevansathi Matches";
                self::$template["MATCHALERT"]="matchalert";
		self::$template["NEW_MATCHES"]="newmatches";
		self::$template["VISITORALERT"]= "visitoralert";
                self::$template["CONTACTVIEWERS"]="contactViewers";
                self::$template["UPLOADED_PHOTO"]="requestedPhotoUploaded";
                self::$template["SAVED_SEARCH"]="savedSearch";
                self::$template["KUNDLI_ALERTS"]="kundliAlert";
                self::$template["FEATURED_PROFILE"]= "featuredProfile";
                self::$template["ZERO_MATCHALERT"]="zeroMatchalertsMail";
                self::$template["PAID_MEMBERS_MAILER"]="paidMembersMail";
                self::$template["ADD_PHOTO_MAILER"]="addPhotoMailer";
                self::$template["EXCLUSIVE_MATCH_MAIL"]="exclusiveMatchMailer";
                
                self::$template["PENDING_INTEREST_MAIL"]="PendingInterestMailer";
                
                self::$template["EXCLUSIVE_PROPOSAL_MAIL"]="exclusiveProposalMailer";
                self::$template["EOI_SIMILAR_PROFILES"]="eoiSimilarProfilesMail";
		self::$membershipTracking["MATCHALERT"]= array('vdGetDiscount'=>'VDMA1','vdPercent'=>'VDMA2','upgrade'=>'MA2','renew'=>'MA2RO','renewPercent'=>'MA2RC');
		self::$membershipTracking["NEW_MATCHES"]= array('vdGetDiscount'=>'VDNP1','vdPercent'=>'VDNP2','upgrade'=>'NP2','renew'=>'NP2RO','renewPercent'=>'NP2RC');
                self::$membershipTracking["VISITORALERT"] = array('vdGetDiscount'=>'VDVA1','vdPercent'=>'VDVA2','upgrade'=>'VA2','renew'=>'VA2R','renewPercent'=>'VA2RC');
                self::$membershipTracking["CONTACTVIEWERS"] = array('vdGetDiscount'=>'VDCV1','vdPercent'=>'VDCV2','upgrade'=>'CD2','renew'=>'CV2R','renewPercent'=>'CV2RC');
		self::$membershipTracking["CONTACTVIEWERS"] = array('vdGetDiscount'=>'VDCV1','vdPercent'=>'VDCV2','upgrade'=>'CD2','renew'=>'CV2R','renewPercent'=>'CV2RC');
                self::$membershipTracking["UPLOADED_PHOTO"] = array('vdGetDiscount'=>'VDUP1','vdPercent'=>'VDUP2','upgrade'=>'UP2','renew'=>'UP2R','renewPercent'=>'UP2RC');
                self::$membershipTracking["SAVED_SEARCH"] = array('vdGetDiscount'=>'VDSS1','vdPercent'=>'VDSS2','upgrade'=>'SS2','renew'=>'SS2R','renewPercent'=>'SS2RC');
                self::$membershipTracking["KUNDLI_ALERTS"] = array('vdGetDiscount'=>'VDKA1','vdPercent'=>'VDKA2','upgrade'=>'KA2','renew'=>'KA2R','renewPercent'=>'KA2RC');
                self::$membershipTracking["PAID_MEMBERS_MAILER"] = array('vdGetDiscount'=>'VDPMM1','vdPercent'=>'VDPMM2','upgrade'=>'PMM2','renew'=>'PMM2R','renewPercent'=>'PMM2RC');
                self::$membershipTracking["ADD_PHOTO_MAILER"] = array('vdGetDiscount'=>'VDAPM1','vdPercent'=>'VDAPM2','upgrade'=>'APM2','renew'=>'APM2R','renewPercent'=>'APM2RC');
                self::$membershipTracking["EXCLUSIVE_MATCH_MAIL"] = array('vdGetDiscount'=>'VDMA1','vdPercent'=>'VDMA2','upgrade'=>'MA2','renew'=>'MA2RO','renewPercent'=>'MA2RC');
                
                self::$membershipTracking["PENDING_INTEREST_MAIL"] = array('vdGetDiscount'=>'VDMA1','vdPercent'=>'VDMA2','upgrade'=>'MA2','renew'=>'MA2RO','renewPercent'=>'MA2RC');
                
                self::$membershipTracking["EXCLUSIVE_PROPOSAL_MAIL"] = array('vdGetDiscount'=>'VDMA1','vdPercent'=>'VDMA2','upgrade'=>'MA2','renew'=>'MA2RO','renewPercent'=>'MA2RC');
                self::$membershipTracking["EOI_SIMILAR_PROFILES"] = array('vdGetDiscount'=>'VDVSP1','vdPercent'=>'VDVSP2','upgrade'=>'VSP2','renew'=>'VSP2RO','renewPercent'=>'VSP2RC');
		self::$userFieldLabel["MATCHALERT"] = "USER";
		self::$userFieldLabel["NEW_MATCHES"] = "USER";
                self::$userFieldLabel["VISITORALERT"] = "VISITOR";
		self::$userFieldLabel["CONTACTVIEWERS"] = "VISITOR";
                self::$userFieldLabel["UPLOADED_PHOTO"] = "USER";
                self::$userFieldLabel["SAVED_SEARCH"] = "USER";
                self::$userFieldLabel["KUNDLI_ALERTS"] = "USER";
                self::$userFieldLabel["PAID_MEMBERS_MAILER"] = "USER";
                self::$userFieldLabel["ADD_PHOTO_MAILER"] = "USER";
                self::$userFieldLabel["EXCLUSIVE_MATCH_MAIL"] = "USER";
                
                self::$userFieldLabel["PENDING_INTEREST_MAIL"] = "USER";
                
            self::$userFieldLabel["EXCLUSIVE_PROPOSAL_MAIL"] = "USER";
                self::$userFieldLabel["EOI_SIMILAR_PROFILES"] = "USER";
		self::$googlePlayTracking["MATCHALERT"] = "utm_content=MatchAlert_M&utm_campaign=JSAA";
                self::$googlePlayTracking["NEW_MATCHES"] = "utm_content=MatchAlert_M&utm_campaign=JSAA";
                self::$googlePlayTracking["VISITORALERT"] = "utm_content=MatchAlert_M&utm_campaign=JSAA";
                self::$googlePlayTracking["CONTACTVIEWERS"] = "utm_content=contactViewers&utm_campaign=JSAA";
                self::$googlePlayTracking["SAVED_SEARCH"] = "utm_content=SavedSearch_M&utm_campaign=JSAA";
                self::$googlePlayTracking["KUNDLI_ALERTS"] = "utm_content=KundliAlert_M&utm_campaign=JSAA";
                self::$googlePlayTracking["PAID_MEMBERS_MAILER"] = "utm_content=paidMembers_M&utm_campaign=JSAA";
                self::$googlePlayTracking["ADD_PHOTO_MAILER"] = "utm_content=AddPhotoMailer_M&utm_campaign=JSAA";
                self::$googlePlayTracking["EXCLUSIVE_MATCH_MAIL"] = "utm_content=EXCLUSIVE_MATCH_MAIL_M&utm_campaign=JSAA";
                
                self::$googlePlayTracking["PENDING_INTEREST_MAIL"] = "utm_content=PENDING_INTEREST_MAIL_M&utm_campaign=JSAA";
                
            self::$googlePlayTracking["EXCLUSIVE_PROPOSAL_MAIL"] = "utm_content=EXCLUSIVE_PROPOSAL_MAIL_M&utm_campaign=JSAA";
                self::$iTunesTracking["MATCHALERT"] = "cc=MatchAlert_M";
                self::$iTunesTracking["NEW_MATCHES"] = "cc=MatchAlert_M";
                self::$iTunesTracking["VISITORALERT"] = "cc=VisitorAlert_M";
                self::$iTunesTracking["CONTACTVIEWERS"] = "cc=contactViewers";
               self::$iTunesTracking["SAVED_SEARCH"] = "cc=SavedSearch_M";
               self::$iTunesTracking["KUNDLI_ALERTS"] = "cc=KundliAlert_M";
               self::$iTunesTracking["PAID_MEMBERS_MAILER"] = "cc=paidMembers_M";
               self::$iTunesTracking["ADD_PHOTO_MAILER"] = "cc=AddPhotoMailer_M";
               self::$iTunesTracking["EXCLUSIVE_MATCH_MAIL"] = "cc=ExclusiveMatchMail_M";
               
               self::$iTunesTracking["PENDING_INTEREST_MAIL"] = "cc=PendingInterestMail_M";
               
                self::$iTunesTracking["EXCLUSIVE_PROPOSAL_MAIL"] = "cc=ExclusiveMatchMail_M";
        }
	
	/* This function is used to get user field label of the given mailer type 
	*@param $type : type of mailer 
	*@return $enum : corresponding user field label value
	*/
	static public function getUserFieldLabel($type)
        {
                self::init();
                if(array_key_exists($type,self::$userFieldLabel))
                        $enum=self::$userFieldLabel[$type];
                else{
                        throw new jsException('',"Invalid Type userFieldLabel Enum is requested in MAILER_COMMON_ENUM.class.php");
                    }
                return $enum;
        }

	/* This function is used to get sender details enum of the given mailer type 
        *@param $type : type of mailer 
        *@return $enum : corresponding sender details value
        */

        static public function getSenderEnum($type)
        {
                self::init();
		if(array_key_exists($type,self::$mailerSenders))
			$enum=self::$mailerSenders[$type];
		else{
			throw new jsException('',"Invalid Type Enum is requested in MAILER_COMMON_ENUM.class.php");
        }

		return $enum;
        }
	
	/* This function is used to get template name of the given mailer type 
        *@param $type : type of mailer 
        *@return $enum : corresponding template value
        */
        static public function getTemplate($type)
        {
                self::init();
		if(array_key_exists($type,self::$template))
                	$enum= self::$template[$type];
		else	
			throw new jsException('',"Invalid type is requested in getTemplate in MAILER_COMMON_ENUM.class.php");
		return $enum;
        }

	/* This function is used to get membership tracking values of the given mailer type 
        *@param $type : type of mailer 
        *@return $enum : corresponding membership tracking value
        */
	static public function getMembershipTracking($type)
        {
                self::init();
                if(array_key_exists($type,self::$membershipTracking))
                        $enum= self::$membershipTracking[$type];
                else  {    
                        throw new jsException('',"Invalid type is requested in getMembershipTracking in MAILER_COMMON_ENUM.class.php");
                    }
                return $enum;
        }
	/* This function is used to get google play tracking values of the given mailer type 
        *@param $type : type of mailer 
        *@return $enum : corresponding google play tracking value
        */
        static public function getGooglePlayTracking($type)
        {
                self::init();
                if(array_key_exists($type,self::$googlePlayTracking))
                        $enum= self::$googlePlayTracking[$type];
                else {
                        throw new jsException('',"Invalid type is requested in getGooglePlayTracking in MAILER_COMMON_ENUM.class.php");
                    }
                return $enum;
        }
        
        /* This function is used to get iTunes tracking values of the given mailer type 
        *@param $type : type of mailer 
        *@return $enum : corresponding iTunes tracking value
        */
        static public function getITunesTracking($type)
        {
                self::init();
                if(array_key_exists($type,self::$iTunesTracking))
                        $enum= self::$iTunesTracking[$type];
                else{
                        throw new jsException('',"Invalid type is requested in getITunesTracking in MAILER_COMMON_ENUM.class.php");
                    }
                return $enum;
        }


}
?>
