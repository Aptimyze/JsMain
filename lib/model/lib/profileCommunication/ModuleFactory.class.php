<?php
/**
 * @brief This class is main factory class for all the profile communication related modules like  myjs
 * @author Reshu Rajput
 * @created 2013-09-26
 */

class ModuleFactory
{

	/* This function will return the object of required module
	*@param module : module name
	*@param profileObj : optional profile obj if required for the module  
	*@return classobj : gets the class name from enum PROFILE_COMMUNICATION_ENUM and return its obj  
	*/ 
        public function getModule($module,$profileObj=null)
        {
		$className = PROFILE_COMMUNICATION_ENUM_INFO::getClass($module);
		if(!is_null($profileObj))
			$obj = new $className($module,$profileObj);
		else
			$obj = new $className($module);

		/** This code is added since JSI team has hardcoded Inbox:profileCount as 10**/
		if($module=='ContactCenterDesktop' && $className=='Inbox')
			$obj::$profileCount = InboxConfig::$ccPCProfilesPerPage;
		/** **/
		return $obj;
        }

}
?>
