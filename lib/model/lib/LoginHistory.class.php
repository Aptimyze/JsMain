<?php
/**
 * @brief This class is used fore login history 
 * @author Reshu Rajput
 * @created 2013-21-Nov
 */

class LoginHistory
{
	
	/*This function is used to retrieve login history from newjs.LOG_LOGIN_HISTORY
	*@param profileid : profile id of required user
	*@param  sqlFoundRows: true,if sql found rows is required
	*@param limit : number of rows required
	*@param limitStart : limit start for pages greater than one
	*@return result : array with found rows,ipaddr and time
	*/
	public function  getLogLoginHistory($profileid,$sqlFoundRows='',$limit='',$limitStart='')
	{
		$shard = JsDbSharding::getShardNo($profileid);
		$loginObj = new LOG_LOGIN_HISTORY($shard);
		$result = $loginObj->loginHistory($profileid,$sqlFoundRows,$limit,$limitStart);
		return $result;
	}

}
?>
