<?php
/**
 * @class LoggedInProfile
 * @brief contains get, set methods for logged-in profile
 * Find more information in http://devjs.infoedge.com/mediawiki/index.php/Social_Project#LoggedInProfile_class
 * @author Tanu Gupta
 * @created 2011-06-15
 */

class LoggedInProfile extends Profile{

	private static $instance;
	public $JPROFILE;
	private $screeningMessage = "";

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database name to which the connection would be made
         * @param $profileid - Profileid of logged-in profile
         */
        public function __construct($dbname="", $profileid=""){
                $this->JPROFILE = JPROFILE::getInstance($dbname);
                if(!$profileid)  $this->setPROFILEID(sfContext::getInstance()->getRequest()->getAttribute('profileid'));
		else $this->setPROFILEID($profileid);
        }

        /**
         * @fn getInstance
         * @brief fetches the current instance of the class
         * @param $dbName - Database name to which the connection would be made
         * @param $profileid - profileId of logged-in profile
         * @return instance of the last object. If required profile object is not present then returns new object.
         */
        public static function getInstance($dbName="",$profileid="")
        {
                if(isset(self::$instance))
                {
			//If different instance is required
                        if($profileid && (self::$instance->getPROFILEID() != $profileid)){
                                $class = __CLASS__;
                                self::$instance = new $class($dbName,$profileid);
                        }
                }
                else
                {
                        $class = __CLASS__;
                        self::$instance = new $class($dbName,$profileid);
                }
                return self::$instance;
        }

        /**
         * @fn edit
         * @brief Edits profile detail.
         */
        public function edit($paramArr=array(), $value="", $criteria=""){
		if(!$criteria) $criteria="PROFILEID";
		if($criteria=="PROFILEID" && !$value) $value = $this->getPROFILEID();
		if($paramArr['PASSWORD'])
			$paramArr['PASSWORD']=PasswordHashFunctions::createHash($paramArr['PASSWORD']);
                if($this->JPROFILE->edit($paramArr, $value, $criteria)){
                        foreach($paramArr as $field=>$value){
                                eval ('$this->set'.$field.'($value);');
                        }
                }
        }
        /**
         * @fn insert
		 * @brief inserts a new Entry into JPROFILE.
		 * !only to be used for registration pages
         */
        public function insert($paramArr=array()){
			$auto_id=new NEWJS_AUTOID();
			while(true){
				$num=$auto_id->getId();
				$arr=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
				$username='';
				$i=0;
				$ind=strlen($num)-4;
				$a=substr($num,0,$ind);
				while($i<strlen($a))
				{
						$b=$a[$i]*25;
						$ret=$b%26;
						$username.=$arr[$ret];
						$i++;
				}
				$b=substr($num,$ind);
				$username.=$b;
				$rows=$this->JPROFILE->getProfileIdsThatSatisfyConditions(array("USERNAME"=>"'$username'"));
				if(count($rows==0))
					break;
			}
			$paramArr['USERNAME']=$username;
			$paramArr['PASSWORD']=PasswordHashFunctions::createHash($paramArr['PASSWORD']);
			if($id=$this->JPROFILE->insert($paramArr))
			{
				foreach($paramArr as $field=>$value)
				{
					eval ('$this->set'.$field.'($value);');
				}
			}
			return $id;
		}
		/**
		 * @function This will update JHOBBY 
		 * */
		public function editHobby($paramArr)
		{
			$hobbyObj=new JHOBBYCacheLib();
			$hobbyObj->update($this->PROFILEID,$paramArr);
		}
		/**
		 * @function This will update JPROFILE_EDUCATION 
		 * */
		public function editEducation($paramArr)
		{
			$eduObj=  ProfileEducation::getInstance();
			$eduObj->update($this->PROFILEID,$paramArr);
		}
		/**
		 * @function This will update JPROFILE_CONTACT
		 * */
		public function editCONTACT($paramArr)
		{
			$contactObj= new ProfileContact();
			$contactObj->update($this->PROFILEID,$paramArr);
		}

	public function getScreeningMessage(){
		return $this->screeningMessage;
	}

	public function setScreeningMessage($message){
		$this->screeningMessage = $message;
	}

        /**
         * getDecoratedSubcaste()
         *
         * Returns subcaste label of logged in profile
         */
        public function getDecoratedSubcaste(){
                $subcaste = $this->getSUBCASTE();
                if($subcaste){
                        if(!Flag::isFlagSet("SUBCASTE",$this->getSCREENING()))
                                $subcaste = $subcaste.$this->getScreeningMessage();
                }else $subcaste = $this->nullValueMarker;
                return $subcaste;
        }

        /**
         * getDecoratedGothra()
         *
         * Returns gothra label of logged in profile
         */
        public function getDecoratedGothra(){
                $gothra = $this->getGOTHRA();
                if($gothra){
                        if(!Flag::isFlagSet("GOTHRA",$this->getSCREENING()))
                                $gothra = $gothra.$this->getScreeningMessage();
                }else $gothra = $this->nullValueMarker;
                return $gothra;
        }

        public function getDecoratedGothraMaternal(){
                $gothra = $this->getGOTHRA_MATERNAL();
                if($gothra){
                        if(!Flag::isFlagSet("GOTHRA_MATERNAL",$this->getSCREENING()))
                                $gothra = $gothra.$this->getScreeningMessage();
                }else $gothra = $this->nullValueMarker;
                return $gothra;
        }

