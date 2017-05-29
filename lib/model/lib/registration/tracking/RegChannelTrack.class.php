<?php
/**
  This class will be used for entering values in database for tracking user events based on channel.
*/
class RegChannelTrack
{
  /**
    This function is used to insert a record for a profile id and a page type 
  * including the channel from which the entry has been made.
   *@param- profile id, page which has been completed, channel through which page has been accessed
  */
	public static function insertPageChannel($profileid,$page,$channel="")
	{
		if($channel=="" && $page!="phoneVerified")
			$channel=CommonFunction::getChannel();
		$channelannelTrack = new REG_TRACK_CHANNEL();
		$channelannelTrack->insert($profileid,$page,$channel);
	}	
}