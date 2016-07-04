<?php
/**
 * @brief This class is used to handle all functionalities related to Bookmarks
 * @author Prinka Wadhwa
 * @created 2012-08-16
 */

class Bookmarks
{
	public function  getProfilesBookmarks($bookmarker, $bookmarkee, $key='')
	{
		$bookmarkObj = new NEWJS_BOOKMARKS();
		$bookmarks = $bookmarkObj-> getProfilesBookmarks($bookmarker, $bookmarkee, $key);
		return $bookmarks;
	}

	public function  addBookmark($bookmarker, $bookmarkee, $note='')
	{
		$bookmarkObj = new NEWJS_BOOKMARKS();
		$bookmarks = $bookmarkObj->addBookmark($bookmarker, $bookmarkee, $note);
		//Roster queue for shortlisted members
		$producerObj=new Producer();
		if($producerObj->getRabbitMQServerConnected())
		{
			$chatData = array('process' =>'CHATROSTERS','data'=>array('type' => 'SHORTLIST','body'=>array('senderid'=>$bookmarker,'receiverid'=>$bookmarkee) ), 'redeliveryCount'=>0 );
			$producerObj->sendMessage($chatData);
		}
		return $bookmarks;
	}
	public function getBookmarkCount($bookmarker,$skipArray=null)
	{
		$bookmarkObj = new newjs_BOOKMARKS();
		$count = $bookmarkObj->getBookmarkCount($bookmarker,$skipArray);
		return $count;
	}
	public function getBookmarkedProfile($profileid,$condition, $skipArray) {
		$bookmarkObj = new newjs_BOOKMARKS();
		$bookmarkedProfile = $bookmarkObj->getBookmarkedProfile($profileid,$condition,$skipArray);
		return $bookmarkedProfile;
	}
	public function  getBookmarkDetails($bookmarker, $bookmarkee)
	{
		$bookmarkObj = new NEWJS_BOOKMARKS();
		$bookmarks = $bookmarkObj-> getBookmarkDetails($bookmarker, $bookmarkee);
		return $bookmarks;
	}
	public function  removeBookmark($bookmarker, $bookmarkee)
	{
		$bookmarkObj = new NEWJS_BOOKMARKS();
		$bookmarks = $bookmarkObj->removeBookmark($bookmarker, $bookmarkee);
                //Roster queue - Remove profile from shortlisted roster
		$producerObj=new Producer();
                if($producerObj->getRabbitMQServerConnected())
                {
                        $chatData = array('process' =>'CHATROSTERS','data'=>array('type' => 'SHORTLIST_REMOVE','body'=>array('senderid'=>$bookmarker,'receiverid'=>$bookmarkee) ), 'redeliveryCount'=>0 );
                        $producerObj->sendMessage($chatData);
                }
		return $bookmarks;
	}
}
?>
