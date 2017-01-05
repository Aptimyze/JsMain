<?php
class JsConstantsConfig
{
public static $all =
	[
        "airToWebTransaction" =>  "array('accId' =>'506980', 'pin' => 'nauk123', 'url'=>'http://luna.a2wi.co.in:7501/failsafe/HttpData_MM')",
        "airToWebPromotion"  =>  "array('accId' =>'501331', 'pin' => 'inf@1', 'url'=>'http://121.241.247.190:7501/failsafe/HttpData_MM')",
        "airToWebOTP"      =>  "array('accId' =>'634268', 'pin' => 'jee@12', 'url'=>'http://121.241.247.222:7501/failsafe/HttpLink')",
        "knowlarityUrlHit"    => "www.smartivr.in",
        "jquery"             => 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',
	];

public static $dev = 
	[
	"whichMachine"       => 'dev',
	"localHostIp"	  => "127.0.0.1",
        "java"               => 'java',
	"crmUrl"             => 'http://crm.jeevansathi.com',
	"siteUrl"            => "%URL_INPUT%",
        "ssl_siteUrl"        => '%SSL_URL_INPUT%',
	"ser6Url"            => 'http://ser6.jeevansathi.com',//Doubt
	"ser2Url"            => 'http://ser2.jeevansathi.com',//Doubt
	"imgUrl"             => '%URL_INPUT%',
	"imgUrl2"            => '%URL_INPUT%',
	"php5path"           => 'php',
	"docRoot"            => '%ROOT_DIR%/web',
	"cronDocRoot"	  => '%ROOT_DIR%',
	"smartyDir"          => '%ROOT_DIR%/lib/vendor/smarty/libs/Smarty.class.php',
	"chatIp"             => '172.16.3.185',
	"regImg"             => '%URL_INPUT%/profile/images/reg',
	"memcache"            => 'array("HOST"=>"172.16.3.185", "PORT"=>"11211")',
	"bmsMemcache"        => 'array("HOST"=>"172.16.3.185", "PORT"=>"11211")',
        "appDown"            => false,
        "enableAllPasswordCombinations" => true,
        "passwordHashingRollback" => false,


	/*Photos*/
	"unscreenedPhotoUrl" => '%URL_INPUT%',
	"screenedPhotosUrl"  => 'http://photos.jeevansathi.com',//Doubt
	"screenedPhotoDir"   => '%ROOT_DIR%/web/uploads',
	"ftpUsername"        => 'ftp_user',
	"ftpPassword"        => 'prinka',
	"ftpHost"            => '172.16.3.185',

	/*Search*/
        "solrServerUrl"      => 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',
        "solrServerUrl1"     => 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',
        "solrServerUrl2"     => 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',

	/* bms */
	"bmsUrl"             => 'http://ieplads.com',
	"bmsDocRoot"         => '%ROOT_DIR%/branches/chat/web',
	"bmsVideoUrl"        => 'mms://video.ieplads.com',
	"bmsStaticUrl"       => 'http://static.ieplads.com',

	"alertDocRoot"       => '%ROOT_DIR%/web',
	"alertSymfonyRoot"   => '%ROOT_DIR%',
	"userHome"           => '/home/tanu',//Doubt
	"alertServerEnable"  => 1,
        "stopOnPeakLoad"     => 1,
        "notificationStop"   => 0,
        //if set to 1, hides unimportant features at time of peak load on site
        "hideUnimportantFeatureAtPeakLoad"     => 0,

	/* Send Mail */
	"mailHost"           => '172.16.3.185',
	"mailPort"           => '25',

	/* mmmjs */
	"mmmjs99acres"	  =>'http://mmm.99acres.com',
        "applicationPhotoUrl"      => '%URL_INPUT%',
        "cloudUrl"                 => 'http://mediacdn.jeevansathi.com',//Doubt
        "cloudArchiveUrl"          => 'https://jeevansathi.s3.amazonaws.com',//Doubt
        "policyFilePath"           => "/home/client/99acres/policy.xml",//Doubt
        "toPath"                   => "http://devjs.infoedge.com:6060/image_server/WSServer.php",//Doubt
        "actionPathUpload"         => "http://wso2.org/upload",//Doubt
        "actionPathGetPid"         => "http://wso2.org/getPidUrl",//Doubt
        "username"                 => "99acres",//Doubt
        "password"                 => "99PW",//Doubt
        "passwordType"             => "Digest",//Doubt
        "xml_ns"                   => "http://devjs.infoedge.com:6060/",//Doubt

        /*photos from mail */
        "pearPath"                 =>  "/usr/share/php/",
        "imageMail"                =>  "testsocial@jeevansathi.com",//Doubt
        "imageMailUser"            =>  "testsocial",
        "imageMailPassword"         => "P@ssw0rd",

        //Boomerang enable.
        "boomerjs"         => "1",

         //FaceDetection Algo
        "faceDetectionFile" => "%ROOT_DIR%/lib/vendor/opencv-2.4.7/samples/c",
        "faceDetectionCascadePath" =>"%ROOT_DIR%/lib/vendor/opencv-2.4.7/data",

	//API Encryption Decryption Variables
	 "api_identifier" => "jEeV@nN$@@Th!",
	 "privateKey" => '0123456789abcdef',
	 "initializationVector" => 'fedcba9876543210',

	 //APP PROMO
	"AndroidPromotion"=>1,

        //Google Api Key For MAPS
        "googleMapApiKey" => "ABQIAAAAUWHQnVB6yTvE0hYQpG-IfxS98Su_m4f99trCT3FLh-rEE5LCWBTPoIQiS2ItFXypzUvNFNzOnwAysQ",//Setting For Jeevansathi Domain         

        // IOS Notification   
        "passphrase" =>'P@ssw0rd',
        "iosCertificateKey" =>'%ROOT_DIR%/lib/model/lib/notifications/ck.pem',
        "iosApnsUrl" =>'ssl://gateway.push.apple.com:2195',

/****Rabbitmq Configurations****/
        "rabbitmqConfig" => "array(
                                'FIRST_SERVER'=>array('HOST'=>'172.16.3.185','PORT'=>'5672','USER'=>'admin','PASS'=>'admin','VHOST'=>'/'),
                                'SECOND_SERVER'=>array('HOST'=>'192.168.120.154','PORT'=>'5672','USER'=>'guest','PASS'=>'guest','VHOST'=>'/')
                                             )",
        "rabbitmqManagementPort" => '15672',

