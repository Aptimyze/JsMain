export const REFERRER_SERVER = "https://www.jeevansathi.com";

export const LOGIN_CALL_URL = '/api/v1/api/login';
//Myjs # Head, Edit bar
export const MYJS_CALL_URL = '/api/v1/myjs/perform';
//Myjs # Daily recommendations
export const MYJS_CALL_URL1 = '/api/v1/search/perform';
//Myjs # Match of the day, Interest recieved, Profile Visitor, Interest expiry
export const MYJS_CALL_URL2 = '/api/v2/inbox/perform';
//Myjs # Hamburger counts, Acceptence count, Just joined counts, Bell counts
export const MYJS_CALL_URL3 = '/api/v1/api/hamburgerDetails';
export const EDIT_SUBMIT = '/api/v1/profile/editsubmit';
export const SEND_INTEREST_API = '/api/v2/contacts/postEOI';
export const ACCEPT_API = '/api/v2/contacts/postAccept';
export const DECLINE_API = '/api/v2/contacts/postNotInterested';
export const ABUSE_FEEDBACK_API = '/api/v1/faq/feedbackAbuse';
export const REMINDER_API = '/api/v2/contacts/postSendReminder';
export const CONTACT_ENGINE_API = {"CONTACT_DETAIL":"/api/v2/contacts/contactDetails","INITIATE":"/api/v2/contacts/postEOI","INITIATE_MYJS":"/api/v2/contacts/postEOI","CANCEL":"/api/v2/contacts/postCancelInterest","SHORTLIST":"/api/v1/common/AddBookmark","DECLINE":"/api/v2/contacts/postNotInterested","REMINDER":"/api/v2/contacts/postSendReminder","MESSAGE":"/api/v2/contacts/postWriteMessage","ACCEPT":"/api/v2/contacts/postAccept","WRITE_MESSAGE":"/api/v2/contacts/WriteMessage","IGNORE":"/api/v1/common/ignoreprofile","PHONEVERIFICATION":"/phone/jsmsDisplay","MEMBERSHIP":"/profile/mem_comparison.php","COMPLETEPROFILE":"/profile/viewprofile.php","PHOTO_UPLOAD":'/social/MobilePhotoUpload',"ACCEPT_MYJS":"/api/v2/contacts/postAccept","DECLINE_MYJS":"/api/v2/contacts/postNotInterested","EDITPROFILE":"/profile/viewprofile.php?ownview=1","REPORT_INVALID_API":"/phone/reportInvalid","PRE_MESSAGE":"/api/v2/contacts/PreMessage","WRITENOW":"/profile/viewprofile.php?ownview=1#Details"};
export const PHOTALBUM_API = "/api/v1/social/getAlbum";
export const HINDI_SITE = "https://hindi.jeevansathi.com";
export const MARATHI_SITE = "https://marathi.jeevansathi.com";
export const SITE_URL = "https://www.jeevansathi.com";
export const BROWSWER_NOTIFICATION = "/api/v1/notification/notificationLayerSettings";
export const PROFILE_GUNA_LOCAL_STORAGE_SIZE = 15;
export const PROFILE_GUNA_LOCAL_STORAGE_TIME_LIMIT = 300; //seconds;
export const PROFILE_LOCAL_STORAGE_KEY = "profileLocalStorage";
export const GUNA_LOCAL_STORAGE__KEY = "gunaLocalStorage";
export const COMM_HISTORY = "/api/v1/contacts/history";
export const INBOX_LISTING_API = "/api/v2/inbox/perform?searchId=";
export const SEARCH_LISTING_API = "/api/v2/search/perform?searchBasedParam=";
export const SIMILAR_PROFILE_API = "/api/v3/search/ViewSimilarProfiles";
export const SEARCH_PAGE_LISTING_API = "/api/v1/search/perform";
export const CRITEO_LISTING_API = "/api/v1/search/perform?criteoProfile=1";
export const SAVED_SEARCH_DELETE_API = "/api/v1/search/saveSearchCall?perform=delete";
export const SAVED_SEARCH_LISTING_API = "/api/v1/search/saveSearchCall?perform=listing";
export const SAVED_SEARCH_SAVE_API = "/api/v1/search/saveSearchCall?perform=savesearch";
export const SAVED_SEARCH_CACHING_TIME = 300; //5 minutes
export const DEFAULT_ABBR = {'COUNTRY':{'USA':'United States',"US":"United States","UK":"United Kingdom","UAE":"United Arab Emirates"},'STATE/CITY':{'UP':"Uttar Pradesh",'MP':"Madhya Pradesh"},'CASTE':{"AGRAW":"Aggarwal"}};
export const NO_SORT_ICON = ["1","2","3","6","10","17","4","20","12","11","7","16","9","kundlialerts","shortlisted","visitors","MobSimilarProfiles",'visitors_A','visitors_M','justJoinedMatches',"matchalerts","21","22","23","24","27","25"];
export const FORGOT_PASSWORD_API = "/api/v1/api/forgotPassword";
export const FRESHCHAT_WIDGET_URL = "https://wchat.freshchat.com/js/widget.js";
export const FRESHCHAT_TOKEN = "5b25d6d5-10ea-49e6-8aab-a80c749f5b39";
export const SCHEDULE_VISIT = "/membership/scheduleVisit";
export const SUCCESS_STORIES_API = "/successStory/story?requestType=ajax";
export const SUCCESS_STORIES_MAIN_API = "/successStory/completestory?requestType=ajax";
export const DPP_REG_API = "/api/v1/profile/editprofile?sectionFlag=dpp&internal=1&dppRegJSMS=1";
export const DPP_SUBMIT_API = "/api/v1/profile/dppsubmit";
export const BONUS_RECOMM = "/api/v1/api/bonusRecommendations";
export const AADH_API = "/api/v1/profile/aadharConfirmation";
export const AGGREGAT0R_V0_SERVER = profileServiceCompositor;
export const AGGREGAT0R_PROFILE_URL = "/jsprofile/v0/profiles";
export const PROFILE_URL = "/api/v1/profile/detail";
export const TRACK_PROFILE_URL = "/api/v1/profile/trackProfileView";
export const AGGREGAT0R_PROFILE_FLAG = true;
export const REPORT_ABUSE="/api/v1/faq/ReportAbuseListV1";
export const REGISTER_DATA= "/static/getFieldData";
export const LOGIN_REGISTER_DATA = "/register/newJsmsPage1";
export const ABOUTME_REGISTER_DATA = "/register/newJsmsPage2";
export const ABOUTFAMILY_REGISTER_DATA = "/api/v1/register/page3";
export const CHAT_CONNECTION = "auth/v1/chat";
export const SETTINGS_INFO = "/api/v1/help/helppageRevamp";

