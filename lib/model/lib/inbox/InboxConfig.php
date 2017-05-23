<?php
/**
 * @brief This class contains the configurable paramters used in Inbox(contact center) module
 */
class InboxConfig
{
    //profiles count per page for contact center desktop
	public static $ccPCProfilesPerPage = 25;

    //default vertical tab id for cc listings 
	public static $defaultVerticalTabID = 2;  //i.e. - acceptances

    //default request subtype id for cc listings 
	public static $defaultRequestTypeID = 0;  //i.e. - photo

	//mapping data for Requests type options photo/horoscope
    public static $ccRequestTypeListArr = array(0=>array("RequestType"=>"Photo","horizontalTabsArrInfoID"=>array(0=>9,1=>14),"defaultHtabInfoID"=>9),
    											1=>array("RequestType"=>"Horoscope","horizontalTabsArrInfoID"=>array(0=>18,1=>15),"defaultHtabInfoID"=>18)
    							);

	//mapping data for tabs in contact center
	public static $cctabArr= array(0=>array("Vname"=>"Interests","horizontalTabsArr"=>array(0=>array("HTabId"=>0,"infoTypeID"=>1,"Hname"=>"Interests Received"),1=>array("HTabId"=>1,"infoTypeID"=>12,"Hname"=>"Filtered Interests"),2=>array("HTabId"=>2,"infoTypeID"=>6,"Hname"=>"Interests Sent")),"defaultHtabInfoID"=>1),
					   1=>array("Vname"=>"Requests","horizontalTabsArr"=>array(0=>array("HTabId"=>3,"infoTypeID"=>9,"Hname"=>"Received"),1=>array("HTabId"=>4,"infoTypeID"=>14,"Hname"=>"Sent")),"defaultHtabInfoID"=>9),
					   2=>array("Vname"=>"Acceptances","horizontalTabsArr"=>array(0=>array("HTabId"=>6,"infoTypeID"=>3,"Hname"=>"I Accepted"),1=>array("HTabId"=>7,"infoTypeID"=>2,"Hname"=>"Accepted Me")),"defaultHtabInfoID"=>3),
					   3=>array("Vname"=>"Messages","horizontalTabsArr"=>array(0=>array("HTabId"=>9,"infoTypeID"=>4,"Hname"=>"All Messages")),"defaultHtabInfoID"=>4),
					   4=>array("Vname"=>"Decline/Blocked Members","horizontalTabsArr"=>array(0=>array("HTabId"=>12,"infoTypeID"=>20,"Hname"=>"Blocked/Ignored"),1=>array("HTabId"=>13,"infoTypeID"=>11,"Hname"=>"I Declined"),2=>array("HTabId"=>14,"infoTypeID"=>10,"Hname"=>"Declined Me")),"defaultHtabInfoID"=>20),
				       5=>array("Vname"=>"Viewed Contacts","horizontalTabsArr"=>array(0=>array("HTabId"=>15,"infoTypeID"=>17,"Hname"=>"Viewed My Contact"),1=>array("HTabId"=>16,"infoTypeID"=>16,"Hname"=>"Contacts I Viewed")),"defaultHtabInfoID"=>17),
				       6=>array("Vname"=>"We Talk For You","horizontalTabsArr"=>array(0=>array("HTabId"=>18,"infoTypeID"=>19,"Hname"=>"People to be called"),1=>array("HTabId"=>19,"infoTypeID"=>21,"Hname"=>"People already called")),"defaultHtabInfoID"=>19)
					);

	//mapping data for tabs in contact center in case of showIdfy
	public static $cctabArrForIdfy = array(0=>array("Vname"=>"Interests","horizontalTabsArr"=>array(0=>array("HTabId"=>0,"infoTypeID"=>1,"Hname"=>"Interests Received"),1=>array("HTabId"=>1,"infoTypeID"=>12,"Hname"=>"Filtered Interests"),2=>array("HTabId"=>2,"infoTypeID"=>6,"Hname"=>"Interests Sent")),"defaultHtabInfoID"=>1),
					   1=>array("Vname"=>"Requests","horizontalTabsArr"=>array(0=>array("HTabId"=>3,"infoTypeID"=>9,"Hname"=>"Received"),1=>array("HTabId"=>4,"infoTypeID"=>14,"Hname"=>"Sent")),"defaultHtabInfoID"=>9),
					   2=>array("Vname"=>"Acceptances","horizontalTabsArr"=>array(0=>array("HTabId"=>6,"infoTypeID"=>3,"Hname"=>"I Accepted"),1=>array("HTabId"=>7,"infoTypeID"=>2,"Hname"=>"Accepted Me"),2=>array("HTabId"=>8,"infoTypeID"=>30,"Hname"=>"Idfy")),"defaultHtabInfoID"=>3),
					   3=>array("Vname"=>"Messages","horizontalTabsArr"=>array(0=>array("HTabId"=>9,"infoTypeID"=>4,"Hname"=>"All Messages"),1=>array("HTabId"=>10,"infoTypeID"=>32,"Hname"=>"Idfy"),2=>array("HTabId"=>11,"infoTypeID"=>31,"Hname"=>"Idfy")),"defaultHtabInfoID"=>4),
					   4=>array("Vname"=>"Decline/Blocked Members","horizontalTabsArr"=>array(0=>array("HTabId"=>12,"infoTypeID"=>20,"Hname"=>"Blocked/Ignored"),1=>array("HTabId"=>13,"infoTypeID"=>11,"Hname"=>"I Declined"),2=>array("HTabId"=>14,"infoTypeID"=>10,"Hname"=>"Declined Me")),"defaultHtabInfoID"=>20),
				       5=>array("Vname"=>"Viewed Contacts","horizontalTabsArr"=>array(0=>array("HTabId"=>15,"infoTypeID"=>17,"Hname"=>"Viewed My Contact"),1=>array("HTabId"=>16,"infoTypeID"=>16,"Hname"=>"Contacts I Viewed")),"defaultHtabInfoID"=>17),
				       6=>array("Vname"=>"We Talk For You","horizontalTabsArr"=>array(0=>array("HTabId"=>18,"infoTypeID"=>19,"Hname"=>"People to be called"),1=>array("HTabId"=>19,"infoTypeID"=>21,"Hname"=>"People already called")),"defaultHtabInfoID"=>19)
					);
    
    /**
	  * fetches get corresponding vertical tab id for passed horizontal tab info id
	  *
	  * @param $tabMappingArr,$HTabId
	  * @return $key if exists otherwise -1
	*/
    public static function getCorrespondingVerticalTabID($tabMappingArr,$HTabId)
	{
		if(InboxConfig::getCorrespondingRequestTypeTabID($HTabId,InboxConfig::$ccRequestTypeListArr)!=-1)
		{
          return 1;
		}
		else
		{
			foreach($tabMappingArr as $key=>$arr)
			{
				foreach($arr["horizontalTabsArr"] as $k=>$v)
				{
					if($v["infoTypeID"]==$HTabId)
						return $key;
				}
			}
		return -1;
		}
	}

	/**
	  * fetches get corresponding requests subtype id for passed horizontal tab info id
	  *
	  * @param horizontalTabID
	  * @return $key if exists otherwise -1
	*/
    public static function getCorrespondingRequestTypeTabID($horizontalTabID,$requestMappingArr)
	{
		foreach($requestMappingArr as $key=>$arr)
		{
			foreach($arr["horizontalTabsArrInfoID"] as $k=>$v)
			{
				if($v==$horizontalTabID)
				{
					return $key;
				}
			}
		}
		return -1;
	}
}
?>
