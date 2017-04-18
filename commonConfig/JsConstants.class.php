<?php

/**
i. Configurations have to be added for both dev/test environment
ii. key-value pair must be string
iii. Dynamic variables to be defined as:
%URL_INPUT%: Domain of the branch
%SSL_URL_INPUT%: Secure domain of the branch
%STATIC_URL_INPUT%: Static domain of the branch 
%ROOT_DIR%: Root directory of the branch
**/
class JsConstantsConfig
{
public static $all =
	[
		/***Essentials***/
		"siteUrl"            => "%URL_INPUT%",
		"ssl_siteUrl"        => '%SSL_URL_INPUT%',
		"smartyDir"          => '%ROOT_DIR%/lib/vendor/smarty/libs/Smarty.class.php',
		"docRoot"            => '%ROOT_DIR%/web',
		"cronDocRoot"	  => '%ROOT_DIR%',
		"ser6Url"            => 'http://ser6.jeevansathi.com',
		"ser2Url"            => 'http://ser2.jeevansathi.com',
		"crmUrl"             => 'http://crm.jeevansathi.com',

		/**SMS**/
		"shortUrlDomain" => 'http://js1.in',
		"airToWebTransaction" =>  "array('accId' =>'506980', 'pin' => 'nauk123', 'url'=>'http://luna.a2wi.co.in:7501/failsafe/HttpData_MM')",
		"airToWebPromotion"  =>  "array('accId' =>'501331', 'pin' => 'inf@1', 'url'=>'http://121.241.247.190:7501/failsafe/HttpData_MM')",
		"airToWebOTP"      =>  "array('accId' =>'634268', 'pin' => 'jee@12', 'url'=>'http://121.241.247.222:7501/failsafe/HttpLink')",

		//Phone verification 
		"knowlarityUrlHit"    => "www.smartivr.in",

		"jquery"             => 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',
		"hindiTranslateURL" => "http://hindi.jeevansathi.com",//Doubt
		"nonRosterRefreshUpdateNew" => 'array("dpp"=>array("Free"=>600000,"Paid"=>300000),"shortlist"=>array("Free"=>600000,"Paid"=>300000))',
    	"androidChatNew"=>'array("chatOn"=>true,"flushLocalStorage"=>false,"xmppBackgroundConnectionTimeout"=>300000)',
	];

public static $dev = 
	[
		"whichMachine"       => 'local',
		"php5path"           => 'php',
		"java"               => 'java',
		"vspServer" => 'local',
		"localImageUrl"      => "http://crawlerjs.infoedge.com/",

		/***Static***/
		"imgUrl"             => '%STATIC_URL_INPUT%',
		"imgUrl2"            => '%STATIC_URL_INPUT%',
		"regImg"             => '%STATIC_URL_INPUT%/profile/images/reg',

		/*Photos*/
		"unscreenedPhotoUrl" => '%URL_INPUT%',
		"screenedPhotosUrl"  => 'http://photos.jeevansathi.com',//Doubt
		"screenedPhotoDir"   => '%ROOT_DIR%/web/uploads',
		"ftpUsername"        => 'ftp_user',
		"ftpPassword"        => 'prinka',
		"ftpHost"            => '172.16.3.185',
                /* facebook */
                "fbId"=>'140798849327439',
                "fbSecret"=>'6a686b1d23bf9a5e3dad7eb7ecf7a32d',
                /* flickr */
                "flickrKey"=>'b65cce30b722eaabab2cb8b435135989',
                "flickrSecret"=>'ec1a1d04f7366e83',
		/****Photos*****/

		/* bms */
		"bmsUrl"             => 'http://ieplads.com',
		"bmsDocRoot"         => '%ROOT_DIR%/branches/chat/web',
		"bmsVideoUrl"        => 'mms://video.ieplads.com',
		"bmsStaticUrl"       => 'http://static.ieplads.com',

		/* alerts */
		"alertDocRoot"       => '%ROOT_DIR%/web',
		"alertSymfonyRoot"   => '%ROOT_DIR%',
		"userHome"           => '/home/tanu',//Doubt
		"alertServerEnable"  => 1,

                /* Send Mail */
                "mailHost"           => '172.16.3.185',
                "mailPort"           => '25',

                /* mmmjs */
                "mmmjs99acres"    	   =>'http://mmm.99acres.com',
                "applicationPhotoUrl"      => '%URL_INPUT%',
                "cloudUrl"                 => 'http://mediacdn.jeevansathi.com',//Doubt
                "cloudArchiveUrl"          => 'https://jeevansathi.s3.amazonaws.com',//Doubt
                "policyFilePath"           => "/home/client/99acres/policy.xml",//Doubt
                "toPath"                   => "http://devjs.infoedge.com:6060/image_server/WSServer.php",//Doubt
                "actionPathUpload"         => "http://wso2.org/upload",//Doubt
                "actionPathGetPid"         => "http://wso2.org/getPidUrl",//Doubt
		"actionPathDeletePid"         => "http://wso2.org/delete",
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
                "baseUrl99"        => "http://99acres.com",

		//API Encryption Decryption Variables
		"api_identifier" => "jEeV@nN$@@Th!",
		"privateKey" => '0123456789abcdef',
		"initializationVector" => 'fedcba9876543210',

		//App promotion
		"AndroidPromotion"=>1,
		"appDown"            => false,

		// Password Encryption
		"enableAllPasswordCombinations" => true,
		"passwordHashingRollback" => false,

		//FaceDetection Algo
		"faceDetectionFile" => "%ROOT_DIR%/lib/vendor/opencv-2.4.7/samples/c",
		"faceDetectionCascadePath" =>"%ROOT_DIR%/lib/vendor/opencv-2.4.7/data",

		//Google Api Key For MAPS
		"googleMapApiKey" => "ABQIAAAAUWHQnVB6yTvE0hYQpG-IfxS98Su_m4f99trCT3FLh-rEE5LCWBTPoIQiS2ItFXypzUvNFNzOnwAysQ",//Setting For Jeevansathi Domain         

		//Setting For Jeevansathi Domain 
		"localHostIp"	  => "127.0.0.1",

		/* Vsp URL */
		"vspMaleUrl"    => 'http://maleapi.analytics.resdex.com:9000/ecpRecommendations_live',
		"vspFemaleUrl"    => 'http://femaleapi.analytics.resdex.com:9000/ecpRecommendations_live',

		/* Post Eoi */
		"postEoiUrl"           => 'http://updateapi.analytics.resdex.com:9000/update_today_IAD',

		/****mailhost***/
		"newMailHost" => '172.16.3.185',
		"mailAllowedArray" => 'array("eshajain88@outlook.com")',

		// IOS Notification   
		"passphrase" =>'P@ssw0rd',
		"iosCertificateKey" =>'%ROOT_DIR%/lib/model/lib/notifications/ck.pem',
		"iosApnsUrl" =>'ssl://gateway.push.apple.com:2195',

		//redis
		"updateSeenQueueConfig" => 'array("ALL_CONTACTS"=>true,
				"ALL_MESSAGES"=>true,
				"PHOTO_REQUEST"=>true,
				"HOROSCOPE_REQUEST"=>true
				)',

