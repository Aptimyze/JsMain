<?php

/**
 * ApiViewConstants.class.php
 * 
 */

/**
 * Class ApiViewConstants represents the constant and static variable used in DetailViewApi
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Kunal Verma
 * @version    09-12-2013
 */
abstract class ApiViewConstants
{
	const HIV					= "HIV +ve";
	const YES					= "Y" ;
	const NO					= "N" ;
	const NULL_VALUE_MARKER 	= "-";
	const SHARE_TEXT = "Hi,\n\nPlease check the below profile on Jeevansathi.com:\n\n";
	public static $YES	= array("Y","YES");
	public static $NO	= array("N","NO");
	public static $userDefinedNullValue = null;
  const JSPC_NULL_VALUE_MARKER = "Not filled in";
	//Occupation Allowed For Currently label
	// Which are Student , Retired , Not working , Looking for a job
	public static $arrOccAllowed = array('36','37','41','44');
	
	
	// Planning After Marriage Or Not
	public static $arrPlan = array(
							"Yes"	=>	"Planning to work after marriage",
							"No"	=>	"Not planning to work after marriage"
								);
								
	//Settling Abroad
	public static $arrSettling_Abroad = array(
							"Yes"	=>	"Interested in settling abroad",
							"No"	=>	"Not interested in settling abroad",
							'Undecided' => "Not decided on settling abroad"
								);
	
	
	public static $arrHoroScope_Required = array(
							"Y"	=>	"Horoscope match is Must",
							"N"	=>	"Horoscope match is not necessary"
								);
	
	public static $arrPostedBy = array(
							"M"	=>	"His profile is managed by",
							"F"	=>	"Her profile is managed by"
								);
								
	// More Religion Info
	public static $arrMulsim_key = array('NAMAZ','ZAKAT','FASTING','UMRAH_HAJJ','QURAN','SUNNAH_BEARD','SUNNAH_CAP','HIJAB','HIJAB_MARRIAGE','WORKING_MARRIAGE');
    public static $arrMulsim_keyLabel = array('NAMAZ'=>"Namaz",'ZAKAT'=>"Zakat",'FASTING'=>"Fasting",'QURAN'=>"Do you Read Quran?",'UMRAH_HAJJ'=>"Umrah/Hajj",'SUNNAH_BEARD'=>"Sunnah Beard",'SUNNAH_CAP'=>"Sunnah Cap",'HIJAB'=>"Hijab",'HIJAB_MARRIAGE'=>'hijab_marriage','WORKING_MARRIAGE'=>"");
	public static $arrChristian_Key = array('BAPTISED','READ_BIBLE','OFFER_TITHE','SPREADING_GOSPEL');		
								
	public static $arrChristian = array(
								'BAPTISED'			=>array('Yes'=>"Baptised",'No'=>"Not Baptised"),
								'READ_BIBLE'		=>array('Yes'=>"Reads bible everyday"),
								'OFFER_TITHE'		=>array('Yes'=>"Offers Tithe regularly"),
								'SPREADING_GOSPEL'	=>array('Yes'=>"Interested in spreading the gospel"),
								);							
							
	public static $arrSikh_Key = array('CUT_HAIR','WEAR_TURBAN','CLEAN_SHAVEN','TRIM_BEARD');		
								
	public static $arrSikh = array(
								'CUT_HAIR'			=>array('Yes'=>"Cuts hair",'No'=>"Doesn't cut hair"),
								'WEAR_TURBAN'		=>array('Yes'=>"Wear turban",'No'=>"Doesn't wear turban",'Occasionally'=>"Wears turban occasionally"),
								'CLEAN_SHAVEN'		=>array('Yes'=>"Clean shaven"),
                                'TRIM_BEARD'        =>array('Yes'=>"Trims beard",'No'=>"Doesn't trims beard"),
								);	


	// Family
	public static $arrFamilyBG = array('getDecoratedFamilyValues','getDecoratedFamilyType','getDecoratedFamilyStatus');								
	
	public static $arrFatherOcc_Mapping = array(
							"1"=>'Businessman',
							"2"=>'Serving in private firm',
							"3"=>'Serving in Govt. / PSU',
							"4"=>'Serving in Army',
							"5"=>'In Civil Services',
							"6"=>'Retired',
							"7"=>'Not Employed',
							"8"=>'Expired'
							);
		
	public static $arrMotherOcc_Mapping = array(
							"1"=>'Housewife',
							"2"=>'Businesswoman',
							"3"=>'Serving in private firm',
							"4"=>'Serving in Govt. / PSU',
							"5"=>'Serving in Army',
							"6"=>'In Civil Services',
							"7"=>'Teacher',
							"8"=>'Retired',
							"9"=>'Expired'
							);
	
