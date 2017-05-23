<?php
/**
 * @brief This class is inbox channels interface class
 */

interface InboxChannelInterface
{
	/** 
	* This function will return the configuration object of required channel
	*/
	public function __construct($params);

	/** This function will return the channel specific variables
	* @param params : need to be set 
	*/
	public function setVariables($params);

	/** This function will set post params for api request
        *@param :request  
        */
	public function setPostParamsForApiRequest($request);
}
?>
