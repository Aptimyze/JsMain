<?php
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
class protect
{
	/***
	**** @class: protect
	**** @version: 1.0;
	**** @author: Puneet Makkar
	**** @date: 14th Feb 2008
	**** @license: GNU GENERAL PUBLIC LICENSE;
	****
	**** This class protects your php pages using a MySQL Database and cookies.
	****/
	private $remSalt = "OsRrHlpiBxyBpNSAepRZ7dF6bZ6ndLTL";
	private $mixer =   "6D5BsZR7mTmxvJE7xpyT1WStW5avfQvr";
	private $errorMsg="";
	private $_KEY = "Radhe Shaam";
	private $_SUBKEY = "muhaafiz Khudi ke";
	private $encryptSeprator="______";
	//private $domain=".jeevansathi.com";
	private $domain="";
	private $accNoCookies="true";		//true = display an error message if the user has deactivated cookies
	private $inactiveMin="35"; 		//The time in minutes to force new login, if account has been inactive
	private $enblRemember="true"; 		// set true to enable Remember Me function
	private $cookieRemName="remnam";		// name of username cookie
	private $cookieRemPass="rempas";		// name of password
	private $cookieExpDays="30"; 		// num of days, when remember me cookies expire
	private $errorNoCookies="YOU MUST ACCEPT COOKIES TO PROCCED!";
	private $errorNoLogin="PLEASE LOGIN FIRST TO VIEW THIS PAGE!";
	private $errorInvalid="INVALID USERNAME OR PASSWORD!";
	private $errorDelay="YOUR ACCOUNT HAS BEEN INACTIVE FOR TOO LONG OR YOU HAVE USED THE LOGIN MORE THAN ONCE! THIS SESSION IS NO LONGER ACTIVE!";
	private $AUTH="AUTHN";
	private $HMT="HMTN";
        private $AUTHCHECKSUM="AUTHCHECKSUM";
	public $allowDeactive="";
	public $allowUsernameLogin=false;	
        private $dateTime1 ='11';
        private $dateTime2 ='22';
	/*
	**** @function: Class Constructor
	**** @include: the class configuration file: Mysql.class.php
	*/
	function __construct()
	{
		$this->domain="";
		$this->expiryTime = 2592000; //30*24*60*60 seconds
		//include("Mysql.class.php");
	}
	


	/*
	**** @function: checkSession(called by class constructor or by checkLogin)
	**** calls hasCookie() and checks if the $globalConfig['acceptNoCookies'] is true;
	**** if no cookie was set and we do not accept that -> makes an error message; else:
	**** checks if a session is active: if not -> checkPost() (checks if some post was sent);
	**** if session exists, it checks if some $_POST['action']==logout -> makeLogout();
	**** if not -> checkTime();
	*/
	public function checkSession($checksum="",$from_input_profile="")	//to be called from authentication func of connect_auth
	{
		if (!$this->js_checkCookie() )
		{
			//$this->errorMsg=$this->errorNoCookies;
			//$this->makeErrorHtml();
			return NULL;
		}
		else
		{
			include_once(JsConstants::$docRoot."/classes/LoginTracking.class.php");
			if ( !isset($_COOKIE[$this->AUTH]) && !isset($_COOKIE[$this->HMT]) && $this->enblRemember && isset($_COOKIE[$this->cookieRemName]) && isset($_COOKIE[$this->cookieRemPass]) )
			{
				//echo 'in rem';
				return $this->checkRemember();
			}
			else
			{
				//echo 'in auth';
				return $this->authentication($checksum,$from_input_profile);
			}
		}
	}




	/*
	**** @function: js_checkCookie
	**** checks if the client's browser accepts cookies or not.
	**** if yes, it returns true;
	**** if not -> it returns false;
	*/
	public function js_checkCookie()
	{
		if(isset($_COOKIE['test_js']))
			return true;
		else
			//return false;
			return true;
			//we were getting problem on systems where system time is not correct.
	}




	/*
	**** @function: logout
	**** deletes all the cookies of the user.
	*/
	public function logout()	//to be changed in connect_auth.inc
	{
		$temp=$this->explode_assoc('=',':',$_COOKIE[$this->AUTH]);
		if($temp[PR])
		{
			if(MobileCommon::isMobile())
			{
				//For JSMS ,disable notifications 
			    $channel = "M";
			    $registrationIdObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION();
			    $registrationIdObj->updateNotificationDisableStatus($temp[PR],$channel,'Y'); 
			    unset($registrationIdObj);
			}

			$mysql= new Mysql;
			$db=$mysql->connect();
			$myDbName=getProfileDatabaseConnectionName($temp[PR]);
			$myDb=$mysql->connect("$myDbName");

			$ip=FetchClientIP();//Gets ipaddress of user
			if(strstr($ip, ","))
			{
				$ip_new = explode(",",$ip);
				$ip = $ip_new[1];
			}
			$ip=trim($ip);
			$ip=mysql_real_escape_string($ip);
			$temp[PR]=mysql_real_escape_string($temp[PR]);
			$logTime=date("Y-m-d H:i:s");
			$sql="insert into LOG_LOGOUT_HISTORY(PROFILEID,IPADDR,`TIME`) values ($temp[PR],'$ip','$logTime')";
			$mysql->executeQuery($sql,$myDb);
		}
		global $smarty;
		$this->domain="";
		$this->removeLoginCookies();
		$this->removeRecentLog($temp[PR]);	
		$smarty->assign("CHECKSUM","");
                $smarty->assign("CHECKSUM4CHAT","");
                $smarty->assign("PROFILEID4CHAT","");
                $smarty->assign("CURRENTGENDER","");
                $smarty->assign("CURRENTACTIVATED","");
                $smarty->assign("CURRENTUSERNAME","");
                $smarty->assign("MYPROFILECHECKSUM","");
	}

