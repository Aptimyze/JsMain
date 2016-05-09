<?php
class LinkFactory{
	public static function getLink($id){
		
		switch($id){
		case 2: //Detailed_PROFILE_LINK
		case 23: //EXPRESS_INTEREST
		case 1: //PHOTO_ALBUM page
		case 54:
		
			return new DetailedViewLink($id);
			break;
		/*case 21:
			return new MailLink($id);
			break;*/
		case 4:
			return new MembershipLink($id);
			break;
		case 26:
			return new CompleteProfileLink($id);
			break;
		case 8:
		case 9:
		case 10:
		case 11:
			return new ResponseTrackingLink($id);
			break;
		default:
			return new LinkClass($id);
		}
	}
   }
