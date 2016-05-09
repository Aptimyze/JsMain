<?php
class PhotoMessagesEnum
{
	const VISIBLE_ON_ACCEPT = "Visible on acceptance of interest";
	const LOGIN_TO_VIEW = "Login to view photo";
	const COMING_SOON = "Photo coming soon";
	const PROFILE_FILTERED = "Profile filtered"; 
	const PHOTO_REQUESTED = "Photo requested";
	const PHOTO_REQ = "Request photo";
	const NO_PHOTO = "No photo";

	const PHOTO_REQUEST_SUCCESS = "You have successfully requested this user for photo";
	const PHOTO_REQUEST_SAME_GENDER = "You cannot request photo to a profile of the same gender";
	const PHOTO_REQUEST_FILTERED_PROFILE = "You cannot request photo as this person has filtered you";
	const PHOTO_REQUEST_LIMIT_EXCEEDED = "You have already requested this user for photo";
	const PHOTO_REQUEST_SENDER_NOT_ACTIVATED = "You cannot request photo as your profile is still being screened";
	const PHOTO_REQUEST_LOGOUT = "You have to login to request photo";

	const PHOTO_REQUEST_SAME_GENDER_HEADER = "Gender incompatible";
	const PHOTO_REQUEST_FILTERED_PROFILE_HEADER = "Profile filtered";
        const PHOTO_REQUEST_BLOCKED = "Blocked Profile";
        const PHOTO_REQUEST_HIDDEN = "Hidden Profile";
        const PHOTO_REQUEST_DELETED = "Deleted Profile";
	const PHOTO_REQUEST_LIMIT_EXCEEDED_HEADER = "Limit exceeded";
	const PHOTO_REQUEST_SENDER_NOT_ACTIVATED_HEADER = "Profile is under screening";
	const PHOTO_REQUEST_SUCCESS_HEADER = "Photo Requested";
	const PHOTO_REQUEST_INITIATE_HEADER = "Request Photo";
        
        //Blocked
        const PHOTO_REQUEST_PG_BLOCKED_POG = "You cannot request photo as you have blocked <PoGID>";
	const PHOTO_REQUEST_POG_BLOCKED_PG = "You cannot request photo as <PoGID> has blocked you";
        const PHOTO_REQUEST_POG_IS_HIDDEN = "You cannot request photo as the profile of <PoGID> is hidden";
        const PHOTO_REQUEST_POG_IS_DELETED = "You cannot request photo as the profile of <PoGID> has been deleted";
}
?>