	/* function removes user login cookies and other cookies
	*/
	function RemoveLoginCookies()
	{
		@setcookie("JS_LAST_LOGIN_DT","",0,"/",$this->domain);
                @setcookie("JS_CASTE","",0,"/",$this->domain);
                @setcookie("JS_MTONGUE","",0,"/",$this->domain);
                @setcookie("JS_INCOME","",0,"/",$this->domain);
                @setcookie("JS_RELIGION","",0,"/",$this->domain);
                @setcookie("JS_AGE","",0,"/",$this->domain);
                @setcookie("JS_FRESHNESS","",0,"/",$this->domain);
                @setcookie("CHATUSERNAME","",0,"/",$this->domain);
                @setcookie("CHATPROFILEID","",0,"/",$this->domain);
                @setcookie("SEARCH_JS","",0,"/",$this->domain);
                @setcookie("CUST_USER","",0,"/",$this->domain);
                @setcookie("save_search_c","",0,"/",$this->domain);
                @setcookie("INVALID_EMAIL","",0,"/",$this->domain);
                @setcookie("INVALID_PHONE","",0,"/",$this->domain);
                @setcookie("JS_HAVEPHOTO","",0,"/",$this->domain);
                @setcookie("JS_HEIGHT","",0,"/",$this->domain);
                @setcookie("JSSearchId","",0,"/",$this->domain);
		$_COOKIE[$this->HMT]="";
		$_COOKIE[$this->AUTH]="";
		$_COOKIE[$this->AUTHCHECKSUM]="";
		$_COOKIE[$this->cookieRemName]="";
		$_COOKIE[$this->cookieRemPass]="";
                @setcookie($this->HMT,"",0,"/",$this->domain);
                @setcookie($this->AUTH,"",0,"/",$this->domain);
                @setcookie($this->AUTHCHECKSUM,"",0,"/",$this->domain);
                //added by manoranjan for removing chatbar cookie
                @setcookie("chatbar","",0,"/",$this->domain);

                @setcookie($this->cookieRemName,"",0,"/",$this->domain);
                @setcookie($this->cookieRemPass,"",0,"/",$this->domain);
	}


	/*
	**** @function: authentication
	**** gets the time of the last page access from HMT cookie and
	**** compares this time with the time now. If the elapsed minutes>inactiveMin (configuration);
	**** or some cookie is missing or value isnt proper -> it creates an error page
	**** if not -> sets the current time in HMT cookie.
	*/
	public function authentication($checksum_hard,$from_input_profile="")
	{
		$this->domain="";
		global $smarty,$checksum,$memcacheObj,$no_time_check;
                $checksum='';
		$insertIntoLoginHistory=0;
                if($from_input_profile!='y')
                $checksum_hard='';
		$smarty->assign("DAY_LIMIT",100);
		$smarty->assign("WEEKLY_LIMIT",500);
                $smarty->assign("MONTH_LIMIT",1000);
                $smarty->assign("OVERALL_LIMIT",10000);
                $smarty->assign("TOTAL_CONTACTS_MADE",0);
		/*if(!$checksum_hard)	this should have been here for increased security but bocs viewprofile pages become static urls, checksum was not passed.
			return NULL;
		*/

	
		if($tmp = $this->js_decrypt($checksum_hard))
			$checksum_simple = $tmp;
		
		if ( (isset($_COOKIE[$this->AUTH]) || isset($_COOKIE[$this->HMT])) && !$from_input_profile)
		{
			$nowtime=time();
			$inactiveSec=$nowtime-$_COOKIE[$this->HMT];
			
			$temp=$this->explode_assoc('=',':',$_COOKIE[$this->AUTH]);
			$temp['ID']=substr($temp['ID'],0,strpos($temp[ID],$this->encryptSeprator));
            $tempPr=$this->js_encrypt(md5($temp['PR'])."i".$temp['PR']);
            $tempPr=substr($tempPr,0,strpos($tempPr,$this->encryptSeprator));
			if ($inactiveSec/60>$this->inactiveMin)
			{
				//$this->errorMsg=$this->errorDelay;
				//$this->makeErrorHtml();
				if ( $this->enblRemember && !isset($_COOKIE[$this->cookieRemName]) && !isset($_COOKIE[$this->cookieRemPass]) || (!$this->enblRemember)  )
					return NULL;
				else
				{
					$insertIntoLoginHistory=1;
					$loginTracking=LoginTracking::getInstance($temp['PR']);
					$loginTracking->setChannel("R");
					$loginTracking->loginTracking($_SERVER['REQUEST_URI']);
				}
			}
			//print_r($temp);
			//die('cya');
			/*echo 'checkusm is '.$checksum;
			echo '<br>';
			echo 'temp id is '.$temp['ID'];
			echo '<br>';
			echo 'checksum hard is '.$checksum_hard;
			echo '<br>';*/
            
			if( !$temp['ID'] || !$temp['PR'] || !$temp['US'] || !$temp['GE'] || !$temp['AC'] )
			{	//print_r($temp);
				//die('1');
				return NULL;}
			elseif( $checksum_hard!='' && $temp['ID'] != $checksum_hard )
			{//print_r($temp);
			//die('2');
				return NULL;}
			elseif( $temp['ID'] != $tempPr )
			{//print_r($temp);
				//die('3');
				return NULL;}
			
			@setcookie($this->HMT,$nowtime,0,"/",$this->domain);

			///Getting memcache value, that was set when profile is screened, if value is 2  than get all the values for db and reset the auth cookie.
	                $key_screen="PROF_SCREEN_".$temp['PR'];
	                $value_screen=memcache_call($key_screen);
			if($value_screen==2 && $temp['PR'])
			{
				$mysql= new Mysql;
				$db=$mysql->connect();
				$sql="select PROFILEID,PASSWORD, SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES from JPROFILE where activatedKey=1 and PROFILEID='$temp[PR]'";
				$result=$mysql->executeQuery($sql,$db);
				$myrow=mysql_fetch_array($result);
				$data["PROFILEID"]=$myrow["PROFILEID"];
				$data["USERNAME"]=$myrow["USERNAME"];
				$data["GENDER"]=$myrow["GENDER"];
				$data["ACTIVATED"]=$myrow["ACTIVATED"];
				$data["SUBSCRIPTION"]=$myrow["SUBSCRIPTION"];
				$data["SOURCE"]=$myrow["SOURCE"];
				$data["COUNTRY_RES"]=$myrow["COUNTRY_RES"];
				$data["HAVEPHOTO"]=$myrow["HAVEPHOTO"];
				$this->setcookies($myrow);
				//Unsetting value in memcache , which was set during screening.
				memcache_call($key_screen,"");

			}
			else
			{
				$data["PROFILEID"]=$temp['PR'];
				$data["USERNAME"]=$temp['US'];
				$data["GENDER"]=$temp['GE'];
				$data["ACTIVATED"]=$temp['AC'];
				$data["SUBSCRIPTION"]=$temp['SU'];
				$data["SOURCE"]=$temp['SO'];
				$data["CHECKSUM"]=$temp['ID'];
				$data["HAVEPHOTO"]=($temp['HP']=='BL')?'':$temp['HP'];//BL means Blank Value
			}
			
		}
		elseif( isset($_COOKIE['JSMBLOGIN']) || $from_input_profile=='y')
		{
			$temp=$this->explode_assoc('=',':',$_COOKIE[$this->AUTH]);
			$temp['ID']=substr($temp['ID'],0,strpos($temp[ID],$this->encryptSeprator));
			$tempPr=$this->js_encrypt(md5($temp['PR'])."i".$temp['PR']);
			$tempPr=substr($tempPr,0,strpos($tempPr,$this->encryptSeprator));
                        if( $checksum_hard!='' && $temp['ID'] != $checksum_hard )
                        {
                                return NULL;
			}
                        elseif( $temp['ID'] != $tempPr )
                        {
                                return NULL;
			}

			$id=$temp[PR];
		
			$mysql= new Mysql;
			$db=$mysql->connect();	

 			$sql="select PROFILEID,PASSWORD, SUBSCRIPTION,USERNAME,GENDER,ACTIVATED,SOURCE,COUNTRY_RES from JPROFILE where  activatedKey=1 and PROFILEID='$id'";

			$result=$mysql->executeQuery($sql,$db);
			$myrow=mysql_fetch_array($result);
			$myrow["SUBSCRIPTION"]=trim($myrow["SUBSCRIPTION"]);

			$data["PROFILEID"]=$myrow["PROFILEID"];
			$data["USERNAME"]=$myrow["USERNAME"];
			$data["GENDER"]=$myrow["GENDER"];
			$data["ACTIVATED"]=$myrow["ACTIVATED"];
			$data["SUBSCRIPTION"]=$myrow["SUBSCRIPTION"];
			$data["SOURCE"]=$myrow["SOURCE"];
			$data["COUNTRY_RES"]=$myrow["COUNTRY_RES"];
			$data["CHECKSUM"]=$checksum_hard;
			$data["HAVEPHOTO"]=$myrow["HAVEPHOTO"];
			if($from_input_profile=='y')
				$this->setcookies($myrow);
		}
		$bmsObj = new BMSHandler();
		$zedo = $bmsObj->setBMSVariable($data,1,$request);
		$smarty->assign("zedoVariable",$zedo);
		$checksum=$data["CHECKSUM"];	
	
		if(!$data)
			return NULL;

		if(substr($data["SOURCE"],0,2)=="mb")
			$data["BUREAU"]=1;
		else
			$data["BUREAU"]=0;
		
		//customisedusername($data["PROFILEID"]);

		if($data["GENDER"]=='F')
		{
			$smarty->assign("lage","18");
			$smarty->assign("hage","35");
		}
		else
		{
			$smarty->assign("lage","21");
			$smarty->assign("hage","40");
		}

		//below code should be removed from here later.
		$all_limit=set_contact_limit($data["SUBSCRIPTION"]);

		$day_limit=$all_limit[0];
		$weekly_limit=$all_limit[1];
		$month_limit=$all_limit[2];
		$overall_limit=$all_limit[3];

		//below code should be removed from here later.
		$data['DAY_LIMIT']=$day_limit;
		$data['MONTH_LIMIT']=$month_limit;
		$data['OVERALL_LIMIT']=$overall_limit;
		$data['WEEKLY_LIMIT']=$weekly_limit;
		$data['NOT_VALID_NUMBER_LIMIT']=$all_limit[4];	
		if($data['USERNAME']=="ZTS5384")
		{
			$data['MONTH_LIMIT']=10000;
		}

		if($data["ACTIVATED"]=="H")
                        $smarty->assign("HIDE_UNHIDE","U");
                else
                        $smarty->assign("HIDE_UNHIDE","H");

		$smarty->assign("DAY_LIMIT",$data['DAY_LIMIT']);
		$smarty->assign("WEEKLY_LIMIT",$data['WEEKLY_LIMIT']);
		$smarty->assign("MONTH_LIMIT",$data['MONTH_LIMIT']);
		$smarty->assign("OVERALL_LIMIT",$data['OVERALL_LIMIT']);
		
		$smarty->assign("TOTAL_CONTACTS_MADE",0);
		$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
		$smarty->assign("CHECKSUM4CHAT",$data["CHECKSUM"]);
		$smarty->assign("PROFILEID4CHAT",$data["PROFILEID"]);
		$smarty->assign("CURRENTGENDER",$data["GENDER"]);
		$smarty->assign("CURRENTACTIVATED",$data["ACTIVATED"]);
		$smarty->assign("CURRENTUSERNAME",$data["USERNAME"]);
		$smarty->assign("MYPROFILECHECKSUM",md5($data["PROFILEID"]) . "i" . $data["PROFILEID"]);

		//Used to set the headers and footers
		common_assign($data);
		self::sendContactCacheInitiateRequest($data["PROFILEID"]);

		self::sendContactCacheInitiateRequest($data["PROFILEID"]);

		//If enable remember and session timeout done
		if($data[PROFILEID] && $insertIntoLoginHistory)
			$this->insert_into_login_history($data[PROFILEID]);
		//added by manoranjan for implementing chat bar for remember me
		//print_r(">>>>>>".isset($_COOKIE['cool_chat']));
		if(isset($_POST))
			$cnt_post=count($_POST);
	
		$this->LogUserEntry($data[PROFILEID]);
		if($_GET['ajax_error']>0)
			$cnt_post=1;
		if(!$cnt_post)	
			$this->ShowChatBar();
		
		
		return $data;
	}



