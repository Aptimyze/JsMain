<?php
/**
 * @brief This class is service class for all the tuples having all the related functions
 * @author Reshu Rajput
 * @created 2013-10-4
 */
class TupleService
{
	//Declaring the logic and different info type arrays
	static public $logics;
	private $IGNORED_PROFILES = Array();

	private $INTEREST_RECEIVED = Array();
	private $INTEREST_EXPIRING = Array();
	private $INTEREST_ARCHIVED = Array();
	private $FILTERED_INTEREST = Array();
	private $INTEREST_SENT = Array();
	private $ACCEPTANCES_RECEIVED = Array();
	private $ACCEPTANCES_SENT = Array();
	private $MESSAGE_RECEIVED = Array();
	private $DECLINE_RECEIVED = Array();
	private $PHOTO_REQUEST_RECEIVED = Array();
	private $PHOTO_REQUEST_SENT = Array();
	private $INTRO_CALLS = Array();
	private $INTRO_CALLS_COMPLETE = Array();
	private $HOROSCOPE_REQUEST_RECEIVED = Array();
	private $HOROSCOPE_REQUEST_SENT = Array();
	private $NOT_INTERESTED = Array();
	private $NOT_INTERESTED_BY_ME = Array();
	private $CONTACTS_VIEWED = Array();
	private $VISITORS = Array();
	private $MATCH_ALERT = Array();
	private $MATCH_OF_THE_DAY = Array();
	private $VIEW_SIMILAR = Array();
	private $MY_MESSAGE = Array();
	private $MY_MESSAGE_RECEIVED = array();
	private $SHORTLIST = Array();
	private $profileDetailsArray;
	private $loginProfile;
    private $loginProfileObj;
    private $PEOPLE_WHO_VIEWED_MY_CONTACTS=Array();
    private $INTEREST_RECEIVED_FILTER = Array();
    private $FEATURED_PROFILE_TUPLE =  Array();
	// Function to initalize logic array which have logic id and corresponding fields mapping
	static public function initLogics()
	{
		self::$logics = Array(
			"PROFILE_LOGIC" => Array(
				"FIELDS" => Array(
					"PROFILEID",
					"USERNAME",
					"GENDER",
					"AGE",
					"HEIGHT",
					"RELIGION",
					"MTONGUE",
					"OCCUPATION",
					"HAVEPHOTO",
					"PHOTO_DISPLAY",
					"CASTE",
					"MSTATUS",
					"SUBCASTE",
					"INCOME",
					"CITY_RES",
					"COUNTRY_RES",
					"ENTRY_DT",
					"LAST_LOGIN_DT",
					"YOURINFO",
					"SCREENING",
                                        "COMPANY_NAME",
                                        "ANCESTRAL_ORIGIN",
                                        "EMAIL"
					
				),
				"LOGIC" => Array(
					"PROFILECHECKSUM"
				)
			),
			"PHOTO_LOGIC" => Array(
				"FIELDS" => ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS,
				"LOGIC" => Array(
					"IS_ALBUM",
					"IS_ALBUM_TEXT",
					"IS_PHOTO_REQUESTED",
					"MOBPHOTOSIZE"
				)
			),
			"HOROSCOPE_LOGIC" => Array(
				"FIELDS" => Array(),
				"LOGIC" => Array(
					"ASTRO_DETAILS"
				)
			),
			"EDUCATION_LOGIC" => Array(
				"FIELDS" => Array("COLLEGE","UG_COLLEGE"),
				"LOGIC" => Array(
					"edu_level_new"
				)
			),
			"MESSAGE_LOGIC" => Array(
				"FIELDS" => Array(
					"MESSAGE",
					"SENT_MESSAGE"
				),
				"LOGIC" => Array()
			),
			"MY_MESSAGE_LOGIC"=>Array(
				"FIELDS" =>Array(
					"LAST_MESSAGE","COUNT"
					),
				"LOGIC" => Array()
			),
			"CONTACT_LOGIC"=>array(
				"FIELDS"=>array(
					"CONTACTS"),
				"LOGIC"=>Array()
			),
			"BOOKMARK_LOGIC"=>array(
				"FIELDS"=>array(
					"IS_BOOKMARKED"),
				"LOGIC"=>Array()
			),
			"INTEREST_VIEWED_LOGIC" => Array(
				"FIELDS" => Array(),
				"LOGIC" => Array(
					"INTEREST_VIEWED_DATE"
				)
			),
                        "NATIVE_LOGIC" => Array(
				"FIELDS" => Array("NATIVE_CITY","NATIVE_STATE"),
				"LOGIC" => Array()
			),
			"NAME_LOGIC" =>Array(
				"FIELDS" => Array("NAME_OF_USER","DISPLAY_NAME"),
				"LOGIC" =>Array()
			),
			
		);
	}
	/* This function will return the fields of the particular tuple class
	@param tuple : name of the tuple
	@return fields : array of fields of required tuple class
	*/
	public function getFields($tuple)
	{
		$tupleClass = PROFILE_COMMUNICATION_ENUM_INFO::getClass($tuple);
		eval('$fields =' . $tupleClass . '::getFields();');
		return $fields;
	}
	/*This function will set the array of object of Tuple class which will contain values of all the fields for all the profiles
	 *in different info type array defined in this service class
	 *@param profileObjArray : This is the array of all the profile ids for which fields will be retrieved
	 *@param fields : array of fields need to be retrieved
	 */
	public function setProfileInfo($profileObjArray, $fields,$profileArrayRB)
	{  
		if (is_null($profileObjArray) || is_null($fields))
			throw new JsException("", "No profileObjArray or no fields array sent in Tuple.class.php");
		self::initLogics();
		$profileIds = array();
		
		/* This loop will create tuple object for all the profile ids and will call setters of all the fields in profileObjArray*/
		foreach ($profileObjArray as $InfoType => $profilesInfoTypeBasedValues) {
                
			if (is_array($profilesInfoTypeBasedValues))
				foreach ($profilesInfoTypeBasedValues as $profileId => $profilesValues) {
					$tuple = new Tuple();
					$tuple->setPROFILEID($profileId);
					$profileIds[] = $profileId;
					
					foreach ($profilesValues as $profileField => $profileValue) {
						eval('$tuple->set' . $profileField . '($profileValue);');
						eval('$this->' . $InfoType . '["' . $profileId . '"] = $tuple;');
					}
				}
		}
		
		/*This loop will call the logics in order of the array to retrieve all the required fields, logic which will have intersecting
		fields get executed*/
		
		foreach (self::$logics as $logic => $value) {
			if (count(array_intersect($fields, $value["FIELDS"])) > 0 || count(array_intersect($fields, $value["LOGIC"])) > 0) {
				$typeFunction = "execute" . $logic;
				if($logic == "MY_MESSAGE_LOGIC")
					$profileInfoObjArray[$logic] = $this->$typeFunction($profileObjArray["MY_MESSAGE"]);
				else
					$profileInfoObjArray[$logic] = $this->$typeFunction($profileIds,$profileArrayRB);
			}
		}
                //echo '<pre>';print_R($profileInfoObjArray);
		       // print_r(this->$p)
              	/*This loop will retrieve all the information from the arrays of different logics and will assign to the tuple object
		calling the setters of various fields*/

		if (is_array($profileObjArray)){
                   	foreach ($profileObjArray as $infoType => $profilesInfoTypeBasedValues) {
                   		
				if (is_array($profilesInfoTypeBasedValues))
					foreach ($profilesInfoTypeBasedValues as $profileId => $profileValues) {
						
						$tupleObject = $this->getTupleObject($infoType, $profileId);
                                                foreach($this->profileDetailsArray as $key=>$tp) {

						if ($profileId==($tp->getPROFILEID()))
	                                                $tupleObject->setprofileObject($this->profileDetailsArray[$key]);
						}
                                                foreach ($profileInfoObjArray as $logic => $logicValues) {
							if (is_array($logicValues[$profileId]))
								foreach ($logicValues[$profileId] as $logicKey => $logicValue) {
									eval('$tupleObject->set' . $logicKey . '($logicValue);');
								}
						}
                                                $this->getlocationWithNativeCity($tupleObject);
						/*Setters of Messages, icons and buttons are called for all the tuple objects*/
						$this->setMessages($tupleObject);
						$this->setDisplayString($tupleObject);
						$this->setIcons($tupleObject);
						$this->setButtons($tupleObject);
                                                
					}
                        }
			}

	}
        
