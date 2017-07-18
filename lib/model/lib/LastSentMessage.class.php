<?php

class LastSentMessage{
	
	private $message;

	public function __construct()
	{

	}

	public function insertMessage($profileid, $type,$message)
	{
		$lastSentMessageObj = new NEWJS_LASTSENTMESSAGE();
		$lastSentMessageObj->insert($profileid,$type,$message);
	}

	public static function getLastSentMessage($profileid,$type)
	{
		$lastSentMessageObj = new NEWJS_LASTSENTMESSAGE();
		$lastSentMessage = $lastSentMessageObj->getLastSentMessage($profileid,$type);
		return html_entity_decode($lastSentMessage);
	}
}