	public static $arrLivingStatus = array(
										'Yes'	=> "Living with parents",
										'No'	=> "Not living with parents",
										'Not Applicable' => "Living with parents- Not Applicable"
										);
	
	public static $arrDrinkLabel = array(
										'Y' => "Drink - Yes",
										'N' => "Non Drinker",
										'O' => "Occasional Drinker",
										);
	
	public static $arrSmokeLabel = array(
										'Y' => "Smoke - Yes",
										'N' => "Non Smoker",
										'O' => "Occasional Smoker",
										);									
	
	public static $arrHouseAndCar = array(
										'YY' => array('Y'=>"Owns house & car"),
										'Y' => array('N'=>"Owns a house"),
										'N' => array('Y'=>"Owns a car"),
										);
											
	public static $arrHobbies = array(
									'HOBBIES'=>'HOBBY',
									'INTEREST'=>'INTEREST',
									'DRESS_STYLE'=>'DRESS',
									'FAV_TV_SHOW'=>'FAV_TVSHOW',
									'FAV_BOOK'=>'FAV_BOOK',
									'FAV_MOVIES'=>'FAV_MOVIE',
									'FAV_CUISINE'=>'CUISINE',
									'I_COOK'=>'FAV_FOOD',
									);
	
	public static $arrPets_Preference = array(
										'Y'=>array('text'=>"Open to pets",'color_code'=>"#FFFFFF"),//Color code of red
										'N' => array('text'=>"Not open to pets",'color_code'=>"#FFFFFF"),//color code of green
										);
				
	public static $arrDPPInfo = array(
								'DPP_MARITAL_STATUS'=>'getDecoratedPARTNER_MSTATUS',
								'DPP_MANGLIK'=>'getDecoratedPARTNER_MANGLIK',
								'DPP_RELIGION'=>'getDecoratedPARTNER_RELIGION',
								'DPP_CASTE'=>'getDecoratedPARTNER_CASTE',
								'DPP_MTONGUE'=>'getDecoratedPARTNER_MTONGUE',
								'DPP_CITY'=>'getDecoratedPARTNER_CITYRES',
								'DPP_COUNTRY'=>'getDecoratedPARTNER_COUNTRYRES',
								'DPP_EDU_LEVEL'=>'getDecoratedPARTNER_ELEVEL_NEW',
								'DPP_OCCUPATION'=>'getDecoratedPARTNER_OCC',
								'DPP_STATE'=>'getDecoratedSTATE',
								);
	
	public static $arrDPP_LifeStyle = array('getDecoratedPARTNER_DIET'=>null,'getPARTNER_SMOKE'=>'arrSmokeLabel','getPARTNER_DRINK'=>'arrDrinkLabel');								
    const HANDICAPPED_NONE = "Not physically or mentally challenged";										
	const HIJAB_AFTER_MARRIAGE	 = "Wear hijab after marriage?";
    public static $arrWorkingMarriage = array(
										'Y' => "The girl can work after marriage",
										'P' => "Prefer a housewife",
                                        'N' => "Prefer a housewife"
										);
    public static $arrOpenToPets = array(
    								'Y'=>'Open to pets',
    								'N'=>'Not open to pets'
    								);
    public static $arrOwnsHouse = array(
    								'Y'=>'House - Yes',
    								'N'=>'House - No'
    								);
    public static $arrOwnsCar = array(
    								'Y'=>'Car - Yes',
    								'N'=>'Car - No'
    								);
    public static $arrDrinkLabelDesktop = array(
    								'Y' => "Drinks",
									'N' => "Doesn't drink",
									'O' => "Drinks occasionally",
    								);
    public static $arrSmokeLabelDesktop = array(
    								'Y' => "Smokes",
									'N' => "Doesn't smoke",
									'O' => "Smokes occasionally",
    								);
    public static $arrChristianLabels = array(
    								0 => array("old_label"=>"DIOCESE",
    										   "new_label"=>"Diocese"
    										   ),
    								1 => array("old_label"=>"BAPTISED",
    										   "new_label"=>"Baptised?"
    										   ),
									2 => array("old_label"=>"READ_BIBLE",
    										   "new_label"=>"Reads Bible"
    										   ),
									3 => array("old_label"=>"OFFER_TITHE",
    										   "new_label"=>"Offers Tithe"
    										   ),
									4 => array("old_label"=>"SPREADING_GOSPEL",
    										   "new_label"=>"Interested to spread the gospel?"
    										   ),
    								);
    public static $arrMuslimMaleLabels = array(
    								0 => array("old_label"=>"MATHTHAB",
    										   "new_label"=>"Ma'thab"
    										   ),
									1 => array("old_label"=>"NAMAZ",
    										   "new_label"=>"Namaz"
    										   ),
									2 => array("old_label"=>"ZAKAT",
    										   "new_label"=>"Zakat"
    										   ),
									3 => array("old_label"=>"FASTING",
    										   "new_label"=>"Fasting"
    										   ),
									4 => array("old_label"=>"UMRAH_HAJJ",
    										   "new_label"=>"Umrah/Hajj"
    										   ),
									5 => array("old_label"=>"QURAN",
    										   "new_label"=>"Reading Quran"
    										   ),
									6 => array("old_label"=>"SUNNAH_BEARD",
    										   "new_label"=>"Sunnah Beard"
    										   ),
									7 => array("old_label"=>"SUNNAH_CAP",
    										   "new_label"=>"Sunnah Cap"
    										   ),	
									8 => array("old_label"=>"HIJAB",
    										   "new_label"=>"Hijab"
    										   ),
									9 => array("old_label"=>"WORKING_MARRIAGE",
    										   "new_label"=>"Can the girl work after marriage?"
    										   ),
    								);