        public function getlocationWithNativeCity($tupleObject){
                $nativeLabel = '';
               
                $citySubstr = substr($tupleObject->CITY_ID, 0,2);
                if($tupleObject->NATIVE_CITY)
                        $nativeLabel = $tupleObject->NATIVE_CITY;
                elseif($tupleObject->NATIVE_STATE && ($citySubstr != $tupleObject->NATIVE_STATE_ID || $tupleObject->ANCESTRAL_ORIGIN != '')){
                        $nativeState = $tupleObject->NATIVE_STATE;
                        if($tupleObject->ANCESTRAL_ORIGIN){
                             $nativeLabel = $tupleObject->ANCESTRAL_ORIGIN.', ';   
                        }
                        $nativeLabel .= $nativeState;
                }

                if($nativeLabel != $tupleObject->CITY && $nativeLabel != ''){
                        $nativeLabel = $tupleObject->CITY.' & '.$nativeLabel;
                        $tupleObject->setCITY($nativeLabel);
                }
        }
	//Getters of all the information type arrays declared in this service
	public function getINTEREST_RECEIVED()
	{
		return $this->INTEREST_RECEIVED;
	}

	public function getINTEREST_EXPIRING()
	{
		return $this->INTEREST_EXPIRING;
	}
	public function getINTEREST_ARCHIVED()
	{
		return $this->INTEREST_ARCHIVED;
	}
	public function getFILTERED_INTEREST()
	{
		return $this->FILTERED_INTEREST ;
	}
	public function getACCEPTANCES_RECEIVED()
	{
		return $this->ACCEPTANCES_RECEIVED;
	}
	public function getMESSAGE_RECEIVED()
	{
		return $this->MESSAGE_RECEIVED;
	}
	public function getHOROSCOPE_REQUEST_RECEIVED()
	{
		return $this->HOROSCOPE_REQUEST_RECEIVED;
	}
	public function getHOROSCOPE_REQUEST_SENT()
	{
		return $this->HOROSCOPE_REQUEST_SENT;
	}
	public function getDECLINE_RECEIVED()
	{
		return $this->DECLINE_RECEIVED;
	}
	public function getPHOTO_REQUEST_RECEIVED()
	{
		return $this->PHOTO_REQUEST_RECEIVED;
	}
	public function getINTRO_CALLS()
	{
		return $this->INTRO_CALLS;
	}
	public function getINTRO_CALLS_COMPLETE()
	{
		return $this->INTRO_CALLS_COMPLETE;
	}
	public function getPHOTO_REQUEST_SENT()
	{
		return $this->PHOTO_REQUEST_SENT;
	}
	public function getVISITORS()
	{
		return $this->VISITORS;
	}
	public function getMATCH_ALERT()
	{
		return $this->MATCH_ALERT;
	}
	public function getMATCH_OF_THE_DAY()
	{
		return $this->MATCH_OF_THE_DAY;
	}
	public function getVIEW_SIMILAR()
	{
		return $this->VIEW_SIMILAR;
	}
	public function getACCEPTANCES_SENT()
	{
		return $this->ACCEPTANCES_SENT;
	}
	public function getMY_MESSAGE()
	{
		return $this->MY_MESSAGE;
	}
	public function getMY_MESSAGE_RECEIVED()
	{
		return $this->MY_MESSAGE_RECEIVED;
	}
	public function getINTEREST_SENT()
	{
		return $this->INTEREST_SENT;
	}
	public function getSHORTLIST()
	{
		return $this->SHORTLIST;
	}
	public function getNOT_INTERESTED()
	{
		return $this->NOT_INTERESTED;
	}
	public function getNOT_INTERESTED_BY_ME()
	{
		return $this->NOT_INTERESTED_BY_ME;
	}
	public function getCONTACTS_VIEWED()
	{
		return $this->CONTACTS_VIEWED;
	}

