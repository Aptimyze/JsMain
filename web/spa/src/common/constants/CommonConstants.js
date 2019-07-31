export const SET_AUTHCHECKSUM =  'SET_AUTHCHECKSUM';
export const AUTHCHECKSUM =  'AUTHCHECKSUM';
export const LOGIN_ATTEMPT_COOKIE =  'loginAttemptNew';
export const DISPLAY_PROPS = {windowWidth : window.innerWidth};
window.addEventListener('resize',()=>{DISPLAY_PROPS.windowWidth = window.innerWidth;});
export const LOGGED_OUT_PAGE = ['/profile/viewprofile.php', '/PageNotFound', 'search/topSearchBand'];
export const SPA_PAGE = ['/', '/profile/viewprofile.php', '/PageNotFound', '/login', '/myjs', '/social/MobilePhotoAlbum', '/static/forgotPassword', '/static/forgotPasswordV2', '/static/resetPassword', 'profile/mainmenu.php', 'search/topSearchBand', 'P/logout.php', 'mobile_view', 'jsmb/login_home.php', 'inbox', 'search', 'search/MobSaveSearch', 'success_stories', 'profile/dpp', 'homepage', '/deleteProfile', 'register/page1', 'register/newjsms', 'register/newjsmsreg', 'register/customreg/', 'register/family', 'register/registration_new','profile/registration_new.php'];
export const JSB9_UNLOAD_TRACKING = true;
export const RESPONSE_STATUS_MESSAGE_PUSH_MESSAGE = ["Something went wrong. Please try again later."];
export const LISTING_PAGE_LIMIT = 10;
export const THRESHOLD_LISTING_DATA = 3000;
export const THRESHOLD_LISTING_DATA_TIME_SECONDS = 1500;
export const PROFILE_TUPLE_CAP = 25;

export const DEFAULT_LISTING_PIC_MALE = 'https://static.jeevansathi.com/images/picture/450x600_m.png?noPhoto';
export const DEFAULT_LISTING_PIC_FEMALE = 'https://static.jeevansathi.com/images/picture/450x600_f.png?noPhoto';

export const THRESHOLD_LISTING_DATA_TIME_SECONDS_LISTING = [['4','justJoinedMatches'],['1', '6', '12','27', '2', '3', '10', '20', '17', '9','23','22'],[]];

export const THRESHOLD_LISTING_DATA_TIME_SECONDS_ARRRAY = [0,120,600];

export const CONTACT_ENGINE_ACTION_LISTING_BURST = {
	INITIATE: ['visitors', 'shortlisted', 'partnermatches', 'twowaymatch', 'reverseDpp', 'kundlialerts', 'verifiedMatches', 'matchalerts','6','QuickSearchBand','visitors_A','visitors_M'],
	CANCEL: ["11",'2',"6"],
	SHORTLIST: ["shortlisted"],
	DECLINE: ["11","1","3","23","22","12","27"],
	ACCEPT: ["3","1","11","23","22","12","27"],
	IGNORE: ['1', '6', '12','27', '2', '3', '11', '10', '20', '4', '17', '9','24','visitors', 'shortlisted', 'MobSaveSearch', 'partnermatches', 'twowaymatch', 'reverseDpp', 'kundlialerts', 'verifiedMatches', 'matchalerts', 'justJoinedMatches','23','22','QuickSearchBand','visitors_A','visitors_M'],
	REPORT_ABUSE: ['1', '6', '12','27', '2', '3', '11', '10', '20', '4', '17', '9','24','visitors', 'shortlisted', 'MobSaveSearch', 'partnermatches', 'twowaymatch', 'reverseDpp', 'kundlialerts', 'verifiedMatches', 'matchalerts', 'justJoinedMatches','23','22','QuickSearchBand','visitors_A','visitors_M'],
	CONTACT_DETAIL:["16"],
	WRITE_MESSAGE:["4"]
};

export const STOPLISTINGBURST = ['CONTACT_DETAIL', 'REPORT_INVALID','SHORTLIST'];
export const STOPLISTINGBURST_SELF = {'CONTACT_DETAIL':"16",'SHORTLIST':"shortlisted"};


export const AADHAAR_LAYER_DATA_OCB = {
  LAYERID:"24",
  TIMES:"255",
  MINIMUM_INTERVAL:"7",
  BUTTON1:"Verify",
  BUTTON2:"Skip",
  ACTION1:"close",
  ACTION2:"close",
  JSMS_ACTION1:"\/",
  JSMS_ACTION2:"\/",
  UNLIMITED:"Y",
  BUTTON2_URL_ANDROID:"/api/v1/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
  CalTracking:"N",
  };


// ['visitors', 'shortlisted', 'MobSaveSearch', 'partnermatches', 'twowaymatch', 'reverseDpp', 'kundlialerts', 'verifiedMatches', 'matchalerts', 'justJoinedMatches']

//search listings: MobSaveSearch

// interest listing list: 1:received,6:sent,12:filtered,2:accepted by them,3:I accepted,11:I Declined,10:Declined by them,20:Block,4:Message,17:Who Viewed My contacts,9:Photo Requests.

// ['1', '6', '12','27', '2', '3', '11', '10', '20', '4', '17', '9','22','23','visitors', 'shortlisted', 'MobSaveSearch', 'partnermatches', 'twowaymatch', 'reverseDpp', 'kundlialerts', 'verifiedMatches', 'matchalerts', 'justJoinedMatches']


export const DPP_FIELDS = ["P_AGE","P_HEIGHT","P_COUNTRY","P_CITY","P_MSTATUS","P_RELIGION","P_CASTE","P_MTONGUE","P_EMPLOYED_IN","P_OCCUPATION_GROUPING","P_EDUCATION","P_INCOME","P_MANGLIK","P_CASTE_MAPPING"];

export const LIST_OF_SHOW_MESSAGE_LISTING = ["1","12","22","shortlisted","17","23"];

export const FINGER_PRINT_CONFIG = {
	SETTINGS: {
		detectScreenOrientation: false,
		// excludeDoNotTrack
		excludePixelRatio : true,
		// excludeUserAgent
		// excludeLanguage
		excludeColorDepth : true,
		// excludeDeviceMemory
		excludeScreenResolution : true,
		excludeAvailableScreenResolution : true,
		// excludeTimezoneOffset
		// excludeSessionStorage
		// excludeSessionStorage
		// excludeIndexedDB
		// excludeAddBehavior
		// excludeOpenDatabase
		// excludeCpuClass
		// excludePlatform
		// excludeDoNotTrack
		// excludeCanvas
		// excludeWebGL
		// excludeWebGLVendorAndRenderer
		// excludeAdBlock
		// excludeHasLiedLanguages
		excludeHasLiedResolution : true,
		// excludeHasLiedOs
		// excludeHasLiedBrowser
		// excludeJsFonts
		// excludeFlashFonts
		// excludePlugins
		// excludeTouchSupport
		// excludeHardwareConcurrency
	},
	COOKIE_NAME: "_jsdid"
};