	public static $arrMuslimFemaleLabels = array(
    								0 => array("old_label"=>"MATHTHAB",
    										   "new_label"=>"Ma'thab"
    										   ),
									1 => array("old_label"=>"NAMAZ",
    										   "new_label"=>"Namaz"
    										   ),
									2 => array("old_label"=>"ZAKAT",
    										   "new_label"=>"Zakat"
    										   ),
									3 => array("old_label"=>"FASTING",
    										   "new_label"=>"Fasting"
    										   ),
									4 => array("old_label"=>"UMRAH_HAJJ",
    										   "new_label"=>"Umrah/Hajj"
    										   ),
									5 => array("old_label"=>"QURAN",
    										   "new_label"=>"Reading Quran"
    										   ),
									6 => array("old_label"=>"HIJAB_MARRIAGE",
    										   "new_label"=>"Hijab after marriage?"
    										   ),
    								);
	public static $arrSikhMaleLabels = array(
    								0 => array("old_label"=>"AMRITDHARI",
    										   "new_label"=>"Amritdhari"
    										   ),
									1 => array("old_label"=>"CUT_HAIR",
    										   "new_label"=>"Cuts hair"
    										   ),
									2 => array("old_label"=>"TRIM_BEARD",
    										   "new_label"=>"Trims beard"
    										   ),
									3 => array("old_label"=>"WEAR_TURBAN",
    										   "new_label"=>"Wears turban"
    										   ),
									4 => array("old_label"=>"CLEAN_SHAVEN",
    										   "new_label"=>"Clean Shaven"
    										   ),
    								);
  	public static $arrSikhFemaleLabels = array(
    								0 => array("old_label"=>"AMRITDHARI",
    										   "new_label"=>"Amritdhari"
    										   ),
									1 => array("old_label"=>"CUT_HAIR",
    										   "new_label"=>"Cuts hair"
    										   )
    								);
	public static $arrParsiLabels = array(
    								0 => array("old_label"=>"ZARATHUSHTRI",
    										   "new_label"=>"Zarusthri"
    										   ),
									1 => array("old_label"=>"PARENTS_ZARATHUSHTRI",
    										   "new_label"=>"Are your parents Zarusthri?"
    										   ),
    								);
	public static $arrJainLabels = array(
    								0 => array("old_label"=>"ZARATHUSHTRI",
    										   "new_label"=>"Zarusthri"
    										   ),
									1 => array("old_label"=>"PARENTS_ZARATHUSHTRI",
    										   "new_label"=>"Parents are zarusthri"
    										   ),
    								);

	public static $hasChildren = array (
									'N'=>"No children",
									'YT'=>"Has children, living together",
									'YS'=>"Has children, living separately"

									);
	public static function getManglikLabel($char)
	{
		if($char == null)
			return null;
			
		switch($char)
		{
			case "M":
				$szManglik = "Manglik";
				break;
			case "N":
				$szManglik = "Non-Manglik";
				break;
			case "A":
				$szManglik = "Angshik";
				break;
            case "D":
                $szManglik = "Don't Know";
				break;
			default:
				$szManglik = null;
		}
		return $szManglik;
	}
  
  /*
   * 
   */
  public static function getNullValueMarker(){
    if(self::$userDefinedNullValue === null)
      return self::NULL_VALUE_MARKER;
    
    return self::$userDefinedNullValue;
  }
  
  public static function setUserDefinedNullValueMarker($val){
    self::$userDefinedNullValue = $val;
  }
  
  public static $arrAllowedHobbies = array("HOBBY","INTEREST","MUSIC","BOOK","MOVIE","SPORTS","CUISINE","LANGUAGE","DRESS","FAV_BOOK","FAV_TVSHOW","FAV_MOVIE","FAV_BOOK","FAV_VAC_DEST","FAV_FOOD");
}

?>