	public function getIGNORED_PROFILES()
	{
		return $this->IGNORED_PROFILES;
	}


	public function getPEOPLE_WHO_VIEWED_MY_CONTACTS()
	{
		return $this->PEOPLE_WHO_VIEWED_MY_CONTACTS;
	}
	public function getINTEREST_RECEIVED_FILTER()
	{
		return $this->INTEREST_RECEIVED_FILTER;
	}
	
	public function getFEATURED_PROFILE_TUPLE()
	{
		return $this->FEATURED_PROFILE_TUPLE;
	}

	/*This function will return particular tuple object from the requested infotype array and given profileid
	 *@param  infotype : information type array defined in this service
	 *@param profileId : profileid to identify the tuple object
	 *@return tupleObject : object of tuple class
	 */
	public function getTupleObject($infoType, $profileId)
	{
                
		eval('$tupleObject = $this->' . $infoType . '["' . $profileId . '"];');
		return $tupleObject;
	}
	/* Various logic implementations defined in initLogics for respective fields
	/*@param profileIds : array of profile ids to find the fields
	/*@return profilesArray : array of profiles with complete information retrieved from this logic
	*/
	public function executePROFILE_LOGIC($profileIds)
	{
		if(!empty($profileIds))
		{
			$profArrObj                = new ProfileArray();
			$profileIdArr["PROFILEID"] = implode(",", $profileIds);
			$this->profileDetailsArray = $profArrObj->getResultsBasedOnJprofileFields($profileIdArr, '', '', implode(',', self::$logics["PROFILE_LOGIC"]["FIELDS"]),'JPROFILE',"newjs_masterRep"); 
			$profilesArray             = $this->getProfileLogiceResultArray();
                       return $profilesArray;
		}
		return null;
	}
	public function executeNAME_LOGIC($profileIds)
	{
		if(!empty($profileIds))
		{
			$nameOfUserObj = new NameOfUser();
			$showNameData = $nameOfUserObj->showNameToProfiles($this->getLoginProfileObj(),$this->profileDetailsArray);
			foreach($showNameData as $k=>$v)
			{
				if($v['SHOW']==true)
					$profileArray[$k]['NAME_OF_USER']=$v['NAME'];
				else
					$profileArray[$k]['NAME_OF_USER']='';
			}
		}
		return $profileArray;
	}
	/* Various logic implementations defined in initLogics for respective fields
	/*@param profileIds : array of profile ids to find the fields
	/*@return profilesArray : array of profiles with complete information retrieved from this logic
	*/
	public function executeEDUCATION_LOGIC($profileIds)
	{
		if(!empty($profileIds))
		{
			$jprofArrObj                = ProfileEducation::getInstance("newjs_masterRep");
			$profileDetailsArray = $jprofArrObj->getProfileEducation($profileIds,'mailer');
				
			foreach($profileDetailsArray as $k=>$row)
			{
				unset($edu);
				if($row["EDU_LEVEL_NEW"])
								$edu[]=FieldMap::getFieldLabel('education',$row["EDU_LEVEL_NEW"]);
				if($row["PG_DEGREE"])
								$edu[]=FieldMap::getFieldLabel('education',$row["PG_DEGREE"]);
				if($row["OTHER_PG_DEGREE"] && Flag::isFlagSet("other_ug_degree", $row["SCREENING"]))
								$edu[]=substr($row["OTHER_PG_DEGREE"],0,30);
				if($row["UG_DEGREE"])
								$edu[]=FieldMap::getFieldLabel('education',$row["UG_DEGREE"]);
				if($row["OTHER_PG_DEGREE"] && Flag::isFlagSet("other_pg_degree", $row["SCREENING"]))
								$edu[]=substr($row["OTHER_UG_DEGREE"],0,30);
				if($row["COLLEGE"] && Flag::isFlagSet("college", $row["SCREENING"])){
								$result[$row["PROFILEID"]]["COLLEGE"]=$row["COLLEGE"];
                                }else{
                                      $result[$row["PROFILEID"]]["COLLEGE"] = '';  
                                }
				if($row["PG_COLLEGE"] && Flag::isFlagSet("pg_college", $row["SCREENING"])){
								$result[$row["PROFILEID"]]["PG_COLLEGE"]=$row["PG_COLLEGE"];
                                }else{
                                        $result[$row["PROFILEID"]]["PG_COLLEGE"] = '';
                                }
				$result[$row["PROFILEID"]]["edu_level_new"]= $edu?implode(", ",array_unique($edu)):"";
			}
		      return $result;
		}
		
		return null;
	}
	
	
	
	
	/**
	 * This function is used to get photo related info (pics/album)... for the profiles to be shown on tuple.
	 * @return array containing all picture related info.
	 */
	public function executePHOTO_LOGIC($profileIds)
	{
		$picObj = new PictureArray($this->profileDetailsArray);
		
		if (is_array($this->profileDetailsArray)) {
			$albumCountArr = $picObj->getScreendPictureCountByPid();
			$pictureArr = $picObj->getProfilePhoto("N","","",$this->getLoginProfileObj());
			unset($picObj);
			
			foreach ($this->profileDetailsArray as $key => $profileObj) {
				$picServiceObj = new PictureService($profileObj);
				
				$profileid = $profileObj->getPROFILEID();
				$gender    = $profileObj->getGENDER();
				foreach(ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS as $i=>$key)
				{
					if(!in_array($key,ProfilePicturesTypeEnum::$PICTURE_SIZES_STOCK))
						$keyStock = "ProfilePicUrl";
					else
						$keyStock = $key;
					${$key} = PictureService::getRequestOrNoPhotoUrl('requestPhoto', $keyStock, $gender);
				}
				
				if ($picServiceObj->isProfilePhotoPresent()) {
					$picObj = $pictureArr[$profileid];
					
					if (isset($picObj)) {
						foreach(ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS as $i=>$key)
						{
							eval('$'.$key.'=$picObj->get'.$key.'();');
			
						}
						
						$arr = PictureFunctions::mapUrlToMessageInfoArr($picObj->getProfilePicUrl(),'ProfilePicUrl','');
			 			if($arr["url"]=='')
							$albumCountArr[$profileid]=0;
                                                $profileArray[$profileid]["PIC_ID"] =$picObj->getPICTUREID();
                                                $pictureIdsArray[$profileid]=$picObj->getPICTUREID();
					}
				} else {
						if (MobileCommon::isNewMobileSite() || MobileCommon::isApp()) {
					 foreach(ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS as $i=>$key)
                                                {
						 ${$key} =null;	
						}

						}
else {
                                    $profileArray[$profileid]["PIC_ID"] =null;
					if ($picServiceObj->isProfilePhotoUnderScreening()) {
						foreach(ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS as $i=>$key)
						{
							if(!in_array($key,ProfilePicturesTypeEnum::$PICTURE_SIZES_STOCK))
								$keyStock = "ProfilePicUrl";
							else
								$keyStock = $key;
							${$key} = PictureService::getRequestOrNoPhotoUrl('underScreening', $keyStock, $gender);
						}
						
					}
		}			
				}
				foreach(ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS as $i=>$key)
				{
					$profileArray[$profileid][$key] = ${$key};
				}
				
				$profileArray[$profileid]["IS_ALBUM"]      = PictureService::mapAlbumCountToLabel($albumCountArr[$profileid]);
				$profileArray[$profileid]["IS_ALBUM_TEXT"] = PictureService::mapAlbumCountToLabel($albumCountArr[$profileid], 'text');
				$profileArray[$profileid]["PHOTO_COUNT"] = $albumCountArr[$profileid]?$albumCountArr[$profileid]:0;
				$profileArray[$profileid]["IS_PHOTO_REQUESTED"] = 1;
					
			
				
			}
		}

		//if(is_array($profileIds))
		if(!empty($profileIds))
		{
			$pictureServiceObj = new PictureService($this->loginProfile);
			$photoRequested = $pictureServiceObj->getIfPhotoRequested(array($this->getLoginProfile()),$profileIds);
			if(is_array($photoRequested))
			{
				foreach($photoRequested["sentByViewer"] as $key=>$value)
					$profileArray[$key]["IS_PHOTO_REQUESTED"] = 0;
			}
		}
		// Setting Mobile App Pic size for all the pictureids
		if(MobileCommon::isMobile() && is_array($pictureIdsArray))
		{
			$pictureSizeObj = new PICTURE_MobAppPicSize("newjs_masterRep");
	    $pictureSize = $pictureSizeObj->getPictureSize($pictureIdsArray); 
	    foreach($pictureIdsArray as $profileId=>$value)
			{
					$profileArray[$profileId]["MOBPHOTOSIZE"] = in_array($profileId,$pictureSize)?$pictureSize[$profileId]:array("WIDTH"=>"450","HEIGHT"=>"600"); 
			}
			
		}
	return $profileArray;
	}
	
