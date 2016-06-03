<?php
/**
 * @brief This class is used to handle all functionalities related to Photo requests
 * @author Pankaj Khandelwal
 * @created 2013-09-20
 */

class PhotoRequest { 

	/*
	 * @function getPhotoRequestCount
	 * @param: profileid skipcontact type in array
	 * @return PhotoRequest count
	 * @description function to get the photo request count for given profile id
	 * it will return in array as total count and unseen count
	 * */
	public function getPhotoRequestCount($profileid,$skipProfile='') {
		$dbName = JsDbSharding::getShardNo($profileid);
		$photoRequestObj = new NEWJS_PHOTO_REQUEST($dbName);
		$photoRequestedCount = $photoRequestObj->getPhotoRequestCount($profileid,$skipProfile);
		return $photoRequestedCount;
	}

	/*
	 * @function getPhotoRequestSentCount
	 * @param: profileid 
	 * @return PhotoRequestSent count
	 * @description function to get the sent photo request count to given profile id
	 * it will return in array as total count
	 * */
	public function getPhotoRequestSentCount($profileid,$skipProfile='') {
		$dbName = JsDbSharding::getShardNo($profileid);
		$photoRequestObj = new NEWJS_PHOTO_REQUEST($dbName);
		$photoRequestedCount = $photoRequestObj->getPhotoRequestSentCount($profileid,$skipProfile);
		return $photoRequestedCount;
	}
	
	public function getPhotoRequestProfile($profileId, $condition, $skipArray)
	{
		$dbName = JsDbSharding::getShardNo($profileId);
		$photoRequestObj = new NEWJS_PHOTO_REQUEST($dbName);
		$photoRequestedProfileArray = $photoRequestObj->getPhotoRequestProfile($condition,$skipArray);
		return $photoRequestedProfileArray;
	}
    
    /*
	This function is used to fetch profiles which have sent photo request to given profile
	@param - $profileId, $condition, $skipArray
	@return - photoRequestedSentProfileArray
	*/
	public function getPhotoRequestSentProfile($profileId, $condition, $skipArray)
	{
		$dbName = JsDbSharding::getShardNo($profileId);
		$photoRequestObj = new NEWJS_PHOTO_REQUEST($dbName);
		$photoRequestedSentProfileArray = $photoRequestObj->getPhotoRequestProfile($condition,$skipArray);
		return $photoRequestedSentProfileArray;
	}

	/*
	This function is used to perform insert or update query on newjs.PHOTO_REQUEST table on shards
	@param - sender profileid and receiver profile id
	@return - 1(insert), 2(update), 0(no action), inconsistent(if output of one of the shards is diff from other)
	*/
	public function insertOrUpdate($senderId,$receiverId)
	{
		$senderDbName = JsDbSharding::getShardNo($senderId);
           	$receiverDbName = JsDbSharding::getShardNo($receiverId);

		$param["PROFILEID"] = $senderId;
		$param["PROFILEID_REQ_BY"] = $receiverId;
		
		$photoRequestObj = new NEWJS_PHOTO_REQUEST($senderDbName);
		$count1 = $photoRequestObj->insertOrUpdate($param);		//count1 = 1 on insert, 2 on update and 0 if no affect by query
		unset($photoRequestObj);

		if($senderDbName!=$receiverDbName)
		{
			$photoRequestObj = new NEWJS_PHOTO_REQUEST($receiverDbName);
			$count2 = $photoRequestObj->insertOrUpdate($param);		//count2 = 1 on insert, 2 on update and 0 if no affect by query
			unset($photoRequestObj);
		}
		else
			$count2 = $count1;

		if($count1 == $count2)
			return $count1;
		else
			return "inconsistent";
	}
	
	
	public function getPhotoRequestCommunication($viewer,$viewed){
		$dbName = JsDbSharding::getShardNo($viewer);
		$photoRequestObj = new NEWJS_PHOTO_REQUEST($dbName);
		$output = $photoRequestObj->getPhotoRequestCommunication($viewer,$viewed);
		return $output;
	}
}
