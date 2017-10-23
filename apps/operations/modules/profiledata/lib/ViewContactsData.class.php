<?php

class ViewContactsData
{
    
	
	function getViewContactsData($profileid)
	{
        $viewContactsObj = new JSADMIN_VIEW_CONTACTS_LOG();
        $result = $viewContactsObj->getViewContactsLogForLegal($profileid);
        
        $profileidArr = array();
        if(is_array($result)){
            foreach($result as $row => $val){
                if(in_array($val["VIEWER"], $profileidArr) === false){
                    $profileidArr[] = $val["VIEWER"];
                }
                if(in_array($val["VIEWED"], $profileidArr) === false){
                    $profileidArr[] = $val["VIEWED"];
                }
            }
            
            $obj = JPROFILE::getInstance('newjs_slave');
            $arrProfiles = $obj->getArray(array('PROFILEID'=> implode(",", $profileidArr)),"","","PROFILEID,USERNAME");
            
            foreach($arrProfiles as $row) {
                $arrMap[$row['PROFILEID']] = $row['USERNAME'];
            }
            
            foreach($result as $k => $row) {
                $result[$k]['VIEWER'] = $arrMap[$row['VIEWER']];
                $result[$k]['VIEWED'] = $arrMap[$row['VIEWED']];
            }
        }
        else{
            $result = false;
        }
        
        return $result;
	}
	
}

?>
