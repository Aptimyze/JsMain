<?php
class CommonFunction
{
	// function to get logged in profile ip
	public static function getIP()
	{
		$ip = FetchClientIP();
		if(strstr($ip, ","))    
		{                       
			$ip_new = explode(",",$ip);
			$ip = $ip_new[1];
		}
		return $ip;
	}
	
  //Function to get client Ip (ipv4 & ipv6)
	public static function getClientIP()
	{
    $ip = getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):(getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):getenv("REMOTE_ADDR"));
    
    $ip=trim(str_replace(" ","",$ip));
    
    $ip_new = explode(",",$ip);
	
    if(!($ip && $ip_new && inet_pton($ip_new[0]))){
        $ip = "";
    }
    
		if(strstr($ip, ","))    
		{                       
			$ip_new = explode(",",$ip);
			$ip = $ip_new[1];
		}
		return $ip;
	}
	// function to get if ip address is suspected
	public static function suspectedIP($ip)
	{
		return 0;//no longer requried
		//List of suspected ip-address and suspected subnet mask.
		$list_ip=array ('61.246.45.67','196.201.*','81.199.125.*','213.136.*','193.220.188.189','67.184.68.175','216.147.159.*','65.91.140.140','82.193.38.18','196.207.*','82.153.50.*','41.207.*','41.219.*');
																															 
		if(in_array($ip,$list_ip))
		{
			return 1;
		}
		else
		{
			$ip_add=explode(".",$ip);
																														 
			$len=count($list_ip);
																														 
			for($i=0;$i<$len;$i++)
			{
				$ip1=$list_ip[$i];
				$ip_add1=explode(".",$ip1);
																													 
				if($ip_add[0]==$ip_add1[0])
				{
					if($ip_add1[1]=='*')
					{
						return(1);
					}
					elseif($ip_add[1]==$ip_add1[1])
					{
						if($ip_add1[2]=='*')
						{
							return(1);
						}
						elseif($ip_add[2]==$ip_add1[2])
						{
							if($ip_add1[3]=='*' || $ip_add[3]==$ip_add1[3])
							{
								return(1);
							}
						}
					}
				}
			}
		}
		return 0;
	}
	
	public static function getContactLimits($subscription,$profileid)
	{
		$day_limit=100;
		$weekly_limit=100;
		$month_limit=400;
		$overall_limit=400;
		$notValidNumber_limit=100;
		
		if(PremiumMember::isDummyProfile($profileid))
		{
			$day_limit=100000;
			$weekly_limit=700000;
			$month_limit=3000000;
			$overall_limit=3000000;
			$notValidNumber_limit=100000;
		}
		else
		{
			if(CommonFunction::isPaid($subscription))
			{
				$day_limit=200;
				$weekly_limit=400;
				$month_limit=800;
				$overall_limit=800000;
				$notValidNumber_limit=100;		
			}
			else if(CommonFunction::isEverPaid())
			{
				$day_limit=100;
				$weekly_limit=100;
				$month_limit=400;
				$overall_limit=800000;
				$notValidNumber_limit=100;
			}
			if(CommonFunction::isOfflineMember($subscription))
			{
				$day_limit=225;
				$weekly_limit=575;
				$month_limit=1400;
				$overall_limit=800000;
				$notValidNumber_limit=100000;
			}
		}
		$limit['DAY_LIMIT']=$day_limit;
		$limit['WEEKLY_LIMIT']=$weekly_limit;
		$limit['MONTH_LIMIT']=$month_limit;
		$limit['OVERALL_LIMIT']=$overall_limit;
		$limit['NOT_VALIDNUMBER_LIMIT']=$notValidNumber_limit;
		return $limit;		
	}
	public static function isPaid($subscription)
	{
		$paid = 0;
        if(self::getMainMembership($subscription)){
            $paid=1;
        }
		return $paid;
	}

	public static function isEverPaid()
	{
		
		$everPaid = false;
		$billing = new BILLING_PURCHASES();
		$loginProfile = LoggedInProfile::getInstance();
		$pid = $loginProfile->getPROFILEID();
		if(!isset($pid))
		{
			return $everPaid;
		}

		$payment = $billing->isPaidEver($pid);
		if(is_array($payment) && $payment[$pid])
		{
			$everPaid = true;
		}
		return $everPaid;
	}

	public static function isEvalueMember($subscription)
	{
		return (mainMem::EVALUE === self::getMainMembership($subscription));
	}

	public static function isErishtaMember($subscription)
	{
		return (mainMem::ERISHTA === self::getMainMembership($subscription));
	}
    
    public static function isJsExclusiveMember($subscription)
	{
		return (mainMem::JSEXCLUSIVE === self::getMainMembership($subscription));
	}
    
	public static function isEadvantageMember($subscription)
	{
		return (mainMem::EADVANTAGE === self::getMainMembership($subscription));
	}

	public static function isOfflineMember($subscription)
	{
        $offline = false;
        if(strstr($subscription,"T"))
                $offline = true;
        return $offline;
	}
	
		/** @param profile Profile
	 *  @returns true if contact number is verified or false otherwise
	 *  */
	public static function isContactVerified(Profile $profile)
	{
		if($profile->getLANDL_STATUS()=='Y' or $profile->getMOB_STATUS()=='Y' or $profile->getExtendedContacts()->ALT_MOB_STATUS=='Y')
			return true;
		else
			return false;
	}
	
	
	public static function getAllCities($City_Res, $output_param="")
	{
	
		$city_str="";
		$city_str=implode(",",array_keys(FieldMap::getFieldLabel("city_india",'',1)));
		if(is_array($City_Res))
		{
			if(!in_array("All",$City_Res) && !in_array("",$City_Res))
			{
				$insertCity=implode($City_Res,",");

				for($i=0;$i<count($City_Res);$i++)
				{
					if(is_numeric($City_Res[$i]))
						$city_usa[]=$City_Res[$i];
					elseif(strlen($City_Res[$i])==2)
					{
						if($city_str)
						{
							preg_match_all ( "/\,($City_Res[$i].*?)\,/" , $city_str , $matches);
							if(is_array($matches[1]))
								foreach ($matches as $key => $value)
									$city_india[]=$matches[1][$i];
						}
					}
					else
					$city_india[]=$City_Res[$i];
				}
			}
		}
		elseif($City_Res!="" && $City_Res!="All")
		{
			$insertCity=$City_Res;
			if(is_numeric($City_Res))
				$city_usa[]=$City_Res;
			else
			{
				if(strlen($City_Res)==2)
				{
				if($city_str)
				{
					preg_match_all ( "/\,($City_Res[$i].*?)\,/" , $city_str , $matches);
					$city_india=$matches[1];
				}
			}
			else
			$city_india[]=$City_Res;
			}
		}
		if(is_array($city_india))
		{
			foreach($city_india as $key=>$value)
			$city_res_list[]=$value;
		}
		if(is_array($city_usa))
		{
			foreach($city_usa as $key=>$value)
			$city_res_list[]=$value;
		}
		if(!$output_param)
			return $city_res_list;
		
		if(is_array($city_res_list))
		{
			$cityList = explode(",",$city_res_list[0]);
			return $cityList;
		}
		else
			return '';

	}
		
	public static function displayFormat($str)
	{
		if($str)
		{
			$str=trim($str,"'");

			$arr=explode("','",$str);
			return $arr;
		}
		
	}
	
	public static function displayFormatModify($str)
	{
		if($str)
		{
			$str=trim($str,"'");
			$str=str_replace("'","",$str);
			$arr=explode(",",$str);
			return $arr;
		}
		
	}

	//Created by Nikhil

	static public function getProfileFromChecksum($checksum)
	{
		if($checksum)
		{
                    $checksum;
			$profileid=substr($checksum,33,strlen($checksum));
			$temp_check=substr($checksum,0,32);
			$real_check=md5($profileid);
			if($temp_check==$real_check)
				return $profileid;
		}
		return 0;
	}

	/**
	 * returns profilechecksum of given profileid
	 * @param $profileid profileid of user
	 * @return $checksum mixed
	 */
	static public function createChecksumForProfile($profileid)
	{
		$checksum='';
		if($profileid)
		{
			$start_tag="start";
			$end_tag="end";
			$checksum=md5($profileid)."i".$profileid;
			//$checksum=md5($start_tag.$profileid.$end_tag).$profileid;

		}
		return $checksum;
	}
	/**
	 * returns 0 if [message is same as old one] return 1 [limit reached and message is not same], return 2 [message is not same and limit not reached] 
	 * @param $message String
	 * @param $draftArray Array format[0=>id,1=>message,2=>name]
	 * @return [0,1,2]	 
	 */
    public static function checkDraftOverflow($message,$draftArray)
	{
		$message = preg_replace("/[\r\n]+/", "",$message);
		$message=htmlspecialchars_decode(addslashes(stripslashes($message)));
		
		foreach($draftArray as $key=>$val)
        {
        	if($message==$val[1])
        		return 0;
        }
        if(count($draftArray)>=7)
        	return 1;
        	
        return 2;	
        
    }	
    
    
    static public function getJsCenterDetails($profileCity="")
	{
		$dbObj = new NEWJS_CONTACT_MAILERS_NEW;
		$result = $dbObj->getAgentDetails($profileCity);
		if(is_array($result))
		{
			foreach($result as $key=>$row)
			{
				$AgentArr["CITY"] = $row["CITY"];
				$AgentArr["LOCALITY"] = $row["LOCALITY"];
				$AgentArr["AGENT"] = $row["AGENT"];
				$AgentArr["MOBILE"] = $row["MOBILE"];
				$AgentArr["ID"] = $row["ID"];
			}
		}
		unset($dbObj);
		return $AgentArr;
	}

	/*
        This function is used to handle special characters present in the string passed
        @param - string
        @return - string with replaced special chars
        */
        public static function myencode($str)
        {
                if(strstr($str,'&'))
                        return (str_replace('&','&amp;',$str));
                elseif(strstr($str,'<'))
                        return (str_replace('<','&lt;',$str));
                elseif(strstr($str,'>'))
                        return (str_replace('>','&gt;',$str));
                elseif(strstr($str,'"'))
                        return (str_replace('"','&quot;',$str));
                else
                        return $str;
        }

	/*
	This function is used to generate an array having mtongue regions with their value and label and mtongues belonging to each region with their values and labels
	@param - if small labels of mtongue are required then pass 1 in the optional parameter
	@return - required 2 dimensional array
	*/
	public static function generateMtongueDropdownForTemplate($community_small='')
	{
		if($community_small)
			$mtongue_drop = "community_small";
		else
			$mtongue_drop = "community";
			
		foreach(FieldMap::getFieldLabel("mtongue_region_label",1,1) as $k=>$v)
		{
			$output[$k]["LABEL"] = $v;
			$output[$k]["VALUES"] = explode(",",FieldMap::getFieldLabel("mtongue_region_registration",$k));
		}

		foreach($output as $k=>$v)
		{
			foreach($v["VALUES"] as $kk=>$vv)
			{
				$temp[$vv] = str_replace("&amp;","&",FieldMap::getFieldLabel($mtongue_drop,$vv));
			}
			$output[$k]["VALUES"] = $temp;
			unset($temp);
		}	

		return $output;
	}
	public static function educationLevelNewMapping($EDU_ARR) {

		$arr=array();
		foreach($EDU_ARR as $KEY=>$VAL)
		{
			$hidden_val="";
			$shown_val="";
			$hiddenStr="";
			$shownStr="";
			$levelStr="";
			foreach ($VAL as $K=>$V)
			{
				$levelStr.=$V."|#|";
				
				$hidden_val.="<input type=\"checkbox\" value=\"".$V."\" name=\"partner_education_arr[]\" id=\"partner_education_".$V."\"><label id=\"partner_education_label_".$V."\">".$K."</label><br>";
				$shown_val.="<input id=\"partner_education_displaying_".$V."\" class=\"chbx\" type=\"checkbox\" onclick=\"add_checkboxes(this); remove_doesnt_matter_conflict(this);\" value=\"".$V."\" name=\"partner_education_displaying_arr[]\"><label id=\"partner_education_displaying_label_".$V."\">".$K."</label><br>";
			}
			
			if($KEY!="Others")
			$levelStr=trim($levelStr,"|#|");
			
			
			if($KEY=="Others")
			{
				$hiddenStr="<input type=\"checkbox\" name=\"partner_education_arr[]\" id=\"partner_education_".$levelStr."\" value=\"".$levelStr."\"> <label id=\"partner_education_label_".$levelStr."\">&nbsp;</label>";
				$shownStr="<span style=\"color:#0a89fe;\">&nbsp;</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
			}
			else
			{
				$hiddenStr="<input type=\"checkbox\" name=\"partner_education_arr[]\" id=\"partner_education_".$levelStr."\" value=\"".$levelStr."\"> <label id=\"partner_education_label_".$levelStr."\">".$KEY."</label>";
				$shownStr="<span style=\"color:#0a89fe;\">".$KEY."</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
			}
			$finalHiddenStr.=$hiddenStr.$hidden_val;
			$finalShownStr.=$shownStr.$shown_val;
		}
	$arr["hiddenStr"]=$finalHiddenStr;
	$arr["shownStr"]=$finalShownStr;
	return $arr;
	}

	/*
	This function is used to extract the jsadmin user name 
	@param - int
	@return - string 
	*/
	public static function getCrmUserName($cid="")
	{
			$temp=explode("i",$cid);
			$userid=$temp[1];
			/*$dbJsadminConnect= new jsadmin_CONNECT();
			$id=$dbJsadminConnect->fetchUser($userid);

			$dbJsadminPSWRDS= new jsadmin_PSWRDS();
			$userName=$dbJsadminPSWRDS->getName($id);*/
			$backendLibObj = new backendActionsLib(array("jsadmin_PSWRDS"=>"newjs_slave","jsadmin_CONNECT"=>"newjs_master"),crmCommonConfig::$useCrmMemcache);
			$details = $backendLibObj->fetchPSWRDSDetailsBySessionID($userid,"","USERNAME");
			unset($backendLibObj);
			if(is_array($details) && $details)
				$userName = $details[0]["USERNAME"];
			return $userName;
	}
	
	/*
	This function is used to genetae the random number
	@param - int
	@return - string 
	*/
	public static function vpin_gen()
	{
		mt_srand((double) microtime() * 10000000);

		$random =(((1*rand(0,9))+(10*rand(0,9))+(100*rand(0,9))+(1000*rand(1,9))));
		return $random;
	}
	
	/*
	This function is used to genetae the differnce in years for age used in keyword
	@param - Date of birth
	@return - string 
	*/
	public static function getAge($newDob)
	{
		$today=date("Y-m-d");
		$datearray=explode("-",$newDob);
		$todayArray=explode("-",$today);
		
		$years=($todayArray[0]-$datearray[0]);
		
		if(intval($todayArray[1]) < intval($datearray[1]))
			$years--;
		elseif(intval($todayArray[1]) == intval($datearray[1]) && intval($todayArray[2]) < intval($datearray[2]))
			$years--;
		
		return $years;
	}

	 public static function sendWelcomeMailer($pid)
	{
		$email_sender=new EmailSender(MailerGroup::WELCOME,1780);
		$emailTpl=$email_sender->setProfileId($pid);
		$profileObj=$emailTpl->getSenderProfile();
		$emailTpl->getSmarty()->assign('gender', $profileObj->getGENDER());
		$email_sender->send();
		
		//top8 mailer after welcome screening mailer
		$top8Mailer=new EmailSender(MailerGroup::TOP8,1782);
		$top8Mailer->setProfileId($pid);
		$top8Mailer->send();
		
		//logging time when user gets activated and phone verified for first time.
		$jprofileDbObject=JPROFILE::getInstance();
		$paramArr["VERIFY_ACTIVATED_DT"]=date("Y-m-d H:i:s");
		$jprofileDbObject->edit($paramArr,$pid);
	}
	public static function getChannel()
	{
		$channel=ChannelUsed::_DESKTOP;
		if(MobileCommon::isNewMobileSite())
			$channel=ChannelUsed::_NMS;
		else if(MobileCommon::isApp())
		{
			if(MobileCommon::isApp()=='A')
				$channel=ChannelUsed::_ANDROID;
			else
				$channel=ChannelUsed::_IOS;
		}
		else if(MobileCommon::isMobile())
			$channel=ChannelUsed::_MS;
		
		return $channel;
	}	

	public static function getMainMembership($subscription){
		if(strpos($subscription,"N") !== false){
			return mainMem::EADVANTAGE;
		} elseif(strstr($subscription,"F,D") || strstr($subscription,"D,F") || strstr($subscription,"D")){
			return mainMem::EVALUE;
		} elseif(strstr($subscription,"F,X") || strstr($subscription,"X,F")){
			return mainMem::JSEXCLUSIVE;
		} elseif(strstr($subscription,"F")){
			return mainMem::ERISHTA;
		} else {
			return false;
		}
	}
	/**
	 * returns E if evalue member contact details are viewed else return A Accepted members contact details are viewed else D for direct call feature 
	 * @param $contactHandler contactHandlerObj
	 */
	public static function getViewContactDetailFlag($contactHandler)
	{
		$source='';
		$viewerState=$contactHandler->getViewer()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus();
		$viewedState=$contactHandler->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus();
		if($viewerState=='FREE' && $viewedState=="EVALUE")
			$source=CONTACT_ELEMENTS::EVALUE_TRACKING;
		else if(($viewerState=="EVALUE" || $viewerState=="ERISHTA") && $contactHandler->getContactObj()->getTYPE()=='A')
			$source=CONTACT_ELEMENTS::ACCEPTANCE_TRACKING;
		else if($viewerState=="EVALUE" || $viewerState=="ERISHTA" && $viewedState=="EVALUE")
			$source=CONTACT_ELEMENTS::EVALUE_TRACKING;
		else
			$source=CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING;
		return $source;
	}
        
        public static function setManglikWithoutDontKnow($manglikVal){
                $manglikArr = explode(",", $manglikVal);
                $returnStr = "";
                foreach ($manglikArr as $key=>$val){
                    if($val == "'D'" || $val=="D" || $val == "Don't know" || $val == " Don't know" || $val == "'S0'" || $val == "S0" || $val == "Select")
                        continue;
                    else
                        $returnStr.=",".$val;
                }
                return trim($returnStr,',');
        }
  
  /**
   * convertUpdateStrToArray : A utility Function
   * Convert string in any of following formats
   *  a) AGE=\"N\", MSTATUS=\"N\", RELIGION=\"N\", COUNTRY_RES=\"N\"
   *  b) AGE='N', MSTATUS='N', RELIGION='N', COUNTRY_RES='N'
   *  
   * to array in which column name are key and value is value specified in string 
   * i.e.
   *    array( "AGE"=>'N',"MSTATUS"=>'N', "RELIGION"=>'N', "COUNTRY_RES"='N');
   * @param type $uptStr
   * @return array
   */
  public static function convertUpdateStrToArray($uptStr)
  {
    if(0===strlen($uptStr)) {
      return array();
    }
    
    $arrayColumns = explode(",",$uptStr);
    $arrOut = array();
    $lastToken = '';
    foreach($arrayColumns as $params) {
      $arrTokens = explode("=",$params);
      if(count($arrTokens) === 1) {
        $arrOut[$lastToken] .= $params;
        continue;
      }
      $szVal = $arrTokens[1];
      $szVal = str_replace(array('\'','"',"\\"), "", $szVal);
      $arrOut[trim($arrTokens[0])] = trim($szVal);
      $lastToken = trim($arrTokens[0]);
    }
    return $arrOut;
  }

  	public static function getMembershipName($profileid){
  		if ($profileid) {
	  		$memHandlerObj = new MembershipHandler();
			$membershipStatus = $memHandlerObj->getRealMembershipName($profileid);
		} else {
			$membershipStatus = 'Free';
		}
		return $membershipStatus;
  	}

  	public static function getRCBDayDropDown()
    {
        $orgTZ = date_default_timezone_get();
        date_default_timezone_set("Asia/Calcutta");

        $server_day = date('j M');
        if (date('H') >= 21) {
            $server_day = date('j M', strtotime('+1 day', strtotime($server_day)));
        }

        $next_1         = date('j M', strtotime('+1 day', strtotime($server_day)));
        $next_2         = date('j M', strtotime('+2 day', strtotime($server_day)));
        $dropDownDayArr = array(
            date("Y-m-d", strtotime($server_day))=>$server_day,
            date("Y-m-d", strtotime($next_1))=>$next_1,
            date("Y-m-d", strtotime($next_2))=>$next_2,
        );
        
        date_default_timezone_set($orgTZ);
        return $dropDownDayArr;
    }

    public static function getRCBStartTimeDropDown() {
    	$dropDownTimeArr1 = array(
            "09:00:00"=>"9 AM",
            "10:00:00"=>"10 AM",
            "11:00:00"=>"11 AM",
            "12:00:00"=>"12 PM",
            "13:00:00"=>"1 PM",
            "14:00:00"=>"2 PM",
            "15:00:00"=>"3 PM",
            "16:00:00"=>"4 PM",
            "17:00:00"=>"5 PM",
            "18:00:00"=>"6 PM",
            "19:00:00"=>"7 PM",
            "20:00:00"=>"8 PM",
        );
        return $dropDownTimeArr1;
    }

    public static function getRCBEndTimeDropDown() {
    	$dropDownTimeArr2 = array(
            "10:00:00"=>"10 AM",
            "11:00:00"=>"11 AM",
            "12:00:00"=>"12 PM",
            "13:00:00"=>"1 PM",
            "14:00:00"=>"2 PM",
            "15:00:00"=>"3 PM",
            "16:00:00"=>"4 PM",
            "17:00:00"=>"5 PM",
            "18:00:00"=>"6 PM",
            "19:00:00"=>"7 PM",
            "20:00:00"=>"8 PM",
            "21:00:00"=>"9 PM",
        );
        return $dropDownTimeArr2;
    }
    
    public static function removeCanChat($loginProfileId,$otherProfileId)
    {
		if(JsMemcache::getInstance()->get("can_chat_".$loginProfileId."_".$otherProfileId,1) || JsMemcache::getInstance()->get("can_chat_".$otherProfileId."_".$loginProfileId,1)){
			JsMemcache::getInstance()->set("can_chat_".$loginProfileId."_".$otherProfileId,false,'','',1);
			JsMemcache::getInstance()->set("can_chat_".$otherProfileId."_".$loginProfileId,false,'','',1);
		}
	}
    
    /*
     * End script 
     * To note statistic of memory and time usages
     * @param : $st_Time [Start Time]
     * @return void
     */
    public static function logResourceUtilization($st_Time, $msg, $moduleName=null)
    {
        $end_time = microtime(TRUE);
        $var = memory_get_usage(true);

//        if ($var < 1024)
//            $mem =  $var." bytes";
//        elseif ($var < 1048576)
//            $mem =  round($var/1024,2)." kilobytes";
//        else
//            $mem = round($var/1048576,2)." megabytes"
        
        //In Mb only
        $mem = round($var/1048576,2);
        
        $timeTaken = ($end_time - $st_Time);
        $msg .= 'Memory usages : '.$mem;
        $msg .= ' Time taken : '.$timeTaken;
        //$arrData['requestId'] = LoggingManager::getInstance()->getUniqueId();
        //LoggingManager::getInstance($moduleName)->logThis(LoggingEnums::LOG_INFO,$msg);    
        return array('mem_usages'=>$mem,'time_elapse'=>$timeTaken,'msg'=>$msg,'requestId'=>LoggingManager::getInstance()->getUniqueId(),'channel'=>CommonFunction::getChannel(),'time_stamp'=>date('Y-m-d H:i:s'));
    }
    
    public static function logIntoProfiler($szModuleName, $arrData) {
      //Add into MQ
      $producerObj = new Producer();
      $queueData = array('process' =>MessageQueues::SCRIPT_PROFILER_PROCESS,'data'=>array('type' => 'elastic','body'=>$arrData), 'redeliveryCount'=>0 );
      $producerObj->sendMessage($queueData);
    }
    
    public static function getCitiesForStates($stateArr){
        $cityList = "";
        foreach($stateArr as $key=>$val){
            $cityList .= ",".FieldMap::getFieldLabel("state_CITY", $val);
        }
        $cityList=explode(",",trim($cityList,","));
        return $cityList;
    }

    	
    /**
     * this function returns occupation groups
     * @param  string  $occupationValues comma separated occuaptaion values
     * @param  boolean $isSingleQuote    whether occupation values are stored as single quote sorrounded
     * @return string                    returns comma separated string.
     */		
    public static function getOccupationGroups($occupationValues,$isSingleQuote=false)
    {
        $occupationGrouping = FieldMap::getFieldLabel('occupation_grouping_mapping_to_occupation', '',1);
        if($isSingleQuote)
        {
        	$occupationValuesArray = explode (",", str_replace("'", "", $occupationValues));
        }
        else
        {
        	$occupationValuesArray = explode (",", $occupationValues);
        }

        $occupationGroupString = "";

    	foreach ($occupationGrouping as $key => $occupationGroupingValues) 
    	{
    		$occupationGroupingValuesArray = array_map('intval',explode(',',$occupationGroupingValues));
    		if ( count(array_intersect($occupationValuesArray,$occupationGroupingValuesArray)) > 0)
    		{
    			$occupationGroupString .= $key.",";
    		}	
    	}
    	$occupationGroupString = rtrim($occupationGroupString,",");

    	if($isSingleQuote)
		{
			$occupationGroupString = "'".$occupationGroupString."'";
			$occupationGroupString = str_replace(",", "','", $occupationGroupString);
		}
    	return $occupationGroupString;
    }

    /**
     * returns occupation values, given occupation groups.
     * @param  string  $occupationGroups comma separated groups
     * @param  boolean $isSingleQuote    whether return values needs to be sorrounded by comma or not
     * @return string                    occupation values, comma separated
     */
    public static function getOccupationValues($occupationGroups,$isSingleQuote=false)
    {
        $occupationGrouping = FieldMap::getFieldLabel('occupation_grouping_mapping_to_occupation', '',1);
		if($isSingleQuote)
        {
        	$occupationGroupsArray = explode (",", str_replace("'", "", $occupationGroups));
        }
        else
        {
        	$occupationGroupsArray = explode (",", $occupationGroups);
        }

		$occupationValuesString = "";

		foreach($occupationGrouping as $key => $occupationGroupingValues) 
		{
			if(in_array($key,$occupationGroupsArray))
			{
				$occupationValuesString .= $occupationGroupingValues.",";
			}		
		}

		$occupationValuesString = rtrim($occupationValuesString,",");

		if($isSingleQuote)
		{
			$occupationValuesString = "'".$occupationValuesString."'";
			$occupationValuesString = str_replace(",", "','", $occupationValuesString);
		}
		return $occupationValuesString;
    }

    public static function getOccupationGroupsLabelsFromValues($occupationGroups)
    {
    	$occupationGroupsArr = explode(",",$occupationGroups);
    	$decoratedOccGroups = "";
    	$occupationGroupingFieldMapLib = FieldMap::getFieldLabel('occupation_grouping', '',1);    	
    	foreach($occupationGroupsArr as $key=>$value)
    	{
    		$decoratedOccGroups.= $occupationGroupingFieldMapLib[$value].", ";
    	}
    	$decoratedOccGroups = rtrim($decoratedOccGroups,", ");
    	return $decoratedOccGroups;
    }

    public static function getContactLimitDates()
	{
		$loginProfile = LoggedInProfile::getInstance();
		$verifyDate = $loginProfile->getVERIFY_ACTIVATED_DT();
		if(!isset($verifyDate) || $verifyDate == '' || $verifyDate == '0000-00-00 00:00:00')
		{
			$verifyDate = $loginProfile->getENTRY_DT();
		}

		$x = date('Y-m-d',strtotime($verifyDate));
		$y = date('Y-m-d');

		$dayObject = date_diff( date_create($y), date_create($x));
		$daysDiff = $dayObject->days;

		$weeks = floor($daysDiff/7) * 7;

		$weekStartDate = date('Y-m-d', strtotime($x. " + $weeks days"));

		$months = floor($daysDiff/30) * 30;

		$monthStartDate = date('Y-m-d', strtotime($x. " + $months days"));

		return array('weekStartDate' => $weekStartDate, 'monthStartDate' => $monthStartDate);
	}

	public static function getLimitEndingDate($errlimit)
	{
		$loginProfile = LoggedInProfile::getInstance();
		$verifyDate = $loginProfile->getVERIFY_ACTIVATED_DT();
		if(!isset($verifyDate) || $verifyDate == '' || $verifyDate == '0000-00-00 00:00:00')
		{
			$verifyDate = $loginProfile->getENTRY_DT();
		}
		$x = date('Y-m-d',strtotime($verifyDate));
		$y = date('Y-m-d');

		$dayObject = date_diff( date_create($y), date_create($x));
		$daysDiff = $dayObject->days;
		if($errlimit == "WEEK")
		{
			if($daysDiff % 7 == 0)
				$daysDiff += 1;
			$weeks = ceil($daysDiff/7) * 7 - 1;
			$endDate = date('Y-m-d', strtotime($x. " + $weeks days"));

		}
		elseif($errlimit == "MONTH")
		{
			if($daysDiff % 30 == 0)
				$daysDiff += 1;
			$months = ceil($daysDiff/30) * 30 - 1;
			$endDate = date('Y-m-d', strtotime($x. " + $months days"));
		}

		return $endDate;
	}

     /**
         * 
         * @param type $country : country is the country that the person belongs to. eg: 51 for INDIA
         * @param type $state :  it is a comma separated string of the form <state>,<native_state>
         * @param type $cityVal : it is a comma separated string of the form <city>,<native_city>
         * @param type $nativeCityOpenText : it is an open text value specifying the native place. eg:faizabad
         * @param type $decoredVal : this is set to "city" 
         * @return string
         */

     public static function getResLabel($country,$state,$cityVal,$nativeCityOpenText,$decoredVal)
     {        
     	$label = '';
     	$city = explode(',',$cityVal);
        $citySubstr = substr($city[0], 0,2); // if city living in's state and native state is same do not show state
        if(FieldMap::getFieldLabel($decoredVal,$city[0]) == '')
        {
        	$label = html_entity_decode(FieldMap::getFieldLabel('country',$country));
        }
        else
        {
        	if(substr($city[0],2)=="OT")
        	{
        		$stateLabel = FieldMap::getFieldLabel("state_india",substr($city[0],0,2));
        		$label = $stateLabel."-"."Others";
        	}
        	else
        	{
        		$label = FieldMap::getFieldLabel($decoredVal,$city[0]);	
        	}        	
        }     
        if(isset($city[1]) && $city[1] != '0' && FieldMap::getFieldLabel($decoredVal,$city[1]) != ''){
        	$nativePlace =  FieldMap::getFieldLabel($decoredVal,$city[1]);
        }
        else
        {
        	$states = explode(',',$state);
        	if($states[1] != '' && ($states[1] != $citySubstr || $nativeCityOpenText != '')){
        		$nativeState = FieldMap::getFieldLabel('state_india',$states[1]);

        		if($nativeCityOpenText != '' && $nativeState != '')
        			$nativePlace = $nativeCityOpenText.', ';

        		$nativePlace .= $nativeState;        		
        	}
        }
        if($nativePlace != '' && $nativePlace != $label)
        	$label .= ' & '.$nativePlace;
        return $label;
    }

    public static function loginTrack($registrationid, $profileid)
	{
		if( ! isset($registrationid) || ! isset($profileid) )
			return ;

		// APP_LOGINTRACKING
		$appType = MobileCommon::getAppName();
		$loginTrack = new MIS_APP_LOGINTRACKING();
		if(!$loginTrack->getRecord($registrationid, $profileid))
		{
			$loginTrack->replaceRecord($profileid, $registrationid, $appType);
			// send mail
			LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO,"Send mail for New login profile : $profileid ",array(LoggingEnums::MODULE_NAME => LoggingEnums::NEW_LOGIN_TRACK));
			CommonFunction::SendEmailNewLogin($profileid);
		}
	}

	public static function SendEmailNewLogin($profileid)
	{
		if(!isset($profileid))
			return ;

		try
		{
			$channel = "Browser";
			if(MobileCommon::isAndroidApp())
			{
				$channel = "Android App";
			}
			else if(MobileCommon::isIOSApp())
			{
				$channel = "Ios App";
			}

			// TODO:
			$deviceName = "device";
			$city = "city";
			$country = "country";

			$top8Mailer = new EmailSender(MailerGroup::TOP8, 1849);
			$tpl = $top8Mailer->setProfileId($profileid);
			// TODO : change subject
			$subject = "new Login related subject here";
			$tpl->setSubject($subject);
			$forgotPasswordStr = ResetPasswordAuthentication::getResetLoginStr($profileid);
			$forgotPasswordUrl = JsConstants::$siteUrl."/common/resetPassword?".$forgotPasswordStr;
			$tpl->getSmarty()->assign("resetPasswordUrl",$forgotPasswordUrl);
			$tpl->getSmarty()->assign("channel", $channel);
			$tpl->getSmarty()->assign("deviceName", $deviceName);
			$tpl->getSmarty()->assign("city", $city);
			$tpl->getSmarty()->assign("country", $country);
			// send mail
			// $top8Mailer->send();
		} catch (Exception $e) {
			throw new jsException($e);
		}
	}

    public static function getFlagForIdfy($profileId)
    {
    	if($profileId)
    	{
    		if(($profileId % 4) == 5) //this needs to be changed as per requirement
    		{
    			return true;
    		}
    		return false;    		
    	}    	
    	return false;
    }
}
?>
