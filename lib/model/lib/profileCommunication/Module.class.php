<?php
/**
 * @brief This class is module interface class for all the profile communication related modules like  myjs
 * @author Reshu Rajput
 * @created 2013-09-26
 */

interface Module
{

	/* This function will return the configuration object of required module
	*/ 
        public function getConfiguration($module);

	/* This function will return the module specific count object
	*@param allFlag : need to be set if new and all count for the infotypes are required
	*@return countObj : obj with infotype and corresponding count mapping
	*/
	public function getCount($allFlag="",$infoTypenav="");
	
	/* This function will return the whole display object  calling the module 
        *@param infoTypeNav : optional required only if information type is called by ajax, it should be key value pair of information type and                              navigation page number required to be retrieved 
        *@return moduleDisplayObj : complete object of all the information requested by the module
        */

        public function getDisplay($infoTypeNav=null,$params=null);
}
?>
