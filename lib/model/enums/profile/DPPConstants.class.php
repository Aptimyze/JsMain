<?php
/**
 * UpdateDPP
 * Date : 23rd Jan 2014 
 */
 
 abstract class DPPConstants
 {
	 private static $arrCOLUMN_NAME = array(
										'P_LAGE'=>'LAGE',
										'P_HAGE'=>'HAGE',
										'P_LHEIGHT'=>'LHEIGHT',
										'P_HHEIGHT'=>'HHEIGHT',
										'P_LRS'=>'LINCOME',
										'P_HRS'=>'HINCOME',
										'P_LDS'=>'LINCOME_DOL',
										'P_HDS'=>'HINCOME_DOL',
										'P_MSTATUS'=>'PARTNER_MSTATUS',
										'P_COUNTRY'=>'PARTNER_COUNTRYRES',
										'P_CITY'=>'PARTNER_CITYRES',
										'P_CASTE'=>'PARTNER_CASTE',
										'P_RELIGION'=>'PARTNER_RELIGION',
										'P_MTONGUE'=>'PARTNER_MTONGUE',
										'P_MANGLIK'=>'PARTNER_MANGLIK',
										'P_DIET'=>'PARTNER_DIET',
										'P_SMOKE'=>'PARTNER_SMOKE',
										'P_DRINK'=>'PARTNER_DRINK',
										'P_COMPLEXION'=>'PARTNER_COMP',
										'P_BTYPE'=>'PARTNER_BTYPE',
										'P_CHALLENGED'=>'HANDICAPPED',//TODO Nature of Handicapped 
										'P_NCHALLENGED'=>'NHANDICAPPED',//TODO Nature of Handicapped 
										'P_EDUCATION'=>'PARTNER_ELEVEL_NEW',
										'P_OCCUPATION'=>'PARTNER_OCC',
										'P_INCOME'=>'PARTNER_INCOME',
										'P_GENDER'=>'GENDER',
                                        'P_HAVECHILD'=>'CHILDREN',
                                        'P_STATE'=>'STATE',
										'P_OCCUPATION_GROUPING'=>'OCCUPATION_GROUPING', //added this. Check if key or value needs to be changed
 										);
	public static $FormatColums = array(
									'P_RELIGION',
									'P_CASTE',
									'P_MSTATUS',
									'P_MTONGUE',
									'P_MANGLIK',
									'P_DIET',
									'P_SMOKE',
									'P_DRINK',
									'P_BTYPE',
									'P_CHALLENGED',
									'P_NCHALLENGED',
									'P_EDUCATION',
									'P_OCCUPATION',
									'P_COMPLEXION',
									'P_COUNTRY',
									'P_CITY',
                                    'P_HAVECHILD',
                                    'P_STATE',
                                    'P_OCCUPATION_GROUPING',
									);									
	public static $arrAP_DPP_TEMP_FIELDS = array(
										'GENDER',
										'CHILDREN',
										'LAGE',
										'HAGE',
										'LHEIGHT',
										'HHEIGHT',
										'HANDICAPPED',
										'CASTE_MTONGUE',
										'PARTNER_BTYPE',
										'PARTNER_CASTE',
										'PARTNER_CITYRES',
										'PARTNER_COUNTRYRES',
										'PARTNER_DIET',
										'PARTNER_DRINK',
										'PARTNER_ELEVEL_NEW',
										'PARTNER_INCOME',
										'PARTNER_MANGLIK',
										'PARTNER_MSTATUS',
										'PARTNER_MTONGUE',
										'PARTNER_NRI_COSMO',
										'PARTNER_OCC',
										'PARTNER_RELATION',
										'PARTNER_RES_STATUS',
										'PARTNER_SMOKE',
										'PARTNER_COMP',
										'PARTNER_RELIGION',
										'PARTNER_NAKSHATRA',
										'NHANDICAPPED',
										'AGE_FILTER',
										'MSTATUS_FILTER',
										'RELIGION_FILTER',
										'CASTE_FILTER',
										'COUNTRY_RES_FILTER',
										'CITY_RES_FILTER',
										'MTONGUE_FILTER',
										'INCOME_FILTER',
										'DATE',
										'CREATED_BY',
										'PROFILEID',
										'ACTED_ON_ID',
										'LINCOME',
										'HINCOME',
										'LINCOME_DOL',
										'HINCOME_DOL',
										);
	

	 public static function BakeQuery($szKey,$InputVal)
	 {
		 $szInput = $InputVal;
		 if($szkey == 'P_CITY' || $szkey =='P_MTONGUE' || $szkey=='P_STATE')
		 {
			 if(is_array($InputVal))
					$InputVal = array_unique($InputVal);
			 else
				$InputVal = array_unique(explode(",",$InputVal));
			 
		 }
		 if(is_array($InputVal))
		 {
			$sztemp = implode("','",$InputVal);
			$szInput = "'" . $sztemp . "'";
		 }
		 $szOut = self::$arrCOLUMN_NAME[$szKey] . "=\"$szInput\"";
		 return $szOut;
	 }
	 
	 public static function getDppFieldMapping($key='',$all='')
	 {
		 if($all =='')
			$szOut = self::$arrCOLUMN_NAME[$key];
		 else
			$szOut = self::$arrCOLUMN_NAME;
		 return $szOut;
	 }
	 
	 public static function getEditValues($arrInput)
	 {
		if(!is_array($arrInput))
			return null;
		
		$arrOut = array();	
		foreach($arrInput as $key=>$val)
		{
			$szValue = $val;
			if(is_array($val))
			{
				$sztemp = implode("','", array_unique($val));
				$szValue = "'" . $sztemp . "'";
			}
		 
			$arrOut[self::$arrCOLUMN_NAME[$key]] = self::FormatInputStr($key,$szValue);
		}
		return $arrOut;
	 }
	 
	 public static function FormatInputStr($szKey,$szStr)
	 {
		
		if($szStr != "" && in_array($szKey,self::$FormatColums))
		{
			$arrTemp = explode(",",$szStr);
			$sztemp = implode("','", array_unique($arrTemp));
			$szValue = "'" . $sztemp . "'";
			
			return $szValue;
		}
		return $szStr;
	 }
    
	 // editDpp
	  public static $titleArray = array(
	  	"BASIC" => "Basic details",
	  	"RELIGION_ETHINICITY" => "Religion & Ethnicity",
	  	"EDUCATION" => "Education & Work",
	  	"LIFESTYLE" => "Lifestyle",
	  	"DESIRED_PARTNER" => "Desired partner",
	  	);
	  public static $editArray = array(
	  	"BASIC" => "editbasic",
	  	"RELIGION_ETHINICITY" => "editreligion",
	  	"EDUCATION" => "editedu",
	  	"LIFESTYLE" => "editlife",
	  	"DESIRED_PARTNER" => "editdpro",
	  	);
	  public static $keyMap = array(
	  	"BASIC" => array("AGE","HEIGHT","MARITAL_STATUS","HAVE_CHILDREN","COUNTRY","CITY"),
	  	"RELIGION_ETHINICITY" => array("RELIGION","CASTE","MOTHER_TONGUE","MANGLIK"),
	  	"EDUCATION" => array("HIGHEST_EDUCATION","OCCUPATION","INCOME"),
	  	"LIFESTYLE" => array("DIETRY_HABITS","DRINKING_HABITS","SMOKING_HABITS","COMPLEXION","BODY_TYPE","CHALLENGED","NATURE_OF_HANDICAP"),
	  	"DESIRED_PARTNER" =>array("DESCRIBE_PARTNER")
	  	);
	  public static $fieldNameArray = array(
	  	"BASIC" => array(
	  		"AGE" => "Age",
	  		"HEIGHT" => "Height",
	  		"MARITAL_STATUS" =>"Marital Status",
	  		"HAVE_CHILDREN" => "Have Children",
	  		"COUNTRY" => "Country",
	  		"CITY" => "State/City"),
	  	"RELIGION_ETHINICITY" => array(
	  		"RELIGION" => "Religion",
	  		"CASTE" => "Caste",
	  		"MOTHER_TONGUE" =>"Mother Tongue",
	  		"MANGLIK" => "Manglik",),
	  	"EDUCATION" => array(
	  		"HIGHEST_EDUCATION" => "Highest Education",
	  		"OCCUPATION" => "Occupation", 
	  		"INCOME" =>"Income",
	  		),
	  	"LIFESTYLE" => array(
	  		"DIETRY_HABITS" => "Dietary habits",
	  		"SMOKING_HABITS" => "Smoking habits",
	  		"DRINKING_HABITS" =>"Drinking habits",
	  		"COMPLEXION" => "Complexion",
	  		"BODY_TYPE" => "Body type",
	  		"CHALLENGED" =>"Challenged",
	  		"NATURE_OF_HANDICAP" => "Nature of handicap"),
	  	"DESIRED_PARTNER" => array(
	  		"DESCRIBE_PARTNER" => "About Desired Partner",),
	  	);	
	  public static $fieldTypeArray = array(
	  	"BASIC" => array(
	  		"AGE" => self::FIELD_TYPE_RANGE_AGE,
	  		"HEIGHT" => self::FIELD_TYPE_RANGE_HEIGHT,
	  		"MARITAL_STATUS" => self::FIELD_TYPE_MULTISELECT,
	  		"HAVE_CHILDREN" => self::FIELD_TYPE_MULTISELECT,
	  		"COUNTRY" => self::FIELD_TYPE_MULTISELECT,
	  		"CITY" => self::FIELD_TYPE_MULTISELECT,),
	  	"RELIGION_ETHINICITY" => array(
	  		"RELIGION" => self::FIELD_TYPE_MULTISELECT,
	  		"CASTE" => self::FIELD_TYPE_MULTISELECT,
	  		"MOTHER_TONGUE" =>self::FIELD_TYPE_MULTISELECT,
	  		"MANGLIK" => self::FIELD_TYPE_MULTISELECT,),
	  	"EDUCATION" => array(
	  		"HIGHEST_EDUCATION" => self::FIELD_TYPE_MULTISELECT,
	  		"OCCUPATION" => self::FIELD_TYPE_MULTISELECT,
	  		"INCOME" => self::FIELD_TYPE_RANGE_INCOME,),
	  	"LIFESTYLE" => array(
	  		"DIETRY_HABITS" => self::FIELD_TYPE_MULTISELECT,
	  		"SMOKING_HABITS" => self::FIELD_TYPE_MULTISELECT,
	  		"DRINKING_HABITS" =>self::FIELD_TYPE_MULTISELECT,
	  		"COMPLEXION" => self::FIELD_TYPE_MULTISELECT,
	  		"BODY_TYPE" => self::FIELD_TYPE_MULTISELECT,
	  		"CHALLENGED" =>self::FIELD_TYPE_MULTISELECT,
	  		"NATURE_OF_HANDICAP" => self::FIELD_TYPE_MULTISELECT,),
	  	"DESIRED_PARTNER" => array(
	  		"DESCRIBE_PARTNER" => self::FIELD_TYPE_TEXT,),
	  	);
		
	 public static $fieldDropDownMapArray = array(
	  	"BASIC" => array(
	  		"AGE" => array(
	  			"minage" => "age",
	  			"maxage" => "age",
	  			),
	  		"HEIGHT" => array(
	  			"minheight" => "height_json",
	  			"maxheight" => "height_json",
	  			),
	  		"MARITAL_STATUS" => "p_mstatus",
	  		"HAVE_CHILDREN" => "children",
	  		"COUNTRY" => "dpp_country",
	  		"CITY" => "dpp_city",
	  		),
	  	"RELIGION_ETHINICITY" => array(
	  		"RELIGION" => "p_religion",
	  		"CASTE" => "p_caste",
	  		"MOTHER_TONGUE" => "p_mtongue",
	  		"MANGLIK" => "p_manglik",
	  		),
	  	"EDUCATION" => array(
	  		"HIGHEST_EDUCATION" => "p_education",
	  		"OCCUPATION" => "p_occupation_grouping",
	  		"INCOME" =>array(
	  			"minIncomeRs"=>"lincome",
	  			"maxIncomeRs"=>"hincome",
	  			"minIncomeDol"=>"lincome_dol",
	  			"maxIncomeDol"=>"hincome_dol",
	  			),
	  		),
	  	"LIFESTYLE" => array(
	  		"DIETRY_HABITS" => "p_diet",
	  		"SMOKING_HABITS" => "p_smoke",
	  		"DRINKING_HABITS" =>"p_drink",
	  		"COMPLEXION" => "p_complexion",
	  		"BODY_TYPE" => "p_btype",
	  		"CHALLENGED" =>"p_challenged",
	  		"NATURE_OF_HANDICAP" => "p_nchallenged"
	  		),
	  	"DESIRED_PARTNER" => array(
	  		"DESCRIBE_PARTNER" => FALSE),
	  	);	
         public static $prefilledKeyArray = array(
	  	"BASIC" => array(
	  		"AGE" => 'P_AGE',
	  		"HEIGHT" => 'P_HEIGHT',
	  		"MARITAL_STATUS" => 'P_MSTATUS',
	  		"HAVE_CHILDREN" => 'P_HAVECHILD',
	  		"COUNTRY" => 'P_COUNTRY',
	  		"CITY" => 'P_CITY',
	  		),
	  	"RELIGION_ETHINICITY" => array(
	  		"RELIGION" => 'P_RELIGION',
	  		"CASTE" => 'P_CASTE',
	  		"MOTHER_TONGUE" => 'P_MTONGUE',
	  		"MANGLIK" => 'P_MANGLIK',
	  		),
	  	"EDUCATION" => array(
	  		"HIGHEST_EDUCATION" => 'P_EDUCATION',
	  		"OCCUPATION" => 'P_OCCUPATION_GROUPING',  //recheck
	  		"INCOME" =>'P_INCOME'
	  		),
	  	"LIFESTYLE" => array(
	  		"DIETRY_HABITS" => 'P_DIET',
	  		"SMOKING_HABITS" => 'P_SMOKE',
	  		"DRINKING_HABITS" => 'P_DRINK',
	  		"COMPLEXION" => 'P_COMPLEXION',
	  		"BODY_TYPE" => 'P_BTYPE',
	  		"CHALLENGED" => 'P_CHALLENGED',
	  		"NATURE_OF_HANDICAP" => 'P_NCHALLENGED'
	  		),
                "DESIRED_PARTNER" => array(
	  		"DESCRIBE_PARTNER" => 'SPOUSE')
              );

	  public static $fieldFilterArray = array(
	  	"BASIC" => array(
	  		"AGE" => array(
                                "FILTER_MAP" => 'AGE',
	  			"FILTER" => self::VALUE_YES,
	  			"FILTER_VALUE" => self::VALUE_NO,
	  			"FILTER_HINT_TEXT" => "Interests from people outside this age range will go to your Filtered Inbox, and they will also not be able to see your Phone/EmailID.",),
	  		"HEIGHT" => FALSE,
	  		"MARITAL_STATUS" => array(
                                "FILTER_MAP" => 'MSTATUS',
	  			"FILTER" => self::VALUE_YES,
	  			"FILTER_VALUE" => self::VALUE_NO,
	  			"FILTER_HINT_TEXT" => "Interests from people outside specified status will go to your Filtered Inbox, and they will also not be able to see your Phone/EmailID.",),
	  			"HAVE_CHILDREN" => FALSE,
	  		"COUNTRY" => array(
                                "FILTER_MAP" => 'COUNTRY_RES',
	  			"FILTER" => self::VALUE_YES,
	  			"FILTER_VALUE" => self::VALUE_NO,
	  			"FILTER_HINT_TEXT" => "Interests from people outside specified countries will go to your Filtered Inbox, and they will also not be able to see your Phone/EmailID.",),
	  		"CITY" => array(
                                "FILTER_MAP" => 'CITY_RES',
	  			"FILTER" => self::VALUE_YES,
	  			"FILTER_VALUE" => self::VALUE_NO,
	  			"FILTER_HINT_TEXT" => "Interests from people outside specified states/cities will go to your Filtered Inbox, and they will also not be able to see your Phone/EmailID.",),),
	  	"RELIGION_ETHINICITY" => array(
	  		"RELIGION" => array(
                                "FILTER_MAP" => 'RELIGION',
	  			"FILTER" => self::VALUE_YES,
	  			"FILTER_VALUE" => self::VALUE_NO,
	  			"FILTER_HINT_TEXT" => " Interests from people outside specified religions will go to your Filtered Inbox, and they will also not be able to see your Phone/EmailID.",),
	  		"CASTE" => array(
                                "FILTER_MAP" => 'CASTE',
	  			"FILTER" => self::VALUE_YES,
	  			"FILTER_VALUE" => self::VALUE_NO,
	  			"FILTER_HINT_TEXT" => "Interests from people outside specified castes will go to your Filtered Inbox, and they will also not be able to see your Phone/EmailID.",),
	  		"MOTHER_TONGUE" => array(
                                "FILTER_MAP" => 'MTONGUE',
	  			"FILTER" => self::VALUE_YES,
	  			"FILTER_VALUE" => self::VALUE_NO,
	  			"FILTER_HINT_TEXT" => " Interests from people outside specified mother tongues will go to your Filtered Inbox, and they will also not be able to see your Phone/EmailID.",),
	  		"MANGLIK" => FALSE,),
	  	"EDUCATION" => array(
	  		"HIGHEST_EDUCATION" => FALSE,
	  		"OCCUPATION" => FALSE,
	  		"INCOME" =>array(
                                "FILTER_MAP" => 'INCOME',
	  			"FILTER" => self::VALUE_YES,
	  			"FILTER_VALUE" => self::VALUE_NO,
	  			"FILTER_HINT_TEXT" => "Interests from people outside this income range will go to your Filtered Inbox, and they will also not be able to see your Phone/EmailID.",),),
	  	"LIFESTYLE" => array(
	  		"DIETRY_HABITS" => FALSE,
	  		"SMOKING_HABITS" => FALSE,
	  		"DRINKING_HABITS" =>FALSE,
	  		"COMPLEXION" => FALSE,
	  		"BODY_TYPE" => FALSE,
	  		"CHALLENGED" =>FALSE,
	  		"NATURE_OF_HANDICAP" => FALSE),
	  	"DESIRED_PARTNER" => array(
	  		"DESCRIBE_PARTNER" => FALSE),
	  	);
	public static $fieldChosenArray = array(
		"BASIC" => array(
	  		"AGE" => self::VALUE_NO,
	  		"HEIGHT" => self::VALUE_NO,
	  		"MARITAL_STATUS" =>self::VALUE_YES,
	  		"HAVE_CHILDREN" => self::VALUE_YES,
	  		"COUNTRY" => self::VALUE_YES,
	  		"CITY" => self::VALUE_YES,),
	  	"RELIGION_ETHINICITY" => array(
	  		"RELIGION" => self::VALUE_YES,
	  		"CASTE" => self::VALUE_YES,
	  		"MOTHER_TONGUE" =>self::VALUE_YES,
	  		"MANGLIK" => self::VALUE_YES,),
	  	"EDUCATION" => array(
	  		"HIGHEST_EDUCATION" => self::VALUE_YES,
	  		"OCCUPATION" => self::VALUE_YES,
	  		"INCOME" =>self::VALUE_NO,),
	  	"LIFESTYLE" => array(
	  		"DIETRY_HABITS" => self::VALUE_YES,
	  		"SMOKING_HABITS" => self::VALUE_YES,
	  		"DRINKING_HABITS" =>self::VALUE_YES,
	  		"COMPLEXION" => self::VALUE_YES,
	  		"BODY_TYPE" => self::VALUE_YES,
	  		"CHALLENGED" =>self::VALUE_YES,
	  		"NATURE_OF_HANDICAP" => self::VALUE_YES,),
	  	"DESIRED_PARTNER" => array(
	  		"DESCRIBE_PARTNER" => self::VALUE_NO,),
	  	);
	public static $alterFeildDataStructureArray = array(
		"lowIncomeRs" => "lincome",
		"highIncomeRs" => "hincome",
		"lowIncomeDol" => "lincome_dol",
		"highIncomeDol" => "hincome_dol",
		"hasChildren" => "children",
		);
        public static $editWhatNewArr = array(
		"Dpp_Info" => "editdpro",
		"Dpp_Details" => "editbasic",
		"Dpp_Religion" => "editreligion",
		"Dpp_Life" => "editlife",
		"Dpp_Edu" => "editedu",
		);
        public static $editWhatNewEnumArr = array("Dpp_Info","Dpp_Details","Dpp_Religion","Dpp_Life","Dpp_Edu");
            
        public static $idNameArray = array(
	  	"BASIC" => array(
	  		"AGE" => "age",
	  		"HEIGHT" => "height",
	  		"MARITAL_STATUS" =>"maritalStatus",
	  		"HAVE_CHILDREN" => "haveChildren",
	  		"COUNTRY" => "country",
	  		"CITY" => "cityLivingIn",),
	  	"RELIGION_ETHINICITY" => array(
	  		"RELIGION" => "religion",
	  		"CASTE" => "caste",
	  		"MOTHER_TONGUE" =>"motherTongue",
	  		"MANGLIK" => "manglik",),
	  	"EDUCATION" => array(
	  		"HIGHEST_EDUCATION" => "highestEducation",
	  		"OCCUPATION" => "occupationGrouping",
	  		"INCOME" =>"income",
	  		),
	  	"LIFESTYLE" => array(
	  		"DIETRY_HABITS" => "dietryHabits",
	  		"SMOKING_HABITS" => "smokingHabits",
	  		"DRINKING_HABITS" =>"drinkingHabits",
	  		"COMPLEXION" => "complexion",
	  		"BODY_TYPE" => "bodyType",
	  		"CHALLENGED" =>"challenged",
	  		"NATURE_OF_HANDICAP" => "natureOfHandicap"),
	  	"DESIRED_PARTNER" => array(
	  		"DESCRIBE_PARTNER" => "describeYourExpectations",),
	  	);	
	public static $sectionIdArray = array(
	  	"BASIC" => "basicDetail",
	  	"RELIGION_ETHINICITY" => "religionDetail",
	  	"EDUCATION" => "educationDetail",
	  	"LIFESTYLE" => "lifestyleDetail",
	  	"DESIRED_PARTNER" => "desiredPartnerDetail",
	  	);
	const FIELD_TYPE_RANGE_AGE = "R_AGE";
	const FIELD_TYPE_RANGE_HEIGHT = "R_HEIGHT";
	const FIELD_TYPE_RANGE_INCOME = "R_INCOME";
	const FIELD_TYPE_MULTISELECT = "M";
	const FIELD_TYPE_TEXT ="T";
	const VALUE_YES = "Y";
	const VALUE_NO = "N";

  const AP_SCREEN_MSG = 'Your desired partner profile will be vetted by our matchmaking expert before it is updated on your profile';
  public static $removeCasteFromDppArr = array("242","243","244","245","246");
  public static $removeLabelFromDpp = "Select";
  public static $editDppFields = Array('ID','PROFILEID','GENDER','CHILDREN','LAGE','HAGE','LHEIGHT','HHEIGHT','HANDICAPPED','DATE','ALERTS','PAGE','DPP','CASTE_MTONGUE','PARTNER_BTYPE','PARTNER_CASTE','PARTNER_CITYRES','PARTNER_COUNTRYRES','PARTNER_DIET','PARTNER_DRINK','PARTNER_ELEVEL_NEW','PARTNER_INCOME','PARTNER_MANGLIK','PARTNER_MSTATUS','PARTNER_MTONGUE','PARTNER_NRI_COSMO','PARTNER_OCC','PARTNER_RELATION','PARTNER_RES_STATUS','PARTNER_SMOKE','PARTNER_COMP','PARTNER_RELIGION','PARTNER_NAKSHATRA','NHANDICAPPED','LINCOME','HINCOME','LINCOME_DOL','HINCOME_DOL','PARTNER_DEGREE','HAVEPHOTO','HIV','HIJAB_MARRIAGE','SPEAK_URDU','SAMPRADAY','ZARATHUSHTRI','HOROSCOPE','AMRITDHARI','CUT_HAIR','MATHTHAB','WEAR_TURBAN','LIVE_PARENTS','EDUCATION_GROUPING','LAST_ACTIVITY','OCCUPATION_GROUPING','INDIA_NRI','STATE','CITY_INDIA','MARRIED_WORKING','GOING_ABROAD','CASTE_GROUP','VIEWED','MAPPED_TO_DPP','CHANNEL');
 }
 
?>
