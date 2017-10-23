<?php
/**
 * @brief This class implements Jpartner Edit log
 * @author Reshu Rajput
 * @created 13 Jun 16
 */
class JpartnerEditLog
{
	

  public function logDppEdit($jpartnerObj,$updatedArr,$param=''){
			$updateLog["PROFILEID"] = $jpartnerObj->getPROFILEID();
			if(is_array($param) && array_key_exists("fromBackend",$param) && $param["fromBackend"]==1)
				$updateLog["CHANNEL"]="BK";
			else
				$updateLog["CHANNEL"]= MobileCommon::getChannel();
			
			foreach ($updatedArr as $k=>$v)
			{
				if(in_array($k,DPPConstants::$editDppFields))
					eval('$updateLog["' . $k . '"]=$jpartnerObj->get'.$k.'();');
			}
			$PROFILE_JPARTNER_EDIT_LOG = new PROFILE_JPARTNER_EDIT_LOG();
			$PROFILE_JPARTNER_EDIT_LOG->addRecords($updateLog);
					
	}

	public function logAPDppEdit($jpartnerObj,$updatedArr,$params=''){
		$updateLog["PROFILEID"] = $jpartnerObj->getPROFILEID();
		$updateLog["CHANNEL"]= "BK";
		$DppFieldsArr= DPPConstants::getDppFieldMapping('','1');
			foreach ($updatedArr as $k=>$v)
			{
				if(in_array($k,$DppFieldsArr))
				{
					eval('$oldValue=$jpartnerObj->get'.$k.'();');
				
					if($oldValue!=$v)
					{
					$updateLog[$k]=$oldValue;
					}
				}
				
			}
			$PROFILE_JPARTNER_EDIT_LOG = new PROFILE_JPARTNER_EDIT_LOG();
			$PROFILE_JPARTNER_EDIT_LOG->addRecords($updateLog);
  }
  
  
  /* Function to log for normal profiles from save search as dpp*/
  public function logDppEditFromSave($oldDpp,$updatedArr,$params=''){
		$updateLog["PROFILEID"] = $oldDpp["PROFILEID"];
		$updateLog["CHANNEL"]= MobileCommon::getChannel();
		foreach ($oldDpp as $k=>$v)
		{
				$updateLog[$k]=$v;
		}
			$PROFILE_JPARTNER_EDIT_LOG = new PROFILE_JPARTNER_EDIT_LOG();
			
			$PROFILE_JPARTNER_EDIT_LOG->addRecords($updateLog);
  }
	        
}
?>