	/* This function returns the astro details of the profile ids
	 *@param profileIds : string of profile ids for which astro details are found
	 *@return $profileArray : array of profileids and corresponding astro details 
	 */
	public function executeHOROSCOPE_LOGIC()
	{
		$horoscopeObj  = new Horoscope();
		$profilesAstro = $horoscopeObj->getMultipleAstroDetails($this->profileDetailsArray);
		if (is_array($profilesAstro)) {
			foreach ($profilesAstro as $pid => $value) {
				$profileArray[$pid]["ASTRO_DETAILS"] = $value;
			}
			return $profileArray;
		}
		return null;
	}

	
	/* This function returns the astro details of the profile ids
	 *@param profileIds : string of profile ids for which astro details are found
	 *@return $profileArray : array of profileids and corresponding astro details 
	 */
	public function executeINTEREST_VIEWED_LOGIC($profileIds)
	{
		$eoiViewLogObj  = new EoiViewLog();
		$profilesEOI = $eoiViewLogObj->getMutipleEoiViewed($profileIds,$this->getLoginProfile());
		if (is_array($profilesEOI)) {
			foreach ($profilesEOI as $pid => $value) {
				$profileArray[$pid]["INTEREST_VIEWED_DATE"] = CommonUtility::convertDateToDay($value["DATE"]);
			}
			return $profileArray;
		}
		return null;
	}
	