	public function ShowChatBar()
	{
		global $isMobile;
		if( (!isset($_COOKIE['chatbar']) || $_COOKIE['chatbar']=='deleted') && isset($_COOKIE[$this->AUTH]) && ($_COOKIE['JS_MOBILE']=='N')){
                        //die;
                        $request_uri=$_SERVER['REQUEST_URI'];
                        //print_r("request_uri is >>>>".$request_uri);
                        $fileslist=array("login.php","login_redirect.php","social/import","register/","sugarcrm","autoSug");

                        $iftrue=true;
                        foreach($fileslist as $key=>$val)
                        {
                                $pos = strpos($request_uri,$val);
                                if(!($pos===false))
                                        $iftrue=false;

                        }
                        /*if($iftrue && !$isMobile){
                                        header("Location:".$SITE_URL."/profile/intermediate.php?parentUrl=".$request_uri);
                                        exit;
                                }*/
                        //}
                }

	}
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
	**** @function: checkRemember (called by checkSession() if no session is active)
	**** checks if some username + password cookies were set and if we have this function enabled;
	**** if yes -> it matches it with database and logins user if everything is correct.
	*/
	public function checkRemember()
	{
		global $smarty,$isMobile;

		$day_limit=100;
                $month_limit=500;
                $overall_limit=500;
                $smarty->assign("DAY_LIMIT",$day_limit);
                $smarty->assign("MONTH_LIMIT",$month_limit);
                $smarty->assign("OVERALL_LIMIT",$overall_limit);
                $smarty->assign("TOTAL_CONTACTS_MADE",0);

		$username=$_COOKIE[$this->cookieRemName];
		$password=$_COOKIE[$this->cookieRemPass];

		if( !strstr($username,'@') )
		{
			$username_temp=$this->get_correct_username($username);
			if($username_temp)
				$username=$username_temp;
		}

		$mysql= new Mysql;
		$db=$mysql->connect();

		$sql="select PROFILEID, PASSWORD, SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT from JPROFILE where  activatedKey=1 and USERNAME = '" .mysql_real_escape_string($username). "' and ACTIVATED<>'D'";
		$result=$mysql->executeQuery($sql,$db);

		if(mysql_num_rows($result) <= 0)
		{
			$sql="select PROFILEID,PASSWORD,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT from JPROFILE where  activatedKey=1 and EMAIL = '" .mysql_real_escape_string($username). "' and ACTIVATED<>'D'";
			$result=$mysql->executeQuery($sql,$db);

			if(mysql_num_rows($result) <= 0)
				return NULL;
		}

		$myrow=mysql_fetch_array($result);

		$pwdData = PasswordHashFunctions::unmixString($myrow['PASSWORD']);
		$pwd = PasswordHashFunctions::encrypt($pwdData['STRING1'],$this->remSalt,$this->mixer);
		if(!PasswordHashFunctions::slowEquals($pwd,mysql_real_escape_string($password)))
			return NULL;
		
		$myrow["SUBSCRIPTION"]=trim($myrow["SUBSCRIPTION"]);

		$data["PROFILEID"]=$myrow["PROFILEID"];
		$data["USERNAME"]=$myrow["USERNAME"];
		$data["GENDER"]=$myrow["GENDER"];
		$data["ACTIVATED"]=$myrow["ACTIVATED"];
		$data["SUBSCRIPTION"]=$myrow["SUBSCRIPTION"];
		$data["SOURCE"]=$myrow["SOURCE"];
		$checksum=$this->js_encrypt(md5($myrow["PROFILEID"])."i".$myrow["PROFILEID"]);
		$data["CHECKSUM"]=$checksum;
		$data["HAVEPHOTO"]=$myrow["HAVEPHOTO"];
		$data["INCOMPLETE"]=$myrow["INCOMPLETE"];
		$data["MOD_DT"]=$myrow["MOD_DT"];
		
		$this->setcookies($myrow);
		
		$sql="INSERT IGNORE INTO newjs.AUTOLOGIN_LOGIN (PROFILEID,ENTRY_DT) VALUES('$data[PROFILEID]',CURDATE())";
		$mysql->executeQuery($sql,$db);
		
		//tracking #2550		
		$loginTracking=LoginTracking::getInstance($data[PROFILEID]);
		$loginTracking->setChannel("R");
		
		$loginTracking->loginTracking($_SERVER['REQUEST_URI']);
		
		$this->insert_into_login_history($data["PROFILEID"]);

		//customisedusername($data["PROFILEID"]);
		incorrect_count_track($data);

		//Log onlne records
		$this->LogUserEntry($data[PROFILEID]);

		$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
		$smarty->assign("CHECKSUM4CHAT",$data["CHECKSUM"]);
		$smarty->assign("CURRENTUSERNAME",$data["USERNAME"]);
		$smarty->assign("CURRENTGENDER",$data["GENDER"]);
		$smarty->assign("CURRENTACTIVATED",$data["ACTIVATED"]);
		$smarty->assign("MYPROFILECHECKSUM",md5($data["PROFILEID"]) . "i" . $data["PROFILEID"]);
		//echo "coming to check remember function";//added by manoranjan
		$this->ShowChatBar();
		$request_uri=$_SERVER['REQUEST_URI'];
		header("Location:".$SITE_URL.$request_uri);
		exit;

		//return $data;
	}




