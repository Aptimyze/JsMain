<?php
Abstract class ApiAuthentication
{
	public static $loginTracking="LOGIN_TRACKING";	
        public static $logTrackingThroughQueue=true;
    private $request;
	protected $encryptSeprator="______";
	protected $_KEY = "Radhe Shaam";
    protected $_SUBKEY = "muhaafiz Khudi ke";
	protected $isMobile=false;
	protected $loginData;
	protected $gapTimeEntry=400;
	public $hashedPasswordFromDb=false;
	public $trackLogin=true; //the variable which allows login tracking to be done
	public $isNotApp=0;
	private $response;
	private $seprator="___";
	private $inActive=1800;
	private $cookieExpDays=30;
	private $AUTHCHECKSUM="AUTHCHECKSUM";
	private $AUTH="AUTHN";
	private $HMT="HMTN";
	private $domain="";
	protected $rememberMe=true;
	private $cookieRemName="remnam";// name of username cookie
	private $cookieRemPass="rempas";// name of password
	private $remSalt = "OsRrHlpiBxyBpNSAepRZ7dF6bZ6ndLTL";
	private $mixer =   "6D5BsZR7mTmxvJE7xpyT1WStW5avfQvr";
	private $inactiveMin="35";//The time in minutes to force new login, if account has been inactive used for older login functionality
	private $expiryCookieTime=2592000;
	private $dateTime1 ='11';
	private $dateTime2 ='22';
	private $expiryTime = 2592000;
	public $mailerProfileId;

	protected $logLoginHistoryTracking=false;
	protected $recentUserTracking=false;
	protected $misLoginTracking=false;
	protected $appLoginProfileTracking=false;
	protected $logLogoutTracking=false;
	protected $recentLogTracking=false;
	protected $channel='R';
	protected $loc="";
	protected $loggedInPId;

	public function __construct($request)
	{
		$this->request=$request;
	}

	
	public function login($email,$password,$rememberMe)		//to be changed in connect_auth.inc
	{
		if($email && $password)
		{
			//Get the Login Data from JProfile -->call Store
			$dbJprofile=new JPROFILE();
			
			$paramArr='PROFILEID,DTOFBIRTH,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES,PASSWORD,PHONE_MOB,EMAIL,SORT_DT';
			
			$loginData=$dbJprofile->get($email,"EMAIL",$paramArr);
			if(is_array($loginData))
			{
				if(PasswordHashFunctions::validatePassword($password, $loginData['PASSWORD'])||$this->hashedPasswordFromDb)
				{
					
					// Create AuthChecksum and Set the loginData
					$loginData["SUBSCRIPTION"]=trim($loginData["SUBSCRIPTION"]);
					
					//What about archived users.
					//What about tracking
					$this->channel='D';
					$this->loginData=$loginData;
					if($this->isNotApp && $this->loginData[PROFILEID] && $this->loginData["GENDER"]=="")
						return $this->loginData;
					if($this->loginData && $this->loginData[ACTIVATED]<>'D')
					{
						$profileObj= LoggedInProfile::getInstance();
						$profileObj->getDetail($this->loginData[PROFILEID],"PROFILEID","*");
						$this->setLoginTrackingCookie($this->loginData);
						if(IncompleteLib::isProfileIncomplete($profileObj) && $profileObj->getINCOMPLETE()!="Y")
						{
							$this->loginData[INCOMPLETE]="Y";
							$updateProfileArr[INCOMPLETE]="Y";
							$updateProfileArr[ACTIVATED]="N";
							$updateProfileArr[PREACTIVATED]=$profileObj->getACTIVATED();
							$profileObj->edit($updateProfileArr);
						}
						else if(!IncompleteLib::isProfileIncomplete($profileObj) && $profileObj->getINCOMPLETE()=="Y")
						{
								$updateProfileArr[INCOMPLETE]="N";
								$updateProfileArr[ACTIVATED]="N";
								$updateProfileArr[SCREENING]=0;
								$updateProfileArr[ENTRY_DT]=date("Y-m-d H:i:s");
								$updateProfileArr[MOD_DT]=date("Y-m-d H:i:s");
								$profileObj->edit($updateProfileArr);
								$this->loginData=self::login($email,$password);
						}
						$this->loginData[AUTHCHECKSUM]=$this->encryptAppendTime($this->createAuthChecksum());
						if($this->trackLogin)
						{
							$this->logLoginHistoryTracking=true;
							$this->recentUserTracking=true;
							$this->misLoginTracking=true;
						}
						//appPromotion off for already installed users
						if(!$this->isNotApp)
							$this->appLoginProfileTracking=true;
					}
			
					if($this->loginData)
					{
						if($rememberMe)
							$this->rememberMe=1;
						else
							$this->rememberMe=0;
						$this->setcookies($this->loginData,$email,$password);
					}
					$this->CommonLoginTracking();
					return $this->loginData;
				}
			}
		}
		return NULL;
	}


/*
	**** @function: logout
	**** deletes all the cookies of the user.
	*/
	public function logout($profileId)	//to be changed in connect_auth.inc
	{
                $this->loggedInPId=$profileId;
		$this->recentLogTracking=true;
		$this->removeLoginCookies();
		$this->logLogoutTracking=true;
                if(MobileCommon::isMobile())
                    $this->browserNotificationLogout= true;
		$this->CommonLoginTracking();
	}

	
    
	/*
	* @function: authenticate
	* check whether user is a valid user
	* @param string authchecksum
	*/
	public function authenticate($authChecksum=null,$gcm=0,$fromMailerAutologin="N")
	{
		//Decrypting Checksum
		if(!$authChecksum)
			$authChecksum=sfContext::getInstance()->getRequest()->getParameter("AUTHCHECKSUM");
		if($this->isNotApp && !$authChecksum)
			$authChecksum=$_COOKIE[AUTHCHECKSUM];
		//die($authChecksum);
		if(!$authChecksum && $this->isNotApp && $fromMailerAutologin=="N")
		{
			$authChecksum=$this->getAuthChecksumFromAuth();
		}
		if(strlen($authChecksum)==0 || !$authChecksum)
		{
			return false;
		}
		$decryptObj= new Encrypt_Decrypt();
		$decryptedAuthChecksum=$decryptObj->decrypt($authChecksum);
		$loginData=$this->fetchLoginData($decryptedAuthChecksum);
		if($loginData["FROM_BACKEND"])
		{
			$this->trackLogin=false;
			if($this->stopBackendUser()){
				$this->removeLoginCookies();
				return false;
			}
		}		
		if( $loginData[CHECKSUM] && $this->js_decrypt($loginData[CHECKSUM]))
		{
			if(strstr($_SERVER["REQUEST_URI"],"api/v1/notification") || strstr($_SERVER["REQUEST_URI"],"api/v3/notification")){
				$this->loginData =$loginData;
				$this->loginData[AUTHCHECKSUM]=$authChecksum;
				return $this->loginData;
			}
			else
                $this->loginData=$this->IsAlive($loginData,$gcm);

			if($this->loginData)
			{
				if($this->trackLogin)
				{
					$this->loginData[AUTHCHECKSUM]=$this->encryptAppendTime($this->createAuthChecksum());
				}
				else
					$this->loginData[AUTHCHECKSUM]=$authChecksum;
				if($this->isNotApp)
				{
					$this->rememberMe=0;
					$this->setcookies($this->loginData);
				}
			}
			else
			{
				if($this->isNotApp)
				{
					$this->removeLoginCookies();
					$this->recentLogTracking=true;
				}
			}
			$this->CommonLoginTracking();
			return $this->loginData;
		}
		return false;
	}
	
	/*
	* @function: IsAlive
	* check whether user is still active or not
	* @param array loginData
	*/
	public function IsAlive($loginData,$gcm=0)
	{
		//need to check the DOB,GENDER,ACTIVATION,INCOMPLETE fields
		//Get the Login Data from JProfile -->call Store
		if(sfContext::getInstance()->getRequest()->getParameter('searchRepConn'))
			$loggedInProfileObj=LoggedInProfile::getInstance();
		else
			$loggedInProfileObj=LoggedInProfile::getInstance();
		$loggedInProfileObj->getDetail($loginData[PROFILEID],"","*");
		//If any changes Found then logout user
		if($loggedInProfileObj->getACTIVATED()=="D" || $loggedInProfileObj->getACTIVATED()=="")
		{
			ValidationHandler::getValidationHandler("","mismatch in important fields of Profile in mobile authenication");
			return null;
		}
		$time=time()-$loginData[TIME];
		
		if($this->isNotApp)
		{
			if(isset($_COOKIE[$this->cookieRemName]) && isset($_COOKIE[$this->cookieRemPass]))
				$this->rememberMe=true;
			else
				$this->rememberMe=false;
		}
		else
		{
			$this->rememberMe=true;
		}

		$difftime = date("Y-m-d H:i:s",$loginData[TIME]);
		if(sfContext::getInstance()->getRequest()->getParameter('searchRepConn'))
			$dbObj=new ProfileAUTO_EXPIRY();
		else
			$dbObj=new ProfileAUTO_EXPIRY();
		
		
		if($dbObj->IsAlive($loginData[PROFILEID],$difftime))
		{
			$loginData[TIME]=time();
			if($time>$this->inActive && $this->trackLogin)
			{
				//CommonUtility::sendtoRabbitMq($loginData[PROFILEID]);
				if($this->rememberMe)
				{
					$this->logLoginHistoryTracking=true;
					$this->misLoginTracking=true;
					if($gcm)
						$this->channel='G';
					else
						$this->channel='R';
				}
				else
				{
					if($this->isNotApp)
						$this->removeLoginCookies();
					return null;
				}
			}
			else if($gcm){
				$this->misLoginTracking=true;
				 $this->channel='G';
			}
			if($this->trackLogin)
				$this->recentUserTracking=true;
			$loginData["EMAIL"]=$loggedInProfileObj->getEMAIL();
			$loginData["PHONE_MOB"]=$loggedInProfileObj->getPHONE_MOB();
            $loginData["ACTIVATED"]=$loggedInProfileObj->getACTIVATED();
            $loginData["INCOMPLETE"]=$loggedInProfileObj->getINCOMPLETE();
            $loginData["DTOFBIRTH"]=$loggedInProfileObj->getDTOFBIRTH();
            $loginData["LAST_LOGIN_DT"]=$loggedInProfileObj->getLAST_LOGIN_DT();
			return $loginData;
		}
                
		return null;
	}
     
    public function CommonLoginTracking()
	{
		if($this->loginData['ACTIVATED']=='D') return ;
		$queueArr['profileId']=$this->loginData["PROFILEID"] ? $this->loginData["PROFILEID"] : $this->loggedInPId;
		$profileId=$this->loginData["PROFILEID"];
		$ip=CommonFunction::getIP();
		$queueArr['ip']=$ip;
		$queueArr['currentTime'] = date("Y-m-d H:i:s");
		if($this->misLoginTracking){
			$websiteVersion=MobileCommon::isApp();
	        if(!$websiteVersion)
			{
				if($this->isNotApp){
					if(MobileCommon::isNewMobileSite()){
						$websiteVersion="N";
						if(MobileCommon::isAppWebView()){
							$websiteVersion="A";
						}
					}
					elseif(MobileCommon::isDesktop())
						$websiteVersion="D";				
				}
			}
			$location=$this->loc;
			if(!$location)
			{
				if(sfContext::getInstance()->getRequest()->getParameter('link_id') && strpos($_SERVER[REQUEST_URI],"/e/")!==false){
					$link=LinkFactory::getLink(sfContext::getInstance()->getRequest()->getParameter('link_id'));
					$request_uri=$link->getLinkAddress();
				}
				else
					$request_uri=$_SERVER[REQUEST_URI];
				$page=explode('?',$request_uri);
				$page=$page[0];
				$page=explode('/',$page);
				$no=count($page);
				$page=$page[$no-1];
			}
			else
			{
				if($location)
					$request_uri=$location;			
				$request_uri=str_replace("CMGFRMMMMJS=","pass=",$request_uri);
				$request_uri=str_replace("&echecksum=","&autologin=",$request_uri);
				$request_uri=str_replace("?echecksum=","?autologin=",$request_uri);
				$request_uri=str_replace("&checksum=","&chksum=",$request_uri);
				$request_uri=str_replace("?checksum=","?ckhsum=",$request_uri);
				$request_uri=str_replace(urlencode($echecksum),"",$request_uri);
				$request_uri=str_replace($echecksum,"",$request_uri);
				$request_uri=ltrim($request_uri,"/");
				$page=$request_uri;
			}
			//$this->loginTracking($this->loginData["PROFILEID"],$websiteVersion,$this->channel,$page);
			
			$queueArr['websiteVersion']=$websiteVersion;
			$queueArr['channel']=$this->channel;
			$queueArr['page']=$page;
                        $queueArr['whichChannel'] = MobileCommon::getChannel();

			$queueArr['misLoginTracking']=true;
		}
		
		if($this->logLoginHistoryTracking){	
			$queueArr['logLoginHistoryTracking']=true;
		}

        if($this->appLoginProfileTracking)
		{
			$queueArr['appLoginProfileTracking']=true;
			
		}
		if($this->logLogoutTracking){
			$queueArr['logLogoutTracking']=true;
		}
		if($this->browserNotificationLogout){
			$queueArr['browserNotificationLogout']=true;
		}
                
                if($this->recentUserTracking)
			$this->RecentUserEntry();
		
		if($this->recentLogTracking)
			$this->removeRecentLog();
                
        if($queueArr['logLoginHistoryTracking'] || $queueArr['misLoginTracking'] || $queueArr['appLoginProfileTracking'] || $queueArr['logLogoutTracking'])
        {
        if(!($this->sendLoggingDataQueue(self::$loginTracking, $queueArr)))
        	self::completeLoginTracking($queueArr);
        }
        $curDat = date('Y-m-d');
        if($queueArr['profileId'] && JsMemcache::getInstance()->get("DISC_HIST_".$curDat."_".$queueArr['profileId']) != "Y"){
            $prodObj=new Producer();
            if($prodObj->getRabbitMQServerConnected())
            {
                $body = array("PROFILEID"=>$queueArr['profileId'],"DATE"=>$curDat);
                $type = "DISCOUNT_LOG";
                $queueData = array('process' =>'DISCOUNT_HISTORY',
                                    'data'=>array('body'=>$body,'type'=>$type),'redeliveryCount'=>0
                                  );
                $prodObj->sendMessage($queueData);
                JsMemcache::getInstance()->set("DISC_HIST_".$curDat."_".$queueArr['profileId'],"Y",(strtotime('tomorrow') - time()));
            }
            unset($prodObj,$queueData,$body);

            // App Registration Update Handling
	    /*
            $appRegCookieName 	='APP_REG_UPDATE';
  	    $registrationid 	=$this->request->getParameter('registrationid');
	    $apiappVersion 	=$this->request->getParameter('CURRENT_VERSION');
            if(!isset($_COOKIE[$appRegCookieName]) && $registrationid){
                        $producerObj = new JsNotificationProduce();
                        if($producerObj->getRabbitMQServerConnected()){
                                $dataSet =array("regid"=>$registrationid,"appVersion"=>$apiappVersion);
                                $msgdata = FormatNotification::formatLogData($dataSet,'REGISTRATION_ID');
                                $producerObj->sendMessage($msgdata);
                                @setcookie("$appRegCookieName",time(),86400,"/",$this->domain);
                        }
            }*/
            // end
        }
	}
	
	
	/*
	* @function: getAuthChecksumFromAuth
	* create authchecksum from auth cookie
	* @param null
	*/ 
	public function getAuthChecksumFromAuth()
	{
		$time="";
		if ( !isset($_COOKIE[$this->AUTH]) && !isset($_COOKIE[$this->HMT]) && $this->rememberMe && isset($_COOKIE[$this->cookieRemName]) && isset($_COOKIE[$this->cookieRemPass]) )
		{
			$username=$_COOKIE[$this->cookieRemName];
			$password=$_COOKIE[$this->cookieRemPass];
			if(sfContext::getInstance()->getRequest()->getParameter('searchRepConn'))
				$dbJprofile=new JPROFILE("newjs_masterRep");
			else
				$dbJprofile=new JPROFILE("newjs_master");
			if($username){
				$paramArr='PROFILEID,DTOFBIRTH,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES,PASSWORD';
				$this->loginData=$dbJprofile->get($username,"USERNAME",$paramArr);
				$pwdData = PasswordHashFunctions::unmixString($this->loginData['PASSWORD']);
				$pwd = PasswordHashFunctions::encrypt($pwdData['STRING1'],$this->remSalt,$this->mixer);
				if(!PasswordHashFunctions::slowEquals($pwd,$password))
					return NULL;
				$time=36*60;
			}
			else
				return NULL;
		}
		else
		{
			$temp=$this->explode_assoc('=',':',$_COOKIE[$this->AUTH]);
			$temp['ID']=substr($temp['ID'],0,strpos($temp[ID],$this->encryptSeprator));
			$tempPr=$this->js_encrypt(md5($temp['PR'])."i".$temp['PR']);
			$tempPr=substr($tempPr,0,strpos($tempPr,$this->encryptSeprator));
			
			$nowtime=time();
			$inactiveSec=$nowtime-$_COOKIE[$this->HMT];
			if ($inactiveSec/60>$this->inactiveMin)
			{
				if (!isset($_COOKIE[$this->cookieRemName]))
					return NULL;
				else
					$time=$inactiveSec;
			}
			
			if( $checksum_hard!='' && $temp['ID'] != $checksum_hard )
			{
					return NULL;
			}
			elseif( $temp['ID'] != $tempPr )
			{
					return NULL;
			}
			$id=$temp[PR];
			if($id){
				if(sfContext::getInstance()->getRequest()->getParameter('searchRepConn'))
					$dbJprofile=new JPROFILE("newjs_masterRep");
				else
					$dbJprofile=new JPROFILE("newjs_master");
				$paramArr='PROFILEID,DTOFBIRTH,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES,PASSWORD';
				$this->loginData=$dbJprofile->get($id,"PROFILEID",$paramArr);
			}
			else
				return NULL;
		}

		return $this->encryptAppendTime($this->createAuthChecksum($time));
	}
		
	/*
	* @function: setcookies
	* create required login cookies
	* @param array,email,pass
	*/ 
    public function setcookies($myrow,$email="",$pass="")
	{        
		if($this->isNotApp)
		{

			if(MobileCommon::isNewMobileSite())
				@setcookie($this->AUTHCHECKSUM,$myrow[AUTHCHECKSUM],time()+$this->expiryCookieTime,"/",$this->domain);
			else
				@setcookie($this->AUTHCHECKSUM,$myrow[AUTHCHECKSUM],0,"/",$this->domain);
			$tm=time();
			@setcookie($this->HMT,$tm,0,"/",$this->domain);
			$checksum=md5($myrow["PROFILEID"])."i".$myrow["PROFILEID"];
			$cookie_str="ID=".$this->js_encrypt($checksum);
			$cookie_str.=":PR=".$myrow["PROFILEID"];
			$cookie_str.=":US=".$myrow["USERNAME"];
			$cookie_str.=":GE=".$myrow["GENDER"];
			$cookie_str.=":SU=".$myrow["SUBSCRIPTION"];
			$cookie_str.=":AC=".$myrow["ACTIVATED"];
			$cookie_str.=":SO=".$myrow["SOURCE"];
			$szHP =(strlen($myrow["HAVEPHOTO"])==0)?'BL':$myrow["HAVEPHOTO"];//BL Stand for Blank Value
			$cookie_str.=":HP=".$szHP;
			@setcookie($this->AUTH,$cookie_str,0,"/",$this->domain);
			
			if($this->rememberMe)
			{
				@setcookie($this->cookieRemName,$myrow["USERNAME"],time()+($this->expiryCookieTime),"/",$this->domain);
				$pwdData = PasswordHashFunctions::unmixString($myrow[PASSWORD]);
				$salt = $pwdData['STRING2'];
				$pwdHash = PasswordHashFunctions::encrypt($pass,$salt);
				$pwd = PasswordHashFunctions::encrypt($pwdHash,$this->remSalt,$this->mixer);
				@setcookie($this->cookieRemPass,$pwd,time()+($this->expiryCookieTime),"/",$this->domain);
			}
		}

	}
	/*
	* @function: removeLoginCookies
	* remove login cookies
	* @param null
	*/ 
	public function removeLoginCookies()
	{
		@setcookie($this->AUTHCHECKSUM,"",0,"/",$this->domain);
		@setcookie($this->HMT,"",0,"/",$this->domain);
		@setcookie($this->AUTH,"",0,"/",$this->domain);
		 @setcookie($this->cookieRemName,"",0,"/",$this->domain);
			@setcookie($this->cookieRemPass,"",0,"/",$this->domain);
	}
		
	/*
	 * remove Recent users entery
	*/ 
    public function removeRecentLog($pid)
	{
		if(is_numeric($pid))
		{
			if(!$this->isMobile){
				//$dbObj=new userplane_recentusers;
				//$dbObj->DeleteRecord($pid);
			}
			
			// Remove Online-User
	                $dateTime =date("H");
        	        $redisOnline =true;
        	        if(($dateTime>=$this->dateTime1) && ($dateTime<$this->dateTime2))
                	        $redisOnline =false;
			if($redisOnline){
				$jsCommonObj =new JsCommon();
				$jsCommonObj->removeOnlineUser($pid);	
			}
		}
	}	
    

	/** Update recent users entry, required to trap in online users
	*/
	public function RecentUserEntry()
	{
		if(!$this->isMobile)
		{
			$allow=0;
			$time=$_COOKIE["LOGUSERENTRY"];
	                if(is_numeric($pid))
        	        {
        	                if(!$time)
					$allow=1;
                        	else
	                        {
        	                        $timepassed=time()-$time;
                	                if($timepassed>$this->gapTimeEntry)
                        	                $allow=1;
	                        }
                	}
			if($allow)
			@setcookie("LOGUSERENTRY",time(),0,"/",$this->domain);
		}

		$allow=1;
		$pid=intval($this->loginData[PROFILEID]);
		if($allow && $pid && !$this->isMobile)
		{
			//$dbObj=new userplane_recentusers("newjs_master");
			//$dbObj->replacedata($pid);
		}

		// Add Online-User
		$dateTime =date("H");
		$redisOnline =true;
		if(($dateTime>=$this->dateTime1) && ($dateTime<$this->dateTime2))
			$redisOnline =false;
		if($pid && $allow && $redisOnline)
		{
			$jsCommonObj =new JsCommon();
			$jsCommonObj->setOnlineUser($pid);
		}

	}
	/*
	**** @function: js_encrypt
	**** input is plain text that is to be encrypted and output is cipher text
	*/
	public function js_encrypt($plainText,$email="")
	{
		//return rawurlencode(md5($this->_KEY . md5($plainText) . $this->_SUBKEY) . "|i|" . $plainText);
		//Embedding mail id
		$cur_time=time();
		if($email)
		{
				$email=$this->ecrypt($email);
		}
		$extra_params=$this->encryptSeprator.$cur_time.$this->encryptSeprator.$email;

		return md5($this->_KEY . md5($plainText) . $this->_SUBKEY) . "|i|" .$plainText.$extra_params;
	}	
    /*
	**** @function: js_decrypt
	**** input is cipher text that is to be decrypted and output is plain text or false on error using md5
	*/
	public function js_decrypt($cipherText,$fromAutoLogin = "N")
	{
		//$arrTmp = explode("|i|", rawurldecode($cipherText));
		$arr=explode($this->encryptSeprator,$cipherText);
		$arrTmp = explode("|i|", $arr[0]);
		$arrTmp[1]=stripslashes($arrTmp[1]);
		//this change was done for earlier usernmames which have special characters in them so as to remove backslas (/) that is added to them.
		if (md5($this->_KEY . md5($arrTmp[1]) . $this->_SUBKEY) == $arrTmp[0])
		{
			if($fromAutoLogin=="Y")
			{
				$profileid=$this->getProfileFrmChecksum($arrTmp[1]);
				if($arr[1]&& $profileid)
				{
					$curTime = time();
					$timediff = $curTime-$arr[1];
					$mailedtime = date("Y-m-d H:i:s",$arr[1]);
					if(sfContext::getInstance()->getRequest()->getParameter('searchRepConn'))
						$dbObj=new ProfileAUTO_EXPIRY("newjs_masterRep");
					else
						$dbObj=new ProfileAUTO_EXPIRY("newjs_master");
					if($timediff > $this->expiryTime || !$dbObj->IsAlive($profileid,$mailedtime))
					{
						
						return false;
					}
					else
						return $arrTmp[1];
				}
				else
					return false;
			}
            else
				return true;
		}
		else
			return false;
	}

	/*
	**** @function: ecrypt
	* input is plain text that is to be encrypted and output is cipher text using base64_encode
	*/
	public function ecrypt($str)
	{
		$key = $this->_KEY;
		for($i=0; $i<strlen($str); $i++) {
			 $char = substr($str, $i, 1);
			 $keychar = substr($key, ($i % strlen($key))-1, 1);
			 $char = chr(ord($char)+ord($keychar));
			 $result.=$char;
		}
		return urlencode(base64_encode($result));
	}
	 /*
	**** @function: encryptAppendTime
	**** input is cipher text and output is 
	*/
	public function encryptAppendTime($checksum)
	{
		//Encrypting Checksum
		$encryptObj= new Encrypt_Decrypt();
		$encryptAuthChecksum=$encryptObj->encrypt($checksum);

		return $encryptAuthChecksum;
	}

	/*
	**** @function: explode_assoc
	*/
	public function explode_assoc($glue1, $glue2, $array)
	{
		$array2=explode($glue2, $array);
		foreach($array2 as  $val)
		{
			$pos=strpos($val,$glue1);
			$key=substr($val,0,$pos);
			$array3[$key] =substr($val,$pos+1,strlen($val));
		}
		return $array3;
	}
	
     /*
	**** @function: createAuthChecksum
	*/ 
	public function createAuthChecksum($time="",$backendCheck)
	{
		$checksum=md5($this->loginData["PROFILEID"])."i".$this->loginData["PROFILEID"];
		$authchecksum="ID=".$this->js_encrypt($checksum);
		$authchecksum.=":PR=".$this->loginData["PROFILEID"];
		$authchecksum.=":US=".$this->loginData["USERNAME"];
		$authchecksum.=":GE=".$this->loginData["GENDER"];
		$authchecksum.=":SU=".$this->loginData["SUBSCRIPTION"];
		$authchecksum.=":AC=".$this->loginData["ACTIVATED"];
		$authchecksum.=":SO=".$this->loginData["SOURCE"];
		$authchecksum.=":IN=".$this->loginData["INCOMPLETE"];
		$authchecksum.=":DOB=".$this->loginData["DTOFBIRTH"];
		$authchecksum.=":HP=".$this->loginData["HAVEPHOTO"];
		if($backendCheck)
			$authchecksum.=":BK=backend";
		
		if($time){
			$t1=time()-$time;
			$authchecksum.=":TM=".$t1;
		}
		else
			$authchecksum.=":TM=".time();
			return $authchecksum;

	}
	/*
	**** @function: fetchLoginData
	*/
	public function fetchLoginData($checksum)
	{
		if($checksum)
		{
			$temp=$this->explode_assoc('=',':',$checksum);
						
			$data["PROFILEID"]=$temp['PR'];
			$data["USERNAME"]=$temp['US'];
			$data["GENDER"]=$temp['GE'];
			$data["ACTIVATED"]=$temp['AC'];
			$data["SUBSCRIPTION"]=$temp['SU'];
			$data["SOURCE"]=$temp['SO'];
			$data["CHECKSUM"]=$temp['ID'];
			$data["INCOMPLETE"]=$temp['IN'];
			$data["DTOFBIRTH"]=$temp['DOB'];
						$data["HAVEPHOTO"]=$temp['HP'];
			$data["TIME"]=$temp[TM];
			$data["FROM_BACKEND"]=$temp['BK'];
			return $data;
		}
		return null;
	}
	/*
	**** @function: authenticateChecksum
	*/
	public function authenticateChecksum($checksum)
	{
			
		if($checksum)
			{
				$temp=$this->explode_assoc('=',':',$checksum); 			
				$time=$this->js_decrypt($temp[ID]);
			}
			return false;
	}
	
	public function stopBackendUser()
	{	
		if(strpos($_SERVER["REQUEST_URI"],"/profile/dpp?fromBackend=1")!==false ||  strpos($_SERVER["REQUEST_URI"],"/api/v1/profile/dppsubmit")!==false  || strpos($_SERVER["REQUEST_URI"],"/api/v1/profile/filtersubmit")!==false || strpos($_SERVER["REQUEST_URI"],"/api/v1/profile/dppSuggestions")!==false || strpos($_SERVER["REQUEST_URI"],"/api/v1/search/matchAlertToggleLogic")!==false)
			return 	false;
		else
			return true;
			
		
	}
	
	public function getProfileFrmChecksum($checksum)
	{
		if($checksum)
		{
			$profileid=substr($checksum,33,strlen($checksum));
			$temp_check=substr($checksum,0,32);
			$real_check=md5($profileid);
			if($temp_check==$real_check)
				return $profileid;

		}
	}

	public function sendLoggingDataQueue($type,$body){

			
        if(self::$logTrackingThroughQueue)
        {
            $producerObj=new Producer();
            if($producerObj->getRabbitMQServerConnected())
            {
                    $trackingData = array('process' =>'LOGGING_TRACKING',
                                            'data'=>
                                            array(
                                            'body'=>$body,
                                             'type'=>$type, 
                                            'redeliveryCount'=>0 )
                                            );
                    $producerObj->sendMessage($trackingData);
                    return true;
            }

	}

        return false;
}

	public static function completeLoginTracking($trackingData)
	{
		$profileId = $trackingData["profileId"];
		if(!$profileId)return ;
		$ip = $trackingData['ip'];
		$currentTime = $trackingData['currentTime'];
		if($trackingData[misLoginTracking])
		{
			include_once(sfConfig::get("sf_web_dir")."/classes/LoginTracking.class.php");
			$loginTracking= LoginTracking::getInstance($profileId);
			$loginTracking->setChannel($trackingData["channel"]);
			$loginTracking->setWebisteVersion($trackingData["websiteVersion"]);
			$loginTracking->setRequestURI($trackingData["page"]);
			$loginTracking->loginTracking('',$currentTime);
                        $trackingData['type'] = LoggingEnums::COOL_M_LOGIN;
                        LoggingManager::getInstance()->writeToFileForCoolMetric($trackingData);  

                        
		}
		if($trackingData[logLoginHistoryTracking])
		{
			$dbName = JsDbSharding::getShardNo($profileId);
			//Insert Into LOG_LOGIN_HISTORY
			$dbLogLoginHistory=new NEWJS_LOG_LOGIN_HISTORY($dbName);
			$dbLogLoginHistory->insertIntoLogLoginHistory($profileId,$ip,$currentTime);
			
			//Insert Ignore Into LOGIN_HISTORY 
			$dbLoginHistory= new NEWJS_LOGIN_HISTORY($dbName);
			$insert=$dbLoginHistory->insertIntoLoginHistory($profileId,$currentTime);
			//if exist then update
			if(!$insert)
			{
				//if exist then update  newjs.LOGIN_HISTORY_COUNT
				$dbLoginHistoryCount= new NEWJS_LOGIN_HISTORY_COUNT($dbName);
				$update=$dbLoginHistoryCount->updateLoginHistoryCount($profileId);
	            if(!$update)
				$dbLoginHistoryCount->replaceLoginHistoryCount($profileId);
			}
			$dbJprofile=new JPROFILE("newjs_master");
			$dbJprofile->updateLoginSortDate($profileId,$currentTime);
		}

		if($trackingData["appLoginProfileTracking"] || ($trackingData[misLoginTracking] && ($trackingData["websiteVersion"] == 'A' ||$trackingData["websiteVersion"] == 'I') ) )
		{
			$dbAppLoginProfiles=new MOBILE_API_APP_LOGIN_PROFILES();
			$appType = $trackingData["websiteVersion"];
			$date = $trackingData["currentTime"];
			$appProfileId=$dbAppLoginProfiles->insertAppLoginProfile($profileId,$appType,$date);
		}
		if($trackingData["logLogoutTracking"])
		{
			$dbObj = new LOG_LOGOUT_HISTORY(JsDbSharding::getShardNo($profileId));
			$dbObj->insert($profileId,$ip,$currentTime);
		}
		if($trackingData["browserNotificationLogout"])
		{
			    $registrationIdObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION();
			    $registrationIdObj->updateNotificationDisableStatus($profileId,'M','Y'); 
		}

	}

	/*
	* @function: setLoginTrackingCookie
	* @param array - login data
	*/ 
    public function setLoginTrackingCookie($loginData)
	{
		if(MobileCommon::isApp())
			return ;

		$username = $loginData["USERNAME"];
		$cookieName = "loginTracking";
		$expiryTime = 31536000; // Approx 1 year

		if(!isset($_COOKIE[$cookieName]))
		{
			@setcookie($cookieName, json_encode(array($username)), time() + $expiryTime, "/", $this->domain);
			// send mail
			LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO,"Send mail for New login User : $username ",array(LoggingEnums::MODULE_NAME => LoggingEnums::NEW_LOGIN_TRACK, LoggingEnums::DETAILS => 'Device info : '.Devicedetails::deviceInfo() ));
			CommonFunction::SendEmailNewLogin($loginData["PROFILEID"]);
		}
		else
		{
			$cookieData = json_decode($_COOKIE[$cookieName], true);
			if(!in_array($username, $cookieData))
			{
				array_push($cookieData, $username);
				@setcookie($cookieName, json_encode($cookieData), time() + $expiryTime, "/", $this->domain);
				// send mail
				LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO,"Send mail for New login User : $username ",array(LoggingEnums::MODULE_NAME => LoggingEnums::NEW_LOGIN_TRACK, LoggingEnums::DETAILS => 'Device info : '.Devicedetails::deviceInfo() ));
				CommonFunction::SendEmailNewLogin($loginData["PROFILEID"]);
			}
		}
	}
}
?>
