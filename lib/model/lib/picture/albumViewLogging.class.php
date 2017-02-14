<?php

//This class is called to log profile album being viewed
class albumViewLogging
{	
	public function logProfileAlbumView($loggedInProfileid,$profileid,$date,$channel)
	{
		$albumViewLogObj = new PICTURE_ALBUM_VIEW_LOGGING();
		$albumViewLogObj->insertLogEntry($loggedInProfileid,$profileid,$date,$channel);
	}
}