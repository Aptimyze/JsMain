<?php

/**
 * This classs list all the configuration excepet mysql configuration.
 */
class JsConstants
{
	public static $whichMachine = 'local';
	public static $localHostIp = "127.0.0.1";
	public static $crmUrl = 'http://crm.jeevansathi.com';
	public static $siteUrl = 'http://milestoneconfig.jeevansathi.com';
	public static $ssl_siteUrl = 'https://milestoneconfig.jeevansathi.com';
	public static $ser6Url = 'http://ser6.jeevansathi.com';
	public static $ser2Url = 'http://ser2.jeevansathi.com';
	public static $imgUrl = 'http://static.milestoneconfig.jeevansathi.com';
	public static $imgUrl2 = 'http://static.milestoneconfig.jeevansathi.com';
	public static $php5path = 'php';
	public static $java = 'java';
	public static $docRoot = '/var/www/htmlrevamp/ser6/branches/milestoneConfig/web';
	public static $cronDocRoot = '/var/www/htmlrevamp/ser6/branches/milestoneConfig';
	public static $smartyDir = '/var/www/htmlrevamp/ser6/branches/milestoneConfig/lib/vendor/smarty/libs/Smarty.class.php';
	public static $chatIp = '172.16.3.185';
	public static $regImg = 'http://static.milestoneconfig.jeevansathi.com/profile/images/reg';
	public static $memcache = array("HOST" => '172.16.3.185', "PORT" => '11211');
	public static $bmsMemcache = array("HOST" => '172.16.3.185', "PORT" => '11211');
	public static $jquery = 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js';
	public static $localImageUrl = "http://crawlerjs.infoedge.com/";
	public static $appDown = false;
	public static $enableAllPasswordCombinations = false;
	public static $passwordHashingRollback = false;
	public static $vspServer = 'local';


	/******Photos******/
	public static $unscreenedPhotoUrl = 'http://milestoneconfig.jeevansathi.com';
	public static $screenedPhotosUrl = 'http://photos.jeevansathi.com';
	public static $screenedPhotoDir = '/var/www/html3/web/uploads';
	public static $ftpUsername = 'ftp_user';
	public static $ftpPassword = 'prinka';
	public static $ftpHost = '172.16.3.185';
	/* facebook */
	public static $fbId = '476279879176513';
	public static $fbSecret = '61fcc58f13beb7bf6557fb29cc881e03';
	/* flickr */
	public static $flickrKey = 'b65cce30b722eaabab2cb8b435135989';
	public static $flickrSecret = 'ec1a1d04f7366e83';
	/******Photos******/


	/*Search*/
	public static $solrServerUrl = 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/';
	public static $solrServerUrl1 = 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/';
	public static $solrServerUrl2 = 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/';
	public static $solrServerUrl3 = 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/';

	public static $solrServerProxyUrl = 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/';
	public static $solrServerProxyUrl1 = 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/';
	
	public static $solrServerLoggedOut     = 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/';
        
        public static $solrServerForVSP = 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/';
        public static $solrServerForKundali = 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/';
        public static $solrServerForVisitorAlert = 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/';
        
              
	/* bms */
	public static $bmsUrl = 'http://milestoneconfig.jeevansathi.com';
	public static $bmsDocRoot = '/var/www/htmlrevamp/ser6/branches/milestoneConfig/web';
	public static $bmsVideoUrl = 'mms://video.ieplads.com';
	public static $bmsStaticUrl = 'http://static.ieplads.com';

	/* alerts */
	public static $alertDocRoot = '/var/www/htmlrevamp/ser6/branches/milestoneConfig/web';
	public static $alertSymfonyRoot = '/var/www/htmlrevamp/ser6/branches/milestoneConfig';
	public static $userHome = '/home/developer';
	public static $alertServerEnable = 1;
	public static $stopOnPeakLoad = 1;
	public static $notificationStop = 0;
	//if set to 1, hides unimportant features at time of peak load on site
	public static $hideUnimportantFeatureAtPeakLoad = 0;

	/* Vsp URL */
	public static $vspMaleUrl = 'http://maleapi.analytics.resdex.com:9000/ecpRecommendations_live';
	public static $vspFemaleUrl = 'http://femaleapi.analytics.resdex.com:9000/ecpRecommendations_live';

	/* Post Eoi */
	public static $postEoiUrl = 'http://updateapi.analytics.resdex.com:9000/update_today_IAD';

	/*airtoweb*/
	public static $airToWebTransaction = array('accId' => '506980', 'pin' => 'nauk123', 'url' => 'http://luna.a2wi.co.in:7501/failsafe/HttpData_MM');
	public static $airToWebPromotion = array('accId' => '501331', 'pin' => 'inf@1', 'url' => 'http://121.241.247.190:7501/failsafe/HttpData_MM');
	public static $airToWebOTP = array('accId' => '634268', 'pin' => 'jee@12', 'url' => 'http://121.241.247.222:7501/failsafe/HttpLink');

	/*knowlarity*/
	public static $knowlarityUrlHit = "www.smartivr.in";

	/* value first*/

	/* Send Mail */
	public static $mailHost = '172.16.3.185';
	public static $mailPort = '25';

	/* mmmjs */
	public static $mmmjs99acres = 'http://mmm.99acres.com';

	public static $applicationPhotoUrl = 'http://milestoneconfig.jeevansathi.com';
	public static $cloudUrl = 'http://172.16.3.185';
	public static $cloudArchiveUrl = 'https://jeevansathi.s3.amazonaws.com';
	public static $policyFilePath = "/home/client/99acres/policy.xml";
	public static $toPath = "http://devjs.infoedge.com:6060/image_server/WSServer.php";
	public static $actionPathUpload = "http://wso2.org/upload";
	public static $actionPathGetPid = "http://wso2.org/getPidUrl";
	public static $actionPathDeletePid = "http://wso2.org/delete";
	public static $username = "99acres";
	public static $password = "99PW";
	public static $passwordType = "Digest";
	public static $xml_ns = "http://devjs.infoedge.com:6060/";

