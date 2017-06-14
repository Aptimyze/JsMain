<?php
/**
* This class will contain the utility functions of mass mailer system.
*/
class MmmUtility
{
	const mmmAppName    = 'masscomm';
	const mmmModuleName = 'mmm';

	/*
	* This function will return the smarty object for mmm module.
	*/
	public static function createSmartyObject()
	{
                $appModuleArr["app"]    = MmmUtility::mmmAppName;
                $appModuleArr["module"] = MmmUtility::mmmModuleName;
		$appModuleArr["useUploads"] = 1;
                $smarty = JsCommon::getSmartySettings('appModule',$appModuleArr);
		return $smarty;
	}


	/**
	* This function is used to write mail into disk based on maileid and header/footer will be added based on site .....
	* @param urlMail url of the mail.
	* @param mailerId mailerid.
	* @param site examples J/9.
	*/
        public static function writeMmmMail($urlMail,$mailerId,$site)
        {
                if(!$urlMail || !$mailerId || !$site)
			throw new jsException("","compulsory field missing in writeMmmMail() of MmmUtility.class.php");

		$smarty = MmmUtility::createSmartyObject();
		$path =  $smarty->template_dir."individual_mailer_templates";
		$pathOfHtmlFile = $path."/".$mailerId.".html";
		$handle = fopen($pathOfHtmlFile, "w+");
		$MmmMailerBasicInfo = new MmmMailerBasicInfo;
		$mailerBasicInfoArr = $MmmMailerBasicInfo->retreiveMailerInfo($mailerId);
		if($handle)
		{		
			$content = CommonUtility::sendCurlGetRequest($urlMail);
			if($site=='J')	
				$mailerBasicInfoArr['RESPONSE_TYPE']='';

			$response_type=$mailerBasicInfoArr['RESPONSE_TYPE'];
			$content = str_replace("<body>",'<body>'."\n".'~if $showHeaderFooter neq "N"`~include_partial("global/header'.$site.'Mailer",[mailerId=>$mailerId,checksum=>$checksum,echecksum=>$echecksum,browserUrl=>$browserUrl,email=>$email,profileid=>$profileid,name=>$name,phone=>$phone,response_type=>$response_type,isTestMailer=>$isTestMailer,serviceMail=>$serviceMail,isMrc=>$isMrc])`~/if`',$content);
			$content = str_replace("</body>",'~if $showHeaderFooter neq "N"`~include_partial("global/footer'.$site.'Mailer",[promoMail=>$promoMail,browserUrl=>$browserUrl,mailerId=>$mailerId,checksum=>$checksum,profileid=>$profileid,isMrc=>$isMrc,email=>$email])`~/if`'."\n".'</body>',$content);

			fwrite($handle, $content);
			fclose($handle);
		}
		else
			throw new jsException("","unable to write to a file in writeMmmMail() of MmmUtility.class.php");
        }

	/*
	* This function will return the menu to be displayed on left side of the mmm interface.
	* return @arr array
	*/
	public static function getLeftPanelMenu()
	{
                $arr["Create new mailer"] = "createMailer";
                $arr["Form Query(Jeevansathi)"] = "formQueryJs";
                $arr["Form Query(99acres)"] = "formQuery_99";
                $arr["Upload a CSV"] = "csvUpload";
                $arr["Write Mail"] = "writeMail";
                $arr["Set Test Email Id "] = "setTestId";
                $arr["Mail Fire Menu"] = "fireMenu";
                $arr["MIS"] = "mis";
				$arr["MIS-99"] = "mis99";
                $arr["MIS-Generate Csv"] = "createCsv";
                $arr["Get Link For Client MIS"] = "clientMISLink";
                $arr["Logout"] = "logout";
		return $arr;
	}	

	/*
	* This function will return the horizontal menu to be displayed on right side of the mmm interface in the fire mail success tpl.
	* return @arr array
	*/
	public static function getFireMailMenu()
	{
		$arr["test"] = "test";
		$arr["retest"] = "retest";
		$arr["start"] = "start";
		$arr["stop"] = "stop";
		return $arr;
	}


	/**
	* This function will get the mailer name based on mailer-id.
	* mailerId unique id of mailer
	* @return name of mailer template.
	*/
	public static function getTemplateName($mailerId)
	{
		$templateName = $mailerId.".html";
		return $templateName;
	}


	/*
	* This function will return the array of years to be used in displaying MIS.
	* return @year array
	*/
	public static function getYears() 
	{
		$year=array();
		for($i=date("Y");$i>2004;$i--)
			$year[]=$i;
		return $year;
	}

	 /** 
	 * This function will return the months in a year i.e. 12
	 * return month array
	 */
	 public static function getMonths() 
	 {
		 $month=array(1,2,3,4,5,6,7,8,9,10,11,12);
		 return $month;
	 }
	 /** 
	 * This function will return the days in a month i.e. 31
	 * return month array
	 */
	 public static function getDays()
	 {
		 $days=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
		 return $days;
	 }

	/** 
         * This function will return email domain.
         */

	public static function checkEmailDomain($email){
		$googleArray = array('googlemail','gmail');
		$yahooArray = array('yahoo','ymail','rocketmail');
		$hotmailArray = array('hotmail','live');
		$rediffArray = array('rediff');
		foreach($googleArray as $v){
			if(strstr($email,'@'.$v)){
				return 'G';
			}
		}
		foreach($yahooArray as $v)
		{
			if(strstr($email,'@'.$v)){
				return 'Y';
			}
		}
		foreach($hotmailArray as $v){
                        if(strstr($email,'@'.$v)){
                                return 'H';
                        }
                }
                foreach($rediffArray as $v){
                        if(strstr($email,'@'.$v)){
                                return 'R';
                        }
                }
                return 'O';
	}

	public static function updateDomainCount($domain,&$sent_data){
        $date = date("Y-m-d");
        if($domain == 'G') $sent_data[$date]['GMAIL_SENT']++;
        else if($domain == 'Y')  $sent_data[$date]['YAHOO_SENT']++;
        else if($domain == 'H') $sent_data[$date]['HOTMAIL_SENT']++;
        else if($domain == 'R') $sent_data[$date]['REDIFF_SENT']++;
        else  $sent_data[$date]['OTHERS_SENT']++;

    }

}
?>