	public function executeMESSAGE_LOGIC($profileIds,$profilesArrayForRB)
	{
		if(!empty($profileIds))
		{
			$messageLogObj = new messageLog();
		
			$messages = $messageLogObj->getEOIMessages($this->getLoginProfile(),$profileIds,$profilesArrayForRB);
			if(is_array($messages))
			{
				foreach($messages as $k=>$value)
				{
					if($value["SENDER"]==$this->getLoginProfile())
						$profilesArray[$value["RECEIVER"]]["SENT_MESSAGE"] = $value["MESSAGE"];
					else
						$profilesArray[$value["SENDER"]]["MESSAGE"] = $value["MESSAGE"];
				}
				return $profilesArray;
			}
		}
		return null;
	}
	public function executeCONTACT_LOGIC($profileIds)
	{
		$contactsObj = new ContactsRecords();
		$output = $contactsObj->getContactsDetails($this->getLoginProfile(),$profileIds);
		foreach($output as $pid=>$value)
		{
			if($value["SENDER"] == $this->getLoginProfile())
			{	
				$profileArray[$value["RECEIVER"]]["CONTACTS"] = $value;
				$profileArray[$value["RECEIVER"]]["CONTACTS"]["SELF"] = "S";
			}
			else
			{	
				$profileArray[$value["SENDER"]]["CONTACTS"] = $value;
				$profileArray[$value["SENDER"]]["CONTACTS"]["SELF"] = "R";
			}
		}
		return $profileArray;
	}
	