		"contactUrl" => "http://contact.jeevansathi.com/",//Doubt
		"webServiceFlag" => true,
		"realTimeIndex" => 1,
		"usePhotoDistributed" => 0,
		"communicationRep" => true,
		"jsChatFlag" => 1, //1=>enable chat, 0=>disable chat
		"multiUserPhotoUrl" => "%URL_INPUT%/api/v1/social/getMultiUserPhoto", //Api from staging for multi user photo being used in chat listing and self photo.
		"chatListingWebServiceUrl" => 'array("dpp"=>"%URL_INPUT%:8190/listings/v1/discover","shortlist"=>"http://%URL_INPUT%:8190/listings/v1/activities","chatAuth"=>"http://%URL_INPUT%:8390/auth/v1/chat")',
		"profilesEligibleForDpp" => "array('allProfiles'=>1,'modulusDivisor'=>100,'modulusRemainder'=>1,'privilegedProfiles'=>'5616315|9061321')",
		"nonRosterRefreshUpdateNew" => 'array("dpp"=>array("Free"=>600000,"Paid"=>300000),"shortlist"=>array("Free"=>600000,"Paid"=>300000))',
		"nonRosterRefreshUpdate" => 300000,
		"stopOnPeakLoad"     => 1,
		"useMongoDb" => false,
		"duplicateLoggingQueue" =>true,
		"photoServerName"=>'JSPIC1',
		"photoServerShardingEnums" => 'array("JSPIC1","JSPIC2","JSPIC3")',
		"notificationStop"   => 0,
                "httpsApplicationPhotoUrl" => 'https://xmppdev1.jeevansathi.com',
                "httpsCloudUrl" => 'https://mediacdn.jeevansathi.com',
		"androidChat" => "array('flag' => 1)",


