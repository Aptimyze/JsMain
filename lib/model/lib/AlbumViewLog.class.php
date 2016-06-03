<?php
/**
 * @brief This class is used to handle all functionalities related MIS_VIEW_ALBUM_LOG class
 * @author Reshu Rajput
 * @created 2013-04-05
 */

class  AlbumViewLog
{
	 /** Function insertRecord added by Reshu
        This function is used to insert record in the file.
        * @param  profileId whose album is viewed,
        * @param  source from where album is viewed.It recieve http referer and split into real source.Source can be either of the following:
        * 'S' for Search,'C' for  My Contact,'E' for Confirmation Page, and 'D' for Profile Page/Social Page
        * @param  count is number of photos given profile id have in album
        **/

        public function misViewAlbumInsert($profileID,$referer,$count)
        {
	
		$refererSplit=explode("?",$referer);
		$source="";
		if(strpos($refererSplit[0],"viewprofile.php")>0 || strpos($refererSplit[1],"profile/albumpage")>0 || strpos($refererSplit[0],"-profiles")>0 )
		{
			$source=VIEW_ALBUM_SOURCE_ENUM::PROFILE;
		}
		elseif( strpos($refererSplit[0],"/search/")>0 || strpos($refererSplit[0],"search.php")>0)
		{
			$source=VIEW_ALBUM_SOURCE_ENUM::SEARCH;
		}
		elseif( strpos($refererSplit[0],"contacts_")>0)
                {
                        $source=VIEW_ALBUM_SOURCE_ENUM::CONTACT;
                }
		elseif( strpos($refererSplit[0],"view_similar_profile.php")>0)
                {
                        $source=VIEW_ALBUM_SOURCE_ENUM::SIMILAR;
                }
		else
		{
			return ; // Any other source except the required ones we need not to enter in the table
		}
		$viewerProfileObj=LoggedInProfile::getInstance('newjs_master');
                $viewerProfileid = $viewerProfileObj->getPROFILEID();
		if($viewerProfileid==$profileID)
			return; // If login user is viewing its own album 
		
		$MISviewLogObject = new MIS_VIEW_ALBUM_LOG();
		$MISviewLogObject->insertRecord($profileID,$viewerProfileid,$source,$count);
	}
}
?>
