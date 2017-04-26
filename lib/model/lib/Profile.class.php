<?php
/**
 * @class Profile
 * @brief contains get, set methods for individual profile registered on Jeevansathi
 * Find more information in http://devjs.infoedge.com/mediawiki/index.php/Social_Project#Profile_Class
 * @author Tanu Gupta
 * @created 2011-06-10
 */

class Profile{

	protected $PROFILEID;
	private $PROFILE_STATE;
	private $USERNAME;
	private $PASSWORD;
	private $GENDER;
	private $RELIGION;
	private $CASTE;
	private $MANGLIK;
	private $MTONGUE;
	private $MSTATUS;
	private $DTOFBIRTH;
	private $OCCUPATION;
	private $COUNTRY_RES;
	private $CITY_RES;
	private $HEIGHT;
	private $EDU_LEVEL;
	private $EMAIL;
	private $IPADD;
	private $ENTRY_DT;
	private $MOD_DT;
	private $RELATION;
	private $COUNTRY_BIRTH;
	private $SOURCE;
	private $INCOMPLETE;
	private $PROMO;
	private $DRINK;
	private $SMOKE;
	private $HAVECHILD;
	private $RES_STATUS;
	private $BTYPE;
	private $COMPLEXION;
	private $DIET;
	private $HEARD;
	private $INCOME;
	private $CITY_BIRTH;
	private $BTIME;
	private $HANDICAPPED;
	private $NTIMES;
	private $SUBSCRIPTION;
	private $SUBSCRIPTION_EXPIRY_DT;
	private $ACTIVATED;
	private $ACTIVATE_ON;
	private $AGE;
	private $GOTHRA;
	private $NAKSHATRA;
	private $MESSENGER_ID;
	private $MESSENGER_CHANNEL;
	private $PHONE_RES;
	private $PHONE_MOB;
	private $FAMILY_BACK;
	protected $SCREENING;
	private $CONTACT;
	private $SUBCASTE;
	private $YOURINFO;
	private $FAMILYINFO;
	private $SPOUSE;
	private $EDUCATION;
	private $LAST_LOGIN_DT;
	private $SHOWPHONE_RES;
	private $SHOWPHONE_MOB;
	private $HAVEPHOTO;
	private $PHOTO_DISPLAY;
	private $PHOTOSCREEN;
	private $PREACTIVATED;
	private $KEYWORDS;
	private $PHOTODATE;
	private $PHOTOGRADE;
	private $TIMESTAMP;
	private $PROMO_MAILS;
	private $SERVICE_MESSAGES;
	private $PERSONAL_MATCHES;
	private $SHOWADDRESS;
	private $UDATE;
	private $SHOWMESSENGER;
	private $PINCODE;
	private $PRIVACY;
	private $EDU_LEVEL_NEW;
	private $FATHER_INFO;
	private $SIBLING_INFO;
	private $WIFE_WORKING;
	private $JOB_INFO;
	private $MARRIED_WORKING;
	private $PARENT_CITY_SAME;
	private $PARENTS_CONTACT;
	private $SHOW_PARENTS_CONTACT;
	private $FAMILY_VALUES;
	private $SORT_DT;
	private $VERIFY_EMAIL;
	private $SHOW_HOROSCOPE;
	private $GET_SMS;
	private $STD;
	private $ISD;
	private $MOTHER_OCC;
	private $T_BROTHER;
	private $T_SISTER;
	private $M_BROTHER;
	private $M_SISTER;
	private $FAMILY_TYPE;
	private $FAMILY_STATUS;
	private $CITIZENSHIP;
	private $BLOOD_GROUP;
	private $HIV;
	private $WEIGHT;
	private $NATURE_HANDICAP;
	private $ORKUT_USERNAME;
	private $WORK_STATUS;
	private $ANCESTRAL_ORIGIN;
	private $HOROSCOPE_MATCH;
	private $SPEAK_URDU;
	private $PHONE_NUMBER_OWNER;
	private $PHONE_OWNER_NAME;
	private $MOBILE_NUMBER_OWNER;
	private $MOBILE_OWNER_NAME;
	private $RASHI;
	private $TIME_TO_CALL_START;
	private $TIME_TO_CALL_END;
	private $PHONE_WITH_STD;
	private $MOB_STATUS;
	private $LANDL_STATUS;
	private $PHONE_FLAG;
	private $CRM_TEAM;
	private $PROFILE_HANDLER_NAME;
	private $PARENT_PINCODE;
	private $FAMILY_INCOME;
	private $THALASSEMIA;
	private $GOTHRA_MATERNAL;
	private $GOING_ABROAD;
	private $OPEN_TO_PET;
	private $HAVE_CAR;
	private $OWN_HOUSE;
	private $SECT;
	private $SEC_SOURCE;
	private $SUNSIGN;
	private $COMPANY_NAME;
	private $HAVE_JCONTACT;
	private $HAVE_JEDUCATION;
	private $ID_PROOF_NO;
	private $ID_PROOF_TYP;
    private $VERIFY_ACTIVATED_DT;
	protected $nullValueMarker = "";
	public $JPROFILE; //JPROFILE Object
	private static $instance; //Instance of the class
	private $SCHOOL;
	private $PG_COLLEGE;
	private $COLLEGE;
	private $NAME;
	private $SERIOUSNESS_COUN;
	private $education_other;
	protected $fieldsArray=array();
        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database name to which the connection would be made
         * @param $profileid - Unique profileid of profile
         */
        public function __construct($dbname="", $profileid=""){
			$this->JPROFILE = JPROFILE::getInstance($dbname);
			if($profileid)	$this->PROFILEID=$profileid;
        }

