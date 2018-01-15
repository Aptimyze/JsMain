<?php
/**
 * PrivilegeFactory contains get methods to create Privilege Object.
 * 
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Nitesh Sethi
 * @version 1.0   SVN: $Id:  Privilege 23810 2012-11-09 nitesh.s $
 */

 
class PrivilegeFactory
	{
	
/**
* create the {@link Privilege} object depending upon its state
* @param ProfileState
* <code>
* $privObj=PriviledgeFactory::getPrivObj(ProfileState $profileState)
* </code>
* @return Privilege
* @access public
*/
	/**
	 * return priviledge Obj
	 * @param ProfileState $profileState
	 * @return Priviledge
	 */
		public static function getPrivObj($profileState)
		{
			if($profileState == "ERISHTA" || $profileState == "EVALUE" || $profileState == "AP")
				$privObj = new PaidPrivilege();
			elseif($profileState == "FTO")
				$privObj = new FTOPrivilege();
			else
				$privObj = new FreePrivilege();
				
			return $privObj;
		}
	}

