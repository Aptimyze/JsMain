<?php
/*
This class acts as the factory class for photo transfer cron and determines which module object should be created based on module name passed
*/
class UpdateModuleTableFactory
{
	static public function getModuleObject($module)
	{
		if($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("PICTURE"))
			$obj = new ScreenedPicture;
		elseif($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("SUCCESS_STORY"))
			$obj = new SuccessStories;
		elseif($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("INDIVIDUAL_STORY"))
			$obj = new IndividualStories;
		elseif($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("FIELD_SALES"))
			$obj = new FieldSales;
		elseif($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("FIELD_SALES"))
                        $obj = new FieldSales;
		elseif($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("VERIFICATION_DOCUMENTS"))
                        $obj = new ProfileDocumentVerificationService;
		elseif($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("VERIFICATION_DOCUMENTS_BYUSER"))
                        $obj = new ProfileDocumentVerificationByUserService;
    if($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("PICTURE_DELETED"))
			$obj = new DeletedPictures;
		return $obj;
	}
}
