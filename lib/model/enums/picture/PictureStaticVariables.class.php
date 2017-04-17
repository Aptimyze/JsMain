<?php


/* 
 * Different static picture variables.
 */
class PictureStaticVariablesEnum
{       
        const MAX_PICTURE_COUNT = 20;
	const MAX_PICTURE_SIZE = 6000000; // 6 MB
	const nonScreenedPhotos = 'N';
	const screenedPhotos = 'S';
	const nonScreened = 0;
	const Screened = 1;
	const maxPhotoSize = 6291456;//6*1024*1024
	const maxNumberOfPhotos = 20;
	const profilePicOrdering = 0;
        const photoLoggingMod=1;
        const photoLoggingRem=0;
	public static $photoFormats = array("jpeg", "gif", "jpg");
	public static $orientationToAngle= array("6"=>"-90","3"=>"180","8"=>"90");
	public static $PICTURE_ALLOWED_FORMATS = array("image/gif","image/jpg","image/jpeg");
        public static $DELETE_REASONS = array("The photo is not clear.",
                                           "We find that the photo you have submitted is inappropriate.",
                                           "The photo is of a well known personality. If it is yours, submit an identity.",
					   "Gender not proper.",
					   "Group photo.",
					   "Age not proper/Age does not match.",
					   "Obscene photo.",
					   "Side face.",
					   "Attachment error.",
					   "Small size / size is not proper.",
					   "Repeated photo.",
					   "Edited/Morphed photo.",
					   "Watermarked photo."	);
        
        public static $PICTURE_STATUS = array("UPLOAD_COMPLETED",
                                              "RESIZE_CRON_COMPLETED",
                                              "FACE_CRON_COMPLETED",
                                              "PROCESS_QUEUE",
                                              "DECISION_DONE",
                                              "CANT_DETERMINE");

        const APP_ALLOT_TIME_INTERVAL = 30;
        const PROCESSING_ALLOT_STATUS = "E";
        const SCREENING_TYPE = "P";
        public static $HAVE_PHOTO_STATUS = array("UNDERSCREENING"=>"U",
                                              "YES"=>"Y",
                                              "NO"=>"N");
        public static $SOURCE = array("NEW"=>"new",
                                      "EDIT"=>"edit",
                                      "MASTER" => "master",
                                      "MAIL"=>"mail");
	

		const	PIC_STATUS_UPLOAD_COMPLETED				= 0;
		const	PIC_STATUS_RESIZE_CRON_COMPLETED		= 1;
		const	PIC_STATUS_FACE_CRON_COMPLETED			= 2;
		const	PIC_STATUS_ACEEPT_REJECT_Q_DONE			= 3;
		const	PIC_STATUS_DECISION_DONE				= 4;	
		const	PIC_STATUS_CANT_DETERMINE				= 5;
		//Master Operation
		const PHOTO_SCREEN_MASTER_INSERT 	= 0;
		const PHOTO_SCREEN_MASTER_UPDATE 	= 1;
		
		const PHOTO_SCREEN_OPERATION_PREPROCESS_CRON		= 1;
		const PHOTO_SCREEN_OPERATION_FACEDETECTION_CRON		= 2;
		
		public static $arrPHOTO_SCREEN_DEVELOPERS = array(
													'reshu.rajput@gmail.com'
													);

		//variables for photo albums import vertical bar(limit and height)
		public static $importPhotosBarCountPerShift = 3;   //3 albums max per shift
		public static $importPhotosBarHeightPerShift = 404;   //404 px : height per shift
		public static $defaultCoverPhotoUrl = "/images/jspc/viewProfileImg/cover1.jpg";
        public static $defaultViewProfileCoverPhotoUrl = "/images/jspc/viewProfileImg/defaultViewProfileCover.jpg?";
        public static $photoColumnArray = Array("TITLE","KEYWORD","PICTUREID","ORDERING","PROFILEID","PICFORMAT","UPDATED_TIMESTAMP");
}

?>
