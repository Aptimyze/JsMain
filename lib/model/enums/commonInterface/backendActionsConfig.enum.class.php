<?php
class uploadVD
{
    const EMPTY_SOURCE = 1;  //not used
    const INCOMPLETE_UPLOAD = 2;  //not used
    const COMPLETE_UPLOAD = 3;   //not used
    public static $RECORDS_SELECTED_PER_TRANSFER = 5000;
    public static $vdDurationArr =array('2','3','6','12','L');
}

class exclusiveMemberList
{
	public static $TYPE_TABID_MAPPING = array("ASSIGNED"=>array("TABID"=>0,"ACTION"=>"UNASSIGN","NAME"=>"Assigned customers"),
											"PENDING"=>array("TABID"=>1,"ACTION"=>"ASSIGN","NAME"=>"Pending customers")
											);
	//public static $displayColumnsNames = array("Client Name","Username","Age","Gender","Marital Status","Height","Religion/Caste","Annual Income","Matches","Contact No","Email","Billing Date","Service Duration","Service Expiry Date","Sales Person","Executive","Action");
	public static $displayColumnsNames = array("Client Name","Username","Age","Gender","Marital Status","Height","Religion/Caste","Annual Income","Contact No","Email","Billing Date","Service Duration","Service Expiry Date","Sales Person","Executive","Action");

	public static $specificColumnMapping = array("HEIGHT"=>"height_without_meters","RELIGION"=>"religion","CASTE"=>"caste_without_religion","INCOME"=>"income_map","SERVICEID"=>"SERVICE_DURATION");

	public static function getSMSContentForAssign($params)
	{
		if(is_array($params) && $params)
		{
			$message = "Your JS Exclusive Advisor is '".$params["EXECUTIVE_NAME"]."' (Phone: +91".$params["EXECUTIVE_PHONE"]."). Send ID proof to ".$params["EXECUTIVE_EMAIL"].", ignore if already sent.";
		}
		else
			$message = "";
		return $message;
	}

	public static function mapColumnsToActualValues($valueArr,$columnsToBeMapped)
	{
		foreach ($columnsToBeMapped as $key => $value) 
		{	
			if($value=="SERVICEID")
			{
				$membership = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $valueArr[$value]);
				$valueArr[exclusiveMemberList::$specificColumnMapping[$value]] = $membership[1]; 
			}
			else
				$valueArr[$value] = FieldMap::getFieldLabel(exclusiveMemberList::$specificColumnMapping[$value],$valueArr[$value]);
		}
		return $valueArr;            
	}
}

class fsoInterfaceDisplay
{
    public static $linksMapping = array(
    									array("linkid"=>0,"linkname"=>"Upload profile verification documents"),
    									array("linkid"=>1,"linkname"=>"Sync profile verification documents"),
    									array("linkid"=>2,"linkname"=>"Edit address of profile"),
    									array("linkid"=>4,"linkname"=>"Agent Checkin/Checkout"),
    									array("linkid"=>3,"linkname"=>"Logout")
										);

    public static $visitInterfacecolumnLabels = array("Username","Contact No","Email ID","Location","Requested By","Requested Visit Date","Action"); 
}

class crmCommonConfig
{
	public static $useCrmMemcache = false;
}

?>
