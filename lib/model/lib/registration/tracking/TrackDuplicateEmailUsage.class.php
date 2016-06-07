<?php

/**
  This class will be used for entering values in database for tracking user events based on channel.
*/
class TrackDuplicateEmailUsage
{
  /**
    This function is used to insert a record for a email id
  * including the channel from which the entry has been made.
   *@param- email id for which entry is to be made
  */
	public static function insertEmailEntry($email)
	{
                $channel=CommonFunction::getChannel();
                if(!$channel)
                    $channel = "Offline";
		$duplicateEmailTrack = new REGISTER_TRACK_REUSAGE_EMAIL_DELETED();
		$duplicateEmailTrack->insert($email,$channel);
	}	
}