	/*
	**** @function: login
	**** checks if some $_POST['username'] and $_POST['password'] was sent;
	**** If not -> it creates an error page;
	**** if yes -> it compares the $_POST with the username and password on database;
	**** if all ok -> it sets the appropriate cookie and logins user.
	*/
	public function login()		//to be changed in connect_auth.inc
	{
		
		$track_tm['ST_TM']=microtime(true);
		global $smarty;
		
		if ( !$_POST )
			return NULL;	
		if ( !$this->js_checkCookie() )
		{	
			$smarty->assign("NO_COOKIES_SET","1");	
			return NULL;	
		}	

		$username=trim($_POST['username']);
		$password=trim($_POST['password']);
		
		if(!$username || !$password)
			return NULL;	

		if(get_magic_quotes_gpc())
		{
			$username=stripslashes($username);
			$password=stripslashes($password);
		}
		$imp_email_check=0;
		
		if( !strstr($username,'@') && $this->allowUsernameLogin)
		{
			$username_temp=$this->get_correct_username($username);
			if($username_temp)
				$username=$username_temp;
		}
		else
		{
				$imp_email_check=1;
		}

		$mysql= new Mysql;
		$db=$mysql->connect();
	
		$first_check="USERNAME";
		$second_check="EMAIL";
		if($imp_email_check==1)
		{
			$first_check="EMAIL";
			$second_check="USERNAME";
		}
		//Only Email login is allowed
		if(!$imp_email_check && !$this->allowUsernameLogin)
			return null;

		$sql="select PROFILEID,PASSWORD,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES from JPROFILE where $first_check = '" .mysql_real_escape_string($username). "'";
		$result=$mysql->executeQuery($sql,$db);
		$myrow=mysql_fetch_array($result);
		$numRows = mysql_num_rows($result);
		if(mysql_num_rows($result) <= 0 && $this->allowUsernameLogin)
		{
			$sql="select PROFILEID,PASSWORD,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES from JPROFILE where $second_check = '" .mysql_real_escape_string($username). "'";
			$result=$mysql->executeQuery($sql,$db);
			$myrow=mysql_fetch_array($result);
			$numRows = mysql_num_rows($result);
		}
		if($numRows<=0)
			return NULL;
		include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		if(!PasswordHashFunctions::validatePassword($password, $myrow['PASSWORD']))
			return NULL;
		return $this->postLogin($myrow);	
	}
	public function postLogin($myrow)
	{
		$mysql= new Mysql;
		$db=$mysql->connect();
                if($myrow['ACTIVATED']=='D')
                {
                                $sql="select PROFILEID from newjs.JSARCHIVED where PROFILEID='$myrow[PROFILEID]' and STATUS ='Y'";
                                $res=$mysql->executeQuery($sql,$db);
                                $arow=mysql_fetch_array($res);
                                if(mysql_num_rows($res) <= 0)
                                {
                                        if(!$this->allowDeactive)
                                                return NULL;
                                        else
                                                return $myrow;
                                }
                                else
                                {
                                        global $ajaxValidation;

                                        if($ajaxValidation)
                                        {
                                                echo "JA";
                                        }
                                        else
                                        {
                                                HEADER("LOCATION:/profile/retrieve_archived.php");
                                        }

                                        die;
                                        //return $myrow['PROFILEID'];
                                }
                }
		global $smarty;
		$myrow["SUBSCRIPTION"]=trim($myrow["SUBSCRIPTION"]);
		$data["PROFILEID"]=$myrow["PROFILEID"];
		$data["USERNAME"]=$myrow["USERNAME"];
		$data["GENDER"]=$myrow["GENDER"];
		$data["ACTIVATED"]=$myrow["ACTIVATED"];
		$data["SUBSCRIPTION"]=$myrow["SUBSCRIPTION"];
		$data["SOURCE"]=$myrow["SOURCE"];
		$checksum=$this->js_encrypt(md5($myrow["PROFILEID"])."i".$myrow["PROFILEID"]);
		$data["CHECKSUM"]=$checksum;
		$data["HAVEPHOTO"]=$myrow["HAVEPHOTO"];
		$data["INCOMPLETE"]=$myrow["INCOMPLETE"];
		$data["MOD_DT"]=$myrow["MOD_DT"];
		$data["COUNTRY_RES"]=$myrow["COUNTRY_RES"];
	
		$this->setcookies($myrow);
		$track_tm['LH_BF_TM']=microtime(true);
		$this->insert_into_login_history($data["PROFILEID"]);
		$track_tm['LH_AF_TM']=microtime(true);
		//customisedusername($data["PROFILEID"]);
		$ret_val=incorrect_count_track($data);
		$track_tm['INC_CNT_TM']=microtime(true);
		//login_relogin_auth($data);	//to be added in connect_functions.inc
		$track_tm['LOG_REL_TM']=microtime(true);

		//Log user entry
		$this->LogUserEntry($data[PROFILEID]);
		
		$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
		$smarty->assign("CHECKSUM4CHAT",$data["CHECKSUM"]);
		$smarty->assign("CURRENTUSERNAME",$data["USERNAME"]);
		$smarty->assign("CURRENTGENDER",$data["GENDER"]);
		$smarty->assign("CURRENTACTIVATED",$data["ACTIVATED"]);
		$smarty->assign("MYPROFILECHECKSUM",md5($data["PROFILEID"]) . "i" . $data["PROFILEID"]);
		if($data["INCOMPLETE"]=='Y')
	        is_complete_now($data["PROFILEID"]);
		$track_tm['FINAL_TM']=microtime(true);
		//Incorrect count tracking function is not returning value from memcache,then only log data.
		if($ret_val!=1)
			$this->logging_time_taken($track_tm);
		self::sendContactCacheInitiateRequest($data[PROFILEID]);
		return $data;
	}



