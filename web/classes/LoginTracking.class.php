<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once("Mysql.class.php");
class LoginTracking
{
	/***
	**** @class: LoginTracking
	**** @version: 1.0;
	**** @author: Nitesh Sethi
	**** @date: 13th Feb 2014
	**** @license: GNU GENERAL PUBLIC LICENSE;
	****
	**** This class tracks all the logins happeing on the site
	****/
	private static $instance;
	private $profileId;
	private $requestURI;
	private $channel;
	private $websiteVersion;
	//private $stype;


		/**
         * @fn __construct
         * @brief Constructor function
         * @param $profileid - Unique profileid of profile
         */
        public function __construct($profileId=""){
			
			if($profileId)	$this->profileId=$profileId;
			$this->setWebisteVersion();
			$this->setChannel();
        }

        /**
         * @fn getInstance
         * @brief fetches the current instance of the class
         * @param $profileid - Unique profileid of profile
         * @return instance of the last object. If required profileid is not present then returns new object.
         */
        public static function getInstance($profileId="")
        {
			if(isset(self::$instance))
			{
				if($profileId && (self::$instance->getPROFILEID() != $profileId)){
					$class = __CLASS__;
					self::$instance = new $class($profileId);
				}
			}
			else
			{
					$class = __CLASS__;
					self::$instance = new $class($profileId);
			}
                return self::$instance;
        }
        
        //Function to track all logins happening through autologin
		//channel can be Mailer(M),SMS(S)
		//Webiste Version Desktop(D),Mobile(M)
		public function loginTracking($requestURI="",$currentTime='')
		{
                if (!strstr($_SERVER["REQUEST_URI"],'notification/poll') && !strstr($_SERVER["REQUEST_URI"],'notification/deliveryTracking'))
                {
			if($this->profileId && $this->channel && $this->websiteVersion)
			{
				if($requestURI)
					$this->setRequestURI($requestURI);
				if($this->requestURI)
				{
					$page=explode('?',$this->requestURI);
					$pageUrl=$page[0];					
					$pageName=explode('/',$pageUrl);
					$no=count($pageName);
					$pageName=$pageName[$no-1];
					
					$pageStype=$_GET["stype"];
                                        if(!$pageStype){
                                            $pgArr=explode('&',$page[1]);
                                            foreach ($pgArr as $key => $value) {
                                                if(strpos($value,'stype')!==false && (strlen($value)>strlen('stype='))){$tempStr=$value;break;}
                                            }  
                                            if($tempStr){
                                                $tempArr=explode('=',$tempStr);
                                                $pageStype=$tempArr[1];
                                            }
                                        } 
				}
				else
				{
					$pageStype="";
					$pageName="";
				}
				$pageStype=substr($pageStype,0,4);
				$pageStype=addslashes($pageStype);
				$pageName=addslashes($pageName);
                                $time = $currentTime ? $currentTime : date("Y-m-d H:i:s");
                                (new MIS_LOGIN_TRACKING())->insert($this->profileId,$time,$pageName,$this->channel,$this->websiteVersion,$pageStype);
			}
		}
		}
		
		//SETTERSS AND GETTERS
			//profileId
		public function setPROFILEID($profileId)
		{
			$this->profileId=$profileId;
		}
		public function getPROFILEID()
		{
			return $this->profileId;
		}
			//requestURI
		public function setRequestURI($requestURI)
		{
			$this->requestURI=$requestURI;
		}
		public function getRequestURI()
		{
			return $this->requestURI;
		}
			//channel
		public function setChannel($channel='D')
		{
			if($channel)
				$this->channel=$channel;
			else
				$this->channel="D";//for desktop
		}
		public function getChannel()
		{
			return $this->channel;
		}
			//webisteVersion
		public function setWebisteVersion($websiteVersion="")
		{
			if(!$websiteVersion)
			{
				if(MobileCommon::isMobile())
				{
				if(MobileCommon::isNewMobileSite())
					$this->websiteVersion="N";
				else
					$this->websiteVersion="M"; //for mobile version
				}
				else
				$this->websiteVersion="D"; //for desktop version
			}
			elseif($websiteVersion)
					$this->websiteVersion=$websiteVersion;//for APP			
		}
		public function getWebisteVersion()
		{
			return $this->webisteVersion;
		}
}
?>