        /**
         * @fn getInstance
         * @brief fetches the current instance of the class
         * @param $dbName - Database name to which the connection would be made
         * @param $profileid - Unique profileid of profile
         * @return instance of the last object. If required profile object is not present then returns new object.
         */
        public static function getInstance($dbName="",$profileid="")
        {
                if(isset(self::$instance))
		{
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

/*************************************Operations***********************************************************/

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
		if(!$criteria){ $criteria = 'PROFILEID'; if(!$value) $value=$this->PROFILEID;} 
		$this->$criteria=$value;
		$res = $this->JPROFILE->get($value, $criteria, $fields);//Fetches results from JPROFILE
        $detail = false;
        if(is_array($res))
        {
            $detail = $this->setDetail($res, $effect);//Sets profile detail to the object
            $this->fieldsArray=array_keys($detail);
        }
		return $detail;
	}

        /**
         * getDecoratedHeight()
         *
         * Returns height label of the profile
         */
        public function getDecoratedHeight(){
                $heightLabel = "";
                if($this->HEIGHT){
			$height = explode("(",FieldMap::getFieldLabel("height",$this->HEIGHT));
			$heightLabel=$height[0];

                }
                else
                {
					if(!in_array("HEIGHT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $heightLabel;
        }

        /**
         * getDecoratedSubcaste()
         *
         * Returns subcaste label of the profile
         */
        public function getDecoratedSubcaste(){
		$subcaste = $this->nullValueMarker;
                if(Flag::isFlagSet("SUBCASTE",$this->SCREENING))
                        $subcaste = $this->SUBCASTE;
                        
                if($this->SUBCASTE=="")
                {
					if(!in_array("SUBCASTE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
		return $subcaste;
	}

        /**
         * getDecoratedGothra()
         *
         * Returns gothra label of the profile
         */
        public function getDecoratedGothra(){
		$gothra = $this->nullValueMarker;
                if(Flag::isFlagSet("GOTHRA",$this->SCREENING))
                        $gothra = $this->GOTHRA;
                if($this->GOTHRA=="")
                {
					if(!in_array("GOTHRA",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
		return $gothra;
	}
        public function getDecoratedGothraMaternal(){
		$gothraM = $this->nullValueMarker;
                if(Flag::isFlagSet("GOTHRA_MATERNAL",$this->SCREENING))
                        $gothraM = $this->GOTHRA_MATERNAL;
                if($this->GOTHRA_MATERNAL=="")
                {
					if(!in_array("GOTHRA_MATERNAL",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
		return $gothraM;
	}

        /**
         * getDecoratedDiet()
         *
         * Returns diet label of the profile
         */
        public function getDecoratedDiet(){
                $dietLabel = $this->nullValueMarker;
                if($this->DIET){
                        $dietLabel = FieldMap::getFieldLabel("diet",$this->DIET);
                }
                else
                {
					if(!in_array("DIET",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $dietLabel;
        }

        /**
         * getDecoratedSmoke()
         *
         * Returns smoke label of the profile
         */
        public function getDecoratedSmoke(){
                $smokeLabel = $this->nullValueMarker;
                if($this->SMOKE){
                        $smokeLabel = FieldMap::getFieldLabel("smoke",$this->SMOKE);
                }
                else
                {
					if(!in_array("SMOKE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $smokeLabel;
        }

        /**
         * getDecoratedDrink()
         *
         * Returns drink label of the profile
         */
        public function getDecoratedDrink(){
                $drinkLabel = $this->nullValueMarker;
                if($this->DRINK){
                        $drinkLabel = FieldMap::getFieldLabel("drink",$this->DRINK);
                }
                else
                {
					if(!in_array("DRINK",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $drinkLabel;
        }

        /**
         * getDecoratedComplexion()
         *
         * Returns complexion label of the profile
         */
        public function getDecoratedComplexion(){
                $complexionLabel = $this->nullValueMarker;
                if($this->COMPLEXION){
                        $complexionLabel = FieldMap::getFieldLabel("complexion",$this->COMPLEXION);
                }
                else
                {
					if(!in_array("COMPLEXION",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $complexionLabel;
        }

        /**
         * getDecoratedBodytype()
         *
         * Returns bodytype label of the profile
         */
        public function getDecoratedBodytype(){
                $bodytypeLabel = $this->nullValueMarker;
                if($this->BTYPE){
                        $bodytypeLabel = FieldMap::getFieldLabel("bodytype",$this->BTYPE);
                }
                else
                {
					if(!in_array("BTYPE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $bodytypeLabel;
        }

        /**
         * getDecoratedBloodGroup()
         *
         * Returns blood_group label of the profile
         */
        public function getDecoratedBloodGroup(){
                $bloodGroupLabel = $this->nullValueMarker;
                if($this->BLOOD_GROUP){
                        $bloodGroupLabel = FieldMap::getFieldLabel("blood_group",$this->BLOOD_GROUP);
                }
                else
                {
					if(!in_array("BLOOD_GROUP",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $bloodGroupLabel;
        }

        /**
         * getDecoratedHandicapped()
         *
         * Returns handicapped label of the profile
         */
        public function getDecoratedHandicapped(){
                $handicappedLabel = "";
                if($this->HANDICAPPED){
                        $handicappedLabel = FieldMap::getFieldLabel("handicapped",$this->HANDICAPPED);
                }
                else
                {
					if(!in_array("HANDICAPPED",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $handicappedLabel;
        }

        /**
         * getDecoratedNatureHandicap()
         *
         * Returns nature_handicap label of the profile
         */
        public function getDecoratedNatureHandicap(){
                $natureHandicapLabel = "";
                if(!($this->getHANDICAPPED()=="NONE" || $this->HANDICAPPED=="")){
                        $natureHandicapLabel = FieldMap::getFieldLabel("nature_handicap",$this->NATURE_HANDICAP);
                }
                if($this->NATURE_HANDICAP=="")
                {
					if(!in_array("NATURE_HANDICAP",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $natureHandicapLabel;
        }


        /**
         * getDecoratedWeight()
         *
         * Returns weight label of the profile
         */
        public function getDecoratedWeight(){
                $weightLabel = $this->nullValueMarker;
                if($this->WEIGHT){
                        $weightLabel = $this->WEIGHT." Kg";
                }
                else
                {
					if(!in_array("WEIGHT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $weightLabel;
        }

        /**
         * getSiblingsInfo()
         *
         * Returns Array of Siblings info of the profile
         */
        public function getSiblings(){
                $tbrother=$this->T_BROTHER;
                if($this->T_BROTHER=="")
                {
					if(!in_array("T_BROTHER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                $mbrother=$this->M_BROTHER;
                if($this->M_BROTHER=="")
                {
					if(!in_array("T_BROTHER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                $tsister=$this->T_SISTER;
                if($this->T_SISTER=="")
                {
					if(!in_array("T_BROTHER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                $msister=$this->M_SISTER;
                if($this->M_SISTER=="")
                {
					if(!in_array("T_BROTHER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                if($tbrother==4) $tbrother="3+";
                if($mbrother==4) $mbrother="3+";
                if($tsister==4) $tsister="3+";
                if($msister==4) $msister="3+";
		return (object)array("tbrother"=>$tbrother,"mbrother"=>$mbrother,"tsister"=>$tsister,"msister"=>$msister);
        }

        /**
         * getDecoratedHiv()
         *
         * Returns hiv label of the profile
         */
        public function getDecoratedHiv(){
                $hivLabel = "";
                if($this->HIV){
                        $hivLabel = FieldMap::getFieldLabel("hiv",$this->HIV);
                }
                else
                {
					if(!in_array("HIV",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $hivLabel;
        }

        /**
         * getDecoratedRstatus()
         *
         * Returns rstatus label of the profile
         */
        public function getDecoratedRstatus(){
                $rstatusLabel = $this->nullValueMarker;
                if($this->RES_STATUS){
                        $rstatusLabel = FieldMap::getFieldLabel("rstatus",$this->RES_STATUS);
                }
                else
                {
					if(!in_array("RES_STATUS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $rstatusLabel;
        }

        /**
         * getDecoratedWorkStatus()
         *
         * Returns work_status label of the profile
         */
        public function getDecoratedWorkStatus(){
                $work_statusLabel = $this->nullValueMarker;
                if($this->WORK_STATUS){
                        $work_statusLabel = FieldMap::getFieldLabel("work_status",$this->WORK_STATUS);
                }
                else
                {
					if(!in_array("WORK_STATUS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $work_statusLabel;
        }

        /**
         * getDecoratedOccupation()
         *
         * Returns occupation label of the profile
         */
        public function getDecoratedOccupation(){
                $occupationLabel = $this->nullValueMarker;
                if($this->OCCUPATION){
                        $occupationLabel = FieldMap::getFieldLabel("occupation",$this->OCCUPATION);
                }
                else
                {
					if(!in_array("OCCUPATION",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $occupationLabel;
        }

        /**
         * getDecoratedIncomeLevel()
         *
         * Returns income_level label of the profile
         */
        public function getDecoratedIncomeLevel(){
                $income_levelLabel = $this->nullValueMarker;
                if($this->INCOME){
                        $income_levelLabel = FieldMap::getFieldLabel("income_level",$this->INCOME);
                }
                else
                {
					if(!in_array("INCOME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $income_levelLabel;
        }

        public function getDecoratedFamilyIncome(){
                $income_Label = $this->nullValueMarker;
                if($this->FAMILY_INCOME){
                        $income_Label = FieldMap::getFieldLabel("income_level",$this->FAMILY_INCOME);
                }
                else
                {
					if(!in_array("FAMILY_INCOME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $income_Label;
        }
        /**
         * getDecoratedCareerAfterMarriage()
         *
         * Returns career_after_marriage label of the profile
         */
        public function getDecoratedCareerAfterMarriage(){
                $career_after_marriageLabel = $this->nullValueMarker;
                if($this->MARRIED_WORKING){
                        $career_after_marriageLabel = FieldMap::getFieldLabel("career_after_marriage",$this->MARRIED_WORKING);
                }
                else
                {
					if(!in_array("MARRIED_WORKING",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $career_after_marriageLabel;
        }

        /**
         * getDecoratedFamilyValues()
         *
         * Returns family_values label of the profile
         */
        public function getDecoratedFamilyValues(){
                $family_valuesLabel = $this->nullValueMarker;
                if($this->FAMILY_VALUES){
                        $family_valuesLabel = FieldMap::getFieldLabel("family_values",$this->FAMILY_VALUES);
                }
                else
                {
					if(!in_array("FAMILY_VALUES",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $family_valuesLabel;
        }

        /**
         * getDecoratedFamilyType()
         *
         * Returns family_type label of the profile
         */
        public function getDecoratedFamilyType(){
                $family_typeLabel = $this->nullValueMarker;
                if($this->FAMILY_TYPE){
                        $family_typeLabel = FieldMap::getFieldLabel("family_type",$this->FAMILY_TYPE);
                }
                else
                {
					if(!in_array("FAMILY_TYPE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $family_typeLabel;
        }

        /**
         * getDecoratedFamilyStatus()
         *
         * Returns family_status label of the profile
         */
        public function getDecoratedFamilyStatus(){
                $family_statusLabel = $this->nullValueMarker;
                if($this->FAMILY_STATUS){
                        $family_statusLabel = FieldMap::getFieldLabel("family_status",$this->FAMILY_STATUS);
                }
                else
                {
					if(!in_array("FAMILY_STATUS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $family_statusLabel;
        }

        /**
         * getDecoratedFamilyBackground()
         *
         * Returns family_background label of the profile
         */
        public function getDecoratedFamilyBackground(){
                $family_backgroundLabel = $this->nullValueMarker;
                if($this->FAMILY_BACK){
                        $family_backgroundLabel = FieldMap::getFieldLabel("family_background",$this->FAMILY_BACK);
                }
                else
                {
					if(!in_array("FAMILY_BACK",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $family_backgroundLabel;
        }

        /**
         * getDecoratedMotherOccupation()
         *
         * Returns mother_occupation label of the profile
         */
        public function getDecoratedMotherOccupation(){
                $mother_occupationLabel = $this->nullValueMarker;
                if($this->MOTHER_OCC){
                        $mother_occupationLabel = FieldMap::getFieldLabel("mother_occupation",$this->MOTHER_OCC);
                }
                else
                {
					if(!in_array("MOTHER_OCC",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $mother_occupationLabel;
        }

        /**
         * getDecoratedCountry()
         *
         * Returns country label of the profile
         */
        public function getDecoratedCountry(){
                $countryLabel = "";
                if($this->COUNTRY_RES){
                        $countryLabel = FieldMap::getFieldLabel("country",$this->COUNTRY_RES);
                }
                else
                {
					if(!in_array("COUNTRY_RES",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $countryLabel;
        }
        /**
         * getDecoratedCitizenship()
         *
         * Returns country label of the profile
         */
        public function getDecoratedCitizenship(){
                $citizenshipLabel = $this->nullValueMarker;
                if($this->CITIZENSHIP){
                        $citizenshipLabel = FieldMap::getFieldLabel("country",$this->CITIZENSHIP);
                }
                else
                {
					if(!in_array("CITIZENSHIP",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $citizenshipLabel;
        }

        /**
         * getDecoratedBirthCountry()
         *
         * Returns birth country label of the profile
         */
        public function getDecoratedBirthCountry(){
                $birthCountryLabel = "";
                if($this->COUNTRY_BIRTH){
                        $birthCountryLabel = FieldMap::getFieldLabel("country",$this->COUNTRY_BIRTH);
                }
                else
                {
					if(!in_array("COUNTRY_BIRTH",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $birthCountryLabel;
        }

        /**
         * getDecoratedManglik()
         *
         * Returns manglik label of the profile
         */
        public function getDecoratedManglik(){
                $manglikLabel = $this->nullValueMarker;
                if($this->MANGLIK){
                        $manglikLabel = FieldMap::getFieldLabel("manglik",$this->MANGLIK);
                }
                else
                {
					if(!in_array("MANGLIK",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $manglikLabel;
        }
        
        public function getDecoratedSHOW_HOROSCOPE(){
            $astroPrivacyLabel = "";
            $astroPrivacy = $this->getSHOW_HOROSCOPE();
            if($astroPrivacy){
                $astroPrivacyLabel = FieldMap::getFieldLabel("astro_privacy_label",$astroPrivacy);
            }
            return $astroPrivacyLabel;
        }
        
        
        /**
         * getDecoratedReligion()
         *
         * Returns religion label of the profile
         */
        public function getDecoratedReligion(){
                $religionLabel = "";
                if($this->RELIGION){
                        $religionLabel = FieldMap::getFieldLabel("religion",$this->RELIGION);
                }
                else
                {
					if(!in_array("RELIGION",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $religionLabel;
        }

        /**
         * getDecoratedCommunity()
         *
         * Returns community label of the profile
         */
        public function getDecoratedCommunity(){
                $communityLabel = $this->nullValueMarker;
                if($this->MTONGUE){
                        $communityLabel = FieldMap::getFieldLabel("community",$this->MTONGUE);
                }
                else
                {
					if(!in_array("MTONGUE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $communityLabel;
        }

        /**
         * getDecoratedCaste()
         *
         * Returns caste label of the profile
         */
        public function getDecoratedCaste(){
                $casteLabel = $this->nullValueMarker;
                if($this->CASTE){
                        $casteLabel = FieldMap::getFieldLabel("caste",$this->CASTE);
                }
                else
                {
					if(!in_array("CASTE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $casteLabel;
        }

        /**
         * getDecoratedSect()
         *
         * Returns sect label of the profile
         */
        public function getDecoratedSect(){
                $sectLabel = $this->nullValueMarker;
                if($this->SECT){
                        $sectLabel = FieldMap::getFieldLabel("sect",$this->SECT);
                }
                else
                {
					if(!in_array("SECT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $sectLabel;
        }

        /**
         * getDecoratedMaritalStatus()
         *
         * Returns marital_status label of the profile
         */
        public function getDecoratedMaritalStatus(){
                $marital_statusLabel = $this->nullValueMarker;
                if($this->MSTATUS){
                        $marital_statusLabel = FieldMap::getFieldLabel("marital_status",$this->MSTATUS);
                }
                else
                {
					if(!in_array("MSTATUS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $marital_statusLabel;
        }
		/**
         * getDecoratedHaveChild()
         *
         * Returns have_child label of the profile
         */
        public function getDecoratedHaveChild(){
                $marital_statusLabel = $this->nullValueMarker;
                if($this->HAVECHILD){
                        $havechildLabel = FieldMap::getFieldLabel("children",$this->HAVECHILD);
                }
                else
                {
					if(!in_array("HAVECHILD",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $havechildLabel;
        }
        /**
         * getDecoratedEducation()
         *
         * Returns education label of the profile
         */
        public function getDecoratedEducation(){
                $educationLabel = $this->nullValueMarker;
                if($this->EDU_LEVEL_NEW){
                        $educationLabel = FieldMap::getFieldLabel("education",$this->EDU_LEVEL_NEW);
                }
                else
                {
					if(!in_array("EDU_LEVEL_NEW",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $educationLabel;
        }

        /**
         * getDecoratedGender()
         *
         * Returns gender label of the profile
         */
        public function getDecoratedGender(){
                $genderLabel = "";
                if($this->GENDER){
                        $genderLabel = FieldMap::getFieldLabel("gender",$this->GENDER);
                }
                else
                {
					if(!in_array("GENDER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $genderLabel;
        }

        /**
         * getDecoratedCity()
         *
         * Returns city label of the profile
         */
        public function getDecoratedCity(){
                $cityLabel = "";
                if($this->CITY_RES||$this->CITY_RES==='0'){
                        $cityLabel = FieldMap::getFieldLabel("city",$this->CITY_RES);
                }
                else
                {
					if(!in_array("CITY_RES",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $cityLabel;
        }

        /**
         * getDecoratedRelation()
         *
         * Returns relation label of the profile
         */
        public function getDecoratedRelation(){
                $relationLabel = "";
                if($this->RELATION){
                        $relationLabel = FieldMap::getFieldLabel("relation",$this->RELATION);
                }
                else
                {
					if(!in_array("RELATION",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $relationLabel;
        }

        /**
         * getDecoratedLiveWithParents()
         *
         * Returns live_with_parents label of the profile
         */
        public function getDecoratedLiveWithParents(){
                $liveWithParentsLabel = $this->nullValueMarker;
                if($this->PARENT_CITY_SAME){
                        $liveWithParentsLabel = FieldMap::getFieldLabel("live_with_parents",$this->PARENT_CITY_SAME);
                }
                else
                {
					if(!in_array("PARENT_CITY_SAME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $liveWithParentsLabel;
        }

        public function getDecoratedThalassemia(){
                $thalassemiaLabel = $this->nullValueMarker;
                if($this->THALASSEMIA){
                        $thalassemiaLabel = FieldMap::getFieldLabel("thalassemia",$this->THALASSEMIA);
                }
                else
                {
					if(!in_array("THALASSEMIA",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $thalassemiaLabel;
        }
        public function getDecoratedHaveCar(){
                $haveCarLabel = $this->nullValueMarker;
                if($this->HAVE_CAR){
                        $haveCarLabel = FieldMap::getFieldLabel("have_car",$this->HAVE_CAR);
                }
                else
                {
					if(!in_array("HAVE_CAR",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $haveCarLabel;
        }
        public function getDecoratedOwnHouse(){
                $ownHouseLabel = $this->nullValueMarker;
                if($this->OWN_HOUSE){
                        $ownHouseLabel = FieldMap::getFieldLabel("own_house",$this->OWN_HOUSE);
                }
                else
                {
					if(!in_array("OWN_HOUSE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $ownHouseLabel;
        }
        public function getDecoratedOpenToPet(){
                $Label = $this->nullValueMarker;
                if($this->OPEN_TO_PET){
                        $Label = FieldMap::getFieldLabel("open_to_pet",$this->OPEN_TO_PET);
                }
                else
                {
					if(!in_array("OPEN_TO_PET",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $Label;
        }
        public function getDecoratedSettlingAbroad(){
                $Label = $this->nullValueMarker;
                if($this->GOING_ABROAD){
					$Label = FieldMap::getFieldLabel("going_abroad",$this->GOING_ABROAD);
                }
                else
                {
					if(!in_array("GOING_ABROAD",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $Label;
        }
        public function getDecoratedLandlineNumberOwner(){
                $Label = $this->nullValueMarker;
                if($this->PHONE_NUMBER_OWNER){
                        $Label = FieldMap::getFieldLabel("number_owner",$this->PHONE_NUMBER_OWNER);
                }
                else
                {
					if(!in_array("PHONE_NUMBER_OWNER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $Label;
        }
        public function getDecoratedMobileNumberOwner(){
                $Label = $this->nullValueMarker;
                if($this->MOBILE_NUMBER_OWNER){
                        $Label = FieldMap::getFieldLabel("number_owner",$this->MOBILE_NUMBER_OWNER);
                }
                else
                {
					if(!in_array("MOBILE_NUMBER_OWNER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $Label;
        }
        public function getDecoratedSunsign(){
                $Label = $this->nullValueMarker;
                if($this->SUNSIGN){
                        $Label = FieldMap::getFieldLabel("sunsign",$this->SUNSIGN);
                }
                else
                {
					if(!in_array("SUNSIGN",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $Label;
        }
        public function getDecoratedID_PROOF_TYP(){
                $Label = $this->nullValueMarker;
                if($this->ID_PROOF_TYP){
                        $Label = FieldMap::getFieldLabel("id_proof_typ",$this->ID_PROOF_TYP);
                }
                else
                {
					if(!in_array("ID_PROOF_TYP",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $Label;
        }
        /**
         * getDecoratedCompany()
         *
         * Returns company label of the profile
         */
        public function getDecoratedCompany(){
				$value=$this->nullValueMarker;
                if(Flag::isFlagSet("company_name",$this->SCREENING))
					$value=$this->COMPANY_NAME;
				if($this->COMPANY_NAME=="")
                {
					if(!in_array("COMPANY_NAME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
				return $value;
        }
        public function getDecoratedPersonHandlingProfile(){
				$value=$this->nullValueMarker;
                if(Flag::isFlagSet("profile_handler_name",$this->SCREENING))
                        $value=$this->PROFILE_HANDLER_NAME;
                if($this->PROFILE_HANDLER_NAME=="")
                {
					if(!in_array("PROFILE_HANDLER_NAME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
				return $value;
        }

        /**
         * getHobbies()
         *
         * Returns Hobbies array
         */
        public function getHobbies($onlyValues="")
        {
                        $viewProfileOptimization = viewProfileOptimization::getInstance('',$this->PROFILEID);
                        $hobbies = $viewProfileOptimization->getHobbiesForUser();
                        if(isset($hobbies) && $onlyValues == '')
                            $userHobbies = $hobbies;
                        else{
                            $hobbyObj=new JHOBBYCacheLib();
                            $userHobbies = $hobbyObj->getUserHobbies($this->PROFILEID,$onlyValues);
                        }
			if($onlyValues)
				return $userHobbies;
			$flag_arr=array(
				"fav_food"=>"FAV_FOOD",
				"fav_tvshow"=>"FAV_TVSHOW",
				"fav_movie"=>"FAV_MOVIE",
				"fav_book"=>"FAV_BOOK",
				"fav_vac_dest"=>"FAV_VAC_DEST",
			);
			$component=$this->addScreeningMsgToComponent($flag_arr,$userHobbies);
			
			return $component;
	}

	/**
	 * getAstroKundali()
	 *
	 * Returns kundli information array
	 */
	public function getAstroKundali($onlyValues="")
	{
		$astro= ProfileAstro::getInstance();

		$astroDetails=$astro->getAstros($this->PROFILEID);
		if($onlyValues)
			return $astroDetails;
		if($astroDetails[CITY_BIRTH])
		{
				$cityOfBirth = $astroDetails['CITY_BIRTH'];
				$countryOfBirth=$astroDetails['COUNTRY_BIRTH'];
		}
		else{
				$countryOfBirth=$this->DecoratedBirthCountry;
				$cityOfBirth=$this->nullValueMarker;
		}

		if($this->BTIME!="")
		{
			$btime=explode(":",$this->BTIME);
			$birthTimeHour=$btime[0];
			$birthTimeMin=$btime[1];
			if($birthTimeHour!="" && $birthTimeMin!="") $birthTime = $birthTimeHour." hrs:".$birthTimeMin." mins";
			else $birthTime = "Not Available";
		}
		else
		{
			$birthTime = "Not Available";
			if(!in_array("BTIME",$this->fieldsArray))
			ProfileFieldsLogging::callFieldStack(1);
		}

		$manglik=$this->getDecoratedManglik();
		$rashiId=$this->RASHI;
		if($this->RASHI=="")
		{
			if(!in_array("RASHI",$this->fieldsArray))
			ProfileFieldsLogging::callFieldStack(1);
		}
		$rashiObj=new NEWJS_RASHI();
		$result=$rashiObj->getRashi($rashiId);
		$rashiLabel=$result[LABEL]?$result[LABEL]:$this->nullValueMarker;
		$nakshatra=$this->NAKSHATRA?$this->NAKSHATRA:$this->nullValueMarker;
		if($this->NAKSHATRA=="")
		{
			if(!in_array("NAKSHATRA",$this->fieldsArray))
			ProfileFieldsLogging::callFieldStack(1);
		}
		$sunsign=$this->SUNSIGN?$this->getDecoratedSunsign():$this->nullValueMarker;
    
    $bAstroDetailExist = true;
    if (0 === count($astroDetails) || false === isset($astroDetails['PROFILEID'])) {
      $bAstroDetailExist = false;
    }
		return (object)array("dateOfBirth"=>JsCommon::formatDate($this->DTOFBIRTH),"dateOfBirthBi"=>JsCommon::formatDate($this->DTOFBIRTH,2),"cityOfBirth"=>$cityOfBirth,"countryOfBirth"=>$countryOfBirth,"birthTimeHour"=>$birthTimeHour,"birthTimeMin"=>$birthTimeMin,"manglik"=>$manglik,"rashi"=>$rashiLabel,"nakshatra"=>$nakshatra, "birthTime"=>$birthTime,"sunsign"=>$sunsign,"astroDetailExist"=>$bAstroDetailExist);
		
	}

        /**
         * getDecoratedAncestralOrigin()
         *
         * Returns native place
         */
	public function getDecoratedAncestralOrigin(){
		$nativePlace = $this->nullValueMarker;
                if(Flag::isFlagSet("ANCESTRAL_ORIGIN",$this->SCREENING))
                        $nativePlace = $this->ANCESTRAL_ORIGIN;
                if($this->ANCESTRAL_ORIGIN=="")
                {
					if(!in_array("ANCESTRAL_ORIGIN",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
		return $nativePlace;
	}

        /**
         * getDecoratedBirthCity()
         *
         * Returns birth city of profile
         */
        public function getDecoratedBirthCity(){
                $birthCity = $this->nullValueMarker;
                if(Flag::isFlagSet("CITYBIRTH",$this->SCREENING))
                        $birthCity = ucwords($this->CITY_BIRTH);
                if($this->CITY_BIRTH=="")
                {
					if(!in_array("CITY_BIRTH",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
                return $birthCity;
        }

        /**
         * getDecoratedHoroscopeMatch()
         *
         * Returns whether horoscope match needed
         */
        public function getDecoratedHoroscopeMatch(){
		$horoMatch = $this->nullValueMarker;
		if($this->HOROSCOPE_MATCH){
			if($this->HOROSCOPE_MATCH == "Y") $horoMatch = "Must";
			else $horoMatch = "Not necessary";
		}
		else
		{
			if(!in_array("HOROSCOPE_MATCH",$this->fieldsArray))
			ProfileFieldsLogging::callFieldStack(1);
		}
		return $horoMatch;
        }

        /**
         * getReligionInfo()
         *
         * Returns Religion specific detail
         */
        public function getReligionInfo($valuesOnly="")
        {
		$data = "";

                //Jain profile
                if($this->getRELIGION()==Religion::JAIN) $data = $this->getJainData($valuesOnly);

                //Christian profile     
                if($this->getRELIGION()==Religion::CHRISTIAN) $data = $this->getChristianData($valuesOnly);

                //Muslim profile
                if($this->getRELIGION()==Religion::MUSLIM) $data = $this->getMuslimData($valuesOnly);

                //Sikh profile
                if($this->getRELIGION()==Religion::SIKH) $data = $this->getSikhData($valuesOnly);

                //Parsi profile
                if($this->getRELIGION()==Religion::PARSI) $data = $this->getParsiData($valuesOnly);

		return $data;
        }

        /**
         * getDecoratedYourInfo()
         *
         * Returns profile information
         */
	public function getDecoratedYourInfo(){

                
                if(Flag::isFlagSet("yourinfo",$this->SCREENING))
                {
                    return nl2br($this->YOURINFO);
                }
                else
                {
                    $profileYourInfoOld = new ProfileYourInfoOld();
                    $oldYourInfo = $profileYourInfoOld->getAboutMeOld($this->PROFILEID)['YOUR_INFO_OLD'];
                    if($this->YOURINFO=="")
                    {
                        if(!in_array("YOURINFO",$this->fieldsArray))
                        ProfileFieldsLogging::callFieldStack(1);
                    }

                    if ( $oldYourInfo !== NULL )
                    {
                        return $oldYourInfo;
                    }
                }
    }
        /**
         * getDecoratedFamilyInfo()
         *
         * Returns family information
         */
    public function getDecoratedFamilyInfo(){
                if(Flag::isFlagSet("familyinfo",$this->SCREENING))
                        return nl2br($this->FAMILYINFO);
                if($this->FAMILYINFO=="")
                {
					if(!in_array("FAMILYINFO",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
	}
        /**
         * getDecoratedEducationInfo()
         *
         * Returns education information
         */
	public function getDecoratedEducationInfo(){
                if(Flag::isFlagSet("education",$this->SCREENING))
                        return nl2br($this->EDUCATION);
                if($this->EDUCATION=="")
                {
					if(!in_array("EDUCATION",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
	}
        /**
         * getDecoratedJobInfo()
         *
         * Returns job information
         */
	public function getDecoratedJobInfo(){
                if(Flag::isFlagSet("job_info",$this->SCREENING))
                        return nl2br($this->JOB_INFO);
                if($this->JOB_INFO=="")
                {
					if(!in_array("JOB_INFO",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
	}
        /**
         * getDecoratedSpouseInfo()
         *
         * Returns spouse information
         */
	public function getDecoratedSpouseInfo(){
                if(Flag::isFlagSet("spouse",$this->SCREENING))
                        return nl2br($this->SPOUSE);
                if($this->SPOUSE=="")
                {
					if(!in_array("SPOUSE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
	}

        /**
         * getParsiData()
         *
         * Returns parsi religion specific data
         */
        private function getParsiData($valuesOnly="")
        {
			$pp=new NEWJS_JP_PARSI();
			$row_parsi=$pp->getJpParsi($this->PROFILEID);
			if($valuesOnly)
				return $row_parsi;
			else{
				$row_parsi[ZARATHUSHTRI]=$row_parsi['ZARATHUSHTRI']?FieldMap::getFieldLabel("zarathushtri",$row_parsi['ZARATHUSHTRI']):$this->nullValueMarker;
				$row_parsi[PARENTS_ZARATHUSHTRI]=$row_parsi['PARENTS_ZARATHUSHTRI']?FieldMap::getFieldLabel("parents_zarathushtri",$row_parsi['PARENTS_ZARATHUSHTRI']):$this->nullValueMarker;
				return (object)$row_parsi;
			}
        }

        /**
         * getSikhData()
         *
         * Returns sikh religion specific data
         */
        private function getSikhData($valuesOnly="")
        {
            $sp=new NEWJS_JP_SIKH();
			$row_sikh=$sp->getJpSikh($this->PROFILEID);
			
			if($valuesOnly)
				return $row_sikh;
			else{
				$row_sikh['AMRITDHARI']=$row_sikh['AMRITDHARI']?FieldMap::getFieldLabel("amritdhari",$row_sikh['AMRITDHARI']):$this->nullValueMarker;
				$row_sikh['CUT_HAIR']=$row_sikh['CUT_HAIR']?FieldMap::getFieldLabel("cut_hair",$row_sikh['CUT_HAIR']):$this->nullValueMarker;
				$row_sikh['TRIM_BEARD']=$row_sikh['TRIM_BEARD']?FieldMap::getFieldLabel("trim_beard",$row_sikh['TRIM_BEARD']):$this->nullValueMarker;
				$row_sikh['WEAR_TURBAN']=$row_sikh['WEAR_TURBAN']?FieldMap::getFieldLabel("wear_turban",$row_sikh['WEAR_TURBAN']):$this->nullValueMarker;
				$row_sikh['CLEAN_SHAVEN']=$row_sikh['CLEAN_SHAVEN']?FieldMap::getFieldLabel("clean_shaven",$row_sikh['CLEAN_SHAVEN']):$this->nullValueMarker;
				return (object)$row_sikh;
			}
	}

        /**
         * getJainData()
         *
         * Returns jain religion specific data
         */
        private function getJainData($valuesOnly="")
        {
			$sampraday=$this->nullValueMarker;
			$sp=new NEWJS_JP_JAIN();
			$sampraday=$sp->getSamPraday($this->PROFILEID);
			if($valuesOnly)
				return $sampraday;
			else{
				$sampraday=$sampraday?FieldMap::getFieldLabel("sampraday",$sampraday):$this->nullValueMarker;
				return $sampraday;
			}
        }

        /**
         * getMuslimData()
         *
         * Returns muslim religion specific data
         */
        private function getMuslimData($valuesOnly="")
        {
			$mp=new NEWJS_JP_MUSLIM();
			$row_muslim=$mp->getJpMuslim($this->PROFILEID);
			if($valuesOnly)
				return $row_muslim;
			else{
				$math_val = $row_muslim['MATHTHAB'];
				
				if($this->getDecoratedCaste() == "Muslim: Sunni")
					$row[MATHTHAB]=$math_val?FieldMap::getFieldLabel("maththab_sunni",$math_val):$this->nullValueMarker;
				if($this->getDecoratedCaste()=="Muslim: Shia")
					$row[MATHTHAB]=$math_val?FieldMap::getFieldLabel("maththab_shia",$math_val):$this->nullValueMarker;	
					
				$row[SPEAK_URDU]=$this->getSPEAK_URDU()?FieldMap::getFieldLabel("speak_urdu",$this->SPEAK_URDU):$this->nullValueMarker;
				$row[NAMAZ]=$row_muslim['NAMAZ']?FieldMap::getFieldLabel("namaz",$row_muslim['NAMAZ']):$this->nullValueMarker;

				$row[ZAKAT]=$row_muslim['ZAKAT']?FieldMap::getFieldLabel("zakat",$row_muslim['ZAKAT']):$this->nullValueMarker;

				$row[FASTING]=$row_muslim['FASTING']?FieldMap::getFieldLabel("fasting",$row_muslim['FASTING']):$this->nullValueMarker;
				$row[QURAN]=$row_muslim['QURAN']?FieldMap::getFieldLabel("quran",$row_muslim['QURAN']):$this->nullValueMarker;
				$row[UMRAH_HAJJ]=$row_muslim['UMRAH_HAJJ']?FieldMap::getFieldLabel("umrah_hajj",$row_muslim['UMRAH_HAJJ']):$this->nullValueMarker;
				$row[SUNNAH_BEARD]=$row_muslim['SUNNAH_BEARD']?FieldMap::getFieldLabel("sunnah_beard",$row_muslim['SUNNAH_BEARD']):$this->nullValueMarker;
				$row[SUNNAH_CAP]=$row_muslim['SUNNAH_CAP']?FieldMap::getFieldLabel("sunnah_cap",$row_muslim['SUNNAH_CAP']):$this->nullValueMarker;
				$row[HIJAB]=$row_muslim['HIJAB']?FieldMap::getFieldLabel("hijab",$row_muslim['HIJAB']):$this->nullValueMarker;
				$row[HIJAB_MARRIAGE]=$row_muslim['HIJAB_MARRIAGE']?FieldMap::getFieldLabel("hijab_marriage",$row_muslim['HIJAB_MARRIAGE']):$this->nullValueMarker;
				$row[WORKING_MARRIAGE]=$row_muslim['WORKING_MARRIAGE']?FieldMap::getFieldLabel("working_marriage",$row_muslim['WORKING_MARRIAGE']):$this->nullValueMarker;
				$row[JAMAAT]=$row_muslim['JAMAAT']?FieldMap::getFieldLabel("jamaat",$row_muslim['JAMAAT']):$this->nullValueMarker;
				return (object)$row;
			}
			//return (object)array("MATHTHAB"=>$maththab,"namaz"=>$namaz,"zakat"=>$row_muslim['ZAKAT'],"fasting"=>$fasting,"quran"=>$quran,"umrah_hajj"=>$umrah_hajj,"sunnah_beard"=>$sunnah_beard,"sunnah_cap"=>$sunnah_cap, "hijab"=>$hijab,"hijab_marriage"=>$hijabMarriage,"working_marriage"=>$working_marriage,"speak_urdu"=>$speakUrdu);
        }

        /**
         * getChristianData()
         *
         * Returns christian religion specific data
         */
        private function getChristianData($valuesOnly="")
        {
		$cp=new NEWJS_JP_CHRISTIAN();
		$row_christian=$cp->getJpChristian($this->PROFILEID);
		if($valuesOnly)
			return $row_christian;
		else{

			$flagArray=array("GOTHRA"=>"DIOCESE");
			$row_christian=(array)$this->addScreeningMsgToComponent($flagArray,$row_christian);
			
			$row[DIOCESE]=$row_christian[DIOCESE]?$row_christian[DIOCESE]:$this->nullValueMarker;
			$row[BAPTISED]=$row_christian['BAPTISED']?FieldMap::getFieldLabel("baptised",$row_christian['BAPTISED']):$this->nullValueMarker;
			$row[READ_BIBLE]=$row_christian['READ_BIBLE']?FieldMap::getFieldLabel("read_bible",$row_christian['READ_BIBLE']):$this->nullValueMarker;
			$row[OFFER_TITHE]=$row_christian['OFFER_TITHE']?FieldMap::getFieldLabel("offer_tithe",$row_christian['OFFER_TITHE']):$this->nullValueMarker;
			$row[SPREADING_GOSPEL]=$row_christian['SPREADING_GOSPEL']?FieldMap::getFieldLabel("spreading_gospel",$row_christian['SPREADING_GOSPEL']):$this->nullValueMarker;
			return (object)$row;
		}
			//return (object)array("diocese"=>$diocese,"baptised"=>$baptised,"read_bible"=>$readBible,"offer_tithe"=>$offerTithe,"spreading_gospel"=>$spreadingGospel);
        }

        /**
         * getAlternativeContacts()
         *
         * Returns alternative contacts array of profile
         */
        public function getExtendedContacts($onlyValues="")
		{
			if($this->HAVE_JCONTACT=="Y"){
				$pc= new ProfileContact();
				$contacts_arr=$pc->getProfileContacts($this->PROFILEID);
				if($onlyValues)
					return $contacts_arr;
				$contacts_arr['ALT_MOBILE_NUMBER_OWNER']=FieldMap::getFieldLabel("number_owner",$contacts_arr['ALT_MOBILE_NUMBER_OWNER']);
				$flagArray=array(
					"linkedin_url"=>"LINKEDIN_URL",
					"fb_url"=>"FB_URL",
					"blackberry"=>"BLACKBERRY"
				);
			}
			if($onlyValues)
				return "";
			$contacts=$this->addScreeningMsgToComponent($flagArrray,$contacts_arr);
			return $contacts;
        }

        /**
         * getEducationDetail()
         *
         * Returns education detail array of profile
         */
        public function getEducationDetail($valuesOnly="",$dbname="")
        {
			if($this->HAVE_JEDUCATION=='Y'){
				//If already fetched then return the fetched object
				//otherwise fetch education details
				if(! $this->education_other instanceof ProfileComponent){
				$pe = ProfileEducation::getInstance($dbname);
				$education_arr=$pe->getProfileEducation($this->PROFILEID);
				if($valuesOnly)
					return $education_arr;
				$flagArray=array(
					"pg_college"=>"PG_COLLEGE",
					"school"=>"SCHOOL",
					"college"=>"COLLEGE",
					"other_ug_degree"=>"OTHER_UG_DEGREE",
					"other_pg_degree"=>"OTHER_PG_DEGREE",
				);
				$education_arr['UG_DEGREE']=FieldMap::getFieldLabel("degree_ug",$education_arr['UG_DEGREE']);
				$education_arr['PG_DEGREE']=FieldMap::getFieldLabel("degree_pg",$education_arr['PG_DEGREE']);
				$this->education_other=$this->addScreeningMsgToComponent($flagArray,$education_arr);
				}
			}
			elseif ($this->HAVE_JEDUCATION=="")
                {
					if(!in_array("HAVE_JEDUCATION",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
				}
			return $this->education_other;
        }

        /**
         * @fn convertObjectToArray
         * @brief Converts Profile Object to array; It will be majorly used in integrating older functionality
         * @return Profile detail array;
         */
	public function convertObjectToArray(){
		$profileArr = array();
		$fields = $this->JPROFILE->fields();
		foreach($this as $key=>$val){
			if(in_array($key,$fields)) $profileArr[$key] = $val;
		}
		return $profileArr;
	}
		public function addScreeningMsgToComponent($flag_array,$resultArray)
		{
			$component=new ProfileComponent();
			$component->setNullValueMarker($this->nullValueMarker);
			if(count($resultArray)<=0)
				return $resultArray;

			if(is_array($resultArray))
				foreach($resultArray as $field=>$value)
					if($value)$component->$field=$value;
			if(is_array($flag_array))
				$component=$this->addScreeningMessages($flag_array,$component);
				return $component;
		}
  		/** @fn To be Overridden method. This appends screening message to fields currently in screening.
		 *  @param array flag_array It is key value array of fields that are to be tested for screening and value will be corresponding name of field.
		 *  @param ProfileComponent component It is ProfileComponent object
		 *  */
		public function addScreeningMessages(array $flags,ProfileComponent $component)
		{
				foreach($flags as $flag=>$field){
					if(!Flag::isFlagSet($flag,$this->SCREENING))
						$component->$field=$this->nullValueMarker;
				}
				
			return $component;
		}
        /**
         * @fn unsetObject
         * @brief unsets Profile Object
         * @return null
         */
        public function unsetObject(){
                $fields = $this->JPROFILE->fields();
                foreach($this as $field=>$val){
			$value = "";
                        if(in_array($field,$fields)) $this->$field=$value;
                }
        }

        /**
         * @fn setDetail
         * @brief sets the detail to Profile Object as per the effect.
         * @param $res Key-value pair of columns and data-value.
         * @param $effect RAW or DECORATED; 
         * @return Profile detail array;
         */
	public function setDetail($res, $effect="DECORATED"){
		if($res){
		/*	if($effect=="DECORATED"){
				$res = $this->mapOutput($res);
		}
		 */
			foreach($res as $field=>$value){
				$this->$field=$value;
				if(in_array($field,ProfileEnums::$saveBlankIfZeroForFields) &&$value=="0")
					$this->$field='';
			}
		}
		return $res;
	}

        /**
         * @fn mapOutput
         * @brief Helper function for DECORATED effect. Uses static FieldMap class
         * @param $output Key-value pair of columns and data-value to decorate
         * @return decorated output
         */
	public function mapOutput($output){
		if(isset($output["GENDER"])) $output["GENDER"] = FieldMap::getFieldLabel($output['GENDER'],"GENDER");
		if(isset($output["MTONGUE"])) $output["MTONGUE"] = FieldMap::getFieldLabel($output["MTONGUE"],"MTONGUE");
 		if(isset($output["CASTE"])) $output["CASTE"] = FieldMap::getFieldLabel($output['CASTE'],"CASTE");
		if(isset($output["SUBSCRIPTION"])) $output["SUBSCRIPTION"] = FieldMap::getFieldLabel($output['SUBSCRIPTION'],"SUBSCRIPTION");
		return $output;
	}

	//Example
	public function getPhoneNumber(){
		if($this->getSHOWPHONE_RES()) return $this->getSTD()."-".$this->PHONE_RES;
		else
		{
			if(!in_array("getSHOWPHONE_RES",$this->fieldsArray))
			ProfileFieldsLogging::callFieldStack(1);
		}
	}

/*************************************Ends here*********************************************************/

/*************************************Getter/Setter*****************************************************/

	function setPROFILEID($PROFILEID) { $this->PROFILEID = $PROFILEID; }
	function getPROFILEID() {
		if(!$this->PROFILEID)
			{
				if(!in_array("PROFILEID",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PROFILEID; }

    function setVERIFY_ACTIVATED_DT($VERIFY_ACTIVATED_DT)
    {
        $this->VERIFY_ACTIVATED_DT = $VERIFY_ACTIVATED_DT;
    }

    function getVERIFY_ACTIVATED_DT()
    {
        if(!$this->VERIFY_ACTIVATED_DT)
        {
            if(!in_array("VERIFY_ACTIVATED_DT",$this->fieldsArray))
                ProfileFieldsLogging::callFieldStack(1);
        }
        return $this->VERIFY_ACTIVATED_DT;
    }
	function setUSERNAME($USERNAME) { $this->USERNAME = $USERNAME; }
	function getUSERNAME() {
		if(!$this->USERNAME)
			{
				if(is_array($this->fieldsArray) && !in_array("USERNAME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->USERNAME;}
	function setPASSWORD($PASSWORD) { $this->PASSWORD = $PASSWORD; }
	function getPASSWORD() {if(!in_array("PASSWORD",$this->fieldsArray)) ProfileFieldsLogging::callFieldStack(1);
	
		if(!$this->PASSWORD)
			{
				if(!in_array("PASSWORD",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PASSWORD; }
	function setGENDER($GENDER) { $this->GENDER = $GENDER; }
	function getGENDER() { 
		if(!$this->GENDER)
			{
				if(!in_array("GENDER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->GENDER; }
	function setRELIGION($RELIGION) { $this->RELIGION = $RELIGION; }
	function getRELIGION() { 
		if(!$this->RELIGION)
			{
				if(!in_array("RELIGION",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->RELIGION; }
	function setCASTE($CASTE) { $this->CASTE = $CASTE; }
	function getCASTE() { 
		if(!$this->CASTE)
			{
				if(!in_array("CASTE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->CASTE; }
	function setMANGLIK($MANGLIK) { $this->MANGLIK = $MANGLIK; }
	function getMANGLIK() { 
		if(!$this->MANGLIK)
			{
				if(!in_array("MANGLIK",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->MANGLIK; }
	function setMTONGUE($MTONGUE) { $this->MTONGUE = $MTONGUE; }
	function getMTONGUE() { 
		if(!$this->MTONGUE)
			{
				if(!in_array("MTONGUE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->MTONGUE; }
	function setMSTATUS($MSTATUS) { $this->MSTATUS = $MSTATUS; }
	function getMSTATUS() { 
		if(!$this->MSTATUS)
			{
				if(!in_array("MSTATUS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->MSTATUS; }
	function setDTOFBIRTH($DTOFBIRTH) { $this->DTOFBIRTH = $DTOFBIRTH; }
	function getDTOFBIRTH() { 
		if(!$this->DTOFBIRTH)
			{
				if(!in_array("DTOFBIRTH",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->DTOFBIRTH; }
	function setOCCUPATION($OCCUPATION) { $this->OCCUPATION = $OCCUPATION; }
	function getOCCUPATION() { 
		if(!$this->OCCUPATION)
			{
				if(!in_array("OCCUPATION",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->OCCUPATION; }
	function setCOUNTRY_RES($COUNTRY_RES) { $this->COUNTRY_RES = $COUNTRY_RES; }
	function getCOUNTRY_RES() { 
		if(!$this->COUNTRY_RES)
			{
				if(!in_array("COUNTRY_RES",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->COUNTRY_RES; }
	function setCITY_RES($CITY_RES) { $this->CITY_RES = $CITY_RES; }
	function getCITY_RES() { 
		if(!$this->CITY_RES)
			{
				if(!in_array("CITY_RES",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->CITY_RES; }
	function setHEIGHT($HEIGHT) { $this->HEIGHT = $HEIGHT; }
	function getHEIGHT() { 
		if(!$this->HEIGHT)
			{
				if(!in_array("HEIGHT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->HEIGHT; }
	function setEDU_LEVEL($EDU_LEVEL) { $this->EDU_LEVEL = $EDU_LEVEL; }
	function getEDU_LEVEL() { 
		if(!$this->EDU_LEVEL)
			{
				if(!in_array("EDU_LEVEL",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->EDU_LEVEL; }
	function setEMAIL($EMAIL) { $this->EMAIL = $EMAIL; }
	function getEMAIL() { 
		if(!$this->EMAIL)
			{
				if(!in_array("EMAIL",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->EMAIL; }
	function setIPADD($IPADD) { $this->IPADD = $IPADD; }
	function getIPADD() { 
		if(!$this->IPADD)
			{
				if(!in_array("IPADD",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->IPADD; }
	function setENTRY_DT($ENTRY_DT) { $this->ENTRY_DT = $ENTRY_DT; }
	function getENTRY_DT() { 
		if(!$this->ENTRY_DT)
			{
				if(!in_array("ENTRY_DT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->ENTRY_DT; }
        function setSERIOUSNESS_COUNT($SERIOUSNESS_COUNT) { $this->SERIOUSNESS_COUNT = $SERIOUSNESS_COUNT; }
        function getSERIOUSNESS_COUNT() {
                if(!$this->SERIOUSNESS_COUNT)
                        {
                                if(!in_array("SERIOUSNESS_COUNT",$this->fieldsArray))
                                        ProfileFieldsLogging::callFieldStack(1);
                        }
                return $this->SERIOUSNESS_COUNT; }
	function setMOD_DT($MOD_DT) { $this->MOD_DT = $MOD_DT; }
	function getMOD_DT() { 
		if(!$this->MOD_DT)
			{
				if(!in_array("MOD_DT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->MOD_DT; }
	function setRELATION($RELATION) { $this->RELATION = $RELATION; }
	function getRELATION() { 
		if(!$this->RELATION)
			{
				if(!in_array("RELATION",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->RELATION; }
	function setCOUNTRY_BIRTH($COUNTRY_BIRTH) { $this->COUNTRY_BIRTH = $COUNTRY_BIRTH; }
	function getCOUNTRY_BIRTH() { 
		if(!$this->COUNTRY_BIRTH)
			{
				if(!in_array("COUNTRY_BIRTH",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->COUNTRY_BIRTH; }
	function setSOURCE($SOURCE) { $this->SOURCE = $SOURCE; }
	function getSOURCE() { 
		if(!$this->SOURCE)
			{
				if(!in_array("SOURCE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SOURCE; }
	function setINCOMPLETE($INCOMPLETE) { $this->INCOMPLETE = $INCOMPLETE; }
	function getINCOMPLETE() { 
		if(!$this->INCOMPLETE)
			{
				if(!in_array("INCOMPLETE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->INCOMPLETE; }
	function setPROMO($PROMO) { $this->PROMO = $PROMO; }
	function getPROMO() { 
		if(!$this->PROMO)
			{
				if(!in_array("PROMO",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PROMO; }
	function setDRINK($DRINK) { $this->DRINK = $DRINK; }
	function getDRINK() { 
		if(!$this->PROFILEID)
			{
				if(!in_array("PROFILEID",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->DRINK; }
	function setSMOKE($SMOKE) { $this->SMOKE = $SMOKE; }
	function getSMOKE() { 
		if(!$this->SMOKE)
			{
				if(!in_array("SMOKE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SMOKE; }
	function setHAVECHILD($HAVECHILD) { $this->HAVECHILD = $HAVECHILD; }
	function getHAVECHILD() { 
		if(!$this->HAVECHILD)
			{
				if(!in_array("HAVECHILD",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->HAVECHILD; }
	function setRES_STATUS($RES_STATUS) { $this->RES_STATUS = $RES_STATUS; }
	function getRES_STATUS() { 
		if(!$this->RES_STATUS)
			{
				if(!in_array("RES_STATUS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->RES_STATUS; }
	function setBTYPE($BTYPE) { $this->BTYPE = $BTYPE; }
	function getBTYPE() { 
		if(!$this->BTYPE)
			{
				if(!in_array("BTYPE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->BTYPE; }
	function setCOMPLEXION($COMPLEXION) { $this->COMPLEXION = $COMPLEXION; }
	function getCOMPLEXION() { 
		if(!$this->COMPLEXION)
			{
				if(!in_array("COMPLEXION",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->COMPLEXION; }
	function setDIET($DIET) { $this->DIET = $DIET; }
	function getDIET() { 
		if(!$this->DIET)
			{
				if(!in_array("DIET",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->DIET; }
	function setHEARD($HEARD) { $this->HEARD = $HEARD; }
	function getHEARD() { 
		if(!$this->HEARD)
			{
				if(!in_array("HEARD",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->HEARD; }
	function setINCOME($INCOME) { $this->INCOME = $INCOME; }
	function getINCOME() { 
		if(!$this->INCOME)
			{
				if(!in_array("INCOME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->INCOME; }
	function setCITY_BIRTH($CITY_BIRTH) { $this->CITY_BIRTH = $CITY_BIRTH; }
	function getCITY_BIRTH() { 
		if(!$this->CITY_BIRTH)
			{
				if(!in_array("CITY_BIRTH",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->CITY_BIRTH; }
	function setBTIME($BTIME) { $this->BTIME = $BTIME; }
	function getBTIME() { 
		if(!$this->BTIME)
			{
				if(!in_array("BTIME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->BTIME; }
	function setHANDICAPPED($HANDICAPPED) { $this->HANDICAPPED = $HANDICAPPED; }
	function getHANDICAPPED() { 
		if(!$this->HANDICAPPED)
			{
				if(!in_array("HANDICAPPED",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->HANDICAPPED; }
	function setNTIMES($NTIMES) { $this->NTIMES = $NTIMES; }
	function getNTIMES() { 
		if(!$this->NTIMES)
			{
				if(!in_array("NTIMES",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->NTIMES; }
	function setSUBSCRIPTION($SUBSCRIPTION) { $this->SUBSCRIPTION = $SUBSCRIPTION; }
	function getSUBSCRIPTION() { 
		if(!$this->SUBSCRIPTION)
			{
				if(!in_array("SUBSCRIPTION",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SUBSCRIPTION; }
	function setSUBSCRIPTION_EXPIRY_DT($SUBSCRIPTION_EXPIRY_DT) { $this->SUBSCRIPTION_EXPIRY_DT = $SUBSCRIPTION_EXPIRY_DT; }
	function getSUBSCRIPTION_EXPIRY_DT() { 
		if(!$this->SUBSCRIPTION_EXPIRY_DT)
			{
				if(!in_array("SUBSCRIPTION_EXPIRY_DT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SUBSCRIPTION_EXPIRY_DT; }
	function setACTIVATED($ACTIVATED) { $this->ACTIVATED = $ACTIVATED; }
	function getACTIVATED() { 
		if(!$this->ACTIVATED)
			{
				if(!in_array("ACTIVATED",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->ACTIVATED; }
	function setACTIVATE_ON($ACTIVATE_ON) { $this->ACTIVATE_ON = $ACTIVATE_ON; }
	function getACTIVATE_ON() { 
		if(!$this->ACTIVATE_ON)
			{
				if(!in_array("ACTIVATE_ON",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->ACTIVATE_ON; }
	function setAGE($AGE) { $this->AGE = $AGE; }
	function getAGE() { 
		if(!$this->AGE)
			{
				if(!in_array("AGE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->AGE; }
	function setGOTHRA($GOTHRA) { $this->GOTHRA = $GOTHRA; }
	function getGOTHRA() { 
		if(!$this->GOTHRA)
			{
				if(!in_array("GOTHRA",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->GOTHRA; }
	function setNAKSHATRA($NAKSHATRA) { $this->NAKSHATRA = $NAKSHATRA; }
	function getNAKSHATRA() { 
		if(!$this->NAKSHATRA)
			{
				if(!in_array("NAKSHATRA",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->NAKSHATRA; }
	function setMESSENGER_ID($MESSENGER_ID) { $this->MESSENGER_ID = $MESSENGER_ID; }
	function getMESSENGER_ID() { 
		if(!$this->MESSENGER_ID)
			{
				if(!in_array("MESSENGER_ID",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->MESSENGER_ID; }
	function setMESSENGER_CHANNEL($MESSENGER_CHANNEL) { $this->MESSENGER_CHANNEL = $MESSENGER_CHANNEL; }
	function getMESSENGER_CHANNEL() { 
		if(!$this->MESSENGER_CHANNEL)
			{
				if(!in_array("MESSENGER_CHANNEL",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->MESSENGER_CHANNEL; }
	function setPHONE_RES($PHONE_RES) { $this->PHONE_RES = $PHONE_RES; }
	function getPHONE_RES() { 
		if(!$this->PHONE_RES)
			{
				if(!in_array("PHONE_RES",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PHONE_RES; }
	function setPHONE_MOB($PHONE_MOB) { $this->PHONE_MOB = $PHONE_MOB; }
	function getPHONE_MOB() { 
		if(!$this->PHONE_MOB)
			{
				if(!in_array("PHONE_MOB",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PHONE_MOB; }
	function setFAMILY_BACK($FAMILY_BACK) { $this->FAMILY_BACK = $FAMILY_BACK; }
	function getFAMILY_BACK() { 
		if(!$this->FAMILY_BACK)
			{
				if(!in_array("FAMILY_BACK",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->FAMILY_BACK; }
	function setSCREENING($SCREENING) { $this->SCREENING = $SCREENING; }
	function getSCREENING() { 
		if(!$this->SCREENING)
			{
				if(!in_array("SCREENING",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SCREENING; }
	function setCONTACT($CONTACT) { $this->CONTACT = $CONTACT; }
	function getCONTACT() { 
		if(!$this->CONTACT)
			{
				if(!in_array("CONTACT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->CONTACT; }
	function setSUBCASTE($SUBCASTE) { $this->SUBCASTE = $SUBCASTE; }
	function getSUBCASTE() { 
		if(!$this->SUBCASTE)
			{
				if(!in_array("SUBCASTE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SUBCASTE; }
	function setYOURINFO($YOURINFO) { $this->YOURINFO = $YOURINFO; }
	function getYOURINFO() { 
		if(!$this->YOURINFO)
			{
				if(!in_array("YOURINFO",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->YOURINFO; }
	function setFAMILYINFO($FAMILYINFO) { $this->FAMILYINFO = $FAMILYINFO; }
	function getFAMILYINFO() { 
		if(!$this->FAMILYINFO)
			{
				if(!in_array("FAMILYINFO",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->FAMILYINFO; }
	function setSPOUSE($SPOUSE) { $this->SPOUSE = $SPOUSE; }
	function getSPOUSE() { 
		if(!$this->SPOUSE)
			{
				if(!in_array("SPOUSE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SPOUSE; }
	function setEDUCATION($EDUCATION) { $this->EDUCATION = $EDUCATION; }
	function getEDUCATION() { 
		if(!$this->EDUCATION)
			{
				if(!in_array("EDUCATION",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->EDUCATION; }
	function setLAST_LOGIN_DT($LAST_LOGIN_DT) { $this->LAST_LOGIN_DT = $LAST_LOGIN_DT; }
	function getLAST_LOGIN_DT() { 
		if(!$this->LAST_LOGIN_DT)
			{
				if(!in_array("LAST_LOGIN_DT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->LAST_LOGIN_DT; }
	function setSHOWPHONE_RES($SHOWPHONE_RES) { $this->SHOWPHONE_RES = $SHOWPHONE_RES; }
	function getSHOWPHONE_RES() { 
		if(!$this->SHOWPHONE_RES)
			{
				if(!in_array("SHOWPHONE_RES",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SHOWPHONE_RES; }
	function setSHOWPHONE_MOB($SHOWPHONE_MOB) { $this->SHOWPHONE_MOB = $SHOWPHONE_MOB; }
	function getSHOWPHONE_MOB() { 
		if(!$this->SHOWPHONE_MOB)
			{
				if(!in_array("SHOWPHONE_MOB",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SHOWPHONE_MOB; }
	function setHAVEPHOTO($HAVEPHOTO) { $this->HAVEPHOTO = $HAVEPHOTO; }
	function getHAVEPHOTO() { 
		if(!$this->HAVEPHOTO)
			{
				if(!in_array("HAVEPHOTO",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->HAVEPHOTO; }
	function setPHOTO_DISPLAY($PHOTO_DISPLAY) { $this->PHOTO_DISPLAY = $PHOTO_DISPLAY; }
	function getPHOTO_DISPLAY() { 
		if(!$this->PHOTO_DISPLAY)
			{
				if(!in_array("PHOTO_DISPLAY",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PHOTO_DISPLAY; }
	function setPHOTOSCREEN($PHOTOSCREEN) { $this->PHOTOSCREEN = $PHOTOSCREEN; }
	function getPHOTOSCREEN() { 
		if(!$this->PHOTOSCREEN)
			{
				if(!in_array("PHOTOSCREEN",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PHOTOSCREEN; }
	function setPREACTIVATED($PREACTIVATED) { $this->PREACTIVATED = $PREACTIVATED; }
	function getPREACTIVATED() { 
		if(!$this->PREACTIVATED)
			{
				if(!in_array("PREACTIVATED",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PREACTIVATED; }
	function setKEYWORDS($KEYWORDS) { $this->KEYWORDS = $KEYWORDS; }
	function getKEYWORDS() { 
		if(!$this->KEYWORDS)
			{
				if(!in_array("KEYWORDS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->KEYWORDS; }
	function setPHOTODATE($PHOTODATE) { $this->PHOTODATE = $PHOTODATE; }
	function getPHOTODATE() { 
		if(!$this->PHOTODATE)
			{
				if(!in_array("PHOTODATE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PHOTODATE; }
	function setPHOTOGRADE($PHOTOGRADE) { $this->PHOTOGRADE = $PHOTOGRADE; }
	function getPHOTOGRADE() { 
		if(!$this->PHOTOGRADE)
			{
				if(!in_array("PHOTOGRADE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PHOTOGRADE; }
	function setTIMESTAMP($TIMESTAMP) { $this->TIMESTAMP = $TIMESTAMP; }
	function getTIMESTAMP() { 
		if(!$this->TIMESTAMP)
			{
				if(!in_array("TIMESTAMP",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->TIMESTAMP; }
	function setPROMO_MAILS($PROMO_MAILS) { $this->PROMO_MAILS = $PROMO_MAILS; }
	function getPROMO_MAILS() { 
		if(!$this->PROMO_MAILS)
			{
				if(!in_array("PROMO_MAILS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PROMO_MAILS; }
	function setSERVICE_MESSAGES($SERVICE_MESSAGES) { $this->SERVICE_MESSAGES = $SERVICE_MESSAGES; }
	function getSERVICE_MESSAGES() { 
		if(!$this->SERVICE_MESSAGES)
			{
				if(!in_array("SERVICE_MESSAGES",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SERVICE_MESSAGES; }
	function setPERSONAL_MATCHES($PERSONAL_MATCHES) { $this->PERSONAL_MATCHES = $PERSONAL_MATCHES; }
	function getPERSONAL_MATCHES() { 
		if(!$this->PERSONAL_MATCHES)
			{
				if(!in_array("PERSONAL_MATCHES",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PERSONAL_MATCHES; }
	function setSHOWADDRESS($SHOWADDRESS) { $this->SHOWADDRESS = $SHOWADDRESS; }
	function getSHOWADDRESS() { 
		if(!$this->SHOWADDRESS)
			{
				if(!in_array("SHOWADDRESS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SHOWADDRESS; }
	function setUDATE($UDATE) { $this->UDATE = $UDATE; }
	function getUDATE() { 
		if(!$this->UDATE)
			{
				if(!in_array("UDATE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->UDATE; }
	function setSHOWMESSENGER($SHOWMESSENGER) { $this->SHOWMESSENGER = $SHOWMESSENGER; }
	function getSHOWMESSENGER() { 
		if(!$this->SHOWMESSENGER)
			{
				if(!in_array("SHOWMESSENGER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SHOWMESSENGER; }
	function setPINCODE($PINCODE) { $this->PINCODE = $PINCODE; }
	function getPINCODE() { 
		if(!$this->PINCODE)
			{
				if(!in_array("PINCODE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PINCODE; }
	function setPRIVACY($PRIVACY) { $this->PRIVACY = $PRIVACY; }
	function getPRIVACY() { 
		if(!$this->PRIVACY)
			{
				if(!in_array("PRIVACY",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PRIVACY; }
	function setEDU_LEVEL_NEW($EDU_LEVEL_NEW) { $this->EDU_LEVEL_NEW = $EDU_LEVEL_NEW; }
	function getEDU_LEVEL_NEW() { 
		if(!$this->EDU_LEVEL_NEW)
			{
				if(!in_array("EDU_LEVEL_NEW",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->EDU_LEVEL_NEW; }
	function setFATHER_INFO($FATHER_INFO) { $this->FATHER_INFO = $FATHER_INFO; }
	function getFATHER_INFO() { 
		if(!$this->FATHER_INFO)
			{
				if(!in_array("FATHER_INFO",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->FATHER_INFO; }
	function setSIBLING_INFO($SIBLING_INFO) { $this->SIBLING_INFO = $SIBLING_INFO; }
	function getSIBLING_INFO() { 
		if(!$this->SIBLING_INFO)
			{
				if(!in_array("SIBLING_INFO",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SIBLING_INFO; }
	function setWIFE_WORKING($WIFE_WORKING) { $this->WIFE_WORKING = $WIFE_WORKING; }
	function getWIFE_WORKING() { 
		if(!$this->WIFE_WORKING)
			{
				if(!in_array("WIFE_WORKING",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->WIFE_WORKING; }
	function setJOB_INFO($JOB_INFO) { $this->JOB_INFO = $JOB_INFO; }
	function getJOB_INFO() { 
		if(!$this->JOB_INFO)
			{
				if(!in_array("JOB_INFO",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->JOB_INFO; }
	function setMARRIED_WORKING($MARRIED_WORKING) { $this->MARRIED_WORKING = $MARRIED_WORKING; }
	function getMARRIED_WORKING() { 
		if(!$this->MARRIED_WORKING)
			{
				if(!in_array("MARRIED_WORKING",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->MARRIED_WORKING; }
	function setPARENT_CITY_SAME($PARENT_CITY_SAME) { $this->PARENT_CITY_SAME = $PARENT_CITY_SAME; }
	function getPARENT_CITY_SAME() { 
		if(!$this->PARENT_CITY_SAME)
			{
				if(!in_array("PARENT_CITY_SAME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PARENT_CITY_SAME; }
	function setPARENTS_CONTACT($PARENTS_CONTACT) { $this->PARENTS_CONTACT = $PARENTS_CONTACT; }
	function getPARENTS_CONTACT() { 
		if(!$this->PARENTS_CONTACT)
			{
				if(!in_array("PARENTS_CONTACT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PARENTS_CONTACT; }
	function setSHOW_PARENTS_CONTACT($SHOW_PARENTS_CONTACT) { $this->SHOW_PARENTS_CONTACT = $SHOW_PARENTS_CONTACT; }
	function getSHOW_PARENTS_CONTACT() { 
		if(!$this->SHOW_PARENTS_CONTACT)
			{
				if(!in_array("SHOW_PARENTS_CONTACT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SHOW_PARENTS_CONTACT; }
	function setFAMILY_VALUES($FAMILY_VALUES) { $this->FAMILY_VALUES = $FAMILY_VALUES; }
	function getFAMILY_VALUES() { 
		if(!$this->FAMILY_VALUES)
			{
				if(!in_array("FAMILY_VALUES",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->FAMILY_VALUES; }
	function setSORT_DT($SORT_DT) { $this->SORT_DT = $SORT_DT; }
	function getSORT_DT() { 
		if(!$this->SORT_DT)
			{
				if(!in_array("SORT_DT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SORT_DT; }
	function setVERIFY_EMAIL($VERIFY_EMAIL) { $this->VERIFY_EMAIL = $VERIFY_EMAIL; }
	function getVERIFY_EMAIL() { 
		if(!$this->PROFILEID)
			{
				if(!in_array("PROFILEID",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->VERIFY_EMAIL; }
	function setSHOW_HOROSCOPE($SHOW_HOROSCOPE) { $this->SHOW_HOROSCOPE = $SHOW_HOROSCOPE; }
	function getSHOW_HOROSCOPE() { 
		if(!$this->SHOW_HOROSCOPE)
			{
				if(!in_array("SHOW_HOROSCOPE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SHOW_HOROSCOPE; }
	function setGET_SMS($GET_SMS) { $this->GET_SMS = $GET_SMS; }
	function getGET_SMS() { 
		if(!$this->GET_SMS)
			{
				if(!in_array("GET_SMS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->GET_SMS; }
	function setSTD($STD) { $this->STD = $STD; }
	function getSTD() { 
		if(!$this->STD)
			{
				if(!in_array("STD",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->STD; }
	function setISD($ISD) { $this->ISD = $ISD; }
	function getISD() { 
		if(!$this->ISD)
			{
				if(!in_array("ISD",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->ISD; }
	function setMOTHER_OCC($MOTHER_OCC) { $this->MOTHER_OCC = $MOTHER_OCC; }
	function getMOTHER_OCC() { 
		if(!$this->MOTHER_OCC)
			{
				if(!in_array("MOTHER_OCC",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->MOTHER_OCC; }
	function setT_BROTHER($T_BROTHER) { $this->T_BROTHER = $T_BROTHER; }
	function getT_BROTHER() { 
		if(!$this->PROFILEID)
			{
				if(!in_array("T_BROTHER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->T_BROTHER; }
	function setT_SISTER($T_SISTER) { $this->T_SISTER = $T_SISTER; }
	function getT_SISTER() { 
		if(!$this->T_SISTER)
			{
				if(!in_array("T_SISTER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->T_SISTER; }
	function setM_BROTHER($M_BROTHER) { $this->M_BROTHER = $M_BROTHER; }
	function getM_BROTHER() { 
		if(!$this->M_BROTHER)
			{
				if(!in_array("M_BROTHER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->M_BROTHER; }
	function setM_SISTER($M_SISTER) { $this->M_SISTER = $M_SISTER; }
	function getM_SISTER() { 
		if(!$this->M_SISTER)
			{
				if(!in_array("M_SISTER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->M_SISTER; }
	function setFAMILY_TYPE($FAMILY_TYPE) { $this->FAMILY_TYPE = $FAMILY_TYPE; }
	function getFAMILY_TYPE() { 
		if(!$this->FAMILY_TYPE)
			{
				if(!in_array("FAMILY_TYPE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->FAMILY_TYPE; }
	function setFAMILY_STATUS($FAMILY_STATUS) { $this->FAMILY_STATUS = $FAMILY_STATUS; }
	function getFAMILY_STATUS() { 
		if(!$this->FAMILY_STATUS)
			{
				if(!in_array("FAMILY_STATUS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->FAMILY_STATUS; }
	function setCITIZENSHIP($CITIZENSHIP) { $this->CITIZENSHIP = $CITIZENSHIP; }
	function getCITIZENSHIP() { 
		if(!$this->CITIZENSHIP)
			{
				if(!in_array("CITIZENSHIP",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->CITIZENSHIP; }
	function setBLOOD_GROUP($BLOOD_GROUP) { $this->BLOOD_GROUP = $BLOOD_GROUP; }
	function getBLOOD_GROUP() { 
		if(!$this->BLOOD_GROUP)
			{
				if(!in_array("BLOOD_GROUP",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->BLOOD_GROUP; }
	function setHIV($HIV) { $this->HIV = $HIV; }
	function getHIV() { 
		if(!$this->HIV)
			{
				if(!in_array("HIV",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->HIV; }
	function setWEIGHT($WEIGHT) { $this->WEIGHT = $WEIGHT; }
	function getWEIGHT() { 
		if(!$this->WEIGHT)
			{
				if(!in_array("WEIGHT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->WEIGHT; }
	function setNATURE_HANDICAP($NATURE_HANDICAP) { $this->NATURE_HANDICAP = $NATURE_HANDICAP; }
	function getNATURE_HANDICAP() { 
		if(!$this->NATURE_HANDICAP)
			{
				if(!in_array("NATURE_HANDICAP",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->NATURE_HANDICAP; }
	function setORKUT_USERNAME($ORKUT_USERNAME) { $this->ORKUT_USERNAME = $ORKUT_USERNAME; }
	function getORKUT_USERNAME() { 
		if(!$this->ORKUT_USERNAME)
			{
				if(!in_array("ORKUT_USERNAME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->ORKUT_USERNAME; }
	function setWORK_STATUS($WORK_STATUS) { $this->WORK_STATUS = $WORK_STATUS; }
	function getWORK_STATUS() { 
		if(!$this->WORK_STATUS)
			{
				if(!in_array("WORK_STATUS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->WORK_STATUS; }
	function setANCESTRAL_ORIGIN($ANCESTRAL_ORIGIN) { $this->ANCESTRAL_ORIGIN = $ANCESTRAL_ORIGIN; }
	function getANCESTRAL_ORIGIN() { 
		if(!$this->ANCESTRAL_ORIGIN)
			{
				if(!in_array("ANCESTRAL_ORIGIN",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->ANCESTRAL_ORIGIN; }
	function setHOROSCOPE_MATCH($HOROSCOPE_MATCH) { $this->HOROSCOPE_MATCH = $HOROSCOPE_MATCH; }
	function getHOROSCOPE_MATCH() { 
		if(!$this->HOROSCOPE_MATCH)
			{
				if(!in_array("HOROSCOPE_MATCH",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->HOROSCOPE_MATCH; }
	function setSPEAK_URDU($SPEAK_URDU) { $this->SPEAK_URDU = $SPEAK_URDU; }
	function getSPEAK_URDU() { 
		if(!$this->SPEAK_URDU)
			{
				if(!in_array("SPEAK_URDU",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SPEAK_URDU; }
	function setPHONE_NUMBER_OWNER($PHONE_NUMBER_OWNER) { $this->PHONE_NUMBER_OWNER = $PHONE_NUMBER_OWNER; }
	function getPHONE_NUMBER_OWNER() { 
		if(!$this->PHONE_NUMBER_OWNER)
			{
				if(!in_array("PHONE_NUMBER_OWNER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PHONE_NUMBER_OWNER; }
	function setPHONE_OWNER_NAME($PHONE_OWNER_NAME) { $this->PHONE_OWNER_NAME = $PHONE_OWNER_NAME; }
	function getPHONE_OWNER_NAME() { 
		if(!$this->PHONE_OWNER_NAME)
			{
				if(!in_array("PHONE_OWNER_NAME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PHONE_OWNER_NAME; }
	function setMOBILE_NUMBER_OWNER($MOBILE_NUMBER_OWNER) { $this->MOBILE_NUMBER_OWNER = $MOBILE_NUMBER_OWNER; }
	function getMOBILE_NUMBER_OWNER() { 
		if(!$this->MOBILE_NUMBER_OWNER)
			{
				if(!in_array("MOBILE_NUMBER_OWNER",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->MOBILE_NUMBER_OWNER; }
	function setMOBILE_OWNER_NAME($MOBILE_OWNER_NAME) { $this->MOBILE_OWNER_NAME = $MOBILE_OWNER_NAME; }
	function getMOBILE_OWNER_NAME() { 
		if(!$this->MOBILE_OWNER_NAME)
			{
				if(!in_array("MOBILE_OWNER_NAME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->MOBILE_OWNER_NAME; }
	function setRASHI($RASHI) { $this->RASHI = $RASHI; }
	function getRASHI() { 
		if(!$this->RASHI)
			{
				if(!in_array("RASHI",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->RASHI; }
	function setTIME_TO_CALL_START($TIME_TO_CALL_START) { $this->TIME_TO_CALL_START = $TIME_TO_CALL_START; }
	function getTIME_TO_CALL_START() { 
		if(!$this->TIME_TO_CALL_START)
			{
				if(!in_array("TIME_TO_CALL_START",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->TIME_TO_CALL_START; }
	function setTIME_TO_CALL_END($TIME_TO_CALL_END) { $this->TIME_TO_CALL_END = $TIME_TO_CALL_END; }
	function getTIME_TO_CALL_END() { 
		if(!$this->TIME_TO_CALL_END)
			{
				if(!in_array("TIME_TO_CALL_END",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->TIME_TO_CALL_END; }
	function setPHONE_WITH_STD($PHONE_WITH_STD) { $this->PHONE_WITH_STD = $PHONE_WITH_STD; }
	function getPHONE_WITH_STD() { 
		if(!$this->PHONE_WITH_STD)
			{
				if(!in_array("PHONE_WITH_STD",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PHONE_WITH_STD; }
	function setMOB_STATUS($MOB_STATUS) { $this->MOB_STATUS = $MOB_STATUS; }
	function getMOB_STATUS() { 
		if(!$this->MOB_STATUS)
			{
				if(!in_array("MOB_STATUS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->MOB_STATUS; }
	function setLANDL_STATUS($LANDL_STATUS) { $this->LANDL_STATUS = $LANDL_STATUS; }
	function getLANDL_STATUS() { 
		if(!$this->LANDL_STATUS)
			{
				if(!in_array("LANDL_STATUS",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->LANDL_STATUS; }
	function setPHONE_FLAG($PHONE_FLAG) { $this->PHONE_FLAG = $PHONE_FLAG; }
	function getPHONE_FLAG() { 
		if(!$this->PHONE_FLAG)
			{
				if(!in_array("PHONE_FLAG",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PHONE_FLAG; }
	function setCRM_TEAM($CRM_TEAM) { $this->CRM_TEAM = $CRM_TEAM; }
	function getCRM_TEAM() { 
		if(!$this->CRM_TEAM)
			{
				if(!in_array("CRM_TEAM",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->CRM_TEAM; }
	function setPROFILE_HANDLER_NAME($PROFILE_HANDLER_NAME) { $this->PROFILE_HANDLER_NAME = $PROFILE_HANDLER_NAME; }
	function getPROFILE_HANDLER_NAME() { 
		if(!$this->PROFILE_HANDLER_NAME)
			{
				if(!in_array("PROFILE_HANDLER_NAME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PROFILE_HANDLER_NAME; }
	function setPARENT_PINCODE($PARENT_PINCODE) { $this->PARENT_PINCODE = $PARENT_PINCODE; }
	function getPARENT_PINCODE() { 
		if(!$this->PARENT_PINCODE)
			{
				if(!in_array("PARENT_PINCODE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PARENT_PINCODE; }
	function setFAMILY_INCOME($FAMILY_INCOME) { $this->FAMILY_INCOME = $FAMILY_INCOME; }
	function getFAMILY_INCOME() { 
		if(!$this->FAMILY_INCOME)
			{
				if(!in_array("FAMILY_INCOME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->FAMILY_INCOME; }
	function setTHALASSEMIA($THALASSEMIA) { $this->THALASSEMIA = $THALASSEMIA; }
	function getTHALASSEMIA() { 
		if(!$this->THALASSEMIA)
			{
				if(!in_array("THALASSEMIA",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->THALASSEMIA; }
	function setGOTHRA_MATERNAL($GOTHRA_MATERNAL) { $this->GOTHRA_MATERNAL = $GOTHRA_MATERNAL; }
	function getGOTHRA_MATERNAL() { 
		if(!$this->GOTHRA_MATERNAL)
			{
				if(!in_array("GOTHRA_MATERNAL",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->GOTHRA_MATERNAL; }
	function setGOING_ABROAD($GOING_ABROAD) { $this->GOING_ABROAD = $GOING_ABROAD; }
	function getGOING_ABROAD() { 
		if(!$this->GOING_ABROAD)
			{
				if(!in_array("GOING_ABROAD",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->GOING_ABROAD; }
	function setOPEN_TO_PET($OPEN_TO_PET) { $this->OPEN_TO_PET = $OPEN_TO_PET; }
	function getOPEN_TO_PET() { 
		if(!$this->OPEN_TO_PET)
			{
				if(!in_array("OPEN_TO_PET",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->OPEN_TO_PET; }
	function setHAVE_CAR($HAVE_CAR) { $this->HAVE_CAR = $HAVE_CAR; }
	function getHAVE_CAR() { 
		if(!$this->HAVE_CAR)
			{
				if(!in_array("HAVE_CAR",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->HAVE_CAR; }
	function setOWN_HOUSE($OWN_HOUSE) { $this->OWN_HOUSE = $OWN_HOUSE; }
	function getOWN_HOUSE() { 
		if(!$this->OWN_HOUSE)
			{
				if(!in_array("OWN_HOUSE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->OWN_HOUSE; }
	function setSECT($SECT) { $this->SECT = $SECT; }
	function getSECT() { 
		if(!$this->SECT)
			{
				if(!in_array("SECT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SECT; }
	function setCOMPANY_NAME($COMPANY_NAME) { $this->COMPANY_NAME = $COMPANY_NAME; }
	function getCOMPANY_NAME() { 
		if(!$this->COMPANY_NAME)
			{
				if(!in_array("COMPANY_NAME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->COMPANY_NAME; }
	function setHAVE_JCONTACT($HAVE_JCONTACT) { $this->HAVE_JCONTACT = $HAVE_JCONTACT; }
	function getHAVE_JCONTACT() { 
		if(!$this->HAVE_JCONTACT)
			{
				if(!in_array("HAVE_JCONTACT",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->HAVE_JCONTACT; }
	function setHAVE_JEDUCATION($HAVE_JEDUCATION) { $this->HAVE_JEDUCATION = $HAVE_JEDUCATION; }
	function getHAVE_JEDUCATION() { 
		if(!$this->HAVE_JEDUCATION)
			{
				if(!in_array("HAVE_JEDUCATION",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->HAVE_JEDUCATION; }
	function setSUNSIGN($SUNSIGN) { $this->SUNSIGN = $SUNSIGN; }
	function getSUNSIGN() { 
		if(!$this->SUNSIGN)
			{
				if(!in_array("SUNSIGN",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SUNSIGN; }
	function setSEC_SOURCE($SEC_SOURCE) { $this->SEC_SOURCE = $SEC_SOURCE; }
	function getSEC_SOURCE() { 
		if(!$this->SEC_SOURCE)
			{
				if(!in_array("SEC_SOURCE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SEC_SOURCE; }
	function setID_PROOF_NO($ID_PROOF_NO) { $this->ID_PROOF_NO = $ID_PROOF_NO; }
	function getID_PROOF_NO() { 
		if(!$this->ID_PROOF_NO)
			{
				if(!in_array("ID_PROOF_NO",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->ID_PROOF_NO; }
	function setID_PROOF_TYP($ID_PROOF_TYP) { $this->ID_PROOF_TYP = $ID_PROOF_TYP; }
	function getID_PROOF_TYP() { 
		if(!$this->PROFILEID)
			{
				if(!in_array("PROFILEID",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->ID_PROOF_TYP; }

	function setSCHOOL($SCHOOL) { $this->SCHOOL = $SCHOOL; }
	function getSCHOOL() { 
		if(!$this->SCHOOL)
			{
				if(!in_array("SCHOOL",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->SCHOOL; }
	function setCOLLEGE($COLLEGE) { $this->COLLEGE = $COLLEGE; }
	function getCOLLEGE() { 
		if(!$this->COLLEGE)
			{
				if(!in_array("COLLEGE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->COLLEGE; }
	function setPG_COLLEGE($PG_COLLEGE) { $this->PG_COLLEGE = $PG_COLLEGE; }
	function getPG_COLLEGE() { 
		if(!$this->PG_COLLEGE)
			{
				if(!in_array("PG_COLLEGE",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->PG_COLLEGE; }
	function setNAME($NAME) { $this->NAME = $NAME; }
	function getNAME() { 
		if(!$this->NAME)
			{
				if(!in_array("NAME",$this->fieldsArray))
					ProfileFieldsLogging::callFieldStack(1);
			}
		return $this->NAME; }

/****************************************
 function getPROFILE_STATE()
Description: returns already set profileState object if exists other wise create the object and returns it on demand
Added by Esha
****************************************/
	public function getPROFILE_STATE($compute='true') 
	{
		if(!($this->PROFILE_STATE instanceof ProfileState))
			$this->PROFILE_STATE = new ProfileState($this);
		if($compute)
		{
                        $this->PROFILE_STATE->getPaymentStates()->updatePaymentState($this,$this->PROFILE_STATE->getFTOStates());
			$this->PROFILE_STATE->getActivationState()->updateActivationState($this);
		}
		return $this->PROFILE_STATE; 
	}
/****************************************
 function setPROFILE_STATE
Input: takes object of profile state
Description: setter function for PROFILE_STATE
Added by Esha
****************************************/
	public function setPROFILE_STATE($PROFILE_STATE)
	{
		if($PROFILE_STATE instanceof ProfileState)
			$this->PROFILE_STATE	=	$PROFILE_STATE;
		else
			throw new jsException("","object is not of class PROFILE_STATE");
	}

	function getFilterParameters()
	{
		return array("AGE"=>$this->AGE,
					"RELIGION"=>$this->RELIGION,
					"CASTE"=>$this->CASTE,
					"COUNTRY_RES"=>$this->COUNTRY_RES,
					"CITY_RES"=>$this->CITY_RES,
					"MSTATUS"=>$this->MSTATUS,
					"INCOME"=>$this->INCOME,
					"MTONGUE"=>$this->MTONGUE);
                                
	}
	/**
	 * @fn setJpartner
	 * @brief set jpartner object.
	 * @param $jpartnerObj partner obj
	 * @return none;
	*/
	public function setJpartner($jpartnerObj)
	{
			$this->jpartnerObj=$jpartnerObj;
	}
	/**
	 * @fn getJpartner
	 * @brief return jpartner object.
	 * @return jpartner object;
	*/
	public function getJpartner()
	{
			return $this->jpartnerObj;
	}
	
	public function setNullValueMarker($val)
	{
		$this->nullValueMarker=$val;
	}
	
  public function getNullValueMarker()
	{
		return $this->nullValueMarker;
	}
/**************************************Ends here********************************************************/
}
?>