		//if set to 1, hides unimportant features at time of peak load on site
		"hideUnimportantFeatureAtPeakLoad"     => 0,
                "solrServerUrls"      => "array(0=>'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',1=>'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',2=>'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',3=>'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',4=>'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/')",
		/*Search*/
		"solrServerUrl"      => 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',
		"solrServerUrl1"     => 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',
		"solrServerUrl2"     => 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',
		"solrServerUrl3"     => 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',
		"solrServerProxyUrl"     => 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',
		"solrServerProxyUrl1"     => 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',
		"solrServerLoggedOut"     => 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',
		"solrServerForVisitorAlert"	=> 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',
		"solrServerForVSP"	=> 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',
		"solrServerForKundali"	=> 'http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/',
		

		/***openfire config for chat**/
		"openfireConfig" => "array('HOST'=>'localhost','WSPORT'=>'7070','SERVER_NAME'=>'localhost')",
		"openfireConfigInternal" => "array('HOST'=>'localhost','PORT'=>'9090','SERVER_NAME'=>'localhost')",
		"openfireRestAPIKey" => "MhXPRu3f4Aw07EbR",

		/***Redis**/
		"memoryCachingSystem" => 'redis1',
		"redisCachingUrl" => 'apitoCaching',
		"redisCluster" => "['tcp://172.10.18.61:7000','tcp://172.10.18.62:7000','tcp://172.10.18.63:7000','tcp://172.10.18.64:7000','tcp://172.10.18.65:7000','tcp://172.10.18.64:7005']",
		"redisSentinel" => "['tcp://172.10.18.65:26379', 'tcp://172.10.18.64:26379','tcp://172.10.18.70:26379']",
		"ifSingleRedis" => 'tcp://172.10.18.65:6379',

		/****Rabbitmq Configurations****/
		"rabbitmqConfig" => "array('FIRST_SERVER'=>array('HOST'=>'172.16.3.185','PORT'=>'5672','USER'=>'admin','PASS'=>'admin','VHOST'=>'/'),'SECOND_SERVER'=>array('HOST'=>'192.168.120.154','PORT'=>'5672','USER'=>'guest','PASS'=>'guest','VHOST'=>'/'))",
		"rabbitmqManagementPort" => '15672',

		/**Misc IP Configurations**/
		"chatIp"             => '172.16.3.185',
		"memcache"            => 'array("HOST"=>"172.16.3.185", "PORT"=>"11211")',
		"bmsMemcache"        => 'array("HOST"=>"172.16.3.185", "PORT"=>"11211")',
		"communicationServiceUrl" => "http://172.16.3.203:8390",
		"profileServiceUrl" => "http://172.16.3.187:8290",
		"presenceServiceUrl" => "http://192.168.120.70:8290",
		"presenceServiceUrl2" => "http://192.168.120.70:8590",
        "chatNotificationService" => "http://192.168.120.239:8490",

