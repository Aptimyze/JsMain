<?php
/**
* This classs list all the enum required by image server LOG table MODULE_NAME field.
*/
class IMAGE_SERVER_MODULE_NAME_ENUM
{
	static public $moduleNameEnum = array();

	/*This function is used to initialize all the required module name enums required, entry for new module name should be done*/
	static public function init()
	{
		self::$moduleNameEnum["PICTURE"]="PICTURE";
		self::$moduleNameEnum["PICTURE_DELETED"]="PICTURE_DELETED";
		self::$moduleNameEnum["SUCCESS_STORY"]="SUCCESS_STORY";
		self::$moduleNameEnum["INDIVIDUAL_STORY"]="INDIVIDUAL_STORY";
		self::$moduleNameEnum["FIELD_SALES"]="FIELD_SALES";
		self::$moduleNameEnum["VERIFICATION_DOCUMENTS"]="VERIFICATION_DOCUMENTS";
		self::$moduleNameEnum["VERIFICATION_DOCUMENTS_BYUSER"]="VERIFICATION_DOCUMENTS_BYUSER";

  	}
	/* This is used to get enum value for corresponding module name*/
	static public function getEnum($name)
	{
		self::init();
		return self::$moduleNameEnum[$name];
	}
	
	/*This is used to get module name for corresponding mosule enum value*/
	static public function getModuleName($enum)
	{
		self::init();
		return array_search($enum,self::$moduleNameEnum);
	}
}
?>
