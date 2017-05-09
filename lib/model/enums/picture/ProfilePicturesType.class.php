<?php

/* 
 * Different Types of Profile Pictures.
 */
class ProfilePicturesTypeEnum
{       
        // SCREEN BIT defined by (Key of pictureSizes array + 2)
	public static $PICTURE_SIZES = array("ProfilePic120Url"=>array("w"=>"120","h"=>"120"),
                                            "ProfilePic235Url"=>array("w"=>"235","h"=>"235"),
                                            "ProfilePic450Url"=>array("w"=>"450","h"=>"450"),
                                            "ProfilePicUrl"=>array("w"=>"150","h"=>"200"),
                                            "MobileAppPicUrl"=>array("w"=>"450","h"=>"600")
						);
	public static $tolerance = 0.01;	
						
	public static $PICTURE_SIZES_MIN_WIDTH = array("ProfilePic120Url"=>array("w"=>"120","h"=>"120"),
										"ProfilePic235Url"=>array("w"=>"200","h"=>"200"),
                                            "ProfilePic450Url"=>array("w"=>"200","h"=>"200"),
                                            "ProfilePicUrl"=>array("w"=>"150","h"=>"200"),
                                            "MobileAppPicUrl"=>array("w"=>"200","h"=>"266.6")
						);
						
	 public static $SCREEN_BITS = array("DEFAULT"=>0, 
                                            "FACE"=>1,
                                            "RESIZE"=>1,
                                            "PROCESS"=>2,
                                            "APPROVE"=>2,
                                            "DELETE"=>3,
                                            "EDIT"=>4);
      
        // SCREEN BIT defined by (Key of pictureSizes array + 2)
	public static $SCREEN_BIT_POSITION = array("FACE","MainPicUrl");
	public static $PICTURE_UPLOAD_DIR = array("ProfilePic120Url"=>"profilePic120",
                                            "ProfilePic235Url"=>"profilePic235",
                                            "ProfilePic450Url"=>"profilePic450",
                                            "ProfilePicUrl"=>"profilePic",
                                            "MobileAppPicUrl"=>"mobileAppPic",
					    "OriginalPicUrl"=>"mainPic",
					    "MainPicUrl"=>"newMainPic",
                                            "Thumbail96"=>"thumbnail96",
                                            "Thumbail"=>"thumbnail",
                                            "ThumbailUrl"=>"thumbnail",
                                            "SearchPicUrl"=>"searchPic");
        
        public static $WATERMARK = array("ProfilePic235Url",
                                         "ProfilePic450Url",
                                         "ProfilePicUrl",
                                         "MobileAppPicUrl",
					 "MainPicUrl",
                                         "SearchPicUrl");
        
        public static $PICTURE_WATERMARK = array(2,3,5,7,11,13);
        
        public static $INTERFACE = array("1"=>"AR", 
                                         "2"=>"PROCESS");
	public static $MAIN_PIC_MAX_SIZE = array("w"=>"990","h"=>"512");
	
	public static $PICTURE_SIZES_FIELDS = array("MainPicUrl","OriginalPicUrl","ProfilePic120Url",
						"ProfilePic235Url","ProfilePicUrl","ProfilePic450Url",
						"MobileAppPicUrl","Thumbail96Url","ThumbailUrl","SearchPicUrl");
	public static $PICTURE_SIZES_STOCK = array("ProfilePic120Url",
						"ProfilePic235Url","ProfilePicUrl","ProfilePic450Url",
						"MobileAppPicUrl","ThumbailUrl","SearchPicUrl");
	public static $PICTURE_NONSCREENED_SIZES_FIELDS = array("MainPicUrl","OriginalPicUrl","ProfilePic120Url",
						"ProfilePic235Url","ProfilePicUrl","ProfilePic450Url",
						"MobileAppPicUrl","Thumbail96Url","ThumbailUrl");
	public static $PICTURE_SCREENED_SIZES_FIELDS = array("MainPicUrl","OriginalPicUrl","ProfilePic120Url",
						"ProfilePic235Url","ProfilePicUrl","ProfilePic450Url",
						"MobileAppPicUrl","Thumbail96Url",
						"TITLE","KEYWORD","PICTUREID","ORDERING","PROFILEID","PICFORMAT");

    //mapping of new cropped image size field to existing picture size field(or array of existing ones)
	public static $CROPPED_NONSCREENED_PICTURE_FIELD_MAPPING = array("imgPreviewLG"=>"ProfilePic450Url",
                                                                    "imgPreviewMD"=>"ProfilePicUrl",
                                                                    "imgPreviewSM"=>array("ProfilePic120Url","ThumbailUrl"),
                                                                    "imgPreviewSS"=>"ProfilePic235Url",
                                                                    "imgPreviewXS"=>"MobileAppPicUrl"
                                                                    );
    
    //mapping of new cropped image size fields to preview dimensions(not actual)
    public static $CROPPED_NONSCREENED_PICTURE_SIZE_MAPPING = array("imgPreviewLG"=>array("w"=>"220","h"=>"220"),
                                                                    "imgPreviewMD"=>array("w"=>"150","h"=>"200"),
                                                                    "imgPreviewSM"=>array("w"=>"87","h"=>"87"),
                                                                    "imgPreviewSS"=>array("w"=>"150","h"=>"150"),
                                                                    "imgPreviewXS"=>array("w"=>"117","h"=>"117")
                                                                    );

    //mapping of image size fields to dimensions(actual)
    public static $CROPPED_NONSCREENED_PICTURE_SIZES = array("ProfilePic120Url"=>array("w"=>"120","h"=>"120"),
                                                            "ProfilePic235Url"=>array("w"=>"235","h"=>"235"),
                                                            "ProfilePic450Url"=>array("w"=>"450","h"=>"450"),
                                                            "ProfilePicUrl"=>array("w"=>"150","h"=>"200"),
                                                            "MobileAppPicUrl"=>array("w"=>"450","h"=>"600"),
                                                            "ThumbailUrl"=>array("w"=>"60","h"=>"60"),
							    "MainPicUrl"=>array("w"=>"990","h"=>"512")
                        );
    //This array is to be used in a oneTimeCron checkPhotoUrlTask where the ordering != 0
    public static $PICTURE_FIELD_FOR_ALBUM_PICS = array("MainPicUrl","OriginalPicUrl",
                        "Thumbail96Url");
        
        
}