        "shortUrlDomain" => 'http://js1.in',
        "newMailHost" => '172.16.3.185',
        "mailAllowedArray" => 'array("eshajain88@outlook.com")',
        "hindiTranslateURL" => "http://hindi.jeevansathi.com",//Doubt
        "contactUrl" => "http://contact.jeevansathi.com/",//Doubt


	"webServiceFlag" => true,
        "vspServer" => 'local',
        "realTimeIndex" => 1,
        "useMongoDb" => false,

	/* redis */

        "updateSeenQueueConfig" => 'array("ALL_CONTACTS"=>true,
                                                "ALL_MESSAGES"=>true,
                                                "PHOTO_REQUEST"=>true,
                                                "HOROSCOPE_REQUEST"=>true
                                                )',
        "duplicateLoggingQueue" =>true,
        "memoryCachingSystem" => 'redis1',
        "redisCachingUrl" => 'apitoCaching',
        "redisCluster" => "['tcp://172.10.18.61:7000','tcp://172.10.18.62:7000','tcp://172.10.18.63:7000','tcp://172.10.18.64:7000','tcp://172.10.18.65:7000','tcp://172.10.18.64:7005']",
        "redisSentinel" => "['tcp://172.10.18.65:26379', 'tcp://172.10.18.64:26379','tcp://172.10.18.70:26379']",
        "ifSingleRedis" => 'tcp://172.10.18.65:6379',
        
    /***openfire config for chat**/
    "openfireConfig" => "array('HOST'=>'localhost','WSPORT'=>'7070','SERVER_NAME'=>'localhost')",
    "openfireConfigInternal" => "array('HOST'=>'localhost','PORT'=>'9090','SERVER_NAME'=>'localhost')",
    "openfireRestAPIKey" => "MhXPRu3f4Aw07EbR",
		/*"ifSingleRedis" => "array(
		'scheme'   => 'tcp',
		'host'     => '127.0.0.1',
		'port'     => 6379,
		'persistent' => true
	)",*/

	 "usePhotoDistributed" => 0,
  "photoServerName"=>'JSPIC1',
	"photoServerShardingEnums" => 'array("JSPIC1","JSPIC2","JSPIC3")',
	"communicationRep" => true,
    "jsChatFlag" => 1, //1=>enable chat, 0=>disable chat
        "presenceServiceUrl" => "http://192.168.120.70:8290",
    "multiUserPhotoUrl" => "http://www.jeevansathi.com/api/v1/social/getMultiUserPhoto", //Api from staging for multi user photo being used in chat listing and self photo.
    "chatListingWebServiceUrl" => 'array("dpp"=>"http://www.jeevansathi.com:8190/listings/v1/discover")',
    "profilesEligibleForDpp" => "array('allProfiles'=>1,'modulusDivisor'=>100,'modulusRemainder'=>1,'privilegedProfiles'=>'5616315|9061321')",
    "nonRosterRefreshUpdate" => 300000

	];