	/*photos from mail */
	public static $pearPath = "/usr/share/php/";
	public static $imageMail = "testsocial@jeevansathi.com";
	public static $imageMailUser = "testsocial";
	public static $imageMailPassword = "P@ssw0rd";

	//Boomerang enable.
	public static $boomerjs = "1";
	public static $baseUrl99 = 'http://99acres.com';

	//FaceDetection Algo
	public static $faceDetectionFile = "/var/www/htmlrevamp/ser6/branches/milestoneConfig/lib/vendor/opencv-2.4.7/samples/c";
	public static $faceDetectionCascadePath = "/var/www/htmlrevamp/ser6/branches/milestoneConfig/lib/vendor/opencv-2.4.7/data";


	//API Encryption Decryption Variables
	public static $api_identifier = "jEeV@nN$@@Th!";
	public static $privateKey = '0123456789abcdef';
	public static $initializationVector = 'fedcba9876543210';

	//APP PROMO
	public static $AndroidPromotion = 0;

	//Google Api Key For MAPS
	public static $googleMapApiKey = "ABQIAAAAUWHQnVB6yTvE0hYQpG-IfxS98Su_m4f99trCT3FLh-rEE5LCWBTPoIQiS2ItFXypzUvNFNzOnwAysQ";//Setting For Jeevansathi Domain
	/****Rabbitmq Configurations****/
	public static $rabbitmqConfig = array(
		'FIRST_SERVER' => array('HOST' => '192.168.120.33', 'PORT' => '5672', 'USER' => 'ankita', 'PASS' => 'ankita1994', 'VHOST' => '/'),
		'SECOND_SERVER' => array('HOST' => '192.168.120.154', 'PORT' => '5672', 'USER' => 'guest', 'PASS' => 'guest', 'VHOST' => '/')
	);
	public static $rabbitmqManagementPort = '15672';

	// IOS Notification
	public static $passphrase = 'P@ssw0rd';
	public static $iosCertificateKey = '/var/www/html/lib/model/lib/notifications/ck.pem';
	public static $iosApnsUrl = 'ssl://gateway.push.apple.com:2195';

	public static $shortUrlDomain = 'http://js1.in';
	public static $newMailHost = '172.16.3.185';
	public static $mailAllowedArray = array("eshajain88@outlook.com");
	public static $hindiTranslateURL = "http://hindi.jeevansathi.com";
	public static $contactUrl = "http://contact.jeevansathi.com/";
	public static $webServiceFlag = 1;
	public static $realTimeIndex = 1;
	public static $useMongoDb = false;

	/* redis */
	public static $updateSeenQueueConfig = array("ALL_CONTACTS" => true,
		"ALL_MESSAGES" => true,
		"PHOTO_REQUEST" => true,
		"HOROSCOPE_REQUEST" => true
	);
	public static $duplicateLoggingQueue = true;

	public static $memoryCachingSystem = 'redis1'; // redis,redisCluster,redisSentinel(needed new library),memcache 
	public static $redisCachingUrl = 'apitoCaching';
	public static $redisCluster = ['tcp://172.10.18.61:7000', 'tcp://172.10.18.62:7000', 'tcp://172.10.18.63:7000', 'tcp://172.10.18.64:7000', 'tcp://172.10.18.65:7000', 'tcp://172.10.18.64:7005'];
	public static $redisSentinel = ['tcp://172.10.18.65:26379', 'tcp://172.10.18.64:26379', 'tcp://172.10.18.70:26379'];
	public static $ifSingleRedis = 'tcp://172.10.18.65:6379';

	/***openfire config for chat**/
	public static $openfireConfig = array('HOST' => 'localhost', 'WSPORT' => '7070', 'SERVER_NAME' => 'localhost');
	public static $openfireConfigInternal = array('HOST' => 'localhost', 'PORT' => '9090', 'SERVER_NAME' => 'localhost');
	public static $openfireRestAPIKey = "MhXPRu3f4Aw07EbR";
	/*public static $ifSingleRedis = array(
	'scheme'   => 'tcp',
	'host'     => '127.0.0.1',
	'port'     => 6379,
	'persistent' => true
);*/

	public static $usePhotoDistributed = 0;
	public static $photoServerName = 'JSPIC1';
	public static $photoServerShardingEnums = array("JSPIC1", "JSPIC2", "JSPIC3");
	public static $communicationRep = true;
	public static $jsChatFlag = 1; //1=enable chat, 0=disable chat
	public static $presenceServiceUrl = "http://192.168.120.70:8290";
	public static $multiUserPhotoUrl = "http://www.jeevansathi.com/api/v1/social/getMultiUserPhoto"; //Api from staging for multi user photo being used in chat listing and self photo.
	public static $chatListingWebServiceUrl = array("dpp" => "http://localhost:8190/listings/v1/discover", "shortlist" => "http://localhost:8190/listings/v1/activities");
	public static $profilesEligibleForDpp = array('allProfiles' => 1, 'modulusDivisor' => 100, 'modulusRemainder' => 1, 'privilegedProfiles' => "5616315|9061321");
	public static $nonRosterRefreshUpdate = array("dpp" => 300000, "shortlist" => 300000);
	public static $httpsApplicationPhotoUrl = 'https://xmppdev1.jeevansathi.com';
	public static $httpsCloudUrl = 'https://mediacdn.jeevansathi.com';
	public static $profileServiceUrl = "http://172.16.3.187:8290";
	public static $androidChat = array("flag" => 1);
}
