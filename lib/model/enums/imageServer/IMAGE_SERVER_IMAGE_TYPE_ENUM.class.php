<?php
/* this classs list all the enum required by image server LOG table IMAGE_TYPE field.
*/
class IMAGE_SERVER_IMAGE_TYPE_ENUM
{
        static public $imageTypeEnum = array();
    	
	/* All the enums for every module and image type combination required need to be mentioned in this initializing function*/
	 static public function init()
        {
                self::$imageTypeEnum["PICTURE"]["MainPicUrl"]="P_M";
                self::$imageTypeEnum["PICTURE"]["ProfilePicUrl"]="P_P";
		self::$imageTypeEnum["PICTURE"]["SearchPicUrl"]="P_S";
                self::$imageTypeEnum["PICTURE"]["ThumbailUrl"]="P_T";
		self::$imageTypeEnum["PICTURE"]["Thumbail96Url"]="P_T96";
		self::$imageTypeEnum["PICTURE"]["MobileAppPicUrl"]="P_MA";
                self::$imageTypeEnum["PICTURE"]["ProfilePic120Url"]="P_P120";
                self::$imageTypeEnum["PICTURE"]["ProfilePic235Url"]="P_P235";
                self::$imageTypeEnum["PICTURE"]["ProfilePic450Url"]="P_P450";
                self::$imageTypeEnum["PICTURE"]["OriginalPicUrl"]="P_OR";
		self::$imageTypeEnum["INDIVIDUAL_STORY"]["FRAME_PIC_URL"]="I_F";
                self::$imageTypeEnum["INDIVIDUAL_STORY"]["MAIN_PIC_URL"]="I_M";
                self::$imageTypeEnum["INDIVIDUAL_STORY"]["HOME_PIC_URL"]="I_H";
                self::$imageTypeEnum["INDIVIDUAL_STORY"]["SQUARE_PIC_URL"]="I_S";
                self::$imageTypeEnum["SUCCESS_STORY"]["PIC_URL"]="S_P";
                self::$imageTypeEnum["FIELD_SALES"]["PHOTO_URL"]="F_E";
		self::$imageTypeEnum["VERIFICATION_DOCUMENTS"]["DOCURL"]="V_D";
		self::$imageTypeEnum["VERIFICATION_DOCUMENTS_BYUSER"]["PROOF_VAL"]="V_DU";
		self::$imageTypeEnum["PICTURE_DELETED"]["MAIN_PHOTO_URL"]="PD_M";
		self::$imageTypeEnum["CRITICAL_INFO_DIVORCED_DOC"]["DOCUMENT_PATH"]="CI_DD";

        }
	
	/* retrieve enum form type eg: returns M for MainPicUrl
	Exception class is used instead of jsException as it is an static function and jsException will not be recognised here
	*@param : type Image type 
	*@param : module Module name
	*@return : enum corresponding to provided type , module
	*/	
        static public function getEnum($type,$module)
        {
                self::init();
		if(array_key_exists($module,self::$imageTypeEnum) && array_key_exists($type,self::$imageTypeEnum[$module]))
			$enum=self::$imageTypeEnum[$module][$type];
		else
			throw new jsException('',"Invalid Image Type Enum is requested in IMAGE_SERVER_IMAGE_TYPE_ENUM.class.php");
		return $enum;
    	}
	
	/* this function is used to retrieve Image type corrsponding to a module name and enum provided.
	Exception class is used instead of jsException as it is an static function and jsException will not be recognised here
	*@param : enum enum value 
	*@param : module Module Name
	*@return : Imagetype Image type cporresponding to provided enum and module name
	*/
        static public function getImageType($enum,$module)
        {
                self::init();
		if(array_key_exists($module,self::$imageTypeEnum))
                	$imageType= array_search($enum,self::$imageTypeEnum[$module]);
		if(!$imageType)		
	  	{   
			throw new jsException('',"Invalid Image Type is requested in IMAGE_SERVER_IMAGE_TYPE_ENUM.class.php");
        }
		return $imageType;
    	}
}
?>