	public function logging_time_taken($track_tm)
	{

		$st_tm=$track_tm['ST_TM'];
		$bef_lg_tm=$track_tm['LH_BF_TM']-$st_tm;
		$af_lg_tm=$track_tm['LH_AF_TM']-$track_tm['LH_BF_TM'];
		$inc_tm=$track_tm['INC_CNT_TM']-$track_tm['LH_AF_TM'];
		$log_rel_tm= $track_tm['LOG_REL_TM']-$track_tm['INC_CNT_TM'];
		$final_tm=$track_tm['FINAL_TM']-$track_tm['LOG_REL_TM'];
/*		
		$sql="insert ignore into MIS.tracking_log(`BEF_LH`,`AFT_LH`,`GET_CNT_TM`,`LG_REL_TM`,`END`,`TODAY_TIME`) values($bef_lg_tm,$af_lg_tm,$inc_tm,$log_rel_tm,$final_tm,now())";
		mysql_query_decide($sql) or die(mysql_error_js());
*/
	}
	public function setchatbarcookie()
	{
		@setcookie("chatbar","yes",0,"/",$this->domain);
	}
	/*
	**** @function: setcookies
	**** it sets all the necessary cookies + the one required for login ie AUTH + HMT
	*/
	public function setcookies($myrow)
	{
		$this->domain="";
		global $isMobile;
		@setcookie("test_js","y",time()+ (60*60*24*365),"/",$this->domain);

		if ($this->enblRemember && @$_POST['rememberme']=="Y")
		{
			@setcookie($this->cookieRemName,@$_POST['username'],time()+(60*60*24*$this->cookieExpDays),"/",$this->domain);
			$pwdData = PasswordHashFunctions::unmixString($myrow['PASSWORD']);
			$salt = $pwdData['STRING2'];
			$pwdHash = PasswordHashFunctions::encrypt(@$_POST['password'],$salt);
			$pwd = PasswordHashFunctions::encrypt($pwdHash,$this->remSalt,$this->mixer);
			@setcookie($this->cookieRemPass,$pwd,time()+(60*60*24*$this->cookieExpDays),"/",$this->domain);
		}
        if(!$isMobile){
			//Setting is_nri to 1 if not from india
			if($myrow['COUNTRY_RES'])
				if($myrow['COUNTRY_RES']!=51)
					setcookie("IS_NRI",1,0,"/",$this->domain);	

			//income below sorted by (value of income), used in 3d
			if($myrow["INCOME"])
			{	
				if($myrow["INCOME"]==0)
					$myrow["INCOME"]=15;
				if($myrow["INCOME"]==15)
					$myrow["INCOME"]=1;
				elseif($myrow["INCOME"]<=7)
					$myrow["INCOME"]++;
				elseif($myrow["INCOME"]>=8 && $myrow["INCOME"]<=14)
					$myrow["INCOME"]+=4;
				elseif($myrow["INCOME"]>=16 && $myrow["INCOME"]<=18)
					$myrow["INCOME"]-=7;
				@setcookie("JS_INCOME",$myrow["INCOME"],0,"/",$this->domain);
			}
			if($myrow["CASTE"])
				@setcookie("JS_CASTE",$myrow["CASTE"],0,"/",$this->domain);
			if($myrow["MTONGUE"])
				@setcookie("JS_MTONGUE",$myrow["MTONGUE"],0,"/",$this->domain);
			if($myrow["RELIGION"])
				@setcookie("JS_RELIGION",$myrow["RELIGION"],0,"/",$this->domain);
			if($myrow["AGE"])
				@setcookie("JS_AGE",$myrow["AGE"],0,"/",$this->domain);
			if($myrow["HEIGHT"])
				@setcookie("JS_HEIGHT",$myrow["HEIGHT"],0,"/",$this->domain);
			if($myrow["HAVEPHOTO"]=='Y')
				@setcookie("JS_HAVEPHOTO",1,0,"/",$this->domain);  
			@setcookie("CHATUSERNAME",$myrow["USERNAME"],0,"/",$this->domain);
			@setcookie("CHATPROFILEID",$myrow["PROFILEID"],time()+300,"/",$this->domain);
		}
		if(substr($myrow["SOURCE"],0,2)!="mb")
		{
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
		}

	}




