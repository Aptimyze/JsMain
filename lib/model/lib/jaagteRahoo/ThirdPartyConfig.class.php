<?php

class ThirdPartyConfig
{
        
        static private $SOLR = array();
        static private $RABBITMQ = array();
        static private $GUNA = array();	
        static private $REDIS = array();
        static private $THRESHHOLDS = array();
        static private $JAVASERVICES = array();
        static private $PRESENCE = array();
         static private $LISTINGS = array();
          static private $PROFILE = array();
           static private $AUTH = array();
          static private $COMMUNICATION = array();

        static private function initConfig()
        {
				self::$JAVASERVICES["API"] = array("PRESENCE","PROFILE","LISTINGS","AUTH");
                self::$SOLR["IP"]["SOLR1"]= "http://10.10.18.70:8080/solr/select";
                self::$SOLR["IP"]["SOLR3"]= "http://10.10.18.64:8080/solr/select";
                self::$SOLR["IP"]["SOLR4"]= "http://10.10.18.73:8080/solr/select";
                self::$SOLR["IP"]["SOLR-66"]= "http://10.10.18.66:8988/solr/collection1/select";
                self::$SOLR["IP"]["SOLR-103"]= "http://10.10.18.103:8988/solr/collection1/select";
                self::$SOLR["URL"]="?q=*:*&wt=json";
                self::$GUNA["URL"]="http://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_FindCompatibility_Matchstro.dll?SearchCompatiblityMultipleFull?144111:1:269.422184:164.03058:110.95199:61.282742:179.759652:357.758077:125.08166:97.683831&8757219:2:160.169542:44.763197:265.293283:45.841224:23.671695:222.129802:89.231171:184.921703@,8730906:2:352.593127:264.509093:140.982952:216.725751:274.722884:357.119697:298.478081:242.720822@,8446577:2:230.807082:275.911688:27.722365:208.396888:268.079492:298.812366:275.97738:223.325678@,8285878:2:353.789848:169.572525:261.853437:117.759631:190.264344:175.490611:213.600166:169.278377@,8154275:2:160.352672:240.613461:188.949418:201.192466:236.977393:356.077952:269.556538:239.990701@,8128123:2:162.103118:150.214777:144.249291:214.34551:173.610938:195.285259:137.71379:177.781263@,8104280:2:200.843789:199.912182:112.486913:162.243333:222.768519:285.283943:181.868323:215.044235@,8091788:2:161.801767:293.91815:263.087792:218.93535:298.115591:302.981428:298.240972:224.679287@,8081842:2:222.114036:206.245552:64.750261:273.632532:224.426625:257.150908:244.078641:205.526289@,8054344:2:268.511397:272.254522:37.180819:289.106117:282.146281:166.679012:252.394473:166.18892@,7968702:2:172.375931:286.126843:22.519859:333.344537:272.28991:274.526807:332.950605:213.347562@,7929608:2:94.271828:121.034155:276.088348:257.968452:103.946915:327.154506:166.81444:219.488286@,7911396:2:188.796475:100.295514:354.933038:259.490384:94.16398:328.846313:143.808408:219.491197@,7898880:2:69.949068:162.365348:243.227313:150.500325:187.503968:3.430407:172.468593:232.2018@,7867785:2:182.703707:90.067345:259.7367:65.004026:69.578804:160.261943:116.38788:160.786424@,7850176:2:99.080069:59.114583:132.522141:269.280272:80.960173:327.898813:94.37647:221.5055@,7826843:2:117.91175:150.311972:7.619808:105.729363:175.648585:171.303471:191.005281:166.888496@,7816473:2:89.864768:198.812531:334.621681:268.14465:213.695734:255.823414:235.133843:204.641027@";
                self::$THRESHHOLDS["TIMEOUT"]["DEFAULT"]= "400";
                self::$THRESHHOLDS["TIMEOUT"]["GUNA"]= "4000";
                self::$PRESENCE["IP"]["PRESENCE_67"]= "http://10.10.18.67:8590";
                self::$PRESENCE["IP"]["PRESENCE_72"]= "http://10.10.18.72:8590";
                self::$PRESENCE["IP"]["PRESENCE_75"]= "http://10.10.18.75:8590";
                self::$PRESENCE["IP"]["PRESENCE_104"]= "http://10.10.18.104:8590";
                self::$PRESENCE["URL"]="/jspresence/v1/presence";
                self::$PROFILE["IP"]["PROFILE_67"]= "http://10.10.18.67:8290";
                self::$PROFILE["IP"]["PROFILE_72"]= "http://10.10.18.72:8290";
                self::$PROFILE["URL"]="/profile/v1/profile?view=shortview&pfids=7902447";
                self::$LISTINGS["IP"]["LISTINGS_67"]= "http://10.10.18.67:8190";
                self::$LISTINGS["IP"]["LISTINGS_72"]= "http://10.10.18.73:8190";
                self::$LISTINGS["URL"]="/listings/v1/discover?type=CHATDPP";
                self::$AUTH["IP"]["AUTH_73"]= "http://10.10.18.73:8390";
                self::$AUTH["IP"]["AUHT_67"]= "http://10.10.18.67:8390";
                self::$AUTH["URL"]="/auth/v1/validate";
                self::$COMMUNICATION["IP"]["COMMUNICATION"]= "http://10.10.18.75:8390";
                self::$COMMUNICATION["IP"]["COMMUNICATION"]= "http://10.10.18.67:8390";
                self::$COMMUNICATION["URL"]="";
        }
        
        static public function getValue($arrayName,$config,$type="")
        {
			self::initConfig();
			
			$array= self::$$arrayName;
			if(array_key_exists($config,$array))
			{
				if($type=="")
					$value= $array[$config];
				elseif(array_key_exists($type,$array[$config]))
					$value= $array[$config][$type];
				else
					$value= $array[$config]["DEFAULT"];
			}
			
			return $value;
        }

        

}
?>

