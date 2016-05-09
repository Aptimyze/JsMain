<?php
class uploadVD
{
    const EMPTY_SOURCE = 1;  //not used
    const INCOMPLETE_UPLOAD = 2;  //not used
    const COMPLETE_UPLOAD = 3;   //not used
    public static $RECORDS_SELECTED_PER_TRANSFER = 5000;
}

class exclusiveMemberList
{
	public static $TYPE_TABID_MAPPING = array("ASSIGNED"=>array("TABID"=>0,"ACTION"=>"UNASSIGN","NAME"=>"Assigned customers"),
											"PENDING"=>array("TABID"=>1,"ACTION"=>"ASSIGN","NAME"=>"Pending customers")
											);
	public static $displayColumnsNames = array("Username","Contact No","Email","Date Of Billing","Executive","Action");

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
}

class fsoInterfaceDisplay
{
    public static $linksMapping = array(
    									array("linkid"=>0,"linkname"=>"Upload profile verification documents"),
    									array("linkid"=>1,"linkname"=>"Sync profile verification documents"),
    									array("linkid"=>2,"linkname"=>"Edit address of profile"),
    									array("linkid"=>3,"linkname"=>"Logout")
										);

    public static $visitInterfacecolumnLabels = array("Username","Contact No","Email ID","Location","Requested By","Requested Visit Date","Action"); 
}

class crmCommonConfig
{
	public static $useCrmMemcache = true;
}

?>
