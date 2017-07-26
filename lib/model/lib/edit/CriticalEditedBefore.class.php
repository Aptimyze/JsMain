<?php
class CriticalEditedBefore
{
    public function __construct()
    {
    }
	public static function canEdit($profileid,$docOnly = false)
	{
		$infoChngObj = new newjs_CRITICAL_INFO_CHANGED();
                $data = $infoChngObj->editedCriticalInfo($profileid,$docOnly);
                unset($infoChngObj);
                if(!empty($data))
		{
                        if($docOnly == true){
                                $infoChngObj = new newjs_CRITICAL_INFO_CHANGED_DOCS();
                                $dataDoc = $infoChngObj->editedCriticalInfo($profileid);
                                if(strstr($data["EDITED_FIELDS"], "MSTATUS") && $dataDoc["SCREENED_STATUS"] =="F"){
                                        return true;
                                }
                        }
			return false;
		}
		else
		{
                        if($docOnly == true){
                                return false;
                        }else{
                                return true;
                        }
		}
	}
}
