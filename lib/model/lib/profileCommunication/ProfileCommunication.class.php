<?php
/**
 * @brief This class is main bridge class for all the profile communication related modules like  myjs
 * @author Reshu Rajput
 * @created 2013-09-25
 */

class ProfileCommunication
{

	/* This function will return the counts calling the module factory of the required module
	*@param module : module name
	*@param profileObj : optional profile obj if required for the module 
	*@return count : count array of all the information requested by the module
	*/ 
	
	public function getCount($module,$profileObj=null,$infoTypenav=null)
	{
		$moduleFactory= new ModuleFactory();
		$moduleObj = $moduleFactory->getModule($module,$profileObj);
		$count= $moduleObj->getCount('',$infoTypenav);
		return $count;
	}

	/* This function will return the whole display object  calling the module factory of the required module
        *@param module : module name 
	*@param profileObj : optional profile obj if required for the module 
	*@param infoTypeNav : optional required only if information type is called by ajax, it should be key value pair of information type and				     navigation page number required to be retrieved 
	*@return moduleDisplayObj : complete object of all the information requested by the module
        */
        
        public function getDisplay($module,$profileObj=null,$infoTypeNav=null,$params=null)
        {
		$moduleFactory= new ModuleFactory();
                $moduleObj = $moduleFactory->getModule($module,$profileObj);
                $displayObj= $moduleObj->getDisplay($infoTypeNav,$params);
                return $displayObj;
        }
		public function getInboxConfiguration($module,$profileObj)
		{
			$moduleFactory= new ModuleFactory();
			$moduleObj = $moduleFactory->getModule($module,$profileObj);
			$displayObj= $moduleObj->getInboxConfiguration();
			return $displayObj;
		}
}
?>
