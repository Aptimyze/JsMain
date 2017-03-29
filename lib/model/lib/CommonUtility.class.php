<?php
/**
 * @brief This class list the possible search paramters and setters and getters functions.
 * @author Lavesh Rawat
 * @created 2012-07-23
 */
class CommonUtility
{
	/**
	*SaveHit function
	*
	*/
	public static function SaveHit($source,$page)
	{
		if($source!="")
		{
			$dbObj=new MIS_SOURCE;
			$date=date("Y-m-d G:i:s");

			if(!$dbObj->isPresent($source))
			{
				$dbObj=new MIS_UNKNOWN_SOURCE;
				$dbObj->insertRecord($source,$date);
				$source="unknown";
			}
			setcookie("JS_SOURCE",$source,time()+2592000,"/",$domain);

			$ip=FetchClientIP();//Gets ipaddress of user
			if(strstr($ip, ","))
			{
				$ip_new = explode(",",$ip);
				$ip = $ip_new[1];
			}
			if($source!="" && !stristr($_SERVER['HTTP_USER_AGENT'],"Adsbot-Google"))
			{
				$dbObj=new MIS_HITS;
				$dbObj->insertRecord($source,$date,$page,$ip);
			}
		}

	}
	/**
	* @param currentPage
	* @param noOfResults
	* @return $arr array (pages to be shown)
	*/
	public static function pagination($currentPage,$noOfResults,$SearchParametersObj="",$actionPoint='search')
	{
		if($actionPoint=='search')
		{
			$maxPages = 9;
			$bucket = 4;
			$profilesPerPage = SearchCommonFunctions::getProfilesPerPageOnSearch($SearchParametersObj);
		}
		elseif($actionPoint=='ccPC')
		{
			$maxPages = 9;
			$bucket = 4;
			$profilesPerPage = InboxConfig::$ccPCProfilesPerPage;
		}


		if($noOfResults%$profilesPerPage == 0)
			$noOfPages = $noOfResults / $profilesPerPage;
		else
			$noOfPages = intval($noOfResults / $profilesPerPage) + 1;
		$temp = $currentPage;
		while($bucket >= 0 && $temp>0)
		{
			$arr[]=$temp;
			$bucket--;
			$temp--;
		}
		$temp = $maxPages - count($arr);
		$i = $currentPage;
		while($temp)
		{
			if($noOfPages <= $i)
				break;
			$arr[]=$i+1;
			$i++;
			$temp--;
		}
		sort($arr);
		return $arr;
	}

	static public function getBlankValues($objName, $fieldsArr)
	{
		foreach($fieldsArr as $fieldName)
		{
			$functionName = "get".$fieldName;
			if($objName->$functionName() == '')
				$blankFields[] = $fieldName;
		}
		if(is_array($blankFields))
			return implode(",",$blankFields);
	}

        static public function DayDiff($StartDate, $StopDate)
        {
                // converting the dates to epoch and dividing the difference
                // to the approriate days using 86400 seconds for a day
                return (date('U', JSstrToTime($StopDate)) - date('U', JSstrToTime($StartDate))) / 86400; //seconds a day
        }

	/* This function is added by Reshu.

	* It will return date differnce from current date in the format of xx days/weeks/months ago
	*@param : date string to find difference from current date
	*@return : jsDate string with required date
	*/
	static public function ConvertDateDiffToJsFormat($date)
	{
		$dateTime= new DateTime();
                $dateToConvert = $dateTime->setTimestamp(strtotime($date));
		$now = new DateTime;
                $diff = date_diff($now,$dateToConvert);
		if($diff->format('%y') < 1 && $diff->format('%m')>0)
		{
			$jsDate = $diff->format('%m');
			if($diff->format('%m') == 1)
                                $jsDate.=" month ago";
                        else
                                $jsDate.=" months ago";

		}
		elseif($diff->format('%d') < 1)
			$jsDate = "today";
		elseif($diff->format('%d') >= 1 && $diff->format('%d') < 7)
                {
                        //within the week
                        $jsDate = $diff->format('%d');
                        if($diff->format('%d') == 1)
                                $jsDate.=" day ago";
                        else
                                $jsDate.=" days ago";
                }
                elseif($diff->format('%d') >= 7 && $diff->format('%d') < 30)
                {
                        //within a month
                        $week=intval($diff->format('%d')/7);
                        $jsDate = $week;
                        if($week == 1)
                                $jsDate.=" week ago";
                        else
                                $jsDate.=" weeks ago";
                }
		return $jsDate;

	}

