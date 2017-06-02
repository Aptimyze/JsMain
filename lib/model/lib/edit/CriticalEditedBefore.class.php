<?php
class CriticalEditedBefore
{
    public function __construct()
    {
    }
	public static function canEdit($profileid)
	{
		$infoChngObj = new newjs_CRITICAL_INFO_CHANGED();
                if($infoChngObj->editedCriticalInfo($profileid) === true)
		{
                        unset($infoChngObj);
			return false;
		}
		else
		{
                        unset($infoChngObj);
			return true;
		}
	}
}