public static $test = 
	[

	"whichMachine"       => 'prod',
	"siteUrl"            => '%URL_INPUT%',
	"ser6Url"            => 'http://ser6.jeevansathi.com',
	"ser2Url"            => 'http://ser2.jeevansathi.com',
	//"ser2Url            => 'http://testsocial2.jeevansathi.com',
	"imgUrl"             => '%URL_INPUT%',
	"imgUrl2"            => '%URL_INPUT%',
	"php5path"           => 'php',
	 "java"              => 'java',
	"docRoot"            => '%ROOT_DIR%/web',
	"cronDocRoot"	  => '%ROOT_DIR%',
	"smartyDir"          => '%ROOT_DIR%/lib/vendor/smarty/libs/Smarty.class.php',
	"chatIp"             => '172.16.3.203',
	"regImg"             => 'http://static.jeevansathi.com/profile/images/reg',
	"memcache"            => 'array("HOST"=>"172.16.3.203", "PORT"=>"11211")',
	"bmsMemcache"        => 'array("HOST"=>"172.16.3.203", "PORT"=>"11211")',
        "ssl_siteUrl"        => '%SSL_URL_INPUT%',
        "crmUrl"             => 'http://crm.jeevansathi.com',

	/****Photos*****/
	"unscreenedPhotoUrl" => '%URL_INPUT%',
	"screenedPhotosUrl"  => 'http://testphotos.jeevansathi.com',
	"screenedPhotoDir"   => '%ROOT_DIR%/web/uploads',
	"ftpUsername"        => 'mike',
	"ftpPassword"        => 'mike123',
	"ftpHost"            => '172.16.3.203',
         /* facebook */
        "fbId"=>'140798849327439',
        "fbSecret"=>'6a686b1d23bf9a5e3dad7eb7ecf7a32d',
        /* flickr */
        "flickrKey"=>'b65cce30b722eaabab2cb8b435135989',
        "flickrSecret"=>'ec1a1d04f7366e83',
	/****Photos*****/

	/*Search*/
        "solrServerUrl"      => 'http://172.16.3.203:8080/solr/',
        "solrServerUrl1"     => 'http://172.16.3.203:8080/solr/',
        "solrServerUrl2"     => 'http://172.16.3.203:8080/solr/',




	/* bms */
	"bmsUrl"             => '%URL_INPUT%',
	"bmsDocRoot"         => '%ROOT_DIR%/web',
	"bmsVideoUrl"        => 'mms://video.ieplads.com',
	"bmsStaticUrl"       => 'http://static.ieplads.com',

	/* alerts */
	"alertDocRoot"       => '%ROOT_DIR%/web',
	"alertSymfonyRoot"   => '%ROOT_DIR%/web',
	"userHome"           => '/home/developer',

	/* Send Mail */
	"mailHost"           => '172.16.3.128',
	"mailPort"           => '25',

	/* mmmjs */
	"mmmjs99acres"	  =>'http://mmm.test99-vm1.infoedge.com',
        "applicationPhotoUrl"      => '%URL_INPUT%',
        "cloudUrl"                 => 'http://mediacdn.jeevansathi.com/',
        "cloudArchiveUrl"          => 'https://jeevansathi.s3.amazonaws.com',
	"policyFilePath"           => "/home/client/99acres/policy.xml",
        "toPath"                   => "http://devjs.infoedge.com:6060/image_server/WSServer.php",
        "actionPathUpload"         => "http://wso2.org/upload",
        "actionPathGetPid"         => "http://wso2.org/getPidUrl",
        "username"                 => "99acres",
        "password"                 => "99PW",
        "passwordType"             => "Digest",
        "xml_ns"                   => "http://devjs.infoedge.com:6060/",

        /*photos from mail */
        "pearPath"                 =>  "/usr/share/php/",
        "imageMail"                =>  "testsocial@jeevansathi.com",
        "imageMailUser"            =>  "testsocial",
        "imageMailPassword"         => "P@ssw0rd",
       
         //Boomerang enable.
        "boomerjs"         => "1",
	"baseUrl99"        => "http://test99-vm1.infoedge.com",

	"alertServerEnable" => 1,


        //API Encryption Decryption Variables
                 "api_identifier" => "jEeV@nN$@@Th!",
                 "privateKey" => '0123456789abcdef',
                 "initializationVector" => 'fedcba9876543210',





//App promotion
        "AndroidPromotion"=>1,
        "appDown"            => false,
// Password Encryption
         "enableAllPasswordCombinations" => false,
        "passwordHashingRollback" =>false,

	 //FaceDetection Algo
        "faceDetectionFile" => "/usr/local/share/OpenCV/samples/c",
        "faceDetectionCascadePath" =>"/usr/local/share/OpenCV",


 //Google Api Key For MAPS
       "googleMapApiKey" => "ABQIAAAAUWHQnVB6yTvE0hYQpG-IfxS98Su_m4f99trCT3FLh-rEE5LCWBTPoIQiS2ItFXypzUvNFNzOnwAysQ",

//Setting For Jeevansathi Domain      
 "localHostIp"        => "127.0.0.1",



/****Rabbitmq Configurations****/
       "rabbitmqConfig" => "array('FIRST_SERVER'=>array('HOST'=>'localhost','PORT'=>'5672','USER'=>'guest','PASS'=>'guest','VHOST'=>'/'), 'SECOND_SERVER'=>array('HOST'=>'192.168.120.154','PORT'=>'5672','USER'=>'guest','PASS'=>'guest','VHOST'=>'/'))",
       "rabbitmqManagementPort" => 15672,
/****mailhost***/
"newMailHost" => '172.16.3.185', 
"mailAllowedArray" => 'array("eshajain88@outlook.com")',
"hindiTranslateURL" => "http://hindi.jeevansathi.com",
"contactUrl" => "http://contacttest.infoedge.com/",
"webServiceFlag" => 0,
"realTimeIndex" => 0,
"vspServer" => 'local',
"vspMaleUrl"    => 'http://maleapi.analytics.resdex.com:9000/ecpRecommendations_live',
"vspFemaleUrl"    => 'http://femaleapi.analytics.resdex.com:9000/ecpRecommendations_live',

 /* redis */
        "updateSeenQueueConfig" => 'array("ALL_CONTACTS"=>true,
                                                "ALL_MESSAGES"=>true,
                                                "PHOTO_REQUEST"=>true,
                                                "HOROSCOPE_REQUEST"=>true
                                                )',
        "memoryCachingSystem" => 'redis', // redis,redisCluster,redisSentinel(needed new library),memcache
        "redisCachingUrl" => 'apitoCaching',
        "redisCluster" => "['tcp://172.10.18.61:7000','tcp://172.10.18.62:7000','tcp://172.10.18.63:7000','tcp://172.10.18.64:7000','tcp://172.10.18.65:7000','tcp://172.10.18.64:7005']",
        "redisSentinel" => "['tcp://172.10.18.65:26379', 'tcp://172.10.18.64:26379','tcp://172.10.18.70:26379']",
        "ifSingleRedis" => 'tcp://172.16.3.203:6380',
        "usePhotoDistributed" => 0,
        "communicationRep" => 1,
        "jsChatFlag"  => '0',
        "presenceServiceUrl" => "http://192.168.120.70:8290",
    "multiUserPhotoUrl" => "http://www.jeevansathi.com/api/v1/social/getMultiUserPhoto", //Api from staging for multi user photo being used in chat listing and self photo.
    "chatListingWebServiceUrl" => 'array("dpp"=>"http://www.jeevansathi.com:8190/listings/v1/discover")',
    "profilesEligibleForDpp" => "array('allProfiles'=>1,'modulusDivisor'=>100,'modulusRemainder'=>1,'privilegedProfiles'=>'5616315|9061321')",
    "nonRosterRefreshUpdate" => 300000,
        "stopOnPeakLoad"     => 1,
        "postEoiUrl"           => 'http://updateapi.analytics.resdex.com:9000/update_today_IAD',
        "actionPathDeletePid"         => "http://wso2.org/delete",
        "passphrase" =>'P@ssw0rd',
        "localImageUrl"      => "http://crawlerjs.infoedge.com/",
        "iosCertificateKey" =>'%ROOT_DIR%/lib/model/lib/notifications/ck.pem',
        "iosApnsUrl" =>'ssl://gateway.push.apple.com:2195',
        "shortUrlDomain" => 'http://js1.in',
        "useMongoDb" => false,
        "duplicateLoggingQueue" =>true,
        "photoServerName"=>'JSPIC1',
        "photoServerShardingEnums" => 'array("JSPIC1","JSPIC2","JSPIC3")',
        
        "notificationStop"   => 0, 
        "hideUnimportantFeatureAtPeakLoad"     => 0,

       /***openfire config for chat**/
    "openfireConfig" => "array('HOST'=>'localhost','WSPORT'=>'7070','SERVER_NAME'=>'localhost')",
    "openfireConfigInternal" => "array('HOST'=>'localhost','PORT'=>'9090','SERVER_NAME'=>'localhost')",
    "openfireRestAPIKey" => "MhXPRu3f4Aw07EbR",
    "communicationServiceUrl" => "http://172.16.3.203:8390",
"multiUserPhotoUrl" =>"http://staging.jeevansathi.com/api/v1/social/getMultiUserPhoto" 

	];
}