        /**
         * getDecoratedAncestralOrigin()
         *
         * Returns ancestralOrigin label of logged in profile
         */
        public function getDecoratedAncestralOrigin(){
                $ancestralOrigin = $this->getANCESTRAL_ORIGIN();
                if($ancestralOrigin){
                        if(!Flag::isFlagSet("ANCESTRAL_ORIGIN",$this->getSCREENING()))
                                $ancestralOrigin = $ancestralOrigin.$this->getScreeningMessage();
                }else $ancestralOrigin = $this->nullValueMarker;
                return $ancestralOrigin;
        }

        /**
         * getDecoratedYourInfo()
         *
         * Returns yourInfo label of logged in profile
         */
        public function getDecoratedYourInfo(){
                $yourInfo = nl2br($this->getYOURINFO());
                if($yourInfo){
                        if(!Flag::isFlagSet("YOURINFO",$this->getSCREENING()))
                                $yourInfo = $yourInfo.$this->getScreeningMessage();
                }
                return $yourInfo;
        }

        /**
         * getDecoratedFamilyInfo()
         *
         * Returns familyInfo label of logged in profile
         */
        public function getDecoratedFamilyInfo(){
                $familyInfo = nl2br($this->getFAMILYINFO());
                if($familyInfo){
                        if(!Flag::isFlagSet("FAMILYINFO",$this->getSCREENING()))
                                $familyInfo = $familyInfo.$this->getScreeningMessage();
                }
                return $familyInfo;
        }

        /**
         * getDecoratedEducationInfo()
         *
         * Returns educationInfo label of logged in profile
         */
        public function getDecoratedEducationInfo(){
                $educationInfo = nl2br($this->getEDUCATION());
                if($educationInfo){
                        if(!Flag::isFlagSet("EDUCATION",$this->getSCREENING()))
                                $educationInfo = $educationInfo.$this->getScreeningMessage();
                }
                return $educationInfo;
        }

        /**
         * getDecoratedJobInfo()
         *
         * Returns jobInfo label of logged in profile
         */
        public function getDecoratedJobInfo(){
                $jobInfo = nl2br($this->getJOB_INFO());
                if($jobInfo){
                        if(!Flag::isFlagSet("JOB_INFO",$this->getSCREENING()))
                                $jobInfo = $jobInfo.$this->getScreeningMessage();
                }
                return $jobInfo;
        }

        /**
         * getDecoratedSpouseInfo()
         *
         * Returns spouseInfo label of logged in profile
         */
        public function getDecoratedSpouseInfo(){
                $spouseInfo = nl2br($this->getSPOUSE());
                if($spouseInfo){
                        if(!Flag::isFlagSet("SPOUSE",$this->getSCREENING()))
                                $spouseInfo = $spouseInfo.$this->getScreeningMessage();
                }
                return $spouseInfo;
        }

        /**
         * getDecoratedBirthCity()
         *
         * Returns birth city of logged in profile
         */
        public function getDecoratedBirthCity(){
                $birthCity = $this->getCITY_BIRTH();
		if($birthCity){
			if(!Flag::isFlagSet("CITYBIRTH",$this->getSCREENING()))
				$birthCity = ucwords($this->getCITY_BIRTH()).$this->getScreeningMessage();
		}else $birthCity = $this->nullValueMarker;
                return $birthCity;
        }               

        /**
         * getDecoratedCompany()
         *
         * Returns company label of the logged in profile
         */
        public function getDecoratedCompany(){
                $company = $this->getCOMPANY_NAME();
                if($company){
                        if(!Flag::isFlagSet("company_name",$this->getSCREENING()))
                                $company = $company.$this->getScreeningMessage();
				}else
					$company=$this->nullValueMarker;
                return $company;
        }
        public function getDecoratedPersonHandlingProfile(){
                $value=$this->getPROFILE_HANDLER_NAME();
				if($value){
                	if(!Flag::isFlagSet("profile_handler_name",$this->getSCREENING()))
						$value.=$this->getScreeningMessage();
				}else
					$value=$this->nullValueMarker;
				return $value;
        }
  		/** @fn Overridden method. This appends screening message to fields currently in screening.
		 *  @param array flag_array It is key value array of fields that are to be tested for screening and value will be corresponding name of field.
		 *  @param ProfileComponent component It is ProfileComponent object
		 *  */
		public function addScreeningMessages(array $flag_array,ProfileComponent $component)
		{
			foreach($flag_array as $flag=>$field){
				if(!Flag::isFlagSet($flag,$this->SCREENING))
					if($component->$field && $component->$field !='-')$component->$field.=$this->screeningMessage;
			}
			return $component;
		}
        /**
         * @fn getDetail
         * @brief fetches profile detail. sets the detail to Profile Object.
         * @param $value Query criteria value
         * @param $criteria Query criteria column
         * @param $fields Columns to query
         * @param $effect RAW or DECORATED; 
		  Use RAW for getting direct results from JPROFILE, 
		  Use DECORATED for getting results to display
         * @return Profile detail array;
         */
        
	public function getDetail($value="", $criteria="", $fields="", $effect="RAW"){
		if(!$criteria){ $criteria = 'PROFILEID'; if(!$value) $value=$this->getPROFILEID();} 
		$this->$criteria=$value;
		//PartitionKey
		$addWhereParam["activatedKey"]=1;
		$res = $this->JPROFILE->get($value, $criteria, $fields,$addWhereParam,true);//Fetches results from JPROFILE
		
		$detail = $this->setDetail($res, $effect);//Sets profile detail to the object		
		
		return $detail;
	}

}
?>
