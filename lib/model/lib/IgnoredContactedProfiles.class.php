<?php

/**
 * This library will return list of ignored, contacted or matchalerts 
 * for a given profileid
 * 
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Ankit
 */
class IgnoredContactedProfiles{
    
	public static function getProfileList($profileid,$type='')
	{
                /* ignored profiles two way */
                $IgnoredProfilesObj = new IgnoredProfiles();
                $hideArr = $IgnoredProfilesObj->listIgnoredProfile($profileid);
                
                
                /* contacted profiles */
                $Obj = new ContactsRecords;
                $hideArr= array_merge($hideArr,$Obj->getContactsList($profileid));
            
            if($type=="matchalerts"){
                /* matchalerts profiles */
                $matchalerts_LOG = new matchalerts_LOG();
                $hideArr= array_merge($hideArr,$matchalerts_LOG->getProfilesSentInMatchAlerts($profileid));
            }
            
            return $hideArr;
	}
        
}