	/*
	**** @function: insert_into_login_history
	**** inserts data into login_history and updates last_login_dt of jprofile.
	*/
	public function insert_into_login_history($profileid)
	{
                if (!strstr($_SERVER["REQUEST_URI"],'notification/poll') && !strstr($_SERVER["REQUEST_URI"],'notification/deliveryTracking'))
                {
		$mysql= new Mysql;
                $myDbName=getProfileDatabaseConnectionName($profileid);
                $db=$mysql->connect();
                $myDb=$mysql->connect("$myDbName");

		$ip=FetchClientIP();//Gets ipaddress of user
        	if(strstr($ip, ","))
	        {
        	        $ip_new = explode(",",$ip);
                	$ip = $ip_new[1];
	        }
		$ip=trim($ip);
		$ip=mysql_real_escape_string($ip);
		$logTime=date("Y-m-d H:i:s");
		$sql="insert into LOG_LOGIN_HISTORY(PROFILEID,IPADDR,`TIME`) values ($profileid,'$ip','$logTime')";
		$mysql->executeQuery($sql,$myDb);
		
                $sql="insert ignore into LOGIN_HISTORY(PROFILEID,LOGIN_DT) values ('" . $profileid . "',now())";
                $mysql->executeQuery($sql,$myDb);


                if($mysql->affectedRows()<=0)
                {
                        $sql="update newjs.LOGIN_HISTORY_COUNT  set TOTAL_COUNT=TOTAL_COUNT+1 where PROFILEID=".$profileid;
                        $res=$mysql->executeQuery($sql,$myDb);

                        if($mysql->affectedRows()<=0)
                        {
                                $sql="replace into newjs.LOGIN_HISTORY_COUNT(PROFILEID,TOTAL_COUNT) values(".$profileid.",1)";
                                $mysql->executeQuery($sql,$myDb);

                        }
                }
                $jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
                $jprofileUpdateObj->updateJProfileLoginSortDate($profileid);
		//$sql="update JPROFILE set LAST_LOGIN_DT=now(),SORT_DT=if(DATE_SUB(NOW(),INTERVAL 7 DAY)>SORT_DT,DATE_SUB(NOW(),INTERVAL 7 DAY),SORT_DT) where PROFILEID='" . $profileid . "'";
          //      $mysql->executeQuery($sql,$db);
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
	**** input is cipher text that is to be decrypted and output is plain text or false on error
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
			$this->verify_mail($arr[2],$arrTmp[1]);
			
			if($arr[1]){
				$mailedtime = date("Y-m-d H:i:s",$arr[1]);
				$dateAutoLoginToBeStopped=substr($mailedtime,0,10);
				if(($dateAutoLoginToBeStopped=="2015-08-13" ||$dateAutoLoginToBeStopped=="2015-08-14")  && $fromAutoLogin=="Y")
				{
					if($arr[1]<1439555369)
						return false;
				}
			}
			if($fromAutoLogin=="Y" && !$this->isAlive($arr[1],$arrTmp[1]))
            	return false;
            else
				return $arrTmp[1];
		}
		else
			return false;
	}
	
	/*
	**** @function: get_correct_username
	**** it solves the problem of case sensitivity
	*/
        public function get_correct_username($username)		//to be removed from login.php
        {
                $mysql= new Mysql;
		$db=$mysql->connect();

		$sql="select USERNAME from NAMES where USERNAME='" .mysql_real_escape_string($username). "'";
                $result=$mysql->executeQuery($sql,$db);
                if(mysql_num_rows($result)>1)
                {
                       return $username;
                }
                elseif(mysql_num_rows($result)==1)
                {
                        $myrow=mysql_fetch_array($result);
						return $myrow["USERNAME"];
                }
                else
                        return "";
        }



	/*
	**** @function: login_affiliate
	**** it checks whether the user is from affiliate program and returns $data accordingly.
	*/
	public function login_affiliate() 	//to be removed from login.php
        {
		if ( !$_POST  )
                        return NULL;    

                $username=trim($_POST['username']);
                $password=trim($_POST['password']);

                if(!$username || !$password)
                        return NULL;    

                if(get_magic_quotes_gpc())
                {
                        $username=stripslashes($username);
                        $password=stripslashes($password);
                }

		$mysql= new Mysql;
		$db=$mysql->connect();

                $sql="select ID,USERNAME,GENDER,EMAIL,ACTIVATED,EMAIL_VALIDATE,RELIGION,COUNTRY_BIRTH from JPROFILE_AFFILIATE where USERNAME = '" .mysql_real_escape_string($username). "' and PASSWORD = '" .mysql_real_escape_string($password). "' and ACTIVATED<>'D' AND USERNAME<>''";
                $result=$mysql->executeQuery($sql,$db);
                if(mysql_num_rows($result) <= 0)
                {
                        $sql="select ID,USERNAME,GENDER,EMAIL,ACTIVATED,EMAIL_VALIDATE,RELIGION,COUNTRY_BIRTH from JPROFILE_AFFILIATE where EMAIL = '" .mysql_real_escape_string($username). "' and PASSWORD = '" .mysql_real_escape_string($password). "' and ACTIVATED<>'D' AND EMAIL<>''";
                        $result=$mysql->executeQuery($sql,$db);
                        if(mysql_num_rows($result) <= 0)
                                return NULL;
                }
                $myrow=mysql_fetch_array($result);
                $data["id"]=$myrow["ID"];
                $data["username"]=$myrow["USERNAME"];
                $data["user_email"]=$myrow["EMAIL"];
                $data["valid_email"]=$myrow["EMAIL_VALIDATE"];
                $data["religion"]=$myrow["RELIGION"];
                $data["country_birth"]=$myrow["COUNTRY_BIRTH"];
                $data["checksum"]=md5($myrow["ID"])."i".$myrow["ID"];
                return $data;
        }



