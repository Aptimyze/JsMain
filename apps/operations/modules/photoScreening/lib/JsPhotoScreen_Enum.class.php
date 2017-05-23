<?php
class JsPhotoScreen_Enum
{	
	//Notify Enum
	const enNOTIFY_CHANNEL_MAIL			= 1;
	const enNOTIFY_CHANNEL_SMS			= 2;
	const enNOTIFY_CHANNEL_MAIL_SMS 	= 3;
	
	//Tracking related enums
	const enTRACK_SOURCE_NEW			= 4;
	const enTRACK_SOURCE_EDIT			= 5;
	const enTRACK_SOURCE_APP			= 6;
	const enTRACK_SOURCE_MAIL			= 7;
	const enTRACK_SOURCE_END			= 8;
	
	const szTRACK_SOURCE_NEW			= "new";
	const szTRACK_SOURCE_EDIT			= "edit";
	const szTRACK_SOURCE_MAIL			= "mail";
	const szTRACK_SOURCE_APP_PIC		= "appPic";
	
	public static $enSCREENED_PIC		= 'S';
	public static $enUNSCREENED_PIC		= 'N';
	
	const YES							= 'Y';
	
	//Have Photo Statis
	const enHAVE_PHOTO_YES				= 'Y';
	const enHAVE_PHOTO_UNDER_SCREEN 	= 'U';
	const enHAVE_PHOTO_NO				= 'N';
	public static $enHAVE_PHOTO_NO  	= array('N','U','');
	
	const szSMS_MSG_ACCEPT				= 'accepted';
	const szSMS_MSG_REJECT				= 'rejected';
	
	//Mailer
	const PHOTO_UPLOADED_MAILER			= 1741;
	const PHOTO_REJECT_MAILER			= 1743;
	const PHOTO_UPLOAD_MAX_MAILER		= 1744;
	
	//Master Operation
	const PHOTO_SCREEN_MASTER_INSERT 	= 0;
	const PHOTO_SCREEN_MASTER_UPDATE 	= 1;
	
	//Photo Screen Status
	const PHOTO_SCREEN_STATUS_INCOMPLETE	= 0;
	const PHOTO_SCREEN_STATUS_COMPLETE 		= 1;

	public static $arrTRACKING_PARAMS	 = array(
										'EXECUTIVE_NAME'=>"EXECUTIVE_NAME",
										'PROFILEID'=>"PROFILEID",
										'NUM_APPROVED_PIC'=>'NUM_APPROVED_PIC',
										'NUM_DELETED_PIC'=>'NUM_DELETED_PIC',
										'NUM_EDIT_PIC'=>'NUM_EDIT_PIC',
										'NUM_MAIL_APPROVED_PIC'=>'NUM_MAIL_APPROVED_PIC',
										'NUM_MAIL_DELETE_PIC'=>'NUM_MAIL_DELETE_PIC',
										'SOURCE'=>'FIRST_SOURCE',
										'SECOND_SOURCE'=>'SECOND_SOURCE',//SOURCE REQUIRED IN CASE OF FIRST_SOURCE='mail'
										'EMAIL_ID'=>'EMAIL_ID',//Required in case of Mail 
										'STATUS_MSG'=>'STATUS_MSG',
										'PIC_DATA'=>'PIC_DATA',
										'TRACK_WRONG_ENTRY'=>'TRACK_WRONG_ENTRY',
										'INTERFACE'=>'INTERFACE',
										'MASTER_TRACK_NEEDED'=>'MASTER_TRACK_NEEDED',
                                        'PHOTO_UPLOAD_TIME'=>'PHOTO_UPLOAD_TIME',
										);
	
	public static $arrNOTIFY_PARAMS		= array(
										'NOTIFY_CHANNEL'=>'NOTIFY_CHANNEL',
										'PROFILEID'=>'PROFILEID',
										'NUM_APPROVED_PIC'=>'NUM_APPROVED_PIC',
										'NUM_DELETED_PIC'=>'NUM_DELETED_PIC',
										'NUM_UPLOADED_PIC'=>'NUM_UPLOADED_PIC',
										'REJECT_REASON'=>'REJECT_REASON',
										);
	public static $arrMAIL_PARAMS 		= array(
										'PHOTOS_UPLOADED'=>'PHOTOS_UPLOADED',
										'PHOTOS_SCREENED'=>'PHOTOS_SCREENED',
										'PHOTOS_REJECTED'=>'PHOTOS_REJECTED',
										'TOTAL_PHOTOS_NOW'=>'TOTAL_PHOTOS_NOW',
										'REJECT_REASON'=>'REJECT_REASON',
										'MALER_TYPE'=>'MALER_TYPE',
										);
										
	const	PIC_STATUS_UPLOAD_COMPLETED				= 0;
	const	PIC_STATUS_RESIZE_CRON_COMPLETED		= 1;
	const	PIC_STATUS_FACE_CRON_COMPLETED			= 2;
	const	PIC_STATUS_ACEEPT_REJECT_Q_DONE			= 3;
	const	PIC_STATUS_DECISION_DONE				= 4;	
	const	PIC_STATUS_CANT_DETERMINE				= 5;	
}
?>
