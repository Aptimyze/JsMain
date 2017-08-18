<?php
// @package    operations
// @subpackage Dialer
// @author     Manoj 

class dialerIvrUpdateAction extends sfActions
{
	/**
	  * Executes backend login action
	  *
	  * @param sfRequest $request A request object
	**/
	function execute($request){

		$fieldName 	=$request->getParameter("fieldName");
		$fieldValue 	=$request->getParameter("fieldValue");
		$username	=$request->getParameter("username");

		/* Parameter:
			1. INFO_DTOFBIRTH
			2. INFO_GENDER
			3. NFO_MSTATUS
			4. INFO_RELIGION
			5. DELETE
		*/
		$fieldNameMapping =array("INFO_DTOFBIRTH"=>"Date Of Birth",
					 "INFO_GENDER"=>"Gender",
					 "NFO_MSTATUS"=>"Martail Status",
					 "INFO_RELIGION"=>"Religion",
					 "DELETE"=>"Profile Deleted"
					);
		if(!array_key_exists("$fieldName",$fieldNameMapping) || !$fieldValue)
			die('Invalid Requiest');

		$field =$fieldNameMapping[$fieldName];

		$subject ="Update Info for User: $username";
		$msg	.="\nPlease find the details below to update:";
		$msg	.="\n $field : $fieldValue";	

		if($subject && $msg)
			$this->sendMail($subject,$msg);

		echo "Success";
		die();
	}
	public function sendMail($subject,$msg)
	{
		$to 		="help@jeevansathi.com";
		$from		="info@jeevansathi.com";
		$cc		="manoj.rana@naukri.com,ankit.jadiya@jeevansathi.com,anant.gupta@naukri.com";
		SendMail::send_email($to,$msg,$subject,$from,$cc);
		
	}
}
?>
