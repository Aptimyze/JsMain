<?php
/**
 * @brief This class is used to handle a facebook user's album data
 * @author Prinka Wadhwa
 * @created 2012-12-10
 */

class FacebookAlbumsData
{
	/**
	  * This function is used to enter a logged in user's album id and no of photos in newjs.FACEBOOK_ALBUM_DETAILS
	**/
	public function insertAlbumsData($albumDataArray)
	{
		$profileObj=LoggedInProfile::getInstance('newjs_master');
		$profileid = $profileObj->getPROFILEID();
		if(is_array($albumDataArray))
		{
			$albumDetailsObj = new FACEBOOK_ALBUM_DETAILS();
			$albumDetailsObj->insertAlbumData($albumDataArray,$profileid);
		}
	}

	/**
	  * This function is used to get no of photos present in a facebook album whose album id is passed
	**/
	public function getAlbumData($aid)
	{
		$profileObj=LoggedInProfile::getInstance('newjs_master');
		$profileid = $profileObj->getPROFILEID();

		$albumDetailsObj = new FACEBOOK_ALBUM_DETAILS();
		$result = $albumDetailsObj->getAlbumData($profileid,$aid);

		return $result;
	}

	/**
	  * This function is used to delete all album entries for a logged in user.
	**/
	public function deleteAlbumData()
	{
		$profileObj=LoggedInProfile::getInstance('newjs_master');
		$profileid = $profileObj->getPROFILEID();

		$albumDetailsObj = new FACEBOOK_ALBUM_DETAILS();
		$albumDetailsObj->deleteProfilesEntries($profileid);
	}
}
?>
