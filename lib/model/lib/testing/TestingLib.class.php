<?php
class TestingLib
{
	public static function getSenderReceiver($contactStatus,$folder)
	{
		if(($contactStatus=="I"&&$folder=="AWAITING_RESPONSE")||($contactStatus=="A" && $folder="I_ACCEPTED")||($contactStatus=="D"&&$folder=="I_DECLINED"))
		{
			$return['loggedIn']="RECEIVER";
			$return['other'] = "SENDER";
		}
		elseif(($contactStatus=="I"&&$folder=="YET_TO_RESPOND")||($contactStatus=="A" && $folder=="ACCEPTED_ME")||($contactStatus=="D" && $folder=="NOT_INTERESTED_IN_ME"))
		{
			$return['loggedIn']="SENDER";
			$return['other'] = "RECEIVER";
		}
		return $return;
	}

}
