<?php
class SMSLib
{
    public $path;
    public $expDate;
    public $dis_entry_dt;
    public $minSmsSendTime   = 10;
    public $maxSmsSendTime   = 20;
    public $incomeDetail     = array();
    public $casteDetail      = array();
    public $mstatusDetail    = array();
    public $mtongueDetail    = array();
    public $educationDetail  = array();
    public $occupationDetail = array();
    public $heightDetail     = array();
    public $cityDetail       = array();
    public $countryDetail    = array();
    public $smsType;
    public $dbMaster;

    public function __construct($smsType = "")
    {
        $this->path = JsConstants::$docRoot;
        if (strstr($_SERVER["PHP_SELF"], "operations.php") || strstr($_SERVER["PHP_SELF"], "operations_dev.php") || strstr($_SERVER["PHP_SELF"], "symfony_index.php") || !function_exists('connect_db')) {
            include_once $this->path . "/profile/connect_db.php";
            $this->symfony = 1;
        } else {
            global $SITE_URL;
        }

        $this->php5_path = $php5_path ? $php5_path : "/etc/php5/apache2";
        $this->SITE_URL  = JsConstants::$siteUrl;
        $this->dbMaster  = connect_db();

        //if($smsType=="S"){
        include JsConstants::$docRoot . "/commonFiles/dropdowns.php";
        $this->incomeDetail     = $INCOME_DATA;
        $this->casteDetail      = $CASTE_DROP;
        $this->mstatusDetail    = $MSTATUS_DROP;
        $this->mtongueDetail    = $MTONGUE_DROP;
        $this->educationDetail  = $EDUCATION_LEVEL_NEW_DROP;
        $this->occupationDetail = $OCCUPATION_DROP;
        $this->heightDetail     = $HEIGHT_DROP;
        $this->cityDetail       = $CITY_INDIA_DROP;
        $this->countryDetail    = $COUNTRY_DROP;
        //}
        $this->smsType = $smsType;
    }
    public function getShortURL($longURL, $profileid = '', $email = '', $withoutLogin = '', $appendUrl = '')
    {
        // $longURL=str_replace('CMGFRMMMMJS=mobile','linkFromSMS=Y' , $longURL);
        include_once $this->path . "/classes/ShortURL.class.php";
        if (!$withoutLogin) {
            include_once $this->path . "/classes/authentication.class.php";
            $protect   = new protect();
            $checksum  = md5($profileid) . "i" . $profileid;
            $echecksum = $protect->js_encrypt($checksum);
            $longURL   = $longURL . "&echecksum=" . $echecksum . "&checksum=" . $checksum;
        }

        $shortURL  = new ShortURL();
        $SHORT_URL = $shortURL->setShortURL($longURL . $appendUrl);
        //echo $SHORT_URL . "\n";
        return $SHORT_URL;
    }
    public function getIncomeDetails($incomeKey)
    {
        foreach ($this->incomeDetail as $key => $subArray) {
            if ($subArray['VALUE'] == $incomeKey) {
                return $key;
                break;
            }
        }
    }

    public function getVariables($variable)
    {
        $varArr = array(
            "USERNAME"        => array("maxlength" => "8"),
            "FIELD_LIST"      => array("maxlength" => "30"),
            "PASSWORD"        => array("maxlength" => "16"),
            "MSTATUS"         => array("maxlength" => "13"),
            "MTONGUE"         => array("maxlength" => "12"),
            "DTOFBIRTH"       => array("maxlength" => "10"),
            "HEIGHT"          => array("maxlength" => "6"),
            "NAME"            => array("maxlength" => "12"),
            "BACK_MATCH_URL"  => array("maxlength" => "23"),
            "PAYMENT"         => array("maxlength" => "5"),
            "NOSLIKEME"       => array("maxlength", "3"),
            "ULOGIN"          => array("maxlength" => "23"),
            "URL_ACCEPT"      => array("maxlength" => "23"),
            "UDESPID"         => array("maxlength" => "23"),
            "CASTE"           => array("maxlength" => "12"),
            "MSTATUS"         => array("maxlength" => "13"),
            "EDU_LEVEL"       => array("maxlength" => "10"),
            "OCCUPATION"      => array("maxlength" => "10"),
            "CITY_RES"        => array("maxlength" => "10"),
            "ANAME"           => array("maxlength" => "11"),
            "ACITY"           => array("maxlength" => "15"),
            "SANAME"          => array("maxlength" => "13"),
            "COMPANY_NAME"    => array("maxlength" => 12),
            "OTHER_EMAIL"     => array("maxlength" => "40"), // added by Palash
            "PHONE_ISD_COMMA" => array("maxlength" => "17"), //      ,,
            "COUNTRY_RES"     => array("maxlength" => "10"), //added by nitesh
            "SERVNAME"        => array("maxlength" => "50"),
            "SHORT_DISC_LINK_URL"=> array("maxlength" => "50"),
        );
        return $varArr[$variable];
    }