	public function TimedOut($headmessage="")	//to be changed in connect_auth.inc
	{
		global $checksum, $username, $From_Mail,$smarty,$isMobile,$SITE_URL;
		
		$lang=$_COOKIE["JS_LANG"];
		
		if( isset($_COOKIE[$this->AUTH]) )
		{
			$temp = $this->explode_assoc('=',':',$_COOKIE[$this->AUTH]);
			$checksum = $this->js_decrypt($temp['ID']);
			$gender=$temp['GE'];
                        $smarty->assign("PROFILEID",$temp['PR']);
		}
			
		$smarty->assign("USERNAME",$username);

		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			if($_POST['METHOD']=="GET")
			{
				$smarty->assign("METHOD", "GET");
				$smarty->assign("CHECKSUM",htmlspecialchars($_POST["checksum"], ENT_QUOTES, false));
				$smarty->assign("REQUESTEDURL",htmlspecialchars($_POST['REQUESTEDURL'], ENT_QUOTES, false));
				$smarty->assign("RELOGIN","Y");
			}
			else
			{
				$j=0;
				foreach($_POST as $key => $value)
				{
					if($value != "")
					{
						$data[$j]["NAME"]=htmlspecialchars($key, ENT_QUOTES, false);
						if(is_array($value))
						{
							$data[$j]["VALUE"]="ARRAY";
							$i=0;
							foreach($value as $val)
								if($val != "")
								{
									$data[$j][$i++]=htmlspecialchars($val, ENT_QUOTES, false);
								}
						}
						else
							$data[$j]["VALUE"]=htmlspecialchars($value, ENT_QUOTES, false);
						$j++;
					}
				}
				
				$smarty->assign("ACTION",$_SERVER['REQUEST_URI']);
				$smarty->assign("RELOGIN","Y");
				$smarty->assign("DATA",$data);
				$smarty->assign("METHOD", "POST");
			}
		}
		elseif($_SERVER['REQUEST_METHOD']=="GET")
		{
			$smarty->assign("METHOD", "GET");
			$smarty->assign("CHECKSUM",htmlspecialchars($_GET["checksum"], ENT_QUOTES, false));
			$smarty->assign("REQUESTEDURL",$_SERVER['REQUEST_URI']);
			$smarty->assign("RELOGIN","Y");
		}

		//For mail link to directly pass to the requested URL
		if($From_Mail=='Y')
			$smarty->assign("MAIL","Y");

		if($headmessage)
			$smarty->assign("HEADMESSAGE",$headmessage);

		if($gender=='M')
			$smarty->assign("gender",'F');
		else
			$smarty->assign("gender",'M');

//		$this->login_register();
		
		$smarty->assign("CAME_FROM_TIMEDOUT","1");