	/* Various logic implementations defined in initLogics for respective fields
	/*@param profileIds : array of profile ids to find the fields
	/*@return profilesArray : array of profiles with complete information retrieved from this logic
	*/
	public function executeBOOKMARK_LOGIC($profileIds)
	{	
		if(!empty($profileIds))
		{	
			$bookmarkObj  = new Bookmarks();
			$isBookmarked = $bookmarkObj->getProfilesBookmarks($this->loginProfile,$profileIds);
				if(is_array($isBookmarked))
				foreach($isBookmarked as $key=>$value)
				{ 
					$profilesArray[$value]["IS_BOOKMARKED"]=1;
				}
				
			return $profilesArray;
		}
		return null;
	}
	public function executeMY_MESSAGE_LOGIC($profileIds)
	{
		
	}
	
	/*This function set all the required messages for all the profileArray passed to be shown on the tuple on the basis of module
	 *@param profileArray : Profile array
	 *@param messageIds : string of comma seperated message ids
	 *@return profileArray : profileArray with added element of comma seperated ids of call out messages for each profile id
	 */
	public function setMessageIds($messageIds, $profileArray)
	{
		foreach ($profileArray as $profileId => $values) {
			$profileArray[$profileId]["CALLOUT_MESSAGES"] = $messageIds;
		}
		return $profileArray;
	}
	/*This function set all the required messages for all the tuples passed to be shown on the tuple on the basis of array set in
	 * its callout message fields by setMessageIds function, it split the comma seperated message position and corresponding message id.
	 * Message position and its message ids are pipe seperated ex: "LeftTopMessage|PC_EOI,RightBottomMessage|PC_PHOTO_REQUEST".
	 *@param tuple: tuple object for which message with dynamic fields will be set
	 */
	public function setMessages(Tuple $tuple)
	{
		$messageIdString = $tuple->getCALLOUT_MESSAGES();
		if ($messageIdString != "") {
			$messageIds    = explode(",", $messageIdString);
			$messages      = array();
			$dynamicValues = array();
			if ($tuple->getGENDER() == "F")
				$dynamicValues["GENDER"] = "She";
			else
				$dynamicValues["GENDER"] = "He";
			/* CommonUtility::ConvertDateDiffToJsFormat is called to find difference between today and the time field of the tuple
			It will return in the required format ex:"2 days ago", "today"
			*/
			$dynamicValues["TIME"] = CommonUtility::ConvertDateDiffToJsFormat($tuple->getTIME());
			foreach ($messageIds as $id) {
				$split         = explode("|", $id);
				$messageId     = $split[0];
				$messageString = $split[1];
				//message along with Dynamic values of gender and time inserted in assigned to the message array
				eval('$messages[' . $messageId . ']=Messages::getMessage(MESSAGES::' . $messageString . ',$dynamicValues);');
			}
			//Array of message position and corresponding message is assigned to thr tuple object
			$tuple->setCALLOUT_MESSAGES($messages);
			
		}
	}
	/*This function set all the required icon for all the profileArray passed to be shown on the tuple on the basis of module
	 *@param profileArray : Profile array
	 *@param iconIds : string of comma seperated icon ids
	 *@return profileArray : profileArray with added element of comma seperated ids of icon for each profile id
	 */
	public function setIconIds($iconIds, $profileArray)
	{
		foreach ($profileArray as $profileId => $values) {
			$profileArray[$profileId]["ICONS"] = $iconIds;
		}
		return $profileArray;
	}
	/*This function set all the required icon for all the tuples passed to be shown on the tuple on the basis of array set in
	 * its icon fields by setIconIds function and the condition to be verified for displaying a particular icon, it split the comma
	 * seperated icon ids. Corresponding values are set if required, Y as default
	 *@param tuple: tuple object for which message with dynamic fields will be set
	 */
	public function setIcons(Tuple $tuple)
	{
		$iconId = $tuple->getICONS();
		if ($iconId != "") {
			$iconIds = explode(",", $iconId);
			$icons   = array();
			foreach ($iconIds as $id) {
				$flag         = "N";
				$value        = "Y";
				$subscription = $tuple->getSUBSCRIPTION();
				if ($id == 'MEMBERSHIP') {
					if (strstr($subscription, "F")) {
						$flag = "Y";
						if (strstr($subscription, "D")) {
							$value     = "EVALUE";
							$iconclass = "evallogo_v1";
							$partial   = "global/eValueHelp";
						} else {
							$value     = "ERISHTA";
							$iconclass = "evallogo_v1"; //wrong value coded for testing purpose
							$partial   = "global/eRishtaHelp";
						}
					}
				} elseif ($id == 'JUST_JOINED') {
					$dateTime  = new DateTime();
					$entryDate = $dateTime->setTimestamp(strtotime($tuple->getENTRY_DT()));
					$now       = new DateTime;
					$diff      = date_diff($now, $entryDate);
					if ($diff->days <= 15 && !strstr($subscription, "F")) {
						$flag      = "Y";
						$value     = "JUST_JOINED";
						$iconclass = "justjoin_v1";
						$partial   = "";
					}
				} elseif ($id == 'HOROSCOPE') {
					$horoscopeObj = new Horoscope();
					$flag         = $horoscopeObj->ifHoroscopePresent($tuple->getPROFILEID());
					if ($flag == "Y") {
						$value     = "HOROSCOPE";
						$iconclass = "guna_v1";
						$partial   = "global/gunaMatch";
					}
				}
				if ($flag == "Y") {
					$icons[$id]["value"]     = $value;
					$icons[$id]["iconClass"] = $iconclass;
					$icons[$id]["partial"]   = $partial;
				}
			}
			$tuple->setICONS($icons);
		}
	}
	/*This function set all the required buttons for all the profileArray passed to be shown on the tuple on the basis of module
	 *@param profileArray : Profile array
	 *@param buttonIds : string of comma seperated button ids
	 *@return profileArray : profileArray with added element of comma seperated ids of buttons for each profile id
	 */
	public function setButtonIds($buttonIds, $profileArray)
	{
		foreach ($profileArray as $profileId => $values) {
            $profileArray[$profileId]["BUTTONS"] = $buttonIds;
        }
		return $profileArray;
	}
	/*This function set all the required buttons for all the tuples passed to be shown on the tuple on the basis of array set in
	 * its buttons fields by setButtonsIds function, it split the comma seperated buttons id.
	 *@param tuple: tuple object for which buttons fields will be set
	 */
	public function setButtons(Tuple $tuple)
	{
		$buttonIdString = $tuple->getBUTTONS();
		$buttonIds      = explode("|", $buttonIdString);
		if ($buttonIdString != "") {
			$buttons = array();
			foreach ($buttonIds as $id) {
				$buttons[] = $id;
			}
			$tuple->setBUTTONS($tuple->getBUTTONS());
		}
	}
	
	
	public function setDisplayString(Tuple $tuple)
	{
		$string = $tuple->getAGE() . "yrs";
		if ($tuple->getHEIGHT()) {
			$height = html_entity_decode($tuple->getHEIGHT());
			$string .= ", " . $height;
			//$string .= ", ".$tuple->getHEIGHT();
		}
		if ($tuple->getCASTE())
			$string .= ", " . $tuple->getCASTE();
		if ($tuple->getMTONGUE())
			$string .= ", " . $tuple->getMTONGUE();
		if ($tuple->getEDUCATION())
			$string .= ", " . $tuple->getEDUCATION();
		if ($tuple->getINCOME())
			$string .= ", " . $tuple->getINCOME();
		if ($tuple->getOCCUPATION())
			$string .= ", " . $tuple->getOCCUPATION();
		if ($tuple->getCITY())
			$string .= ", " . $tuple->getCITY();
		
		$tuple->setDisplayString($string);
		
	}
	
