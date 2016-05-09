<?php
/**
* This class is used to store common config for forwarding modules in case of mobile site.
*/
class MobileSiteForwardConfig
{
	public static $forwardArr = array("search#perform"=>"search#MobSearch",
									  "search#topSearchBand"=>"search#MobTopSearchBand",
									  "profile#album"=>"social#MobilePhotoAlbum",
									  "social#album"=>"social#MobilePhotoAlbum",
									  ""=>""
									 );
}
?>