        "chatOnlineFlag" => "array('profile'=>true,'contact'=>true,'search'=>true)",

		/**Kibana constants**/

		"kibana" =>  "array('ELK_SERVER' =>'elkjs.js.jsb9.net', 'ELASTIC_PORT' => '9200', 'KIBANA_PORT'=>'5601','AURA_SERVER' => 'es.aura.resdex.com','AURA_PORT'=>'9203')",
	];

public static $test = 
	[
		"whichMachine"       => 'test',
		"php5path"           => 'php',
		"java"              => 'java',
		"vspServer" => 'local',
		"localImageUrl"      => "http://crawlerjs.infoedge.com/",

		/***Static***/
		"imgUrl"             => '%URL_INPUT%',
		"imgUrl2"            => '%URL_INPUT%',
		"regImg"             => '%URL_INPUT%/profile/images/reg',

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

		/* bms */
		"bmsUrl"             => '%URL_INPUT%',
		"bmsDocRoot"         => '%ROOT_DIR%/web',
		"bmsVideoUrl"        => 'mms://video.ieplads.com',
		"bmsStaticUrl"       => 'http://static.ieplads.com',

		/* alerts */
		"alertDocRoot"       => '%ROOT_DIR%/web',
		"alertSymfonyRoot"   => '%ROOT_DIR%/web',
		"userHome"           => '/home/developer',
		"alertServerEnable" => 1,

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
                "actionPathUpload"         => "http://wso2.org/upload",//Doubt
                "actionPathGetPid"         => "http://wso2.org/getPidUrl",//Doubt
		"actionPathDeletePid"         => "http://wso2.org/delete",
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

		/* Vsp URL */
		"vspMaleUrl"    => 'http://maleapi.analytics.resdex.com:9000/ecpRecommendations_live',
		"vspFemaleUrl"    => 'http://femaleapi.analytics.resdex.com:9000/ecpRecommendations_live',

		/* Post Eoi */
		"postEoiUrl"           => 'http://updateapi.analytics.resdex.com:9000/update_today_IAD',

		/****mailhost***/
		"newMailHost" => '172.16.3.185', 
		"mailAllowedArray" => 'array("eshajain88@outlook.com")',


		// IOS Notification   
		"passphrase" =>'P@ssw0rd',
		"iosCertificateKey" =>'%ROOT_DIR%/lib/model/lib/notifications/ck.pem',
		"iosApnsUrl" =>'ssl://gateway.push.apple.com:2195',

		//redis
		"updateSeenQueueConfig" => 'array("ALL_CONTACTS"=>true,
				"ALL_MESSAGES"=>true,
				"PHOTO_REQUEST"=>true,
				"HOROSCOPE_REQUEST"=>true
				)',

		"contactUrl" => "http://contacttest.infoedge.com/",
		"webServiceFlag" => 0,
		"realTimeIndex" => 0,
		"usePhotoDistributed" => 0,
		"communicationRep" => 1,
		"jsChatFlag"  => '0',
		"multiUserPhotoUrl" => "%URL_INPUT%/api/v1/social/getMultiUserPhoto", //Api from staging for multi user photo being used in chat listing and self photo.
		"chatListingWebServiceUrl" => 'array("dpp"=>"%URL_INPUT%:8190/listings/v1/discover","shortlist"=>"http://%URL_INPUT%:8190/listings/v1/activities","chatAuth"=>"http://%URL_INPUT%:8390/auth/v1/chat")',
		"profilesEligibleForDpp" => "array('allProfiles'=>1,'modulusDivisor'=>100,'modulusRemainder'=>1,'privilegedProfiles'=>'5616315|9061321')",
		"nonRosterRefreshUpdate" => 300000,
		"stopOnPeakLoad"     => 1,
		"useMongoDb" => false,
		"duplicateLoggingQueue" =>true,
		"photoServerName"=>'JSPIC1',
		"photoServerShardingEnums" => 'array("JSPIC1","JSPIC2","JSPIC3")',
		"notificationStop"   => 0, 
		"httpsApplicationPhotoUrl" => 'https://xmppdev1.jeevansathi.com',
		"httpsCloudUrl" => 'https://mediacdn.jeevansathi.com',
		"androidChat" => "array('flag' => 1)",


		//if set to 1, hides unimportant features at time of peak load on site
		"hideUnimportantFeatureAtPeakLoad"     => 0,

		/*Search*/
                "solrServerUrls"      => "array(0=>'172.16.3.203:8983/solr/techproducts',1=>'172.16.3.203:8983/solr/techproducts',2=>'172.16.3.203:8983/solr/techproducts',3=>'172.16.3.203:8983/solr/techproducts',4=>'172.16.3.203:8983/solr/techproducts')",
		"solrServerUrl"      => '172.16.3.203:8983/solr/techproducts',
		"solrServerUrl1"     => '172.16.3.203:8983/solr/techproducts',
		"solrServerUrl2"     => '172.16.3.203:8983/solr/techproducts',
		"solrServerUrl3"     => '172.16.3.203:8983/solr/techproducts',
		"solrServerProxyUrl"     => '172.16.3.203:8983/solr/techproducts',
		"solrServerProxyUrl1"     => '172.16.3.203:8983/solr/techproducts',
		"solrServerLoggedOut"     => '172.16.3.203:8983/solr/techproducts',
		"solrServerForVisitorAlert"	=> '172.16.3.203:8983/solr/techproducts',
		"solrServerForVSP"	=> '172.16.3.203:8983/solr/techproducts',
		"solrServerForKundali"	=> '172.16.3.203:8983/solr/techproducts',

		/***openfire config for chat**/
		"openfireConfig" => "array('HOST'=>'172.16.3.203','PORT'=>'9090','WSPORT'=>'7070','SERVER_NAME'=>'testjs-new')",
		"openfireConfigInternal" => "array('HOST'=>'172.16.3.203','PORT'=>'9090','WSPORT'=>'7070','SERVER_NAME'=>'testjs-new')",
		"openfireRestAPIKey" => "kj8WbXpE8l52cdLg",

		/***Redis**/
		"memoryCachingSystem" => 'redis', // redis,redisCluster,redisSentinel(needed new library),memcache
		"redisCachingUrl" => 'apitoCaching',
		"redisCluster" => "['tcp://172.10.18.61:7000','tcp://172.10.18.62:7000','tcp://172.10.18.63:7000','tcp://172.10.18.64:7000','tcp://172.10.18.65:7000','tcp://172.10.18.64:7005']",
		"redisSentinel" => "['tcp://172.10.18.65:26379', 'tcp://172.10.18.64:26379','tcp://172.10.18.70:26379']",
		"ifSingleRedis" => 'tcp://172.16.3.203:6380',

		/****Rabbitmq Configurations****/
		"rabbitmqConfig" => "array('FIRST_SERVER'=>array('HOST'=>'172.16.3.203','PORT'=>'5672','USER'=>'guest1','PASS'=>'guest','VHOST'=>'/'), 'SECOND_SERVER'=>array('HOST'=>'192.168.120.154','PORT'=>'5672','USER'=>'guest','PASS'=>'guest','VHOST'=>'/'))",
		"rabbitmqManagementPort" => 15672,

		/**Misc IP Configurations**/
		"chatIp"             => '172.16.3.203',
		"memcache"            => 'array("HOST"=>"172.16.3.203", "PORT"=>"11211")',
		"bmsMemcache"        => 'array("HOST"=>"172.16.3.203", "PORT"=>"11211")',
		"communicationServiceUrl" => "http://172.16.3.203:8390",
		"profileServiceUrl" => "http://172.16.3.187:8290",
		"presenceServiceUrl" => "http://192.168.120.70:8290",
        "chatNotificationService" => "http://192.168.120.239:8490"
	];
}