	public function unsetNotRequiredTupleFields($infoType, $tuple)
	{
		$tupleFields = $this->getFields($tuple);
		
		
	}
	
	
	
	
	private function getProfileLogiceResultArray()
	{
		if (is_array($this->profileDetailsArray)) {
			
			foreach ($this->profileDetailsArray as $key => $profileObj) {
				$profileid                      = $profileObj->getPROFILEID();
				/*$incentiveObj = new incentive_NAME_OF_USER();
				$name = $incentiveObj->getName($profileid);*/
				//replace username with incentive_NAME_OF_USER entry if present
				/*if(MobileCommon::isDesktop() && $name)
					$result[$profileid]["USERNAME"] = $name;
				else*/
					$result[$profileid]["USERNAME"] = $profileObj->getUSERNAME();
				$result[$profileid]["CITY"]     = $profileObj->getDecoratedCity();
				$result[$profileid]["CITY_ID"]     = $profileObj->getCITY_RES();
				if (!$result[$profileid]["CITY"])
					$result[$profileid]["CITY"] = $profileObj->getDecoratedCountry();
				$result[$profileid]["OCCUPATION"]      = $profileObj->getDecoratedOccupation();
				//$result[$profileid]["EDUCATION"]       = $profileObj->getDecoratedEducation();
				$result[$profileid]["INCOME"]          = FieldMap::getFieldLabel("income_map", $profileObj->getINCOME());
				$result[$profileid]["CASTE"]           = str_replace("-", "", FieldMap::getFieldLabel("caste", $profileObj->getCaste()));
				$result[$profileid]["MSTATUS"]           = str_replace("-", "", FieldMap::getFieldLabel("mstatus", $profileObj->getMSTATUS()));
				$result[$profileid]["RELIGION"]        = $profileObj->getDecoratedReligion();
				$result[$profileid]["MTONGUE"]         = FieldMap::getFieldLabel("community_small", $profileObj->getMTONGUE());
				$result[$profileid]["HEIGHT"]          = $profileObj->getDecoratedHeight();
				$result[$profileid]["AGE"]             = $profileObj->getAge();
				$result[$profileid]["SUBCASTE"]        = $profileObj->getSubcaste();
				$result[$profileid]["HAVEPHOTO"]       = $profileObj->getHavePhoto();
				$result[$profileid]["PHOTO_DISPLAY"]   = $profileObj->getPHOTO_DISPLAY();
				$result[$profileid]["ACTIVATED"]       = $profileObj->getActivated();
				$result[$profileid]["GENDER"]          = $profileObj->getGENDER();
				$result[$profileid]["ENTRY_DT"]        = $profileObj->getENTRY_DT();
				$result[$profileid]["SUBSCRIPTION"]    = $profileObj->getSUBSCRIPTION();
				$result[$profileid]["LAST_LOGIN_DT"]    = $profileObj->getLAST_LOGIN_DT();
				$result[$profileid]["EMAIL"]    = $profileObj->getEMAIL();
                                if(Flag::isFlagSet("company_name",$profileObj->getSCREENING()))
                                        $result[$profileid]["COMPANY_NAME"]          = $profileObj->getCOMPANY_NAME();
                                else
                                        $result[$profileid]["COMPANY_NAME"]          = "";
                                
				if(Flag::isFlagSet("yourinfo",$profileObj->getSCREENING()))
					$result[$profileid]["YOURINFO"]    = $profileObj->getYOURINFO();
				$result[$profileid]["PROFILECHECKSUM"] = JsAuthentication::jsEncryptProfilechecksum($profileid);
                                
                                if(Flag::isFlagSet("ancestral_origin", $profileObj->getSCREENING()))
                                        $result[$profileid]["ANCESTRAL_ORIGIN"]          = $profileObj->getDecoratedAncestralOrigin();
                                else
                                        $result[$profileid]["ANCESTRAL_ORIGIN"]          = "";
			}
			return $result;
		}
		return NULL;
		
	}
        public function executeNATIVE_LOGIC($profileIds)
	{
		if(!empty($profileIds))
		{
			$jprofArrObj                = ProfileNativePlace::getInstance("newjs_masterRep");
                         if(!is_array($profileIds)){
                            $profileIds = array($profileIds);
                        }
                        
                        $profileDetailsArray        = $jprofArrObj->getNativeDataForMultipleProfiles($profileIds);
                        if(!empty($profileDetailsArray)){
                                foreach($profileDetailsArray as $profileData){
                                        if($profileData["NATIVE_CITY"])
                                                $result[$profileData["PROFILEID"]]["NATIVE_CITY"]=FieldMap::getFieldLabel('city',$profileData["NATIVE_CITY"]);
                                        if($profileData["NATIVE_STATE"]){
                                                $result[$profileData["PROFILEID"]]["NATIVE_STATE"]=FieldMap::getFieldLabel('state_india',$profileData["NATIVE_STATE"]);
                                                $result[$profileData["PROFILEID"]]["NATIVE_STATE_ID"]=$profileData["NATIVE_STATE"];
                                        }
                                }
                        }
		      return $result;
		}
		
		return null;
	}
	public function setLoginProfile($profileid)
	{
		$this->loginProfile = $profileid;
	}
	public function getLoginProfile()
	{
		return $this->loginProfile;
	}
	public function setLoginProfileObj($profileObj)
	{
		$this->loginProfileObj = $profileObj;
	}
	public function getLoginProfileObj()
	{
		return $this->loginProfileObj;
	}
}
?>