	/** This function is added by Reshu Rajput
	* This function returns IP address of the current user
	* @return ipaddr - ip address of current machine
	**/

	public static function getCurrentIP()
	{
		$ipaddr=getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):(getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):getenv("REMOTE_ADDR"));
		if(strstr($ipaddr, ","))
		{
			$ip_new = explode(",",$ip);
			$ipaddr = $ip_new[1];
		}
		return $ipaddr;
	}


	/**
	  * This function returns whether a religion has caste/sect/none of these associated to it
	  * @param - $religion - religion for which the above needs to be done
	  * @return - caste/sect/null
	**/

	public static function getCasteOrSectToBeUsed($religion)
	{
		if(strstr($religion,'2') || strstr($religion,'3'))
			return 'Sect';
		elseif(strstr($religion,'7'))
			return null;
		else
			return 'Caste';
	}


	/**
	  * When a profile is called (in a new tab) from the search page, a static url is called.
	  * This static url is made from a static username. This function returns that static username.
	  * @param - $PROFILEID - profileid of the user
	  * @param - $username - username of the user
	  * @return - static username of the user.
	**/
	public static function statName($PROFILEID,$username)
	{
		$sumProfileid=0;

		for($tempcnt=0;$tempcnt<strlen($PROFILEID);$tempcnt++)
		{
			$sumProfileid=$sumProfileid+$PROFILEID{$tempcnt};      //sum of profileid digits
		}
		$rotator=$sumProfileid%(strlen($PROFILEID));           //sum mod length of profileid rotator

		for($tempcnt=0;$tempcnt<strlen($PROFILEID);$tempcnt++)
		{
			$newpos=($tempcnt+$rotator)%strlen($PROFILEID);
			$rotatedProfileidArr[$newpos]=$PROFILEID{$tempcnt};    //rotated profileid
		}

		ksort($rotatedProfileidArr);

		if(count($rotatedProfileidArr)>1)
			$rotatedProfileid=implode("",$rotatedProfileidArr);
		else
			$rotatedProfileid=$rotatedProfileidArr[0];

		unset($rotatedProfileidArr);
		unset($sumProfileid);

		for($tempcnt=0;$tempcnt<strlen($username);$tempcnt++)
		{
			$asciiChr=ord($username{$tempcnt});

			if($asciiChr>=33 && $asciiChr<=126)
			{
				$stat_uname=$rotatedProfileid.$username{$tempcnt}.$rotator;
				break;
			}
		}
		unset($rotatedProfileid);
		unset($rotator);
		return $stat_uname;
	}

	public static function fetchDrafts($profileid,$decline='N')
	{
		$draftObj=new NEWJS_DRAFTS;
		$drafts=$draftObj->getDrafts($profileid,'N');
		for($i=0;$i<count($drafts);$i++)
			$drafts[$i][MESSAGE]=preg_replace("/\\r\\n|\\n|\\r/","#n#",htmlspecialchars($drafts[$i][MESSAGE],ENT_QUOTES));
		$i++;
		$drafts[$i][MESSAGE]='';
		$drafts[$i][DRAFTID]='WNM';
		$drafts[$i][DRAFTNAME]='Write New Message';



		return $drafts;
	}
	/**
	 * Returns trueif caste is present in following religion
	 * @param religion int
	 * return boolean true/false
	 */
	public static function CasteAllowed($religion)
	{
		if(in_array($religion,array(1,2,3,4,9)))
			return true;
		else
			return false;
	}
	/**
	 * return canonical url of profile
	 */
	public static function CanonicalProfile($profileObj)
	{
		if(CommonUtility::CasteAllowed($profileObj->getRELIGION()))
			$casteAllow=1;
			//Canonical url
			$can_url=$profileObj->getDecoratedCommunity()."-".$profileObj->getDecoratedReligion();
			if($casteAllow)
				$can_url.="-".$profileObj->getDecoratedCaste();
			$can_url=CommonUtility::urlCompatible($can_url);
			$can_url.="-".urlencode(str_replace("-","_____",$profileObj->getUSERNAME()))."-profiles";
			if($profileObj->getGENDER()=="M")
				$can_url="groom-".$can_url;
			else
				$can_url="bride-".$can_url;


			return $can_url;
	}

        /**
        * General Utility function to send post curl request.
        */
        public static function sendCurlPostRequest($urlToHit,$postParams,$timeout='',$headerArr="")
        {
	        if(!$timeout)
		        $timeout = 50000;
/*
$postParams1=str_replace("&wt=phps","",$postParams);
$x = explode("sort=",$postParams1);
$y = explode("desc",$x[1]);
$postParams1.="&fl=*,".$y[0];
echo "<br><br><br>".$urlToHit."?".$postParams1;echo "<br><br>\n\n";
die;
*/
//echo "<br><br><br>".$urlToHit."?".$postParams;echo "<br><br>\n\n";//die;
                $ch = curl_init($urlToHit);
		if($headerArr)
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
		else
                	curl_setopt($ch, CURLOPT_HEADER, 0);
		if($postParams)
	                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($postParams)
                	curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
	        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
	        curl_setopt($ch,CURLOPT_NOSIGNAL,1);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout*10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $output = curl_exec($ch);
	    return $output;
                /*
                header('Content-Type: text/xml');
                echo $api_output;
                curl_close($ch);
                die;
                */
        }

        /**
        * General Utility function to send 'get' curl request.
        */
        public static function sendCurlGetRequest($urlToHit,$timeout='')
        {
	        if(!$timeout)
		        $timeout = 50000;
                $ch = curl_init($urlToHit);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POST, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
	        curl_setopt($ch,CURLOPT_NOSIGNAL,1);
	        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout*10);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $output = curl_exec($ch);
		return $output;
	}

	/**
	 * General Utility function to send 'get/post' curl async request.
	 * @param $url
	 * @param $params
	 * @param string $type
	 */
	public function curl_request_async($url, $params, $type='GET')
	{
		foreach ($params as $key => &$val) {
			if (is_array($val)) $val = implode(',', $val);
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$parts=parse_url($url);

		$fp = fsockopen($parts['host'],
			isset($parts['port'])?$parts['port']:80,
			$errno, $errstr, 30);

		// Data goes in the path for a GET request
		if('GET' == $type) $parts['path'] .= '?'.$post_string;

		$out = "$type ".$parts['path']." HTTP/1.1\r\n";
		$out.= "Host: ".$parts['host']."\r\n";
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out.= "Content-Length: ".strlen($post_string)."\r\n";
		$out.= "Connection: Close\r\n\r\n";
		// Data goes in the request body for a POST request
		if ('POST' == $type && isset($post_string)) $out.= $post_string;

		fwrite($fp, $out);
		fclose($fp);
	}

	/**
	 * Returns url compatible string
	 *
	 */
	public static function urlCompatible($url)
	{
		$url=htmlspecialchars_decode($url,ENT_QUOTES);
        $url = preg_replace("/[&?:;@,!_=\/'\s()]/", "-",$url);
        $url = preg_replace("/-+/", "-",$url);
        $words=explode('-',$url);
        $array_size = count($words);
        $j=1;
        $wordstats[0] = $words[0];
        for ($i=1; $i<=$array_size; $i++)
        {
           if($words[$i] != $words[$i-1])
           $wordstats[($j++)]=$words[$i];
        }
        $url=implode('-',$wordstats);
        $url = rtrim($url, "-");
		//$url=urlencode($url);
		return $url;
	}

	public static function InvalidLimitReached($profileObj)
	{
		$limits=CommonFunction::getContactLimits($profileObj->getSUBSCRIPTION(),$profileObj->getPROFILEID());
		$invalidNumberLimit=$limits[NOT_VALIDNUMBER_LIMIT];
		$overall_cont=CommonUtility::getContactsMadeAfterDuplication($profileObj);
		if($overall_cont>=$invalidNumberLimit)
                	return true;

	}
	public static function getContactsMadeAfterDuplication($profileObj)
	{
		$profileMemcacheServiceObj = new ProfileMemcacheService($profileObj);
		return $profileMemcacheServiceObj->get("CONTACTS_MADE_AFTER_DUP");
	}
	public static function isPaid($subscription)
	{
		 $paid = 0;
		if(strstr($subscription,"F,D") || strstr($subscription,"D,F") || strstr($subscription,"D") || strstr($subscription,"F"))
			$paid=1;
		return $paid;
	}
	public static function isOfflineMember($subscription)
	{
		$offline = false;
		if(strstr($subscription,"T"))
			$offline = true;
		return $offline;
	}
	public static function UploadImageCheck($fname)
	{
		if (isset($_FILES[$fname]) )
		{
			$file = $_FILES[$fname]['tmp_name'];
			$error = false;
			$size = false;
			$fileSize=$_FILES[$fname]['size'];
                        if(!$fileSize)
                                $fileSize=$_FILES['photoupload']['size'];
			if (!is_uploaded_file($file))
			{
				$error = 'File not uploaded via HTTP POST';
			}
			else if ($fileSize > (sfConfig::get("app_max_photo_size")) * 1024 * 1024 )
			{
				$error = 'Please upload only files smaller than '.sfConfig::get("app_max_photo_size").'Mb!';
			}
			else if (!$error && !($size = getimagesize($file)))
			{
				$error = 'FormatError';
			}
			else if (!$error && !in_array($size[2], array(1, 2) ) )
			{
				$error = 'FormatError';
			}
			else
			{
				if(!in_array($size[2],array(1,2,3,4,5,6,7,8)))
					$error="Only jpg, gif, png, swf, psd, bmp, tif photo format allowed";
			}
			return $error;

		}
		return $error;;

	}
	public static function CheckValidEmail($email)
	{
		$email=trim($email);

		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email))
			return false;

		$part=explode("@",$email);

		if(strtolower($part[1])=="jeevansaathi.com")
			return false;
		elseif(strtolower($part[1])=="jeevansathi.com")
			return false;

		return true;
	}
	public static function UploadPic($id,$where,$photoContent)
	{

			$site_url=sfConfig::get("app_site_url");
			$web_dir=sfConfig::get("sf_web_dir");
			$symRoot=
			$filepath=$web_dir."/uploads/$where/story/$id.jpg";
			$fileAbs=$site_url."/uploads/$where/story/$id.jpg";

			//  echo $filepath;die;
			$file = fopen($filepath,"w");
				//var_dump($file);die;
			//$photoContent="";
			fwrite($file,$photoContent);
			fclose($file);
			return $fileAbs;
	}
	/**
	* This function will remove quotes
	*/
	public static function removeQuotes($input)
	{
		$remove[] = "'";
		$remove[] = '"';
		$out = str_replace( $remove, "", $input );
		return $out;
	}


	/**
	* This function check if url exists with no redirection using curl
	* @param url to be checked
	* @return 'N' if url does not exists.
	*/
	public static function isUrlExistsUsingCurl($url)
	{
		$handle = curl_init($url);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($handle);

		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

		/***Check error code here ***/
		if($httpCode == 200)
		{
			return 'Y';
		}
		curl_close($handle);
		return 'N';
	}

	/**
	* check if indian city
	*/
	public static function isIndia($city)
	{
		$sub2 = substr($city,0,2);
		if(is_numeric($sub2))
			return NULL;
		return true;
	}

	/**
	* check if indian city
	*/
	public function jsmsHttpRef($request)
	{
		$httpRef = $_SERVER["HTTP_REFERER"];
                if(!stristr($_SERVER["HTTP_REFERER"],'searchId'))
                {
                        if(strstr($_SERVER["HTTP_REFERER"],'?'))
                                $httpRef.="&searchId=".$request->getParameter('searchId');
                        else
                                $httpRef.="?searchId=".$request->getParameter('searchId');
                }
        	return $httpRef;
	}

	//This function return number in xx,xx,xxx format
	public static function moneyFormatIndia($num)
	{
		$explrestunits = "" ;
		if(strlen($num)>3)
		{
			$lastthree = substr($num, strlen($num)-3, strlen($num));
			$restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
			$restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
			$expunit = str_split($restunits, 2);
			for($i=0; $i<sizeof($expunit); $i++)
			{
				// creates each of the 2's group and adds a comma to the end
				if($i==0)
				{
					$explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
				}
				else
				{
					$explrestunits .= $expunit[$i].",";
				}
			}
			$thecash = $explrestunits.$lastthree;
		}
		else
		{
			$thecash = $num;
		}
		return $thecash; // writes the final format where $currency is the currency symbol.
	}

	/**
	* Find the Differnce between two date
	* @return int no.of days
	*/
	public static function dateDiff($startDate,$endDate)
	{
		$datetime1 = new DateTime($startDate);
		$datetime2 = new DateTime($endDate);
		$interval = $datetime1->diff($datetime2);
		return $interval->format('%R%a');
	}
	/**
         * This function converts the $date date variable into IST timezone and on the basis of date difference returns the formatted string
         * @param type $date date
         * @return formatted Date
         */
	public static function convertDateToDay($date){

                /*
                  Comment below two lines and uncomment commented line. For date time change.
                */
                $createDate = new DateTime($date);
                $date = $createDate->format('Y-m-d');
               // $tz = new DateTimeZone("Asia/Calcutta");
                $todayDate = new DateTime("now");
                //$todayDate->setTimezone($tz);
		$actionDate = new DateTime($date);
                //$actionDate->setTimezone($tz);
		$diff = $actionDate->diff($todayDate);
		$daydiff = $diff->days;
		if($daydiff < 1)
		{
			$lastOnlineStr = 'today';
			//$lastOnlineStr .= ' '.$actionDate->format('h:i A');
		}
		else
		{
			$lastOnlineStr= $actionDate->format('d-M-y');
		}
                return $lastOnlineStr;
        }
	public static function convertDateToDayDiff($date)
	{
		$todayDate = new DateTime("now");
		$actionDate = new DateTime($date);
		$diff = $actionDate->diff($todayDate);
		$daydiff = $diff->days;
		if($daydiff < 1)
		{
			if($diff->h < 12)
				$lastOnlineStr= "recently";
			else
				$lastOnlineStr= "today";
		}
		else if ($daydiff == 1)
		{
			$lastOnlineStr= "yesterday";
		}
		elseif($daydiff > 1 && $daydiff < 7)
		{
			//within the week
			$lastOnlineStr= intval($daydiff);
			if(intval($daydiff) == 1)
				$lastOnlineStr.=" day ago";
			else
				$lastOnlineStr.=" days ago";
		}
		elseif($daydiff >= 7 && $daydiff < 30)
		{
			//within a month
			$daydiff/=7;
			$lastOnlineStr= intval($daydiff);
			if(intval($daydiff) == 1)
				$lastOnlineStr.=" week ago";
			else
				$lastOnlineStr.=" weeks ago";
		}
		elseif($daydiff >=30)
		{
			//above 1 months
			$daydiff/=30;
			if(intval($daydiff) == 1)
				$lastOnlineStr="1 month ago";
			elseif(intval($daydiff) == 2)
				$lastOnlineStr="2 months ago";
			else
				$lastOnlineStr="2 months ago";
		}
		return $lastOnlineStr;
	}

	public static function getFreshDeskDetails($profileid=''){
		if(!empty($profileid) && is_numeric($profileid) && $profileid != ''){
			$profileObj = LoggedInProfile::getInstance();
			$userData['USERNAME'] = $profileObj->getUSERNAME();
			$userData['EMAIL'] = $profileObj->getEMAIL();
	        return json_encode(array('username' => $userData['USERNAME'], 'email' => $userData['EMAIL']));
		} else {
			return NULL;
		}
	}

	public static function convertDateTimeToDisplayDate($date)
	{
		$dateTimeObj = new DateTime($date);
		$displayDate = $dateTimeObj->format('d M Y');
		return $displayDate;
	}

	//function to strip off selected tags from content
	public static function strip_selected_tags($text, $tags = array())
    {
    	$args = func_get_args();
        $text = array_shift($args);
        $tags = func_num_args() > 2 ? array_diff($args,array($text))  : (array)$tags;
        foreach ($tags as $tag){
            if(preg_match_all('/<'.$tag.'[^>]*>(.*)/iU', $text, $found))
            {
            	$text = str_replace($found[0],$found[1],$text);
            }
        }
        return $text;
    }
    /*
   * Memcache functionality implemented to avoid user refreshing the page
   */

  public static function avoidPageRefresh($keyPrefix, $name, $interface = '', $skipMemcache, $time = '5') {
    if ($skipMemcache != 1) {
      $key = $keyPrefix."_" . $name . $interface;

      if (JsMemcache::getInstance()->get($key)) {
        JsMemcache::getInstance()->set($key, $name, $time);
        exit("Please refresh after 5 seconds.");
      } else
        JsMemcache::getInstance()->set($key, $name, $time);
    }
  }
        public static function updateProfileCompletionScore($profileid)
        {
                $cScoreObj = ProfileCompletionFactory::getInstance(null,null,$profileid);
                $cScoreObj->updateProfileCompletionScore();
        }

  //send instant sms
  public static function sendInstantSms($SMS_MESSAGE,$PHONE,$PROFILEID,$TYPE="transaction")
  {
  	include_once(JsConstants::$docRoot."/classes/SmsVendorFactory.class.php");
    $smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
    $xmlData = $xmlData . $smsVendorObj->generateXml($PROFILEID,$PHONE,$SMS_MESSAGE);
	if($xmlData){
		$smsVendorObj->send($xmlData,$TYPE);
	}
	unset($xmlData);
  }

  //send instant sms with tracking
  public static function sendPlusTrackInstantSMS($key,$profileid,$tokenArr=null)
  {
  	include_once(sfConfig::get("sf_web_dir")."/P/InstantSMS.php");
	if (!empty($tokenArr) && is_array($tokenArr)) {
		$sms=new InstantSMS($key,$profileid, $tokenArr);
	} else {
		$sms=new InstantSMS($key,$profileid);
	}
	$sms->send();
  }

	function sendCurlDeleteRequest($url,$timeout)
	{
		if(!$timeout)
			$timeout = 10000;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_NOSIGNAL,1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout*10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		$result = curl_exec($ch);
		$result = json_decode($result);
		curl_close($ch);

		return $result;
	}

	/*checkChatPanelCondition
	* check whether to show chat panel or not acc to module
	* @inputs: $loggedIn,$module
	* @return: $showChat
	*/
	public static function checkChatPanelCondition($loggedIn,$module, $action,$activated){
		$chatNotAvailModuleArr = ["membership","register","phone","social","settings"];
        $chatNotAvailActioneArr = ["phoneVerificationPcDisplay","page500","404","dpp","ApiMembershipDetailsV3"];
		$showChat = 1;
		if(!$loggedIn){
			$showChat = 0;
        }
		else if(in_array($module, $chatNotAvailModuleArr) || in_array($action, $chatNotAvailActioneArr) || $activated != 'Y'){
			$showChat = 0;
		}
		return $showChat;
	}

	/*fetchSelfUserName
	* fetch user self name for chat header
	* @inputs: $loggedIn,$loggedInProfile,$module, $action,$showChat
	* @return: $userName
	*/
	public static function fetchSelfUserName($loggedIn,$loggedInProfile,$module, $action,$showChat){
		$excludeModuleArr = ["profile","myjs","homepage"];
        $excludeActionArr = ["edit","jspcPerform"];
        $getName = 1;
        $userName = "";
        if(!$loggedIn || !$loggedInProfile || $showChat == 0){
			$getName = 0;
        }
		else if(in_array($module, $excludeModuleArr) || in_array($action, $excludeActionArr)){
			$getName = 0;
		}
		if($getName){
			$nameOfUserObj = new incentive_NAME_OF_USER("newjs_slave");
			$userName = $nameOfUserObj->getName($loggedInProfile);
		}
		//error_log("ankita-".$getName);
		return $userName;
	}




	public static function webServiceRequestHandler($url,$params="",
	                                                $type="GET",$timeout='',
	                                                $doinvalidate=0)
	{
		//echo $timeout;die;
		if(!$timeout)
			$timeout = 5;
		$result = null;
		if($type=="GET")
		{
			$response = CommonUtility::sendCurlGetRequest($url,$timeout);
		}
		elseif($type=="POST")
		{
			$response = self::sendCurlPostRequest($url,$params,$timeout);
		}
		elseif($type == "DELETE")
		{
			$response = CommonUtility::sendCurlDeleteRequest($url,$timeout);
		}

		$response = json_decode($response,true);
		if($response['_meta']['status'] == "SUCCESS" )
		{
			if($response['_meta']['count'])
			{
				$result = $response['records'];
			}
			else{
				$result = null;
			}
		}
		else
		{
			if(is_array($doinvalidate))
			{
				self::sendtoRabbitMq($doinvalidate[0],1);
				self::sendtoRabbitMq($doinvalidate[1],1);
			}
			$result = false;
		}
		return $result;
	}


	public static function sendtoRabbitMq($profileid,$invalidate=0){
		if(JsConstants::$webServiceFlag == 1)
		{
			if($invalidate == 1)
				$process = "INVALIDATE";
			else
				$process = "CACHE";
			$producerObj=new Producer();
			if($producerObj->getRabbitMQServerConnected())
			{
				$sendCacheData = array('process' =>$process,'data'=>$profileid, 'redeliveryCount'=>0 );
				$producerObj->sendMessage($sendCacheData);
			}
		}
		return;
	}

	public static function validatePhoneNo($phone){
		$regExIndian = "/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/";
        $regExIndianLandline = "/^[0-9]\d{2,4}[-. ]?\d{6,8}$/";
        $regExInternational = "/^\+(?:[0-9][-. ]? ?){7,14}[0-9]$/";
		if (preg_match($regExIndian, $phone) || preg_match($regExInternational, $phone) || preg_match($regExIndianLandline, $phone)) {
			return true;
		} else {
			return false;
		}
	}



	public static function validateEmail($email){
		$regExEmail = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/";
		if (preg_match($regExEmail, $email)) {
			return true;
		} else {
			return false;
		}
	}

	public static function makeTime($date, $format = 'YYYY-MM-DD')
	{
		$value = CommonUtility::datetotime($date, $format);
		$time = mktime(date('H'), date('i'), date('s'), $value["month"], $value["day"], $value["year"]);
		return date("Y-m-d H:i:s", $time);
	}

	public static function datetotime ($date, $format = 'YYYY-MM-DD')
	{
		if ($format == 'YYYY-MM-DD') list($year, $month, $day) = explode('-', $date);
		if ($format == 'YYYY/MM/DD') list($year, $month, $day) = explode('/', $date);
		if ($format == 'YYYY.MM.DD') list($year, $month, $day) = explode('.', $date);

		if ($format == 'DD-MM-YYYY') list($day, $month, $year) = explode('-', $date);
		if ($format == 'DD/MM/YYYY') list($day, $month, $year) = explode('/', $date);
		if ($format == 'DD.MM.YYYY') list($day, $month, $year) = explode('.', $date);

		if ($format == 'MM-DD-YYYY') list($month, $day, $year) = explode('-', $date);
		if ($format == 'MM/DD/YYYY') list($month, $day, $year) = explode('/', $date);
		if ($format == 'MM.DD.YYYY') list($month, $day, $year) = explode('.', $date);

		$result = array("day" => $day, "month" => $month, "year" => $year);
		return $result;
	}

	public static function hideFeaturesForUptime(){
		
		if(in_array(date('H'),array("10","11","12","13")))
		{
			return 1;
		}
		return 0;

	}
	
	/*function to redirect site to appropriate language based on cookie
	* @inputs: $request
	* @return : $redirectUrl
	*/	
	public static function translateSiteLanguage($request){
		$redirectUrl = "";
		$loginData = $request->getAttribute("loginData");
        $authchecksum = $request->getcookie('AUTHCHECKSUM');
       
		if($request->getcookie("jeevansathi_hindi_site_new")=='Y'){
			if($request->getParameter('newRedirect') != 1 && $request->getcookie("redirected_hindi_new")!='Y'){
				@setcookie('redirected_hindi_new', 'Y',time() + 10000000000, "/","jeevansathi.com");
				if(isset($_SERVER["REQUEST_URI"])){
					$newRedirectUrl = JsConstants::$hindiTranslateURL.$_SERVER["REQUEST_URI"];
					if(strpos($newRedirectUrl,"?") != false){
						$newRedirectUrl = $newRedirectUrl."&";
					}
					else{
						$newRedirectUrl = $newRedirectUrl."?";
					}
					$newRedirectUrl = $newRedirectUrl."AUTHCHECKSUM=".$authchecksum."&newRedirect=1";
					return $newRedirectUrl;
				}
				return (JsConstants::$hindiTranslateURL."?AUTHCHECKSUM=".$authchecksum."&newRedirect=1");
			}
            else if($request->getcookie("redirected_hindi_new")=='Y'){
				@setcookie('redirected_hindi_new', 'Y',time() + 10000000000, "/","jeevansathi.com");
                //redirect to hindi site if referer is blank and newRedirect is not set
                if(!isset($_SERVER['HTTP_REFERER']) && $request->getParameter('newRedirect') != 1){
                	$newRedirectUrl = JsConstants::$hindiTranslateURL;
                	if(isset($_SERVER["REQUEST_URI"])){
						$newRedirectUrl = $newRedirectUrl.$_SERVER["REQUEST_URI"];
					}
					if(strpos($newRedirectUrl,"?") != false){
						$newRedirectUrl = $newRedirectUrl."&";
					}
					else{
						$newRedirectUrl = $newRedirectUrl."?";
					}
					$newRedirectUrl = $newRedirectUrl."AUTHCHECKSUM=".$authchecksum."&newRedirect=1";
					return $newRedirectUrl;
                }
            }
		} else {
			if($request->getcookie("redirected_hindi_new")=='Y'){
				@setcookie('redirected_hindi_new', 'N', 0, "/","jeevansathi.com");
				return (JsConstants::$siteUrl.'?AUTHCHECKSUM='.$authchecksum);	
			}
			else{
				@setcookie('redirected_hindi_new', 'N', 0, "/","jeevansathi.com");
			}
		}
		return $redirectUrl;
	}
    public function correctSplitOnBasisDate($arr, $dataIndex){
        if(is_array($arr)){
            $date = $arr[$dataIndex];
            if (DateTime::createFromFormat('Y-m-d G:i:s', $date) !== FALSE) {
                return true;
            }
        }
        return false;
    }
  public static function runFeatureAtNonPeak(){
		
		if(in_array(date('H'),array("17","18","19","20","21")))
		{
			return 1;
		}
		return 0;

	}
}
?>