		$smarty->assign("var_in","0");
                $smarty->assign("LOGOUT","1");
                $smarty->assign("CURRENTUSERNAME","");
                $smarty->assign("FOOT",$smarty->fetch("footer.htm"));
                $smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
                $smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
                $smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
                $smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
                $smarty->assign("head_tab",'my jeevansathi');
				if($isMobile)
				{
					assignHamburgerSmartyVariables();
					$smarty->assign("LOGIN_ICON",1);
					$smarty->display("mobilejs/jsmb_login.html");
				}
				else
                	$smarty->display("logout_1.htm");

	}

	

	public function login_register()	//move it to connect_functions.inc
	{
		global $smarty,$username;
		include_once($_SERVER["DOCUMENT_ROOT"]."/P/hits.php");
//		include_once("hits.php");
		
		$smarty->assign("USERNAME",$username);

		$lang=$_COOKIE["JS_LANG"];
		if(isset($_COOKIE['JS_SOURCE']))
		{
			$smarty->assign("TIEUP_SOURCE",$_COOKIE['JS_SOURCE']);
		}
		else
		{
			savehit("js_login",$_SERVER['PHP_SELF']);
			$smarty->assign("TIEUP_SOURCE","js_login");
		}
		
		populate_day_month_year();
		
		$smarty->assign("height",create_dd(8,"Height","","","Y"));
		$smarty->assign("income",create_dd("","Income","","","Y"));
		$smarty->assign("city_usa",create_dd("","City_USA","","","Y"));
		$smarty->assign("religion",populate_religion(1,"Y"));
		$smarty->assign("mtongue",create_dd("","Mtongue","","","Y"));
		$smarty->assign("country_residence",create_dd("","Country_Residence"));
		$smarty->assign("top_country",create_dd("51","top_country"));
		$smarty->assign("city_india",create_dd("","City_India"));
		$smarty->assign("state_india",create_dd("","State_India"));
		$smarty->assign("SMALL_HEAD",$smarty->fetch("small_headnew.htm"));

		$smarty->assign("CHECKBOXALERT1","A");
		$smarty->assign("CHECKBOXALERT2","S");

		$smarty->assign("showphone","Y");
		$smarty->assign("showmobile","Y");

		$ccc=create_code("COUNTRY");
		$csc=create_code("CITY_INDIA");
		$smarty->assign("country_isd_code",$ccc);
		$smarty->assign("india_std_code",$csc);
	}


	

	/*
	**** @function: makeErrorHtml
	**** creates the error html page, if something went wrong;
	**** sets MySQL Time Field=0 and SessionID Field='' and closes the session;
	*/
	public function makeErrorHtml()
	{
		if ($_SESSION)
		{
			$db=new mysql_dialog();
			$db->connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbase);
			$SQL="UPDATE ".$this->tbl." SET ";
			$SQL.=$this->tblLastLog."= 0, ";
			$SQL.=$this->tblSessionID."='' ";
			$SQL.="WHERE ".$this->tblID."='".$_SESSION['userID']."'";
			$db->speak($SQL);
		}
		if ($this->enblRemember && isset($_COOKIE[$this->cookieRemName]) && isset($_COOKIE[$this->cookieRemPass]))
		{
			@setcookie($this->cookieRemName,$_COOKIE[$this->cookieRemName],time());
			@setcookie($this->cookieRemPass,$_COOKIE[$this->cookieRemPass],time());
		}
		session_destroy();
		$out="<html>\n<head><title>".$this->errorPageTitle."</title>\n";
		if ($this->errorCssUrl!="")
		{
			$out.="<link rel=\"stylesheet\" type=\"text/css\" href=\"".$this->errorCssUrl."\">\n";
		}
		if ($this->errorCharset!="")
		{
			$out.="<meta http-equiv=\"content-type\" content=\"text/html;charset=".$this->errorCharset."\">\n";
		}
		$out.="</head>\n<body>\n";
		$out.="<h1>".$this->errorPageH1."</h1>\n";
		$out.="<p>".$this->errorMsg."</p>\n";
		$out.="<p><a href=".$this->loginUrl.">".$this->errorPageLink."</a></p>\n";
		$out.="</body>\n</html>";
		print $out;
	}
	public function profile_percent_calculation($pid)
	{
		$mysql= new Mysql;
		$db=$mysql->connect();

		$sql="SELECT HAVEPHOTO,PHOTO_DISPLAY,EDUCATION,JOB_INFO,BTYPE,COMPLEXION,HANDICAPPED,DIET,SMOKE,DRINK,SHOW_HOROSCOPE,COUNTRY_BIRTH,CITY_BIRTH,BTIME,MANGLIK,NAKSHATRA,RES_STATUS,FAMILY_BACK,FAMILY_VALUES,FAMILYINFO,FATHER_INFO,SIBLING_INFO,CONTACT,PHONE_RES,PHONE_MOB,PARENTS_CONTACT,PARENT_CITY_SAME,GOTHRA,SUBCASTE,EDU_LEVEL_NEW,EDU_LEVEL,OCCUPATION,INCOME,PRIVACY FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$pid'";
		$result=$mysql->executeQuery($sql,$db);
		$row=$mysql->fetchAssoc($result);

		return 0;

	}
	
	public function ecrypt($str){
	  $key = $this->_KEY;
	  for($i=0; $i<strlen($str); $i++) {
		 $char = substr($str, $i, 1);
		 $keychar = substr($key, ($i % strlen($key))-1, 1);
		 $char = chr(ord($char)+ord($keychar));
		 $result.=$char;
	  }
	  return urlencode(base64_encode($result));
	}


	public function decrypt($str){
	  if( stripos($str,"%") === false )
		$str=urlencode($str);	
	  
	  $str = base64_decode(urldecode($str));
	  $result = '';
	  $key = $this->_KEY;
	  for($i=0; $i<strlen($str); $i++) {
		$char = substr($str, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
	  }
	return $result;
	}
	/* @function: getProfileFrmChecksum
	*/
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
	public function verify_mail($email,$checksum)
	{
		$profileid=$this->getProfileFrmChecksum($checksum);
		if($profileid && $email)
		{
			$email=$this->decrypt($email);
			$mysql= new Mysql;
			$db=$mysql->connect();
			$sql="select EMAIL from newjs.JPROFILE where PROFILEID='$profileid' and EMAIL='$email' and activatedKey=1";
			$result=$mysql->executeQuery($sql,$db);
			if($row=mysql_fetch_assoc($result))
			{
				$date=date("Y-m-d");
				$sql="select PROFILEID from newjs.VERIFY_EMAIL where PROFILEID='$profileid' and STATUS='Y'";
				$result=$mysql->executeQuery($sql,$db);
				if(!($row=mysql_fetch_assoc($result)))
				{
					$sql="replace into newjs.VERIFY_EMAIL (PROFILEID,ENTRY_DT,STATUS) values('$profileid','$date','Y')";
					$mysql->executeQuery($sql,$db);
				}
			}
		}
	}
	public function authenticate_reg1($reg_checksum="",$p1=""){
		if($p1){
		$arr=explode("r",$this->js_decrypt($reg_checksum));
		if($arr[1]==md5($arr[0])){
			$this->set_cookies_reg1($arr[0],'N');
			return array('REGID'=>$arr[0],'CONVERTED'=>'N');
		}
		else
			return false;
		}
		else{
		$temp=$this->explode_assoc('=',':',$_COOKIE['REG_AUTH']); 
		$reg_checksum=$this->js_decrypt($temp["ID"]);
		$reg_id=$temp["RI"];
		$data['REGID']=$temp["RI"];
		$data['CONVERTED']=$temp['CT'];
		if($reg_checksum==$reg_id."r".md5($reg_id))
			return $data;
		else
			return false; 
		}
	}
	public function set_cookies_reg1($reg_id,$converted='N'){
		$reg_checksum=$reg_id."r".md5($reg_id);
		$cookie_str="ID=".$this->js_encrypt($reg_checksum);
		$cookie_str.=":RI=".$reg_id;
		$cookie_str.=":CT=".$converted;
		setcookie("REG_AUTH",$cookie_str,0,"/");
	}
	public function isAlive($autoLoginTime,$checksum)
    {
    	$profileid=$this->getProfileFrmChecksum($checksum);
    	if($autoLoginTime && $profileid)
    	{
    		$curTime = time();
        	$timediff = $curTime-$autoLoginTime;
            $mailedtime = date("Y-m-d H:i:s",$autoLoginTime);
//            $db = connect_db();
//            $sql = "select count(*) CNT from jsadmin.AUTO_EXPIRY WHERE PROFILEID = '$profileid' AND DATE > '$mailedtime'";
//            $result = mysql_query($sql,$db) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception(mysql_error($result)));
//            $row = mysql_fetch_assoc($result);   
            
            $objAuto_Expiry = new ProfileAUTO_EXPIRY;
            $row = $objAuto_Expiry->getRecord($profileid, $mailedtime);
            
        	if($timediff > $this->expiryTime || $row['CNT']) //30*24*60*60 seconds or email or password changed after mail sent.
        		return false;
        	else
           	return true;
    	}
    }
	public function LogUserEntry($pid)
	{
		$time=$_COOKIE["LOGUSERENTRY"];
		$allow=0;
		if(is_numeric($pid))
                {
                        $mysql= new Mysql;
                        $db=$mysql->connect();
			if(!$time)
				$allow=1;
			else
			{
				$timepassed=time()-$time;
				if($timepassed>400)
					$allow=1;
			}
                        if($allow)
                        {
				/*
				$time=date("Y-m-d G:i:s");
                                $sql="replace into userplane.recentusers(userID,lastTimeOnline) values('$pid','$time')";
                                $mysql->executeQuery($sql,$db);*/

	                        // Add Online-User
	                        $dateTime =date("H");
	                        $redisOnline =true;
	                        if(($dateTime>=$this->dateTime1) && ($dateTime<$this->dateTime2))
	                                $redisOnline =false;
				if($redisOnline){
	                        	$jsCommonObj =new JsCommon();
	                        	$jsCommonObj->setOnlineUser($pid);
				}
                        }
                }
                
                @setcookie("LOGUSERENTRY",time(),0,"/",$this->domain);

	}
	public function removeRecentLog($pid)
	{
		if(is_numeric($pid))
                {
			/*
                        $mysql= new Mysql;
                        $db=$mysql->connect();
                        $sql="delete from  userplane.recentusers where userID='$pid'";
                        $mysql->executeQuery($sql,$db);*/

                        // Remove Online-User
                        $dateTime =date("H");
                        $redisOnline =true;
                        if(($dateTime>$this->dateTime1) && ($dateTime<$this->dateTime2))
                                $redisOnline =false;
			if($redisOnline){
	                        $jsCommonObj =new JsCommon();
	                        $jsCommonObj->removeOnlineUser($pid);
			}
                }
	}

	public static function sendContactCacheInitiateRequest($profileid)
	{
		include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		CommonUtility::sendtoRabbitMq($profileid);
	}
	
}

?>