    //Returns sms token value
    public function getTokenValue($messageToken, $tokenValue = array())
    {
    	print_r($tokenValue);
    	print_r("\n");
        if (!isset($tokenValue["DATA_TYPE"])) {
            $messageValue                          = $tokenValue;
            $messageValue["RECEIVER"]["USERNAME"]  = $tokenValue["USERNAME"];
            $messageValue["RECEIVER"]["PROFILEID"] = $tokenValue["PROFILEID"];
            $messageValue["RECEIVER"]["EMAIL"]     = $tokenValue["EMAIL"];
        } else {
            $messageValue                          = $tokenValue["DATA"];
            $messageValue["RECEIVER"]["USERNAME"]  = $tokenValue["RECEIVER"]["USERNAME"];
            $messageValue["RECEIVER"]["PROFILEID"] = $tokenValue["RECEIVER"]["PROFILEID"];
            $messageValue["RECEIVER"]["EMAIL"]     = $tokenValue["EMAIL"];
        }

        switch ($messageToken) {
        	case "DESCRIPTION_LINK" :
        		$longURL = $this->SITE_URL . "/profile/viewprofile.php?username=" . $tokenValue[USERNAME_ID]. "&CMGFRMMMMJS=mobile";
        			return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);
        			
        	case "USERNAME_ID": 
        		$USERNAME_ID= $tokenValue[USERNAME_ID];
        		return $USERNAME_ID;
            case "FIELD_LIST":
                    $FIELD_LIST = $this->getVariables("FIELD_LIST");
                    $fieldsList = strlen($tokenValue['editedFields']) <= $FIELD_LIST["maxlength"] ? $tokenValue['editedFields'] : substr($tokenValue['editedFields'], 0, $FIELD_LIST["maxlength"] - 2) . "..";
                    return $fieldsList;
                        break;
            case "WAS_WERE":                    
                    $wasWere = $tokenValue['WAS_WERE']; 
                    return $wasWere;
                        break;
            case "PHOTO_REJECTION_REASON":                    
                    $rejectReasonArr = explode(". or ", $messageValue['PHOTO_REJECTION_REASON'], 2);                    
                    return $rejectReasonArr[0];
                        break;
            case "SALES_ADDRESS_NOIDA":
                return "B-8,Sector 132,Noida-201301";

            case "USERNAME":
                $username = $this->getVariables("USERNAME");
                return strlen($messageValue["RECEIVER"]["USERNAME"]) <= $username["maxlength"] ? $messageValue["RECEIVER"]["USERNAME"] : substr($messageValue["RECEIVER"]["USERNAME"], 0, $username["maxlength"] - 2) . "..";

            case "OTHER_USERNAME":
                $username = $this->getVariables("USERNAME");
                return strlen($messageValue["USERNAME"]) <= $username["maxlength"] ? $messageValue["USERNAME"] : substr($messageValue["USERNAME"], 0, $username["maxlength"] - 2) . "..";
            case "USERNAME_FULL":
                return $messageValue["USERNAME"];
            case "PASSWORD":
                return $messageValue["PASSWORD"];
            case "EMAIL":
                return $messageValue["EMAIL"];

            case "OTHER_EMAIL":
                $other_email = $this->getVariables("OTHER_EMAIL");
                return strlen($messageValue["EMAIL"]) <= $other_email["maxlength"] ? $messageValue["EMAIL"] : substr($messageValue["EMAIL"], 0, $other_email["maxlength"] - 3) . "...";

            case "FTO_EXPIRY_DATE":
//                $expiryDate = date('d-M', JSstrToTime($messageValue['FTO_EXPIRY_DATE']. ' + 1 day'));
                $expiryDate = date('d-M', JSstrToTime($messageValue['FTO_EXPIRY_DATE']));
                return $expiryDate;
            case "VIRTUAL_NUMBER":
                return $messageValue["VIRTUAL_NUMBER"];
            case "DTOFBIRTH":
                $dtofbirth = $this->getVariables("DTOFBIRTH");
                return strlen($messageValue["DTOFBIRTH"]) <= $dtofbirth["maxlength"] ? $messageValue["DTOFBIRTH"] : substr($messageValue["DTOFBIRTH"], 0, $dtofbirth["maxlength"]);

            case "GENDER":
                //return "test";
                return $messageValue["GENDER"] == "M" ? "Male" : "Female";
                break;

            case "CASTE":
                $caste = $this->getVariables("CASTE");
                $html  = $this->casteDetail[$messageValue["CASTE"]];
                if (strstr($html, ": ")) {
                    $first      = strpos($html, ': ');
                    $casteValue = substr($html, $first + 2);
                } else {
                    $casteValue = $html;
                }

                return strlen($casteValue) <= $caste["maxlength"] ? $casteValue : substr($casteValue, 0, $caste["maxlength"] - 2) . "..";

            case "MSTATUS":
                $mstatus      = $this->getVariables("MSTATUS");
                $MSTATUS_DROP = $this->mstatusDetail[$messageValue["MSTATUS"]];
                return strlen($MSTATUS_DROP) <= $mstatus["maxlength"] ? $MSTATUS_DROP : substr($MSTATUS_DROP, 0, $mstatus["maxlength"] - 2) . "..";

            case "MTONGUE":
                $mtongue      = $this->getVariables("MTONGUE");
                $MTONGUE_DROP = $this->mtongueDetail[$messageValue["MTONGUE"]];
                return strlen($MTONGUE_DROP) <= $mtongue["maxlength"] ? $MTONGUE_DROP : substr($MTONGUE_DROP, 0, $mtongue["maxlength"] - 2) . "..";

            case "EDU_LEVEL":
                $edu_level = $this->getVariables("EDU_LEVEL");
                $EDU_LEVEL = $this->educationDetail[$messageValue["EDU_LEVEL"]];
                return strlen($EDU_LEVEL) <= $edu_level["maxlength"] ? $EDU_LEVEL : substr($EDU_LEVEL, 0, $edu_level["maxlength"] - 2) . "..";

            case "OCCUPATION":
                $occupation = $this->getVariables("OCCUPATION");
                $OCCUPATION = $this->occupationDetail[$messageValue["OCCUPATION"]];
                return strlen($OCCUPATION) <= $occupation["maxlength"] ? $OCCUPATION : substr($OCCUPATION, 0, $occupation["maxlength"] - 2) . "..";

            case "HEIGHT":
                $height = $this->getVariables("HEIGHT");
                $HEIGHT = htmlspecialchars_decode($this->heightDetail[$messageValue["HEIGHT"]]);
                return strlen($HEIGHT) <= $height["maxlength"] ? $HEIGHT : substr($HEIGHT, 0, $height["maxlength"]);

            case "AGE":
                return $messageValue["AGE"];

            case "WEIGHT":
                return $messageValue["WEIGHT"] ? $messageValue["WEIGHT"] . "Kgs" : "";

            case "INCOME":
                $currentIncomeDetails = $this->incomeDetail[$this->getIncomeDetails($messageValue["INCOME"])];
                if (!$currentIncomeDetails || $messageValue["INCOME"] == 0) {
                    return "no income";
                } else {
                    $min = $currentIncomeDetails["MIN_LABEL"];
                    $max = $currentIncomeDetails["MAX_LABEL"];
                    if ($min == "No Income") {
                        return "<" . $max;
                    }

                    if (stristr($max, "Above")) {
                        return ">" . $min;
                    }

                    if (stristr($min, "Lakh") && stristr($max, "Crore")) {
                        $min = substr($min, 0, -4); //trucate lakh
                        $min = trim($min) . "L"; //remove whitespace
                        $max = substr($max, 3); //remove Rs.
                        $max = substr($max, 0, -6);
                        $max = trim($max) . " Cr";
                        return $min . "-" . $max;
                    }

                    if ($currentIncomeDetails["TYPE"] == "RUPEES") {
                        $min = substr($min, 0, -4); //trucate lakh
                        $min = trim($min); //remove whitespace
                        $max = substr($max, 3); //remove Rs.
                        return $min . "-" . $max;
                    } elseif ($currentIncomeDetails["TYPE"] == "DOLLARS") {
                        $min_a   = explode(",", $min);
                        $min_val = $min_a[0];
                        $max_a   = explode(",", $max);
                        $max_val = substr($max_a[0], 1);
                        return $min_val . "-" . $max_val . "K";
                    }
                }

            case "CITY_RES":
                $city_res = $this->getVariables("CITY_RES");
                $CITY_RES = $this->cityDetail[$messageValue["CITY_RES"]];
                if ($CITY_RES) {
                    return strlen($CITY_RES) <= $city_res["maxlength"] ? $CITY_RES : substr($CITY_RES, 0, $city_res["maxlength"] - 2) . "..";
                } else {
                    $country_res = $this->getVariables("COUNTRY_RES");
                    $COUNTRY_RES = $this->countryDetail[$messageValue["COUNTRY_RES"]];
                    if ($COUNTRY_RES) {
                        return strlen($COUNTRY_RES) <= $country_res["maxlength"] ? $COUNTRY_RES : substr($COUNTRY_RES, 0, $country_res["maxlength"] - 2) . "..";
                    }

                }

            case "TOLLNO":
                return "18004196299";

            case "NOIDALANDL":
                return "0120-4393500";

            case "VALUEFRSTNO":
                return "9870803838";

            case "VERIFY_CODE":
                return "Y";

            case "PROF_PERCENT":
                return $messageValue["PROF_PERCENT"];

            case "ACCEPT_COUNT":
                return $messageValue["ACCEPT_COUNT"];

            case "ANAME":
                $aname = $this->getVariables("ANAME");
                $ANAME = $messageValue["CONTACT"]["SMS_NAME"];
                return strlen($ANAME) <= $aname["maxlength"] ? $ANAME : substr($ANAME, 0, $aname["maxlength"] - 2) . "..";

            case "AADDRSS":
                return $messageValue["CONTACT"]["ADDRESS"];

            case "ALANDL":
                return $messageValue["CONTACT"]["LANDLINE_1"];

            case "AMOBILE":
                return $messageValue["CONTACT"]["SMS_MOBILE"];

            case "ACITY":
                $acity = $this->getVariables("ACITY");
                $ACITY = $messageValue["CONTACT"]["BRANCH"];
                return strlen($ACITY) <= $acity["maxlength"] ? $ACITY : substr($ACITY, 0, $acity["maxlength"] - 2) . "..";

            case "EOI_COUNT":
                return $messageValue["EOI_COUNT"];
            case "VISITOR_COUNT":
                return $messageValue["VISITOR_COUNT"];

            case "PHOTO_REQUEST_COUNT":
                return $messageValue["PHOTO_REQUEST_COUNT"];

            case "PHTOUPNO":
                return $messageValue["PHOTO_UPLOAD_COUNT"];
            
            case "SHORT_DISC_LINK_URL":
                return $this->getShortURL($tokenValue["SHORT_DISC_LINK_URL"], $messageValue["RECEIVER"]["PROFILEID"], $messageValue["EMAIL"], '', '');

            case "URL_EDIT_PHONE":
                $longURL   = $this->SITE_URL . "/profile/viewprofile.php?ownview=1";
                $appendUrl = '#Contact';
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["EMAIL"], '', $appendUrl);
            case "URL_FAMILY":
                $longURL   = $this->SITE_URL . "/profile/viewprofile.php?ownview=1";
                $appendUrl = '#Family';
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["EMAIL"], '', $appendUrl);
            case "PHOTO_UPLOAD_URL":
                $longURL = $this->SITE_URL . "/social/addPhotos?noUseVar=1";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["EMAIL"]);
            case "URL_EDUCATION":
                $longURL   = $this->SITE_URL . "/profile/viewprofile.php?ownview=1";
                $appendUrl = '#Education';
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["EMAIL"], '', $appendUrl);

            case "URL_CAREER":
                $longURL   = $this->SITE_URL . "/profile/viewprofile.php?ownview=1";
                $appendUrl = '#Career';
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["EMAIL"], '', $appendUrl);

            case "PHOTO_REQUEST_SENT":
                $longURL = $this->SITE_URL . "/profile/contacts_made_received.php?page=photo&filter=M&CMGFRMMMMJS=mobile";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);
            case "FORGOT_PASSWORD_URL":
                $forgotPasswordStr = ResetPasswordAuthentication::getResetLoginStr($messageValue["RECEIVER"]["PROFILEID"]);
                $longURL           = $this->SITE_URL . "/common/resetPassword?" . $forgotPasswordStr;
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"], $withoutLogin = 1);
            case "APP_STORE_URL":
                $appStoreUrl = $this->SITE_URL . "/SMS-Download-Android-App";
                return $this->getShortURL($appStoreUrl, '', '', $withoutLogin = 1);
            case "MEMB_PAGE_URL":
                $longURL = $this->SITE_URL . "/profile/mem_comparison.php?from_source=memSms";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);
            case "URL_CONTACTS":
                $longURL = $this->SITE_URL . "/profile/contacts_made_received.php?CMGFRMMMMJS=mobile";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);

            case "PHOTO_UPLOADER_PROFILE":
                $longURL = $this->SITE_URL . "/profile/viewprofile.php?username=" . $messageValue["USERNAME"] . "&CMGFRMMMMJS=mobile";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);

            case "MATCH_COUNT":
                return $messageValue["MATCH_COUNT"];

            case "HOROSCOPE_UPLOADER":
                return $messageValue["USERNAME"];

            case "HOROSCOPE_UPLOADER_PROFILE":
                $longURL = $this->SITE_URL . "/profile/viewprofile.php?username=" . $messageValue["USERNAME"] . "&CMGFRMMMMJS=mobile";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);

            case "BACK_MATCH_URL":
                $url = $this->SITE_URL . "/search/reverseDpp?CMGFRMMMMJS=mobile";
                return $this->getShortURL($url, $messageValue["PROFILEID"], $messageValue["EMAIL"]);
            //Dependency on Lavesh
            case "NOSLIKEME":
                $profileid       = $messageValue["PROFILEID"];
                $profileChecksum = md5($profileid) . "i" . $profileid;
                $url             = $this->SITE_URL . "/search/reverseDpp?profileChecksum=" . $profileChecksum . "&callingSource=sms";
                file_get_contents($url);
                include_once $this->path . "/profile/connect_functions.inc";
                if ($this->symfony) {
                    $count = JsMemcache::getInstance()->get("SMS_MEM_LOOK_ME");
                } else {
                    $count = memcache_call("SMS_MEM_LOOK_ME");
                }

                return $count;

            case "URL_CONTACTS":
                $url = $this->SITE_URL . "/profile/contacts_made_received.php?CMGFRMMMMJS=mobile";
                return $this->getShortURL($url, $messageValue["PROFILEID"], $messageValue["EMAIL"]);

            case "URL_ACCEPT":
                $url = $this->SITE_URL . "/profile/contacts_made_received.php?page=accept&filter=R&CMGFRMMMMJS=mobile";
                return $this->getShortURL($url, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);

            case "URL_EOI":
                $url = $this->SITE_URL . "/profile/contacts_made_received.php?page=eoi&filter=R&CMGFRMMMMJS=mobile";
                return $this->getShortURL($url, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["EMAIL"]);

            case "URL_OTHER_PROFILE":
                $longURL = $this->SITE_URL . "/profile/viewprofile.php?username=" . $messageValue["USERNAME"] . "&stype=" . SearchTypesEnums::CONTACT_DETAIL_SMS;
                return $this->getShortURL($longURL, $messageValue["PROFILEID"], '', 'Y');
    
            case "URL_PROFILE":
                $longURL = $this->SITE_URL . "/profile/viewprofile.php?username=" . $messageValue["USERNAME"] . "&CMGFRMMMMJS=mobile";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);
            case "URL_EDIT_HOROSCOPE":
                $longURL = $this->SITE_URL . "/P/viewprofile.php?username=" . $messageValue["USERNAME"] . "&CMGFRMMMMJS=mobile&ownview=1&EditWhatNew=AstroData";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);

            case "EOI_PROFILE":
                $longURL = $this->SITE_URL . "/profile/viewprofile.php?username=" . $messageValue["USERNAME"] . "&CMGFRMMMMJS=mobile&responseTracking=" . JSTrackingPageType::SMS;
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);

            case "EDIT_CONTACT_LAYER":
                $longURL = $this->SITE_URL . "/profile/viewprofile.php?username=" . $messageValue["RECEIVER"]["USERNAME"] . "&CMGFRMMMMJS=mobile";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);

            case "PHOTO_UPLOADER":
                return $messageValue["USERNAME"];

            case "EXPDATE":
                return $this->expDate;

            case "AFTREXPDATE":
                list($yy, $mm, $dd) = explode("-", $this->expDate);
                $timestamp          = mktime(0, 0, 0, $mm, $dd + 10, $yy);
                $aftrexpdate        = date('Y-m-d', $timestamp);
                return $aftrexpdate;

            case "PHOTOHELPNO":
                return "9971540202";

            case "DISCOUNT":
                return $messageValue["ATSDISCOUNT"];

            case "DISCOUNTDATE":
                list($yy, $mm, $dd) = explode("-", $this->dis_entry_dt);
                $date_tstamp        = mktime(0, 0, 0, $mm, $dd + 7, $yy);
                $day_format         = date("Y-m-d", $date_tstamp);
                return $day_format;
            case "UDESPID":
                $url = $this->SITE_URL . "/search/partnermatches?CMGFRMMMMJS=mobile";
                return $this->getShortURL($url, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);
            case "URL_MATCH":
                $url = $this->SITE_URL . "/profile/contacts_made_received.php?page=matches&filter=R&CMGFRMMMMJS=mobile";
                return $this->getShortURL($url, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);
            case "PAYMENT":
                return $messageValue["PAYMENT"];
            case "FRDDPP":
                $profileid       = $messageValue["PROFILEID"];
                $profileChecksum = md5($profileid) . "i" . $profileid;
                $url             = $this->SITE_URL . "/search/partnermatches?profileChecksum=" . $profileChecksum . "&callingSource=sms";
                file_get_contents($url);
                include_once $this->path . "/profile/connect_functions.inc";
                if ($this->symfony) {
                    $count = JsMemcache::getInstance()->get("SMS_MEM_LOOK");
                } else {
                    $count = memcache_call("SMS_MEM_LOOK");
                }

                return $count >= 347 ? $count : 347;

            case "OWN_HOUSE":
                if ($messageValue["OWN_HOUSE"] == 'Y') {
                    return "Own House,";
                } else {
                    return "";
                }

            case "COMPANY":
                $cname = $this->getVariables("COMPANY_NAME");
                $CNAME = $messageValue["COMPANY_NAME"];
                if ($CNAME == '') {
                    return "";
                } else {
                    return strlen($CNAME) <= $cname["maxlength"] ? "in " . $CNAME : "in " . substr($CNAME, 0, $cname["maxlength"] - 2) . "..";
                }

            case "PHOTO_EMAIL_ID":
                return "photo@js1.in";
            case "FAMILY_INCOME":
                $currentIncomeDetails = $this->incomeDetail[$this->getIncomeDetails($messageValue["INCOME"])];
                if (!$currentIncomeDetails || $messageValue["FAMILY_INCOME"] == 0) {
                    return "no income";
                } else {
                    $min = $currentIncomeDetails["MIN_LABEL"];
                    $max = $currentIncomeDetails["MAX_LABEL"];
                    if ($min == "No Income") {
                        return "<" . $max;
                    }

                    if (stristr($max, "Above")) {
                        return ">" . $min;
                    }

                    if ($currentIncomeDetails["TYPE"] == "RUPEES") {
                        $min = substr($min, 0, -4); //trucate lakh
                        $min = trim($min); //remove whitespace
                        $max = substr($max, 3); //remove Rs.
                        return $min . "-" . $max;
                    } elseif ($currentIncomeDetails["TYPE"] == "DOLLARS") {
                        $min_a   = explode(",", $min);
                        $min_val = $min_a[0];
                        $max_a   = explode(",", $max);
                        $max_val = substr($max_a[0], 1);
                        return $min_val . "-" . $max_val . "K";
                    }
                }
            case "HOROSCOPE_HELPLINE_NUMBER":
                return "9971176905";
            case "FTO_AGENT":
                $aname = $this->getVariables("ANAME");
                $ANAME = $messageValue["FTO_AGENT"];
                return strlen($ANAME) <= $aname["maxlength"] ? $ANAME : substr($ANAME, 0, $aname["maxlength"] - 2) . "..";
            case "SANAME":
                $saname = $this->getVariables("SANAME");
                $SANAME = $messageValue["SANAME"];
                return strlen($SANAME) <= $saname["maxlength"] ? $SANAME : substr($SANAME, 0, $saname["maxlength"] - 2) . "..";
            case "SAPHONE":
                return $messageValue["SAPHONE"];
            case "KYC_PAGE":
                $url = $this->SITE_URL . "?CMGFRMMMMJS=mobile";
                return $this->getShortURL($url, $messageValue["PROFILEID"], $messageValue["EMAIL"]);

            case "VD_DISCOUNT":
                return $messageValue["VD_DISCOUNT"];

            case "VD_END_DT":
                return $messageValue["VD_END_DT"];

            case "VD_URL":
                $longURL = $this->SITE_URL . "/membership/jspc?from_source=VD_SMS_ALERT";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);

            case "OTHER_ISD":
                return $messageValue["ISD"];

            case "PHONE_ISD_COMMA":
                if (($messageValue["SHOWPHONE_MOB"] != 'N') && ($messageValue["PHONE_MOB"])) {
                    $mob     = $messageValue["ISD"] . $messageValue["PHONE_MOB"];
                    $mob_len = $this->getVariables("PHONE_ISD_COMMA");
                    $mob     = strlen($mob) <= $mob_len["maxlength"] ? $mob : substr($mob, 0, $mob_len["maxlength"] - 2) . "..";
                    $mob     = '+' . $mob . ', ';
                } else {
                    $mob = '';
                }

                return $mob;

            case "ISD_PHONE_MOB":
                if ($messageValue["ISD_PHONE_MOB"]) {
                    $mob = $messageValue["ISD_PHONE_MOB"];
                } else {
                    $mob = '';
                }

                return $mob;

            case "ISD_ALT_MOB_COMMA":
                if ($messageValue["ISD_ALT_MOB_COMMA"]) {
                    $mob = ", " . $messageValue["ISD_ALT_MOB_COMMA"];
                } else {
                    $mob = '';
                }

                return $mob;

            case "ISD_LANDLINE_COMMA":
                if ($messageValue["ISD_LANDLINE_COMMA"]) {
                    $mob = ', ' . $messageValue["ISD_LANDLINE_COMMA"];
                } else {
                    $mob = '';
                }

                return $mob;

            case "INCOMP_URL":
                $longURL = $this->SITE_URL . "/e/tracking/Incomplete?channel=INCOM_SMS&EditWhatNew=incompletProfile";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);

            case "ACCEPTANCE_COUNT":
                return $messageValue["ACCEPTANCE_COUNT"];

            case "RC_URL":
                $longURL = $this->SITE_URL . "/membership/jsms?from_source=REQUEST_CALLBACK";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);

            case "EXEC_NAME":
                return $messageValue["EXEC_NAME"];
            case "OTP":
                //return "test";
                return $messageValue["OTP"];
                //added case for sending sms to user in case an entry goes into bounce mails
            case "EDIT_CONTACT_ON_BOUNCE":
                $longURL = $this->SITE_URL . "/profile/viewprofile.php?" . $messageValue["RECEIVER"]["USERNAME"] . "&CMGFRMMMMJS=mobile&ownview=1";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"], '', "#Contact");

            case "SURVEY_SMS_30":
                return "https://www.surveymonkey.com/r/NRZ7S8C";
            case "SURVEY_SMS_90":
                return "https://www.surveymonkey.com/r/NTHDX6K";
            case "ISD_MOB":
                return $messageValue['PHONE_MOB'];
            case "SERVNAME":
                $memHandlerObj = new MembershipHandler();
                $servName = $memHandlerObj->getRenewCronSMSServiceName($tokenValue['PROFILEID']);
                return $servName;
            case "MEM_AUTO_LOGIN": 
                $longURL = $this->SITE_URL . "/membership/jspc?from_source=CRM_SMS_OFFER";
                return $this->getShortURL($longURL, $messageValue["RECEIVER"]["PROFILEID"], $messageValue["RECEIVER"]["EMAIL"]);
            case "DISCOUNT_TEXT": 
                return $tokenValue['DISCOUNT_TEXT'];
            case "BRANCH_ADDRESS": 
                return $tokenValue['BRANCH_ADDRESS'];
            case "CRM_AGENT": 
                return $tokenValue['CRM_AGENT'];
            case "CRM_SMS_APP_URL":
                $appStoreUrl = $this->SITE_URL . "/SMS-Download-Android-App";
                return $this->getShortURL($appStoreUrl, '', '', $withoutLogin = 1);
            case "LINK_DEL":
               $linkToDel = $this->SITE_URL . "/settings/jspcSettings?hideDelete=1";
                return $this->getShortURL($linkToDel, '', '', $withoutLogin = 0);

            case "REPORT_INVALID_PHONE_ISD_COMMA":

                $toSendForPrivacy = 0;

                if($messageValue["SHOWPHONE_MOB"] == 'C')
                { 
                    include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
                   $contactDetails = Contacts::getContactsTypeCache($messageValue["RECEIVER"]["PROFILEID"],$messageValue["PROFILEID"]);
                   $contactArr = explode('_',$contactDetails);
                   $smaller = $messageValue["RECEIVER"]["PROFILEID"] > $messageValue["PROFILEID"] ? $messageValue["PROFILEID"] :
                   $messageValue["RECEIVER"]["PROFILEID"]; 
                   $type = $contactArr[0];
                   $senderReceiver = $contactArr[1];

                   if($type == 'A' || $smaller == $messageValue["PROFILEID"] && $senderReceiver == 'S' && $type == 'I')
                   {
                        $toSendForPrivacy = 1;
                   }
                   else
                   {
                        $toSendForPrivacy = 0;
                   }
                }
                if ($toSendForPrivacy || (($messageValue["SHOWPHONE_MOB"] == 'Y') && ($messageValue["PHONE_MOB"]))) { 
                    $mob     = $messageValue["ISD"] . $messageValue["PHONE_MOB"];
                    $mob_len = $this->getVariables("PHONE_ISD_COMMA");
                    $mob     = strlen($mob) <= $mob_len["maxlength"] ? $mob : substr($mob, 0, $mob_len["maxlength"] - 2) . "..";
                    $mob     = '+' . $mob . ' , ';
                } 
                else {
                    $mob = '';
                }
                return $mob;

            default:
                return "";
        }
    }

    public function getMobileCorrectFormat($mobile, $isd = "", $sendToInt = false)
    {
        include_once JsConstants::$docRoot . "/commonFiles/sms_inc.php";
        $mobile = getMobileCorrectFormat($mobile);
        if (checkMobilePhone($mobile)) {
            if ($isd) {
                if ($isd == 91 || $sendToInt) {
                    return $mobile;
                } else {
                    return false;
                }

            } else {
                return $mobile;
            }

        }
        return false;
    }

    public function inSmsSendTimeRange()
    {
        include_once JsConstants::$docRoot . "/commonFiles/sms_inc.php";
        $hour = getIndianTime();
        if ($hour >= $this->minSmsSendTime && $hour <= $this->maxSmsSendTime) {
            return true;
        }

        return false;
    }

    public function errormail($msg_sql, $error, $commentText = '')
    {
        include_once JsConstants::$docRoot . "/commonFiles/comfunc.inc";
        $cc      = 'palash.chordia@jeevansathi.com,nitesh.s@jeevansathi.com,prashant.pal@jeevansathi.com';
        $to      = 'nitesh.s@jeevansathi.com';
        $msg     = '';
        $subject = "Scheduled SMS sendSmsAlert cron error mail";
        if ($commentText) {
            $commentText = $commentText . '<br/><br/>';
        }

        $msg = $commentText . 'Mysql Error occured:<br/><br/>' . $error . '<br/><br/> while executing the query: <br/><br/> ' . $msg_sql . '<br/><br/>Warm Regards';
            //echo $msg."\n\n\n";
        send_email($to, $msg, $subject, "", $cc);
    }

    public function bestMatchMail($count, $countSmsGenerated, $countBestMatchCalculated, $num)
    {
        include_once JsConstants::$docRoot . "/commonFiles/comfunc.inc";
        $cc      = 'esha.jain@jeevansathi.com,pankaj.khandelwal@jeevansathi.com';
        $to      = 'nitesh.s@jeevansathi.com';
        $subject = "Match of the day Sms Calculation mail";
        $msg     = "Calculation Number = " . ($num + 1) . "<br/> Total Profile: " . $count . " <br/> Total Sms Generated: " . $countSmsGenerated . "<br/> Total New Best Match Calculated: " . $countBestMatchCalculated . "<br/><br/>Warm Regards";
        send_email($to, $msg, $subject, "", $cc);
    }

}
