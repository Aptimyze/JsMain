<?php
/**
 * @brief This class is used to handle all functionalities related view log table
 * @author lavesh rawat
 * @created 2012-08-22
 */

class  ViewedLog
{
	public function findViewedProfiles($pid,$key='')
	{
		$VIEW_LOGobj = new VIEW_LOG();
		$onlineUsers = $VIEW_LOGobj->get($pid,'',$key);
		return $onlineUsers;
	}
}
?>
