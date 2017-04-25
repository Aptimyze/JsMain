<?php
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
class ScheduleSms
{
    var $scheduleSettings = array();
    var $helplineContacts = array();
    var $helplineContactToAssign = array();
    var $timeCriteria = array();
    var $sms = array();
    var $smsDetail = array();
    var $dbSlave;
    var $dbMaster;
    var $dbMatch;
    var $dbShards = array();
    var $tempJPROFILE;
    var $smsSubscription = array();
    var $smsPerDay = 3;
    
    function __construct()
    {
        
        /*****************/
        $mysqlObj = new Mysql;
        global $activeServers, $noOfActiveServers, $slave_activeServers;
        for ($activeServerId = 0; $activeServerId < $noOfActiveServers; $activeServerId++) {
            $myDbName           = getActiveServerName($activeServerId, "slave");
            $myDbarr[$myDbName] = $mysqlObj->connect("$myDbName");
            mysql_query('set session wait_timeout=30000,interactive_timeout=30000,net_read_timeout=10000', $myDbarr[$myDbName]);
        }
        $db_slave = connect_slave();
        mysql_query('set session wait_timeout=30000,interactive_timeout=30000,net_read_timeout=10000', $db_slave);
        /*****************/
        $match_slave = connect_slave81();
        mysql_query('set session wait_timeout=30000,interactive_timeout=30000,net_read_timeout=10000', $match_slave);
        $db_master = connect_db();
        mysql_query('set session wait_timeout=30000,interactive_timeout=30000,net_read_timeout=10000', $db_master);
        $this->mysqlObj = $mysqlObj;
        $this->dbSlave  = $db_slave;
        $this->dbMaster = $db_master;
        $this->dbMatch  = $match_slave;
        $this->dbShards = $myDbarr;
        include_once(JsConstants::$docRoot."/classes/SMSLib.class.php");
	include_once(JsConstants::$docRoot."/profile/connect_functions.inc");
	include_once(JsConstants::$docRoot."/profile/horoscope_upload.inc");
        $this->SMSLib                  = new SMSLib("S");
        $this->scheduleSettings        = $this->getScheduleSmsSettings();
        $this->helplineContacts        = $this->getHelplineContacts();
        $this->helplineContactToAssign = $this->getHelplineContactAssign();
    }
    function getScheduleSmsSettings()
    {
        $weekDay  = strtoupper(date("D", time()));
        $settings = array();
        $sql      = "select * from SMS_TYPE where SMS_TYPE IN ('D','" . $weekDay . "') and status='Y' order by priority";
        $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured in getScheduleSmsSeting() function while getting SMS details for the day in ScheduleSms.class.php");
        while ($row = mysql_fetch_assoc($res)) {
            $settings[$row["SMS_KEY"]][$row["ID"]]              = $row;
            $settings[$row["SMS_KEY"]][$row["ID"]]["VARIABLES"] = $this->getSmsVariables($row["MESSAGE"]);
            $timeCriteria[$row["SMS_KEY"]]                      = $row["TIME_CRITERIA"];
            $smsSubscription[$row["SMS_KEY"]]                   = $row["SMS_SUBSCRIPTION"];
        }
        $this->timeCriteria    = $timeCriteria; //Key, Time criteria map
        $this->smsSubscription = $smsSubscription;
        return $settings;
    }
    function getHelplineContactAssign()
    {
        $states = array_keys($this->helplineContacts);
        foreach ($states as $k => $val)
            $helplineContactToAssign[$val] = 0;
        return $helplineContactToAssign;
    }
    function getHelplineContacts()
    {
        //		$sql = "select NAME, STATE, CONTACT_PERSON, PHONE, MOBILE, ADDRESS from CONTACT_US";
        $sql = "select * from SMS_CONTACTS";
        $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while getting helpline contact information from SMS_CONTACTS in getHelplineContacts() function in Schedule.class.php");
        while ($row = mysql_fetch_assoc($res)) {
            $state                      = array_search($row["BRANCH"], $this->SMSLib->cityDetail);
            $helplineContacts[$state][] = $row;
        }
        return $helplineContacts;
    }
    function getContactPersonDetail($city = '')
    {
        $all_contact_for_city = $this->helplineContacts[$city];
        if(!$all_contact_for_city) return false;
        $contact              = $this->assignContactRoundRobin($all_contact_for_city, $city);
        if ($contact)
            return $contact;
        else
            return false;
    }
    function assignContactRoundRobin($cityArr, $city)
    {
        $optionCount = count($cityArr);
        if ($optionCount == 1)
            return $this->helplineContacts[$city][0];
        else {
            $arrayKey = $this->helplineContactToAssign[$city];
            $contact  = $cityArr[$arrayKey];
            if (($arrayKey + 1) == $optionCount)
                $this->helplineContactToAssign[$city] = 0;
            else
                $this->helplineContactToAssign[$city] = $arrayKey + 1;
            return $contact;
        }
    }
    function getKeyDate($days)
    {
        if ($days == 0) {
            $today_timestamp = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
            return $today_timestamp;
        } else //backdate
            {
            $hrs                = $days * 24;
            //			$days = $days+20;
            $day_back_timestamp = mktime(date("H") - $hrs, date("i"), date("s"), date("m"), date("d"), date("Y"));
            return $day_back_timestamp;
        }
        /*	elseif($days<0)
        {
        $day_frwd_timestamp=mktime(0,0,0,date("m"),date("d")+$days,date("Y"));
        return $day_frwd_timestamp;
        }*/
    }
    function processData($key, $num = '')
    {
        $day_timestamp       = $this->getKeyDate($this->timeCriteria[$key]);
        $back_day_format     = date("Y-m-d H:i:s", $day_timestamp);
        $back_day            = date("Y-m-d", $day_timestamp);
        $timestamp_24        = JSstrToTime("+1 day", $day_timestamp);
        $time24_format       = date("Y-m-d H:i:s", $timestamp_24);
        $current_time        = $this->getKeyDate(0);
        $today_date_format   = date("Y-m-d H:i:s", $current_time);
        $todays_date         = date("Y-m-d", $current_time);
        $forward_date        = JSstrToTime("+1 day", $current_time);
        $forward_date_format = date("Y-m-d H:i:s", $forward_date);
        $finalSms            = array();
        $details1            = array();
        $details2            = array();
        $temp                = array();
        $chunk               = 2000;
	if(in_array($key,array("EOI","ACCEPT","PHOTO_REQUEST")))
	{
                $sql_ins = "REPLACE INTO newjs.TEMP_SMS_TIMELIMITS (SMS_KEY,TIME1,TIME2) VALUES ('".$key."','".$back_day_format."','".$today_date_format."')";
                mysql_query($sql_ins, $this->dbMaster) or $this->SMSLib->errormail($sql_ins, mysql_errno() . ":" . mysql_error(), "Error occured while inserting sms time limits temp data in newjs.TEMP_SMS_TIMELIMITS in setTempJPROFILE() function");
	}

        switch ($key) {
            case "MADELIVE_1":
            case "MADELIVE_7":
            case "MADELIVE_35":
            case "MADELIVE_28":
            case "MADELIVE_14":
            case "MADELIVE_21":
            case "MADELIVE_10":
                $sql = "select " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " where !( GENDER =  'M' AND AGE < 24 ) AND MOB_STATUS='Y' AND ENTRY_DT BETWEEN '" . $back_day_format . "' AND '" . $time24_format . "' " . $this->getSmsSubscriptionCriteria($key);
                if ($key == "MADELIVE_21")
                    $sql .= " AND HAVEPHOTO IN ('N','')";
                echo $sql . "\n";
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$row["PROFILEID"]] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
                            $agentDetails = $this->allotedToAgent($row_k);
                            if ($agentDetails["BRANCH"] == "Noida") {
                                continue;
                            }
                            if (!is_array($agentDetails)) {
                                $agentDetails = $this->getContactPersonDetail($row_v["CITY_RES"]);
                            }
                            if (!is_array($agentDetails))continue;
                            $finalSms[$key][$row_k]["RECEIVER"]        = $row_v;
                            $finalSms[$key][$row_k]["DATA_TYPE"]       = "SELF";
                            $finalSms[$key][$row_k]["DATA"]            = $row_v;
                            $finalSms[$key][$row_k]["DATA"]["CONTACT"] = $agentDetails;
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;

                case "MADELIVE_30":
                case "MADELIVE_90":
                //$lastLoginDtCheck = date("Y-m-d",strtotime("-14 days"));
                $sql = "select " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " where MOB_STATUS='Y' AND ISD='91' AND SUBSCRIPTION = '' AND ENTRY_DT BETWEEN '" . $back_day_format . "'AND '" . $time24_format . "' " . $this->getSmsSubscriptionCriteria($key);
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk)
                    {
                        $row_v[$row["PROFILEID"]] = $row;
                        $trans++;
                    }
                    if ($row_v) {
                        foreach ($row_v as $k1 => $v1) {
                            $finalSms[$key][$k1]["DATA"]      = $v1;
                            $finalSms[$key][$k1]["DATA_TYPE"] = "SELF";
                            $finalSms[$key][$k1]["RECEIVER"]  = $v1;

                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }             
                break;

                case "PROFILECOMPLETION_2":
                $sql = "select " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " where VERIFY_ACTIVATED_DT < ( NOW( ) - INTERVAL 48 HOUR ) AND VERIFY_ACTIVATED_DT > (NOW( ) - INTERVAL 96 HOUR) AND FAMILYINFO='' AND MOB_STATUS='Y'";
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk)
                    {
                        $row_v[$row["PROFILEID"]] = $row;
                        $trans++;
                    }
                    if ($row_v) {
                                foreach ($row_v as $k1 => $v1) {
                                        $finalSms[$key][$k1]["DATA"]      = $v1;
                                		$finalSms[$key][$k1]["DATA_TYPE"] = "SELF";
                                		$finalSms[$key][$k1]["RECEIVER"]  = $v1;
                                    
                                }
                                $this->smsDetail = $finalSms;
                                $this->getSmsContent($key);
                                $this->insertInSmsLog();
                                unset($this->smsDetail);
                            }
                        }
                        break;
                
                case "PROFILECOMPLETION_3":
                $sql = "select " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " where VERIFY_ACTIVATED_DT < ( NOW( ) - INTERVAL 96 HOUR ) AND VERIFY_ACTIVATED_DT > (NOW( ) - INTERVAL 144 HOUR) AND EDUCATION ='' AND MOB_STATUS='Y'";
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk)
                    {
                        $row_v[$row["PROFILEID"]] = $row;
                        $trans++;
                    }
                    if ($row_v) {
                                foreach ($row_v as $k1 => $v1) {
                                        $finalSms[$key][$k1]["DATA"]      = $v1;
                                		$finalSms[$key][$k1]["DATA_TYPE"] = "SELF";
                                		$finalSms[$key][$k1]["RECEIVER"]  = $v1;
                                    
                                }
                                $this->smsDetail = $finalSms;
                                $this->getSmsContent($key);
                                $this->insertInSmsLog();
                                unset($this->smsDetail);
                            }
                        }
                        break;

                case "PROFILECOMPLETION_4":
				$sql = "select " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " where VERIFY_ACTIVATED_DT < ( NOW( ) - INTERVAL 144 HOUR ) AND VERIFY_ACTIVATED_DT > (NOW( ) - INTERVAL 192 HOUR) AND JOB_INFO ='' AND MOB_STATUS='Y'";
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function"); 
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk)
                    {
                        $row_v[$row["PROFILEID"]] = $row;
                        $trans++;
                    }
                    if ($row_v) {
                                foreach ($row_v as $k1 => $v1) {
                                        $finalSms[$key][$k1]["DATA"]      = $v1;
                                		$finalSms[$key][$k1]["DATA_TYPE"] = "SELF";
                                		$finalSms[$key][$k1]["RECEIVER"]  = $v1;
                                    
                                }
                                $this->smsDetail = $finalSms;
                                $this->getSmsContent($key);
                                $this->insertInSmsLog();
                                unset($this->smsDetail);
                            }
                        }
                        break;
                


            case "PHOTO_REQUEST":
                foreach ($this->dbShards as $k => $conn) {
                    $sql = "SELECT PROFILEID_REQ_BY AS REQ_RECEIVER,PROFILEID AS REQUESTEE FROM newjs.PHOTO_REQUEST where DATE BETWEEN '" . $back_day_format . "' AND '" . $today_date_format . "'  AND SEEN!='Y' ORDER BY  `DATE` DESC ";
                    //$sql = "SELECT PROFILEID_REQ_BY AS REQ_RECEIVER,PROFILEID AS REQUESTEE FROM PHOTO_REQUEST where DATE BETWEEN '2012-01-01 00:00:00' AND '".$back_day_format." 23:59:59'";
                    $res = mysql_query($sql, $conn) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                    $count       = mysql_num_rows($res);
                    $chunk       = 2000;
                    $totalChunks = ceil($count / $chunk);
                    for ($j = 0; $j < $totalChunks; $j++) {
                        $temp      = array();
                        $eoi_count = array();
                        $finalSms  = array();
                        $trans     = 0;
                        $row_pool  = array();
                        $skip      = $j * $chunk;
                        mysql_data_seek($res, $skip);
                        while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                            if ($k == $this->ShardIdForProfile($row["REQ_RECEIVER"])) {
                                $temp[$row["REQ_RECEIVER"]][] = $row["REQUESTEE"];
                                $trans++;
                            }
                        }
                        if ($temp) {
                            $req_receiver = array_keys($temp);
                            $req_sender   = $this->find_array_values($temp);
                            $receiver     = implode(",", $req_receiver);
                            $details1     = $this->getSenderDetail($receiver, $key);
                            $sender       = implode(",", $req_sender);
                            $details2     = $this->getReceiverDetail($sender);
                            foreach ($temp as $k => $v) {
                                    if ($details1[$k] && $details2[$v[0]]) {
                                        $finalSms[$key][$k]["RECEIVER"]  = $details1[$k];
                                        $finalSms[$key][$k]["RECEIVER"]["COUNT"]           = count($v);
					
                                        $finalSms[$key][$k]["DATA_TYPE"] = "OTHER";
                                        $finalSms[$key][$k]["DATA"]      = $details2[$v[0]];
                                        $finalSms[$key][$k]["DATA"]["PHOTO_REQUEST_COUNT"] = count($v)-1;
                                    }
                            }
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "PHOTO_REQ_WEEK":
                $sql = "select " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " WHERE MOB_STATUS='Y' AND HAVEPHOTO IN ('N','') " . $this->getSmsSubscriptionCriteria($key);
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                echo $sql . "\n";
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms           = array();
                    $trans              = 0;
                    $row_pool           = array();
                    $skip               = $j * $chunk;
                    $considered_profile = array();
                    $profile_str        = '';
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$row["PROFILEID"]] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        $considered_profile = array_keys($row_pool);
                        $profile_str        = implode("','", $considered_profile);
                        $profile_str        = "'" . $profile_str . "'";
                        foreach ($this->dbShards as $k => $conn) {
                            $temp = array();
                            $sql  = "SELECT PROFILEID_REQ_BY AS PHOTO_REQ_RECEIVER,PROFILEID AS PHOTO_REQUESTEE FROM PHOTO_REQUEST WHERE PROFILEID_REQ_BY IN (" . $profile_str . ")";
                            //$sql = "SELECT PROFILEID_REQ_BY AS PHOTO_REQ_RECEIVER,PROFILEID AS PHOTO_REQUESTEE FROM PHOTO_REQUEST where DATE BETWEEN '2012-01-01' AND '".$today_date_format."'";
                            $res = mysql_query($sql, $conn) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function while executing on shards: " . $conn);
                            while ($row = mysql_fetch_assoc($res)) {
                                if ($k == $this->ShardIdForProfile($row["PHOTO_REQ_RECEIVER"]))
                                    $temp[$row["PHOTO_REQ_RECEIVER"]][] = $row["PHOTO_REQUESTEE"];
                            }
                            if ($temp) {
                                $req_receiver = array_keys($temp);
                                $req_sender   = $this->find_array_values($temp);
                                $details      = $this->getDetailArr($req_sender, 'getReceiverDetail');
                                foreach ($temp as $k1 => $v1) {
                                    if ($row_pool[$k1] && $details[$v1[0]]) {
                                        $finalSms[$key][$k1]["RECEIVER"]                    = $row_pool[$k1];
                                        $finalSms[$key][$k1]["RECEIVER"]["COUNT"]           = count($v1);
                                        $finalSms[$key][$k1]["DATA_TYPE"]                   = "OTHER";
                                        $finalSms[$key][$k1]["DATA"]                        = $details[$v1[0]];
                                        $finalSms[$key][$k1]["DATA"]["PHOTO_REQUEST_COUNT"] = count($v1);
                                    }
                                }
                            }
                            $this->smsDetail = $finalSms;
                            $this->getSmsContent($key);
                            $this->insertInSmsLog();
                            unset($this->smsDetail);
                        }
                    }
                }
                break;
            case "ADD_PHOTO_OFFLINE":
                $sql = "select " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " where MOB_STATUS='Y' AND SOURCE like 'S\_%' AND LAST_LOGIN_DT >'" . $back_day_format . "' AND HAVEPHOTO IN ('N', '')" . $this->getSmsSubscriptionCriteria($key);
                echo $sql . "\n";
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms           = array();
                    $trans              = 0;
                    $row_pool           = array();
                    $skip               = $j * $chunk;
                    $considered_profile = array();
                    $profile_str        = '';
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$row["PROFILEID"]] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
                            $finalSms[$key][$row_k]["RECEIVER"]  = $row_v;
                            $finalSms[$key][$row_k]["DATA_TYPE"] = "SELF";
                            $finalSms[$key][$row_k]["DATA"]      = $row_v;
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "PHOTO_REQ_OFF_WEEK":
                $sql = "select " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " where MOB_STATUS='Y' AND SOURCE like 'S\_%' AND HAVEPHOTO IN ('N','')" . $this->getSmsSubscriptionCriteria($key);
                //$sql = "select ".$this->getJPROFILEFields()." from ".$this->tempJPROFILE." where ENTRY_DT BETWEEN '2012-01-01 00:00:00' AND '".$back_day_format." 23:59:59'";
                echo $sql . "\n";
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $chunk       = 10;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms           = array();
                    $trans              = 0;
                    $row_pool           = array();
                    $considered_profile = array();
                    $profile_str        = '';
                    $skip               = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$row["PROFILEID"]] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        $considered_profile = array_keys($row_pool);
                        $profile_str        = implode("','", $considered_profile);
                        $profile_str        = "'" . $profile_str . "'";
                        foreach ($this->dbShards as $k => $conn) {
                            $temp         = array();
                            $req_receiver = array();
                            $details      = array();
                            $sql          = "SELECT PROFILEID_REQ_BY AS PHOTO_REQ_RECEIVER,PROFILEID AS PHOTO_REQUESTEE FROM PHOTO_REQUEST where PROFILEID_REQ_BY IN (" . $profile_str . ")";
                            $res = mysql_query($sql, $conn) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function while executing on shards: " . $conn);
                            while ($row = mysql_fetch_assoc($res)) {
                                if ($k == $this->ShardIdForProfile($row["PHOTO_REQ_RECEIVER"]))
                                    $temp[$row["PHOTO_REQ_RECEIVER"]][] = $row["PHOTO_REQUESTEE"];
                            }
                            if ($temp) {
                                $req_receiver = array_keys($temp);
                                $req_sender   = $this->find_array_values($temp);
                                $details      = $this->getDetailArr($req_sender, 'getReceiverDetail');
                                foreach ($temp as $k1 => $v1) {
                                    if ($row_pool[$k1] && $details[$v1[0]]) {
                                        $finalSms[$key][$k1]["RECEIVER"]                    = $row_pool[$k1];
                                        $finalSms[$key][$k1]["RECEIVER"]["COUNT"]           = count($v1);
                                        $finalSms[$key][$k1]["DATA_TYPE"]                   = "OTHER";
                                        $finalSms[$key][$k1]["DATA"]                        = $details[$v1[0]];
                                        $finalSms[$key][$k1]["DATA"]["PHOTO_REQUEST_COUNT"] = count($v1);
                                    }
                                }
                                $this->smsDetail = $finalSms;
                                $this->getSmsContent($key);
                                $this->insertInSmsLog();
                                unset($this->smsDetail);
                            }
                        }
                    }
                }
                break;
            case "PHONE_VERIFY":
                $sql = "select " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " where MOB_STATUS!='Y' AND LANDL_STATUS!='Y' " . $this->getSmsSubscriptionCriteria($key);
                echo $sql . "\n";
                include_once(JsConstants::$docRoot . "/ivr/jsivrFunctions.php");
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_p    = array();
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$row["PROFILEID"]] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        $considered_profile = array();
                        $profile_str        = "";
                        $considered_profile = array_keys($row_pool);
                        $profile_str        = implode("','", $considered_profile);
                        $profile_str        = "'" . $profile_str . "'";
                        echo $sqlAlt = "SELECT PROFILEID FROM newjs.JPROFILE_CONTACT WHERE PROFILEID IN (" . $profile_str . ") AND ALT_MOB_STATUS='Y' AND ALT_MOBILE!=''";
                        $resAlt = mysql_query($sqlAlt, $this->dbMaster) or $this->SMSLib->errormail($sqlAlt, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                        while ($rowAlt = mysql_fetch_assoc($resAlt)) {
                            $row_p[$rowAlt['PROFILEID']] = $rowAlt['PROFILEID'];
                        }
                        $row_pool = array_diff_key($row_pool, $row_p);
                        if ($row_pool) {
                            foreach ($row_pool as $row_k => $row_v) {
                                $no        = $row_v["PHONE_MOB"];
                                $type      = "M";
                                $profileid = $row_k;
                                $dup_chk   = chkDuplicatePhone($no, $type, $profileid);
                                if (substr($dup_chk, 0, 1) == "U") {
                                    $finalSms[$key][$row_k]["DATA"]                        = $row_v;
                                    $finalSms[$key][$row_k]["DATA_TYPE"]                   = "SELF";
                                    $finalSms[$key][$row_k]["RECEIVER"]                    = $row_v;
                                    $finalSms[$key][$row_k]['RECEIVER']["CUSTOM_CRITERIA"] = $this->ftoTimeCriteria($row_v, $today_date_format);
                                }
                            }
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                /*				foreach($temp as $k=>$val)
                {
                $sms[$k][$key] = $row;
                $sms[$k][$key]["CODE"]= getVerificationCode($k);
                }*/
                break;
            case "MATCHALERT":
                $sql_sms_count = "SELECT count(*) AS COUNT, PROFILEID FROM `TEMP_SMS_DETAIL` WHERE ADD_DATE BETWEEN '" . $todays_date . " 00:00:00' AND '" . $todays_date . " 23:59:59' GROUP BY PROFILEID HAVING COUNT >=" . $this->smsPerDay;
                $res_sms_count = mysql_query($sql_sms_count, $this->dbMaster) or $this->SMSLib->errormail($sql_sms_count, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                while ($row_sms_count = mysql_fetch_assoc($res_sms_count))
                    $profiles_not_to_consider[$row_sms_count["PROFILEID"]] = $row_sms_count['COUNT'];
                $sql = "select " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " where MOB_STATUS='Y' AND LAST_LOGIN_DT >'" . $back_day_format . "'" . $this->getSmsSubscriptionCriteria($key);
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms            = array();
                    $p_id_Arr            = array();
                    $match_count_details = array();
                    $consider_profiles   = array();
                    $trans               = 0;
                    $row_pool            = array();
                    $skip                = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$row["PROFILEID"]] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
                            if (!$profiles_not_to_consider[$row_k])
                                $consider_profiles[$row_k] = $row_v;
                        }
                        $p_id_Arr = array_keys($consider_profiles);
                        if ($p_id_Arr) {
                            $match_count_details = $this->getDetailArr($p_id_Arr, 'matchalert');
                            if ($match_count_details) {
                                foreach ($consider_profiles as $k1 => $v1) {
                                    if ($match_count_details[$k1]) {
                                        $finalSms[$key][$k1]["DATA"]                = $v1;
                                        $finalSms[$key][$k1]["DATA_TYPE"]           = "SELF";
                                        $finalSms[$key][$k1]["DATA"]["MATCH_COUNT"] = $match_count_details[$k1];
                                        $finalSms[$key][$k1]["RECEIVER"]            = $v1;
                                    }
                                }
                                $this->smsDetail = $finalSms;
                                $this->getSmsContent($key);
                                $this->insertInSmsLog();
                                unset($this->smsDetail);
                            }
                        }
                    }
                }
                break;
            case "PHOTO_UPLOAD":
                $finalSms = array();
                //				$single_ar=array();
                //				$multiple_ar=array();
                foreach ($this->dbShards as $k => $conn) {
                    $sql = "SELECT PROFILEID, COUNT(*) AS CNT FROM PHOTO_REQUEST WHERE UPLOAD_DATE BETWEEN '" . $back_day_format . "' AND '" . $today_date_format . "' GROUP BY PROFILEID";
                    echo $sql . "\n";
                    $res = mysql_query($sql, $conn) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function while executing on shards: " . $conn);
                    $count       = mysql_num_rows($res);
                    $chunk       = 2000;
                    $totalChunks = ceil($count / $chunk);
                    for ($j = 0; $j < $totalChunks; $j++) {
                        $trans           = 0;
                        $row_pool_single = array();
                        $row_pool_mul    = array();
                        $skip            = $j * $chunk;
                        mysql_data_seek($res, $skip);
                        while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                            if ($k == $this->ShardIdForProfile($row["PROFILEID"])) //esha
                                {
                                if ($row["CNT"] == 1)
                                    $row_pool_single[] = $row["PROFILEID"];
                                else
                                    $row_pool_mul[$row["PROFILEID"]] = $row["CNT"];
                                $trans++;
                            }
                        }
                        if ($row_pool_single) {
                            $considered_profile = array();
                            $profile_str        = "";
                            $considered_profile = array_values($row_pool_single);
                            $profile_str        = implode("','", $considered_profile);
                            $profile_str        = "'" . $profile_str . "'";
                            $sql1               = "SELECT PROFILEID,PROFILEID_REQ_BY FROM PHOTO_REQUEST WHERE PROFILEID IN (" . $profile_str . ") AND UPLOAD_DATE BETWEEN '" . $back_day_format . "' AND '" . $today_date_format . "'";
                            $res1 = mysql_query($sql1, $conn) or $this->SMSLib->errormail($sql1, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function while executing on shards: " . $conn);
                            $single_req_arr = array();
                            while ($row1 = mysql_fetch_assoc($res1))
                                $single_req_arr[$row1["PROFILEID"]] = $row1["PROFILEID_REQ_BY"];
                            if ($single_req_arr) {
                                $sngl_cnt_pid_requestee = array();
                                $sngl_cnt_pid_requested = array();
                                $details1               = array();
                                $details2               = array();
                                $sngl_cnt_pid_requestee = array_keys($single_req_arr);
                                $sngl_cnt_pid_requested = array_values($single_req_arr);
                                $details1               = $this->getDetailArr($sngl_cnt_pid_requestee, 'havephoto', $key);
                                $details2               = $this->getDetailArr($sngl_cnt_pid_requested, 'getReceiverDetail');
                                foreach ($single_req_arr as $k1 => $v1) {
                                    if ($details1[$k1] && $details2[$v1]) {
                                        $finalSms[$key][$k1]["DATA"]              = $details2[$v1];
                                        $finalSms[$key][$k1]["DATA_TYPE"]         = "OTHER";
                                        $finalSms[$key][$k1]["RECEIVER"]          = $details1[$k1];
                                        $finalSms[$key][$k1]["RECEIVER"]["COUNT"] = 1; //ask tanu
                                        if ($details1[$k1]["HAVEPHOTO"] == "Y" || $details1[$k1]["HAVEPHOTO"] == "U")
                                            $finalSms[$key][$k1]["RECEIVER"]["CUSTOM_CRITERIA"] = 1;
                                        else
                                            $finalSms[$key][$k1]["RECEIVER"]["CUSTOM_CRITERIA"] = 0;
                                    }
                                }
                                $this->smsDetail = $finalSms;
                                $this->getSmsContent($key);
                                $this->insertInSmsLog();
                                unset($this->smsDetail);
                                unset($finalSms);
                            }
                        }
                        if ($row_pool_mul) {
                            $considered_profile = array();
                            $details            = array();
                            $profile_str        = "";
                            $considered_profile = array_keys($row_pool_mul);
                            $details            = $this->getDetailArr($considered_profile, 'havephoto', $key);
                            foreach ($row_pool_mul as $k1 => $v1) {
                                if ($details[$k1]) {
                                    $finalSms[$key][$k1]["DATA"]                       = $details[$k1];
                                    $finalSms[$key][$k1]["DATA_TYPE"]                  = "OTHER";
                                    $finalSms[$key][$k1]["RECEIVER"]                   = $details[$k1];
                                    $finalSms[$key][$k1]["RECEIVER"]["COUNT"]          = $v1;
                                    $finalSms[$key][$k1]["DATA"]["PHOTO_UPLOAD_COUNT"] = $v1;
                                    if ($details[$k1]["HAVEPHOTO"] == 'Y' || $details[$k1]["HAVEPHOTO"] == 'U')
                                        $finalSms[$key][$k1]["RECEIVER"]["CUSTOM_CRITERIA"] = 1;
                                    else
                                        $finalSms[$key][$k1]["RECEIVER"]["CUSTOM_CRITERIA"] = 0;
                                }
                            }
                            $this->smsDetail = $finalSms;
                            $this->getSmsContent($key);
                            $this->insertInSmsLog();
                            unset($this->smsDetail);
                            unset($finalSms);
                        }
                    }
                }
                break;
            case "HOROSCOPE_UPLOAD":
                $temp = array();
                foreach ($this->dbShards as $k => $conn) {
                    $temp = array();
                    $sql1 = "SELECT PROFILEID,PROFILEID_REQUEST_BY FROM newjs.HOROSCOPE_REQUEST WHERE UPLOAD_DATE BETWEEN '" . $back_day_format . "' AND '" . $today_date_format . "'";
                    echo $sql1 . "\n";
                    //$sql1="SELECT PROFILEID,PROFILEID_REQUEST_BY FROM HOROSCOPE_REQUEST WHERE UPLOAD_DATE BETWEEN '2012-01-01' AND '".$today_date_format."'";
                    $res1 = mysql_query($sql1, $conn) or $this->SMSLib->errormail($sql1, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function while executing on shards: " . $conn);
                    while ($row1 = mysql_fetch_assoc($res1)) {
                        if ($k == $this->ShardIdForProfile($row1["PROFILEID"]))
                            $temp[$row1["PROFILEID"]] = $row1["PROFILEID_REQUEST_BY"];
                    }
                    if ($temp) {
                        $horo_reqstee    = array_keys($temp);
                        $horo_reqsted_to = array_values($temp);
                        $details1        = $this->getDetailArr($horo_reqstee, 'getSenderDetail', $key);
                        $details2        = $this->getDetailArr($horo_reqsted_to, 'getReceiverDetail');
                        $details_astro   = $this->getDetailArr($horo_reqstee, 'getAstroDetail');
                        foreach ($temp as $k1 => $v1) {
                            if ($details1[$k1] && $details2[$v1] && (!$details_astro[$k1]))
                                $temp2[] = $k1;
                        }
                        if ($temp2) {
                            $parr_horo    = array_values($temp2);
                            $details_horo = $this->getDetailArr($parr_horo, 'getHoroDetail');
                        }
                        foreach ($temp as $k1 => $v1) {
                            if ($details1[$k1] && $details2[$v1]) {
                                $finalSms[$key][$k1]["DATA"]                   = $details2[$v1];
                                $finalSms[$key][$k1]["DATA_TYPE"]              = "OTHER";
                                $finalSms[$key][$k1]["PROFILDEID_HORO_REQTED"] = $k1;
                                $finalSms[$key][$k1]["RECEIVER"]               = $details1[$k1];
                                if ($details_astro[$k1] || $details_horo[$k1])
                                    $finalSms[$key][$k1]["RECEIVER"]["CUSTOM_CRITERIA"] = 1;
                                else
                                    $finalSms[$key][$k1]["RECEIVER"]["CUSTOM_CRITERIA"] = 0;
                            }
                        }
                    }
                    $this->smsDetail = $finalSms;
                    $this->getSmsContent($key);
                    $this->insertInSmsLog();
                    unset($this->smsDetail);
                }
                break;
            case "EOI_WEEKLY":
            case "EOI":
                $date7daysBack = date("Y-m-d",strtotime("-7 days"));
                foreach ($this->dbShards as $k => $conn) {
                    switch($k){
                        case $this->ShardIdForProfile(3) :
                            $shardNum = 0;
                         break;
                        case $this->ShardIdForProfile(2) :
                            $shardNum = 2;
                         break;
                        case $this->ShardIdForProfile(1) :
                            $shardNum = 1;
                         break;
                        
                    }
                    $temp      = array();
                    $eoi_count = array();
                    if ($key == "EOI")
                        $sql = "SELECT SENDER,RECEIVER FROM newjs.CONTACTS where RECEIVER % 3 = $shardNum AND TIME BETWEEN '" . $back_day_format . "' AND '" . $today_date_format . "' AND TYPE='I' AND FILTERED!='Y' AND COUNT=1 AND SEEN!='Y'";
                    elseif ($key == "EOI_WEEKLY")
                        $sql = "SELECT SENDER,RECEIVER FROM newjs.CONTACTS where RECEIVER % 3 = $shardNum AND TIME BETWEEN '" . $back_day_format . "' AND '" . $today_date_format . "' AND TYPE='I' AND FILTERED!='Y' AND COUNT=1 AND SEEN!='Y'";
                    echo $sql . "\n";
                    $res = mysql_query($sql, $conn) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function while executing on shards: " . $conn);
                    /*					$count = mysql_num_rows($res);
                    $chunk=2000;
                    $totalChunks=ceil($count/$chunk);
                    for($j = 0;$j<$totalChunks;$j++)
                    //
                    $temp=array();
                    $eoi_count=array();
                    $finalSms = array();
                    $trans = 0;
                    $row_pool = array();
                    $skip = $j*$chunk;
                    mysql_data_seek($res,$skip);
                    while(($row=mysql_fetch_assoc($res)) && $trans<$chunk){
                    $temp[$row["RECEIVER"]] = $row["SENDER"];
                    $eoi_count[$row["RECEIVER"]][] = $row["SENDER"];
                    $trans++;
                    }*/


                    while ($row = mysql_fetch_assoc($res)) {
                            if($this->userLoggedInFromApp($row["RECEIVER"],$date7daysBack))continue;
                            $temp[$row["RECEIVER"]]        = $row["SENDER"];
                            $eoi_count[$row["RECEIVER"]][] = $row["SENDER"];
                    }
                    if ($temp) {
                        $receiver = array_unique(array_keys($temp));
                        $sender   = array_unique(array_values($temp));
                        $details1 = $this->getDetailArr($receiver, 'getSenderDetail', $key);
                        $details2 = $this->getDetailArr($sender, 'getReceiverDetail');
                        unset($sender);
                        unset($receiver);
                        foreach ($temp as $k1 => $v1) {
                            if ($details1[$k1] && $details2[$v1]) {
                                $finalSms[$key][$k1]["DATA"]              = $details2[$v1];
                                $finalSms[$key][$k1]["DATA_TYPE"]         = "OTHER";
                                $finalSms[$key][$k1]["RECEIVER"]          = $details1[$k1];
                                $finalSms[$key][$k1]["RECEIVER"]["COUNT"] = count($eoi_count[$k1]);
                                $finalSms[$key][$k1]["DATA"]["EOI_COUNT"] = count($eoi_count[$k1]);
                            }
                        }
                        unset($details1);
                        unset($details2);
                        unset($temp);
                        unset($eoi_count);
                        $this->smsDetail = $finalSms;
                        unset($finalSms);
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "ACCEPT":
            case "ACCEPT_WEEKLY":
                foreach ($this->dbShards as $k => $conn) {
                    $temp         = array();
                    $accept_count = array();
                    if ($key == "ACCEPT")
                        $sql = "SELECT SENDER,RECEIVER FROM newjs.CONTACTS where TIME BETWEEN '" . $back_day_format . "' AND '" . $today_date_format . "' AND TYPE='A'  AND SEEN!='Y' ORDER BY TIME DESC";
                    elseif ($key == "ACCEPT_WEEKLY")
                        $sql = "SELECT SENDER,RECEIVER FROM newjs.CONTACTS where TIME BETWEEN '" . $back_day_format . "' AND '" . $today_date_format . "' AND TYPE='A'  AND SEEN!='Y'";
                    echo $sql . "\n";
                    $res = mysql_query($sql, $conn) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function while executing on shards: " . $conn);
                    while ($row = mysql_fetch_assoc($res)) {
                        if ($k == $this->ShardIdForProfile($row["SENDER"])) {
                            $temp[$row["SENDER"]]           = $row["RECEIVER"];
                            $accept_count[$row["SENDER"]][] = $row["RECEIVER"];
                        }
                    }
                    if ($temp) {
                        $finalSms = array();
                        $sender   = array_unique(array_keys($temp));
                        $receiver = array_unique(array_values($temp));
                        $details1 = $this->getDetailArr($sender, 'getSenderDetail', $key);
                        $details2 = $this->getDetailArr($receiver, 'getReceiverDetail');
                        foreach ($temp as $k1 => $v1) {
                            if ($details1[$k1] && $details2[$v1]) {
                                $finalSms[$key][$k1]["DATA"]                 = $details2[$v1];
                                $finalSms[$key][$k1]["DATA"]["ACCEPT_COUNT"] = count($accept_count[$k1]);
                                $finalSms[$key][$k1]["DATA_TYPE"]            = "OTHER";
                                $finalSms[$key][$k1]["RECEIVER"]             = $details1[$k1];
                                ;
                                $finalSms[$key][$k1]["RECEIVER"]["COUNT"] = count($accept_count[$k1]);
                            }
                        }
                    }
                    $this->smsDetail = $finalSms;
                    $this->getSmsContent($key);
                    $this->insertInSmsLog();
                    unset($this->smsDetail);
                }
                break;
            case "EOI_REMINDER":
                $sql = "SELECT SENDER,RECEIVER FROM newjs.CONTACTS_ONCE  where MESSAGE LIKE 'This is a system generated reminder%' AND TIME BETWEEN '" . $back_day . " 00:00:00' AND '" . $back_day . " 23:59:59'";
                $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms     = array();
                    $trans        = 0;
                    $row_pool     = array();
                    $sender_count = array();
                    $skip         = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$row["RECEIVER"]]       = $row["SENDER"];
                        $sender_count[$row["RECEIVER"]][] = $row["SENDER"];
                    }
                    if ($row_pool) {
                        $sender   = array_unique(array_values($row_pool));
                        $receiver = array_unique(array_keys($row_pool));
                        $details1 = $this->getDetailArr($sender, 'getReceiverDetail');
                        $details2 = $this->getDetailArr($receiver, 'getSenderDetail', $key);
                        foreach ($row_pool as $k1 => $v1) {
                            if ($details1[$v1] && $details2[$k1]) {
                                $finalSms[$key][$k1]["DATA"]              = $details1[$v1];
                                $finalSms[$key][$k1]["DATA"]["EOI_COUNT"] = count($sender_count[$k1]);
                                $finalSms[$key][$k1]["DATA_TYPE"]         = "OTHER";
                                $finalSms[$key][$k1]["RECEIVER"]          = $details2[$k1];
                                ;
                                $finalSms[$key][$k1]["RECEIVER"]["COUNT"] = count($sender_count[$k1]);
                            }
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "MEM_EXPIRE_A15":
            case "MEM_EXPIRE_A10":
            case "MEM_EXPIRE_A5":
            case "MEM_EXPIRE_B1":
            case "MEM_EXPIRE_B5":
		if($key=='MEM_EXPIRE_B1'||$key=='MEM_EXPIRE_B5')
			$sql = "SELECT PROFILEID FROM billing.SERVICE_STATUS where EXPIRY_DT ='" . $back_day . "' AND ACTIVE='E' AND SERVEFOR LIKE '%F%' group by PROFILEID HAVING MAX(ID)";
		else
			$sql = "SELECT PROFILEID FROM billing.SERVICE_STATUS where EXPIRY_DT ='" . $back_day . "' AND ACTIVE='Y' AND SERVEFOR LIKE '%F%' group by PROFILEID HAVING MAX(ID)";
                echo $sql . "\n";
                $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");;
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$trans] = $row["PROFILEID"];
                        $trans++;
                    }
                    if ($row_pool) {
                        $details = $this->getDetailArr($row_pool, 'getSenderDetail', $key);
                        foreach ($row_pool as $row_k => $row_v) {
                            if ($details[$row_v]) {
                                $finalSms[$key][$row_v]["DATA"]      = $details[$row_v];
                                $finalSms[$key][$row_v]["DATA_TYPE"] = "SELF";
                                $finalSms[$key][$row_v]["RECEIVER"]  = $details[$row_v];
                            }
                        }
                        $this->SMSLib->expDate = date("d-M", strtotime($back_day));
                        $this->smsDetail       = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "INVALID_EMAIL":
                $sql = "SELECT PROFILEID FROM newjs.INVALID_EMAIL_MAILER where MOD_DT = '" . $back_day . "'";
                echo $sql . "\n";
                $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $chunk       = 10;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$trans] = $row["PROFILEID"];
                        $trans++;
                    }
                    if ($row_pool) {
                        $details = $this->getDetailArr($row_pool, 'getSenderDetail', $key);
                        foreach ($row_pool as $row_k => $row_v) {
                            if ($details[$row_v]) {
                                $finalSms[$key][$row_v]["DATA"]      = $details[$row_v];
                                $finalSms[$key][$row_v]["DATA_TYPE"] = "SELF";
                                $finalSms[$key][$row_v]["RECEIVER"]  = $details[$row_v];
                            }
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "DISCOUNT_0":
            case "DISCOUNT_3":
            case "DISCOUNT_7": //DISCOUNT
                $sql = "SELECT PROFILEID, DISCOUNT FROM MIS.ATS_DISCOUNT WHERE ENTRY_DT = '" . $back_day . "'";
                $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                echo $sql . "\n";
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool["PROFILEID"][$trans] = $row["PROFILEID"];
                        $row_pool["DISCOUNT"][$trans]  = $row["DISCOUNT"];
                        $trans++;
                    }
                    if ($row_pool) {
                        $details = $this->getDetailArr($row_pool["PROFILEID"], 'getSenderDetail', $key);
                        foreach ($row_pool["PROFILEID"] as $row_k => $row_v) {
                            if ($details[$row_v]) {
                                $finalSms[$key][$row_v]["DATA"]                = $details[$row_v];
                                $finalSms[$key][$row_v]["DATA_TYPE"]           = "SELF";
                                $finalSms[$key][$row_v]["RECEIVER"]            = $details[$row_v];
                                $finalSms[$key][$row_v]["DATA"]["ATSDISCOUNT"] = $row_pool["DISCOUNT"][$row_k];
                            }
                        }
                        $this->SMSLib->dis_entry_dt = $back_day;
                        $this->smsDetail            = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "PROFILE_COMPLETE":
                $sql = "SELECT  " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " WHERE LAST_LOGIN_DT > '" . $back_day_format . "'" . $this->getSmsSubscriptionCriteria($key);
                echo $sql . "\n";
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$trans] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
							$cScoreObject = ProfileCompletionFactory::getInstance(null,null,$row_v["PROFILEID"]);
							
							$percent = $cScoreObject->getProfileCompletionScore();
                            //$percent = profile_percent($row_v["PROFILEID"]);
                            if ($percent < 85) {
                                $finalSms[$key][$row_v["PROFILEID"]]["DATA"]                 = $row_v;
                                $finalSms[$key][$row_v["PROFILEID"]]["DATA_TYPE"]            = "SELF";
                                $finalSms[$key][$row_v["PROFILEID"]]["RECEIVER"]             = $row_v;
                                $finalSms[$key][$row_v["PROFILEID"]]["DATA"]["PROF_PERCENT"] = $percent;
                            }
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "PHOTO_NCR":
                include_once(JsConstants::$docRoot . "/profile/functions.inc");
                $sql = "SELECT  " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " WHERE HAVEPHOTO IN ('N','') AND LAST_LOGIN_DT > '" . $back_day_format . "' AND CITY_RES IN ('DE00','HA02','UP12','HA03','UP25')" . $this->getSmsSubscriptionCriteria($key);
                echo $sql . "\n";
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$trans] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA"]      = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]["RECEIVER"]  = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA_TYPE"] = "SELF";
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "ADD_PHOTO":
                include_once(JsConstants::$docRoot . "/profile/functions.inc");
                $sql = "SELECT  " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " WHERE HAVEPHOTO IN ('N','') AND LAST_LOGIN_DT > '" . $back_day_format . "'" . $this->getSmsSubscriptionCriteria($key);
                echo $sql . "\n";
                //$sql="SELECT  ".$this->getJPROFILEFields()." from ".$this->tempJPROFILE." WHERE HAVEPHOTO='N' AND LAST_LOGIN_DT > '2012-01-01'";
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");;
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$trans] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA"]      = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]["RECEIVER"]  = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA_TYPE"] = "SELF";
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "INCOMPLETE":
                $sql = "SELECT " . $this->getJPROFILEFields() . " FROM " . $this->tempJPROFILE . " WHERE INCOMPLETE = 'Y' AND MOB_STATUS='Y' AND SUBSCRIPTION =''" . $this->getSmsSubscriptionCriteria($key);
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$trans] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA"]      = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]["RECEIVER"]  = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA_TYPE"] = "SELF";
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "TAKE_MEMB":
                $sql = "select " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " where LAST_LOGIN_DT > '" . $back_day_format . "' AND SUBSCRIPTION='' AND MOB_STATUS='Y'" . $this->getSmsSubscriptionCriteria($key);
                echo $sql . "\n";
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$trans] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA"]      = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]["RECEIVER"]  = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA_TYPE"] = "SELF";
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            //trac 1073
            case "MATCH_ALERT":
                echo $sql = "select " . $this->getJPROFILEFields() . " from " . $this->getTempJPROFILE() . " WHERE MOB_STATUS='Y' AND PROFILEID %3 = " . $num . $this->getSmsSubscriptionCriteria($key);
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count                    = mysql_num_rows($res);
                $chunk                    = 2000;
                $countBestMatchCalculated = 0;
                $countSmsGenerated        = 0;
                $totalChunks              = ceil($count / $chunk);
                $today                    = mktime(0, 0, 0, date("m"), date("d"), date("Y")); //timestamp for today
                $zero                     = mktime(0, 0, 0, 01, 01, 2006); //timestamp for 1 Jan 2006
                $gap                      = ($today - $zero) / (24 * 60 * 60) - $this->timeCriteria[$key];
                // $gap = 20;    //for testing
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $profileid   = $row["PROFILEID"];
                        $bestProfile = $this->getBestMatchProfile($profileid);
                        if ($bestProfile) {
                            $row_pool[$row["PROFILEID"]]               = $row;
                            $row_pool[$row["PROFILEID"]]["BEST_MATCH"] = $bestProfile;
                            $trans++;
                        } else {
                            $arr = $this->matchAlertArray($profileid, $gap);
                            if (!empty($arr)) {
                                $row_pool[$row["PROFILEID"]]                = $row;
                                $row_pool[$row["PROFILEID"]]["MATCH_ARRAY"] = $arr;
                                $trans++;
                            }
                        }
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
                            $profileid = $row_k;
                            if ($row_pool[$row_k]["BEST_MATCH"]) {
                                $bestProfileA = $row_pool[$row_k]["BEST_MATCH"];
                                $updateFlag   = 1;
                            } else {
                                $matchArr     = $row_pool[$row_k]["MATCH_ARRAY"];
                                $bestProfileA = $this->getBestProfile($profileid, $gap, $matchArr);
                            }
                            if ($bestProfileA) {
                                $bestProfile      = $bestProfileA['PROFILEID'];
                                $bestProfileScore = $bestProfileA['VIEW_COUNT'];
                                $bestProfileArr   = array(
                                    $bestProfile
                                );
                                $detail           = $this->getDetailArr($bestProfileArr, 'getReceiverDetail');
                                if ($row_v["INCOMPLETE"] == 'Y')
                                    $row_v["FTO_SUB_STATE"] = 'IU';
                                if (empty($detail)) {
                                    continue;
                                }
                                $familyIncome                       = $detail[$bestProfile]["FAMILY_INCOME"];
                                $finalSms[$key][$row_k]["RECEIVER"] = $row_v;
                                if ($row_v["FTO_SUB_STATE"] == '' || $row_v["FTO_SUB_STATE"] == 'E1' || $row_v["FTO_SUB_STATE"] == 'E2') {
                                    if ($familyIncome > 4 && $familyIncome != 8 && $familyIncome != 15 && $familyIncome != 19) {
                                        $finalSms[$key][$row_k]["RECEIVER"]["CUSTOM_CRITERIA"] = 1;
                                    } else {
                                        $finalSms[$key][$row_k]["RECEIVER"]["CUSTOM_CRITERIA"] = 0;
                                    }
                                }
                                $finalSms[$key][$row_k]["DATA_TYPE"] = "OTHER";
                                $finalSms[$key][$row_k]["DATA"]      = $detail[$bestProfile];
                                if ($updateFlag != 1) {
                                    $this->insertInBestSmsLog($profileid, $bestProfile, $bestProfileScore);
                                    $countBestMatchCalculated++;
                                    $updateFlag = 0;
                                }
                            }
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        $countSmsGenerated++;
                        unset($this->smsDetail);
                    }
                }
                $this->SMSLib->bestMatchMail($count, $countSmsGenerated, $countBestMatchCalculated, $num);
                break;
            case "CALLNOW_FAIL":
                $sql = "SELECT CALLER_PID,RECEIVER_PID FROM newjs.CALLNOW where CALL_DT  between '" . $back_day_format . "' and '" . $today_date_format . "' AND CALL_STATUS NOT IN ('I','R','E')";
                //echo $sql."\n";
                $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                    $row_pool[$row["RECEIVER_PID"]] = $row["CALLER_PID"];
                }
                if ($row_pool) {
                    $caller_Arr   = array_unique(array_keys($row_pool));
                    $receiver_Arr = array_unique(array_values($row_pool));
                    $details1     = $this->getDetailArr($caller_Arr, 'getSenderDetail', $key);
                    $details2     = $this->getDetailArr($receiver_Arr, 'getReceiverDetail');
                    foreach ($row_pool as $row_k => $row_v) {
                        if ($details1[$row_k] && $details2[$row_v]) {
                            $finalSms[$key][$row_k]["DATA"]      = $details2[$row_v];
                            $finalSms[$key][$row_k]["DATA_TYPE"] = "OTHER";
                            $finalSms[$key][$row_k]["RECEIVER"]  = $details1[$row_k];
                        }
                    }
                    $this->smsDetail = $finalSms;
                    $this->getSmsContent($key);
                    $this->insertInSmsLog();
                    unset($this->smsDetail);
                }
                break;
            case "MOVEMENT_TO_E2":
                $sql = "SELECT " . $this->getJPROFILEFields() . " FROM " . $this->getTempJPROFILE() . " st LEFT JOIN FTO.FTO_STATES fs ON (st.FTO_SUB_STATE = fs.SUBSTATE) WHERE MOB_STATUS='Y' AND st.FTO_SUB_STATE NOT IN ('E1','E2') AND FTO_EXPIRY_DATE BETWEEN '" . $today_date_format . "' and '" . $forward_date_format . "'";
                $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$trans] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA"]      = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]["RECEIVER"]  = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA_TYPE"] = "SELF";
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "ADD_PHOTO_FTO_REMIND":
                echo $sql = "SELECT " . $this->getJPROFILEFields() . " FROM " . $this->getTempJPROFILE() . " WHERE MOB_STATUS='Y' AND FTO_SUB_STATE IN ('C1','C2') ". $this->getSmsSubscriptionCriteria($key);
                $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$trans] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA"]                        = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]["RECEIVER"]                    = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]['RECEIVER']["CUSTOM_CRITERIA"] = $this->ftoTimeCriteria($row_v, $today_date_format);
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA_TYPE"]                   = "SELF";
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "REMINDER_SENDING_EOI":
                $sql = "SELECT " . $this->getJPROFILEFields() . " FROM " . $this->getTempJPROFILE() . " WHERE FTO_SUB_STATE IN ('D2','D3') AND MOB_STATUS='Y'";
                $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$trans] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA"]                        = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]["RECEIVER"]                    = $row_v;
                            $finalSms[$key][$row_v["PROFILEID"]]["RECEIVER"]["CUSTOM_CRITERIA"] = $this->ftoTimeCriteria($row_v, $today_date_format);
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA_TYPE"]                   = "SELF";
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "DECLINE":
                foreach ($this->dbShards as $k => $conn) {
                    $temp         = array();
                    $sql          = "SELECT SENDER,RECEIVER FROM newjs.CONTACTS where TIME BETWEEN '" . $back_day_format . "' AND '" . $today_date_format . "' AND TYPE='D' ORDER BY TIME DESC";
                    $res = mysql_query($sql, $conn) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function while executing on shards: " . $conn);
                    while ($row = mysql_fetch_assoc($res)) {
                        if ($k == $this->ShardIdForProfile($row["SENDER"])) {
                            $temp[$row["SENDER"]] = $row["RECEIVER"];
                        }
                    }
                    if ($temp) {
                        $finalSms = array();
                        $sender   = array_unique(array_keys($temp));
                        $receiver = array_unique(array_values($temp));
                        $details1 = $this->getDetailArr($sender, 'getSenderDetail', $key);
                        $details2 = $this->getDetailArr($receiver, 'getReceiverDetail');
                        foreach ($temp as $k1 => $v1) {
                            if ($details1[$k1] && $details2[$v1]) {
                                $finalSms[$key][$k1]["DATA"]      = $details2[$v1];
                                $finalSms[$key][$k1]["DATA_TYPE"] = "OTHER";
                                $finalSms[$key][$k1]["RECEIVER"]  = $details1[$k1];
                            }
                        }
                    }
                    $this->smsDetail = $finalSms;
                    $this->getSmsContent($key);
                    $this->insertInSmsLog();
                    unset($this->smsDetail);
                }
                break;
            case "CANCEL":
                foreach ($this->dbShards as $k => $conn) {
                    $temp         = array();
                    $sql = "SELECT SENDER,RECEIVER FROM newjs.CONTACTS where TIME BETWEEN '" . $back_day_format . "' AND '" . $today_date_format . "' AND TYPE = 'C' ORDER BY TIME DESC";
                    $res = mysql_query($sql, $conn) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function while executing on shards: " . $conn);
                    while ($row = mysql_fetch_assoc($res)) {
                        if ($k == $this->ShardIdForProfile($row["RECEIVER"])) {
                            $temp[$row["RECEIVER"]] = $row["SENDER"];
                        }
                    }
                    if ($temp) {
                        $finalSms = array();
                        $sender   = array_unique(array_keys($temp));
                        $receiver = array_unique(array_values($temp));
                        $details1 = $this->getDetailArr($sender, 'getSenderDetail', $key);
                        $details2 = $this->getDetailArr($receiver, 'getReceiverDetail');
                        foreach ($temp as $k1 => $v1) {
                            if ($details1[$k1] && $details2[$v1]) {
                                $finalSms[$key][$k1]["DATA"]      = $details2[$v1];
                                $finalSms[$key][$k1]["DATA_TYPE"] = "OTHER";
                                $finalSms[$key][$k1]["RECEIVER"]  = $details1[$k1];
                            }
                        }
                    }
                    $this->smsDetail = $finalSms;
                    $this->getSmsContent($key);
                    $this->insertInSmsLog();
                    unset($this->smsDetail);
                }
                break;
			case "FTO_SERVICE":
				$sql = "SELECT PROFILEID,ENTRYBY FROM incentive.HISTORY where ENTRY_DT BETWEEN '" . $back_day_format . "' AND '" . $today_date_format . "' ORDER BY ENTRY_DT DESC";
                $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$trans] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
							$receiver = $this->getDetailArr(array($row_v["PROFILEID"]), 'getSenderDetail', $key);
							$finalSms[$key][$row_v["PROFILEID"]]["DATA"]["FTO_AGENT"]           = $row_v["ENTRYBY"];
                            $finalSms[$key][$row_v["PROFILEID"]]["RECEIVER"]                    = $receiver[$row_v["PROFILEID"]];
                            $finalSms[$key][$row_v["PROFILEID"]]["DATA_TYPE"]                   = "OTHER";
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "UPLOAD_HOROSCOPE":
				$sql = 	"SELECT " . $this->getJPROFILEFields() . " FROM " . $this->getTempJPROFILE() . " WHERE FTO_SUB_STATE IN ('D3','D4') AND MOB_STATUS='Y'";
				$res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$trans] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        foreach ($row_pool as $row_k => $row_v) {
							if(!check_astro_details($row_v["PROFILEID"], "Y") &&  !get_horoscope($row_v["PROFILEID"]))
							{
								$finalSms[$key][$row_v["PROFILEID"]]["DATA"]           		= $row_v;
	                            $finalSms[$key][$row_v["PROFILEID"]]["RECEIVER"]            = $row_v;
	                            $finalSms[$key][$row_v["PROFILEID"]]["DATA_TYPE"]           = "SELF";
	                            $finalSms[$key][$row_v["PROFILEID"]]['RECEIVER']["CUSTOM_CRITERIA"] = $this->ftoTimeCriteria($row_v, $today_date_format);
							}
                            
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
		case "PHONE_VERIFY_SCREEN":
	                $sql = "select " . $this->getJPROFILEFields() . " from " . $this->tempJPROFILE . " where MOB_STATUS!='Y' AND LANDL_STATUS!='Y' AND ENTRY_DT BETWEEN '" . $back_day_format . "' AND '" . $time24_format . "' " . $this->getSmsSubscriptionCriteria($key);
                echo $sql . "\n";
                include_once(JsConstants::$docRoot. "/ivr/jsivrFunctions.php");
		include_once(JsConstants::$docRoot."/ivr/knowlarityFunctions.php");
                $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                $count       = mysql_num_rows($res);
                $chunk       = 2000;
                $totalChunks = ceil($count / $chunk);
                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans    = 0;
                    $row_p    = array();
                    $row_pool = array();
                    $skip     = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                        $row_pool[$row["PROFILEID"]] = $row;
                        $trans++;
                    }
                    if ($row_pool) {
                        $considered_profile = array();
                        $profile_str        = "";
                        $considered_profile = array_keys($row_pool);
                        $profile_str        = implode("','", $considered_profile);
                        $profile_str        = "'" . $profile_str . "'";
                      echo $sqlAlt = "SELECT PROFILEID FROM newjs.JPROFILE_CONTACT WHERE PROFILEID IN (" . $profile_str . ") AND ALT_MOB_STATUS='Y' AND ALT_MOBILE!=''";
                        $resAlt = mysql_query($sqlAlt, $this->dbMaster) or $this->SMSLib->errormail($sqlAlt, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                        while ($rowAlt = mysql_fetch_assoc($resAlt)) {
                            $row_p[$rowAlt['PROFILEID']] = $rowAlt['PROFILEID'];
                        }
                        $row_pool = array_diff_key($row_pool, $row_p);
                        if ($row_pool) {
                            foreach ($row_pool as $row_k => $row_v) {
                                $no        = ltrim($row_v["PHONE_MOB"],'0');
                                $isd        = ltrim($row_v["ISD"],'0');
                                $type      = "M";
                                $profileid = $row_k;
                                $dup_chk   = chkDuplicatePhone($no, $type, $profileid);
                                if (substr($dup_chk, 0, 1) == "U") {
				    $virtualNumber = getVirtualNumber($row_k,$no,$isd);
                                    $finalSms[$key][$row_k]["DATA"]                        = $row_v;
                                    $finalSms[$key][$row_k]["DATA_TYPE"]                   = "SELF";
                                    $finalSms[$key][$row_k]["RECEIVER"]                    = $row_v;
				    $finalSms[$key][$row_v["PROFILEID"]]["DATA"]["VIRTUAL_NUMBER"] = $virtualNumber;
                                }
                            }
                        }
                        $this->smsDetail = $finalSms;
                        $this->getSmsContent($key);
                        $this->insertInSmsLog();
                        unset($this->smsDetail);
                    }
                }
                break;
            case "VD1" :
            case "VD2" :
            	$entry_dt 		=$num;
                $flatCount              =0;
                $uptoCount              =0;
                $flatDiscountKey        ='VD2';
                $uptoDiscountKey        ='VD1';

            	//$vd_pool = 0;
		$membershipObj 		=new Membership();
            	$negTreatObj 		=new INCENTIVE_NEGATIVE_TREATMENT_LIST('newjs_slave');
            	$vdDiscObj 		=new billing_VARIABLE_DISCOUNT();
            	$vdDiscountSmsLog 	=new billing_VARIABLE_DISCOUNT_SMS_LOG();

                $variableDiscountObj 	=new VariableDiscount();
                error_log("ankita confirm what to pass in input,profileid");
                $durationArr =$variableDiscountObj->getActiveDurations();

		$smsLogDetails	=$vdDiscountSmsLog->getFrequencyAndTimes($entry_dt);		
            	list($frequency, $noOfTimes) =$smsLogDetails;
            	//if($key == "VD1"){
            		$vdDiscountSmsLog->updateStartTime($entry_dt);
            	//}

            	// Condition added for same day SMS Scheduling
            	/*$profileCount = $vdDiscObj->checkValidProfileCountForDate($entry_dt,$noOfTimes,$frequency);
            	if(empty($profileCount) || $profileCount == 0){
            		$new_entry_dt = date("Y-m-d", (strtotime($entry_dt)+86400));
            	} else {
            		$new_entry_dt = $entry_dt;
            	}*/
            	// End condition
            	if($noOfTimes > 1 && $frequency < $noOfTimes){
                    //Start: JSC-2624 Find total count and get equal number of rows
                    $sql1 = "SELECT count(1) AS CNT FROM billing.VARIABLE_DISCOUNT WHERE '$entry_dt' BETWEEN SDATE AND EDATE";
                    $numberOfRowsObj = mysql_query($sql1, $this->dbSlave) or $this->SMSLib->errormail($sql1, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
                    $rowObj = mysql_fetch_array($numberOfRowsObj);
                    $totalCount  = $rowObj['CNT'];
                    $numberOfRows = ceil($totalCount / $noOfTimes);

                    /*$sql ="SELECT PROFILEID,DISCOUNT,SDATE,EDATE,SENT FROM billing.VARIABLE_DISCOUNT WHERE '$entry_dt' BETWEEN SDATE AND EDATE AND PROFILEID%$noOfTimes=$frequency AND SENT!='Y'"; 
                    * //Commented old logic of using mod because it was retuning unequal number of profiles                       */
                    $sql ="SELECT PROFILEID,DISCOUNT,SDATE,EDATE,SENT FROM billing.VARIABLE_DISCOUNT WHERE '$entry_dt' BETWEEN SDATE AND EDATE AND SENT!='Y' LIMIT $numberOfRows";
                    //End: JSC-2624 Find total count and get equal number of rows
            	} else {
            		$sql ="SELECT PROFILEID,DISCOUNT,SDATE,EDATE,SENT FROM billing.VARIABLE_DISCOUNT WHERE '$entry_dt' BETWEEN SDATE AND EDATE AND SENT!='Y'";
            	}
		$res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
		$count = mysql_num_rows($res);
                $chunk = 2000;
                $totalChunks = ceil($count / $chunk);

                for ($j = 0; $j < $totalChunks; $j++) {
                    $finalSms = array();
                    $trans = 0;
                    $row_pool = array();
                    $discount_pool = array();
                    $discount_endDt = array();
                    $url_pool = array();
                    $skip = $j * $chunk;
                    mysql_data_seek($res, $skip);
                    while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
                    	$profileid = $row["PROFILEID"];
                    	$discount = $row["DISCOUNT"];
                    	$sdate = $row['SDATE'];
                    	$edate = $row["EDATE"];
                    	$discountEndDate = date("d-M",strtotime($edate));
			$flatVdDiscount =$this->checkFlatVdDiscount($profileid, $durationArr);
			if($flatVdDiscount)
				$tempKey = $flatDiscountKey;
			else
				$tempKey = $uptoDiscountKey;
			/*if($tempKey!= $key)
				continue;*/	
			$sqlJ ="SELECT PHONE_MOB,EMAIL,PHONE_FLAG,GET_SMS,SUBSCRIPTION,AGE,GENDER FROM newjs.JPROFILE WHERE PROFILEID='$profileid' AND ACTIVATED IN('Y','H')";
			$resJ = mysql_query($sqlJ, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key: " . $key . " in processData() function");
			if($rowJ=mysql_fetch_array($resJ))
			{
				$phoneMob 	= $rowJ["PHONE_MOB"];
				$email 		= $rowJ["EMAIL"];
				$phoneFlag 	= $rowJ["PHONE_FLAG"];
				$getSms		= $rowJ["GET_SMS"];
				$subscription 	= $rowJ['SUBSCRIPTION'];
				$ageVal 	= $rowJ['AGE'];
				$genderVal 	= $rowJ['GENDER'];
				$subArr 	= @explode(",",$subscription);		
				if($genderVal=='M' && $ageVal<=23)
					continue;

				// Renewal check	
				$isRenewal =$membershipObj->isRenewable($profileid);				
				if($isRenewal && ($isRenewal!=1)){
					$renewalFlag =true;
						continue;
				}
				$negativeFilterReq = $negTreatObj->isNegativeTreatmentRequired($profileid);
				if($negativeFilterReq)
					continue;
				if(in_array("F",$subArr) || in_array("D",$subArr))
					continue;
							
				if($phoneMob && $phoneFlag!='I' && $getSms!='N'){
					$fieldVal =$this->filterProfileForVD($profileid,$phoneMob,$this->dbSlave);
					if($fieldVal){
			                	$row_pool[$trans] 	= $profileid;
			                	$discount_pool[$trans] 	= $discount;
						$discount_endDt[$trans] = $discountEndDate;
						$url_pool[$trans] 	= $profileid;
						$keyPool[$trans]        = $tempKey;
			                	$trans++;
                                                if($tempKey==$flatDiscountKey)
                                                       $flatCount++;   
                                                elseif($tempKey==$uptoDiscountKey)
                                                       $uptoCount++;   
                                                //$vdDiscObj->updateSendVDStatus($profileid,"Y");
					}
				}
				unset($renewalFlag);
			}
                    }
                    if ($row_pool) {
                        //$details = $this->getDetailArr($row_pool, 'getReceiverDetail', $key);
			$details = $this->getDetailArr($row_pool, 'getReceiverDetail');
                        foreach ($row_pool as $row_k => $row_v) {
                            if ($details[$row_v]) {
                                $key1           =$keyPool[$row_k];
                                $profileid      =$row_v;

                                $finalSms[$key1][$row_v]["DATA"] = $details[$row_v];
                                $finalSms[$key1][$row_v]["DATA"]["VD_DISCOUNT"] = $discount_pool[$row_k];
                                $finalSms[$key1][$row_v]["DATA"]["VD_END_DT"] = $discount_endDt[$row_k];
                                $finalSms[$key1][$row_v]["DATA"]["VD_URL"] = $url_pool[$row_k];
                                $finalSms[$key1][$row_v]["DATA_TYPE"] = "SELF";
                                $finalSms[$key1][$row_v]["RECEIVER"] = $details[$row_v];
				$vdDiscObj->updateSendVDStatus($profileid,"Y");
                            }
                        }
                        $this->smsDetail = $finalSms;
                        // Flat Calculation
			$this->vd_sms=1;
                        $this->getSmsContent($flatDiscountKey);
                        $this->insertInSmsLog();

                        // Upto Calculation
                        $this->getSmsContent($uptoDiscountKey);
                        $this->insertInSmsLog();

			//$this->updateVDSmsSent($row_pool); // Removed as part of requirement
                        unset($this->smsDetail);
			unset($this->vd_sms);
                    }
                }
                /*if($key == "VD1"){
                	$upto_count = $vd_pool;
                	$vdDiscountSmsLog->updateUptoCount($entry_dt, $upto_count);
                } else if($key == "VD2"){
                	$flat_count = $vd_pool;
                	$vdDiscountSmsLog->updateFlatCount($entry_dt, $flat_count);
                }*/
                //if($key == "VD2"){
                	//$vdDiscountSmsLog->updateEndTime($entry_dt);
			$vdDiscountSmsLog->updateEndTime($entry_dt,$flatCount,$uptoCount);
                //}
                unset($vdDiscountSmsLog);
                unset($vdDiscObj);
                unset($negTreatObj);
            break;
            case "REQUEST_CALLBACK" : 
                //set of conditions for request callback 
                $lastLoginOffset = "- 15 day"; 
                $neverPaidFlag = true;
                $requestCallbackFlag = true;
                $acceptanceLowerLimit = 4;

                $mmObj = new MembershipMailer();
                $smsEligibleProfilesArr=$mmObj->fetchOfferConditionsBasedProfiles($lastLoginOffset,'',$neverPaidFlag,$requestCallbackFlag,$acceptanceLowerLimit);
                unset($mmObj);
                
                if ($smsEligibleProfilesArr) {
                    foreach($smsEligibleProfilesArr as $k=>$val)
                    {
                        $row_pool[] = $k;
                    }
                    //$row_pool = array_map(function ($arr) { return $arr['PROFILEID']; }, $smsEligibleProfilesArr);     
                    $details = $this->getDetailArr($row_pool , 'getReceiverDetail', $key);
                    $logSMSDetail = array();
                    $trans = 0;
                    foreach ($row_pool as $row_k => $row_v) {
                        //$proId = array_search($row_v, $smsEligibleProfilesArr);
                        if($details[$row_v]){
                            $finalSms[$key][$row_v]["DATA"] = $details[$row_v];
                            $finalSms[$key][$row_v]["RECEIVER"] = $details[$row_v];
                            $finalSms[$key][$row_v]["DATA_TYPE"] = "SELF"; 
                            $finalSms[$key][$row_v]["DATA"]["ACCEPTANCE_COUNT"] = $smsEligibleProfilesArr[$row_v]["acceptanceCount"];
                            $logSMSDetail[$trans] = $row_v;
                            $trans++;
                        }
                    }
                    unset($smsEligibleProfilesArr);
                    unset($row_pool);
                    $this->smsDetail = $finalSms;
                    $this->getSmsContent($key);
                    $this->logSentSMS($key,$logSMSDetail);
                    $this->insertInSmsLog();
                    unset($logSMSDetail);
                }
            break;
        }   
    }
    function getSmsVariables($message)
    {
        $arrays = $this->splitMsg($message);
        return $arrays;
    }
    private function explodeManyDel($delimiters, $string)
    {
        $return_array = Array(
            $string
        );
        $d_count      = 0;
        while (isset($delimiters[$d_count])) {
            $new_return_array = Array();
            foreach ($return_array as $el_to_split) {
                $put_in_new_return_array = explode($delimiters[$d_count], $el_to_split);
                foreach ($put_in_new_return_array as $substr) {
                    $new_return_array[] = $substr;
                }
            }
            $return_array = $new_return_array;
            $d_count++;
        }
        return $return_array;
    }
    protected function splitMsg($msg)
    {
        $arrexp = $this->explodeManyDel(array(
            "{",
            "}"
        ), $msg);
        if (is_numeric($arrexp[0])) {
            $flagPos = 'V'; //variable @first postion
            foreach ($arrexp as $key => $value) {
                if ($key % 2 == 0)
                    $varMsg[] = $value;
                else
                    $staticMsg[] = $value;
            }
        } else {
            $flagPos = 'S'; //static message @first position
            foreach ($arrexp as $key => $value) {
                if ($key % 2 == 0)
                    $staticMsg[] = $value;
                else
                    $varMsg[] = $value;
            }
        }
        $arr["staticMsg"] = $staticMsg;
        $arr["varMsg"]    = $varMsg;
        $arr["flagPos"]   = $flagPos;
        return $arr;
    }
    protected function mergeMsg($arr1, $arr2)
    {
        $mrgMsg = $arr1[0];
        $cnt    = 0;
        foreach ($arr2 as $key => $value) {
            $mrgMsg .= $value;
            $cnt++;
            $mrgMsg .= $arr1[$cnt];
        }
        return $mrgMsg;
    }
    function getSmsMessage($smsKey, $senderDetail)
    {
        foreach ($this->scheduleSettings[$smsKey] as $k => $v) {
            $subscription = explode(",", $v["SUBSCRIPTION"]);
            foreach ($subscription as $key => $value){
                $temp[$value][$v["GENDER"]][$v["COUNT"]][$v["CUSTOM_CRITERIA"]] = $v["ID"];
            }
        }
        if ($senderDetail["COUNT"] && $senderDetail["COUNT"] > 1)
            $count = "MUL";
        else
            $count = "SINGLE";
        if ($senderDetail["CUSTOM_CRITERIA"])
            $custom_criteria = $senderDetail["CUSTOM_CRITERIA"];
        else
            $custom_criteria = 0;
        
        $subscription = $this->getSubscriptionString($senderDetail["SUBSCRIPTION"], $senderDetail["FTO_SUB_STATE"]);
        
        if ($temp[$subscription][$senderDetail["GENDER"]][$count][$custom_criteria])
            $mess = $temp[$subscription][$senderDetail["GENDER"]][$count][$custom_criteria];
        elseif ($temp[$subscription]["A"][$count][$custom_criteria])
            $mess = $temp[$subscription]["A"][$count][$custom_criteria];
        elseif ($temp["A"][$senderDetail["GENDER"]][$count][$custom_criteria])
            $mess = $temp["A"][$senderDetail["GENDER"]][$count][$custom_criteria];
        else
            $mess = $temp["A"]["A"][$count][$custom_criteria];
        return $mess;
    }
    function getSubscriptionString($sub, $fto)
    {
        if (strstr($sub, "F"))
            $sub = "P";
        else {
            if (!empty($fto)) {
                $sub = $fto;
            } else {
                $sub = "F";
            }
        }
        return $sub;
    }
    function getSmsContent($key)
    {
        $i = 0; 
        if ($this->smsDetail) {
            foreach ($this->smsDetail[$key] as $pid => $v) {
                if ($this->SMSLib->getMobileCorrectFormat($v["RECEIVER"]["PHONE_MOB"],$v["RECEIVER"]["ISD"])) {
                    $smsValues = array();
                    $mes       = "";
                    $smsKey    = $key;
                    $smsId = $this->getSmsMessage($smsKey, $v["RECEIVER"]);
                    if ($smsId) {
                        $varArray = $this->scheduleSettings[$smsKey][$smsId]["VARIABLES"];
                       
                        if (in_array('FTO_EXPIRY_DATE', $varArray['varMsg'])) {
							$todays_date = date("Y-m-d");
							$today = JSstrToTime($todays_date);
							$expiration_date = JSstrToTime($v['DATA']['FTO_EXPIRY_DATE']);
							if ($expiration_date < $today)
								continue;
						}

						foreach ($varArray['varMsg'] as $k => $var) {

                            $smsValues[$var] = $this->SMSLib->getTokenValue($var, $v);
                        }
                        
                        if ($smsValues) {
                            if ($arrays["flagPos"] == 'V')
                                $mes = $this->mergeMsg($smsValues, $varArray["staticMsg"]);
                            else
                                $mes = $this->mergeMsg($varArray["staticMsg"], $smsValues);
                        }
                        $data[$smsKey][$pid]        = $mes;
                        $this->sms[$i]["PHONE_MOB"] = $v["RECEIVER"]["PHONE_MOB"];
                        $this->sms[$i]["PROFILEID"] = $v["RECEIVER"]["PROFILEID"];
                        $this->sms[$i]["SMS_KEY"]   = $smsKey;
                        $this->sms[$i]["SMS_TYPE"]  = $this->scheduleSettings[$smsKey][$smsId]["SMS_TYPE"];
                        ;
                        $this->sms[$i]["MESSAGE"]  = $mes;
                        //					$this->sms[$i]["MESSAGE"] = $this->checkMsgSyntax($mes);
                        $this->sms[$i]["PRIORITY"] = $this->scheduleSettings[$smsKey][$smsId]["PRIORITY"];
                        $sub                       = $this->getSubscriptionString($v["RECEIVER"]["SUBSCRIPTION"], $v["RECEIVER"]["FTO_SUB_STATE"]);
                        if ($sub == "F" || $sub == "E1" || $sub == "E2")
                            $this->sms[$i]["MUL_SMS"] = 'N';
                        else
                            $this->sms[$i]["MUL_SMS"] = 'Y';
                        $i++;
                    }
                }
            }
	    if($this->vd_sms){}
	    else		
            	unset($this->smsDetail);
            return $data;
        }
    }
    protected function getSenderDetail($p_id_str, $key)
    {
        $row = array();
        $sql = "SELECT " . $this->getJPROFILEFields() . " FROM " . $this->tempJPROFILE . " WHERE PROFILEID IN (" . $p_id_str . ") AND COUNTRY_RES='51'" . $this->getSmsSubscriptionCriteria($key);
        //echo $sql."\n\n";
        //$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching sender details for SMS Key: " . $key . " in getSenderDetail() function");
        while ($row = mysql_fetch_assoc($res))
            $temp[$row["PROFILEID"]] = $row;
        return $temp;
    }
    function getDetailArr($p_array, $query_tag, $key = '')
    {
        $str = '';
        /*                                        $count=count($p_array);
        $limit=2000;
        $loop_count=ceil($count/$limit);
        for($i=0;$i<$loop_count;$i++)
        {
        $arr_splice=array_splice($p_array,($i*$limit),(($i*$limit) +$limit));*/
        //                                                $str=implode("','",$arr_splice);
        $str = implode("','", $p_array);
        $str = "'" . $str . "'";
        switch ($query_tag) {
            case 'getSenderDetail':
                $details = $this->getSenderDetail($str, $key);
                break;
            case 'getReceiverDetail':
                $details = $this->getReceiverDetail($str);
                break;
            case 'matchalert':
                $details = $this->getMatchAlertDetail($str);
                break;
            case 'havephoto':
                $details = $this->getHavePhotoDetail($str, $key);
                break;
            case 'getAstroDetail':
                $details = $this->getAstroDetail($str);
                break;
            case 'getHoroDetail':
                $details = $this->getHoroDetail($str);
                break;
            case 'checkphoto':
                $details = $this->checkPhotoDetail($str); //str has username
                break;
        }
        /*                                                        if($i==0)
        $details1=$details;
        else
        $details1= array_merge($details,$details1);
        
        }
        return $details1;*/
        return $details;
    }
    protected function checkPhotoDetail($p_id_str)
    {
        $row  = array();
        $temp = array();
        $sql  = "select PROFILEID from " . $this->tempJPROFILE . " WHERE USERNAME IN (" . $p_id_str . ") AND HAVEPHOTO IN ('N', '')";
        $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching photo details in checkPhotoDetail() function");
        while ($row = mysql_fetch_assoc($res))
            $temp[] = $row["PROFILEID"];
        return $temp;
    }
    protected function getMatchAlertDetail($p_id_str)
    {
        $row           = array();
        $temp          = array();
        $sql           = "SELECT count(*) as COUNT,RECEIVER from matchalerts.LOG where RECEIVER IN (" . $p_id_str . ") GROUP BY RECEIVER";
        $logTableError = 0;
        $res = mysql_query($sql, $this->dbMatch) or ($logTableError = 1);
        if ($logTableError) {
            sleep(20);
            $sql = "SELECT count(*) as COUNT,RECEIVER from matchalerts.LOG where RECEIVER IN (" . $p_id_str . ") GROUP BY RECEIVER";
            $res = mysql_query($sql, $this->dbMatch) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching matchalert details in getMatchAlertDetail() function");
        }
        while ($row = mysql_fetch_assoc($res))
            $temp[$row["RECEIVER"]] = $row["COUNT"];
        return $temp;
    }
    protected function getAstroDetail($p_id_str)
    {
        $row  = array();
        $temp = array();
        $sql  = "select PROFILEID from newjs.ASTRO_DETAILS WHERE PROFILEID IN (" . $p_id_str . ")";
        $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching Astro details in getAstroDetail function");
        ;
        while ($row = mysql_fetch_assoc($res))
            $temp[$row["PROFILEID"]] = 1;
        return $temp;
    }
    protected function getHoroDetail($p_id_str)
    {
        $row  = array();
        $temp = array();
        $sql  = "select count(*) as COUNT from newjs.HOROSCOPE WHERE PROFILEID IN (" . $p_id_str . ")";
        $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching Horoscope details in getHoroDetail() function");
        ;
        while ($row = mysql_fetch_assoc($res))
            $temp[$row["PROFILEID"]] = 1;
        return $temp;
    }
    protected function getHavePhotoDetail($p_id_str, $key)
    {
        $row  = array();
        $temp = array();
        $sql  = "select " . $this->getJPROFILEFields() . ",HAVEPHOTO from " . $this->tempJPROFILE . " WHERE PROFILEID IN (" . $p_id_str . ") AND COUNTRY_RES='51'" . $this->getSmsSubscriptionCriteria($key);
        $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching have_photo details in getHavePhotoDetail() function");
        while ($row = mysql_fetch_assoc($res))
            $temp[$row["PROFILEID"]] = $row;
        return $temp;
    }
    protected function getReceiverDetail($p_id_str)
    {
        $row = array();
        $sql = "SELECT " . $this->getJPROFILEFields() . " FROM " . $this->getTempJPROFILE() . " WHERE PROFILEID IN (" . $p_id_str . ")";
        //echo $sql."\n\n";
        $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching receiver details in getReceiverDetail() function");
        while ($row = mysql_fetch_assoc($res))
            $temp[$row["PROFILEID"]] = $row;
        return $temp;
    }
    function find_array_values($arr)
    {
        $arr1    = $this->array_flatten($arr);
        $ar_val  = array_values($arr1);
        $unq_val = array_unique($ar_val);
        return $unq_val;
    }
    function array_flatten($array)
    {
        if (!is_array($array))
            return FALSE;
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value))
                $result = array_merge($result, $this->array_flatten($value));
            else
                $result[$key] = $value;
        }
        return $result;
    }
    function getJPROFILEFields()
    {
        return "PROFILEID, GENDER, USERNAME, SUBSCRIPTION, PHONE_MOB, PASSWORD, CASTE, DTOFBIRTH, MSTATUS, MTONGUE, COUNTRY_RES,CITY_RES, WEIGHT, AGE, HEIGHT, EDU_LEVEL, INCOME, ENTRY_DT,MOB_STATUS, LAST_LOGIN_DT, HAVEPHOTO, OCCUPATION, COUNTRY_RES, GET_SMS, SOURCE, SERVICE_MESSAGES,EMAIL,COMPANY_NAME,OWN_HOUSE,FAMILY_INCOME,VIEW_COUNT,FTO_SUB_STATE,FTO_ENTRY_DATE,FTO_EXPIRY_DATE,INCOMPLETE,ACTIVATED,LANDL_STATUS,VERIFY_ACTIVATED_DT,FAMILYINFO,EDUCATION,JOB_INFO,ISD";
    }
    
    function getJPROFILEFieldsNew()
    {
        return "PROFILEID, GENDER, USERNAME, SUBSCRIPTION, PHONE_MOB, PASSWORD, CASTE, DTOFBIRTH, MSTATUS, MTONGUE, CITY_RES, WEIGHT, AGE, HEIGHT, EDU_LEVEL, INCOME, ENTRY_DT,MOB_STATUS, LAST_LOGIN_DT, HAVEPHOTO, OCCUPATION, COUNTRY_RES, GET_SMS, SOURCE, SERVICE_MESSAGES,EMAIL,COMPANY_NAME,OWN_HOUSE,FAMILY_INCOME,VIEW_COUNT,FTO_SUB_STATE,FTO_ENTRY_DATE,FTO_EXPIRY_DATE,INCOMPLETE,ACTIVATED,LANDL_STATUS,VERIFY_ACTIVATED_DT,FAMILYINFO,EDUCATION,JOB_INFO,ISD";
    }
    
    function setTempJPROFILE()
    {
        $today = mktime(0, 0, 0, date("m"), date("d"), date("Y")); //timestamp for today
        $sql   = "select count(*) cnt from newjs.SMS_TEMP_TABLE";
        $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while checking (if the table SMS_TEMP_TABLE exists) in setTempJPROFILE() function");
        $row = mysql_fetch_assoc($res);
        if (!$row['cnt']) {
            $timestamp   = mktime(0, 0, 0, date("m"), date("d") - 150, date("Y"));
            $time_format = date("Y-m-d", $timestamp);
            /*
            $sql="CREATE TABLE newjs.SMS_TEMP_TABLE AS SELECT ".$this->getJPROFILEFields()." FROM newjs.JPROFILE WHERE LAST_LOGIN_DT > '".$time_format."'";
            $res = mysql_query($sql,$this->dbMaster) or die(mysql_error());
            $this->tempJPROFILE = "newjs.SMS_TEMP_TABLE";
            */
            $chunk       = 2000;
            $sql_a       = "SELECT EMAIL, jp.PROFILEID, INCOMPLETE, GENDER, USERNAME, SUBSCRIPTION, PHONE_MOB, PASSWORD, CASTE, DTOFBIRTH, MSTATUS, MTONGUE, COUNTRY_RES,CITY_RES, WEIGHT, AGE, HEIGHT, EDU_LEVEL_NEW, INCOME, ENTRY_DT,MOB_STATUS, LANDL_STATUS, LAST_LOGIN_DT, HAVEPHOTO, OCCUPATION, COUNTRY_RES, GET_SMS, SOURCE, ACTIVATED, COMPANY_NAME, FAMILY_INCOME, OWN_HOUSE, VERIFY_ACTIVATED_DT, FAMILYINFO, EDUCATION, JOB_INFO, ISD, jt.NTIMES,fs.SUBSTATE AS FTO_SUB_STATE,fto.FTO_ENTRY_DATE,fto.FTO_EXPIRY_DATE FROM newjs.JPROFILE jp LEFT JOIN newjs.JP_NTIMES jt ON (jt.PROFILEID = jp.PROFILEID) LEFT JOIN FTO.FTO_CURRENT_STATE fto ON (fto.PROFILEID = jp.PROFILEID) LEFT JOIN FTO.FTO_STATES fs ON (fto.STATE_ID = fs.STATE_ID) WHERE LAST_LOGIN_DT>='$time_format' and activatedKey=1 ";
            //			echo $sql_a = "SELECT J.PROFILEID, J.GENDER, J.USERNAME, J.SUBSCRIPTION, J.PHONE_MOB, J.PASSWORD, J.CASTE, J.DTOFBIRTH, J.MSTATUS, J.MTONGUE, J.CITY_RES, J.WEIGHT, J.AGE, J.HEIGHT, J.EDU_LEVEL, J.INCOME, J.ENTRY_DT,J.MOB_STATUS, J.LAST_LOGIN_DT, J.HAVEPHOTO, J.OCCUPATION, J.COUNTRY_RES, A.SERVICE_SMS, J.GET_SMS, J.SOURCE FROM newjs.JPROFILE J LEFT JOIN newjs.JPROFILE_ALERTS A ON J.PROFILEID = A.PROFILEID AND J.LAST_LOGIN_DT>='$time_format' AND J.ACTIVATED='Y' and J.activatedKey=1";
            $res_a = mysql_query($sql_a, $this->dbSlave) or $this->SMSLib->errormail($sql_a, mysql_errno() . ":" . mysql_error(), "Error occured while fetching 5 months active details from JPROFILE in setTempJPROFILE() function");
            ;
            $count_a       = mysql_num_rows($res_a);
            $totalChunks_a = ceil($count_a / $chunk);
            for ($j = 0; $j < $totalChunks_a; $j++) {
                $trans_a  = 0;
                $row_pool = array();
                $skip_a   = $j * $chunk;
                mysql_data_seek($res_a, $skip_a);
                $pArr = array();
                while (($row_a = mysql_fetch_assoc($res_a)) && $trans_a < $chunk) {
                    $row_pool[$row_a['PROFILEID']]               = $row_a;
                    $ntime                                       = $row_a['NTIMES'];
                    $entrydt                                     = JSstrToTime($row_a['ENTRY_DT']);
                    $days                                        = (($today - $entrydt) / (24 * 60 * 60));
                    $viewcount                                   = ($ntime / pow($days, .7));
                    $row_pool[$row_a['PROFILEID']]['VIEW_COUNT'] = $viewcount;
                    $trans_a++;
                    $pArr[] = $row_a['PROFILEID'];
                }
                $pid_str       = implode("','", $pArr);
                //$pid_str="'".$pid_str."'";
                $sql_sms_alert = "SELECT PROFILEID, SERVICE_SMS FROM newjs.JPROFILE_ALERTS WHERE PROFILEID IN ('" . $pid_str . "')";
                $res_sms_alert = mysql_query($sql_sms_alert, $this->dbSlave) or $this->SMSLib->errormail($sql_sms_alert, mysql_errno() . ":" . mysql_error(), "Error occured while fetching data from JPROFILE_ALERTS in setTempJPROFILE() function");
                while ($row_sms_alert = mysql_fetch_assoc($res_sms_alert))
                    $row_pool[$row_sms_alert['PROFILEID']]['SERVICE_SMS'] = $row_sms_alert['SERVICE_SMS'];
                $sql_ins = "INSERT INTO newjs.SMS_TEMP_TABLE(" . $this->getJPROFILEFieldsNew() . ") VALUES";
                foreach ($row_pool as $k => $v) {
                    $sql_ins .= "('$v[PROFILEID]', '$v[GENDER]', '" . addslashes($v["USERNAME"]) . "', '$v[SUBSCRIPTION]', '$v[PHONE_MOB]', '" . addslashes($v["PASSWORD"]) . "', '$v[CASTE]', '$v[DTOFBIRTH]', '$v[MSTATUS]', '$v[MTONGUE]', '$v[CITY_RES]', '$v[WEIGHT]', '$v[AGE]', '$v[HEIGHT]', '$v[EDU_LEVEL_NEW]', '$v[INCOME]', '$v[ENTRY_DT]', '$v[MOB_STATUS]', '$v[LAST_LOGIN_DT]', '$v[HAVEPHOTO]', '$v[OCCUPATION]', '$v[COUNTRY_RES]', '$v[GET_SMS]', '$v[SOURCE]', '$v[SERVICE_SMS]','$v[EMAIL]','" . addslashes($v[COMPANY_NAME]) . "','$v[OWN_HOUSE]','$v[FAMILY_INCOME]','$v[VIEW_COUNT]','$v[FTO_SUB_STATE]','$v[FTO_ENTRY_DATE]','$v[FTO_EXPIRY_DATE]', '$v[INCOMPLETE]','$v[ACTIVATED]','$v[LANDL_STATUS]','$v[VERIFY_ACTIVATED_DT]','" . addslashes($v[FAMILYINFO]) . "','" . addslashes($v[EDUCATION]) . "','" . addslashes($v[JOB_INFO]) . "','$v[ISD]'),";
                }
                $sql_ins = substr($sql_ins, 0, -1);
                //echo $sql_ins."\n\n";
                mysql_query($sql_ins, $this->dbMaster) or $this->SMSLib->errormail($sql_ins, mysql_errno() . ":" . mysql_error(), "Error occured while inserting 5 months data in SMS_TEMP_TABLE in setTempJPROFILE() function");
            }
        }
        $this->tempJPROFILE = "newjs.SMS_TEMP_TABLE";
    }
    function unsetTempJPROFILE()
    {
        $sql = "truncate table newjs.SMS_TEMP_TABLE";
        mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while truncating table SMS_TEMP_TABLE unsetTempJPROFILE() function");
    }
    function insertInSmsLog()
    {
        if ($this->sms) {
            $sql = "";
            foreach ($this->sms as $key => $val) {
                $val["MESSAGE"] = addslashes($val["MESSAGE"]);
                $sql            = $sql . "('$val[PROFILEID]', '$val[SMS_TYPE]', '$val[SMS_KEY]', '$val[MESSAGE]', '$val[PHONE_MOB]', '$val[PRIORITY]', now(),'$val[MUL_SMS]'),";
            }
            if ($sql) {
                $sql_ex = "INSERT IGNORE INTO newjs.TEMP_SMS_DETAIL(PROFILEID, SMS_TYPE, SMS_KEY, MESSAGE, PHONE_MOB, PRIORITY, ADD_DATE, MUL_SMS) VALUES " . substr($sql, 0, -1);
                //echo $sql_ex."\n\n\n";
                mysql_query($sql_ex, $this->dbMaster) or $this->SMSLib->errormail($sql_ex, mysql_errno() . ":" . mysql_error(), "Error occured while inserting generated SMSs into TEMP_SMS_DETAIL in insertInSmsLog() function");
                //mysql_query($sql_ex,$master) or die(mysql_error());
            }
        }
        unset($this->sms);
    }
    function getSmsSubscriptionCriteria($key)
    {
        if ($this->smsSubscription[$key] == "SERVICE")
            $str = " AND SERVICE_MESSAGES!='U'";
        elseif ($this->smsSubscription[$key] == "PROMO")
            $str = " AND GET_SMS!='N'";
        if ($key == "MATCH_ALERT")
            $str;
        elseif ($key == "INCOMPLETE")
            $str .= " AND ACTIVATED = 'N'";
        elseif($key == "PHONE_VERIFY_SCREEN" || $key=="MADELIVE_30" || $key=="MADELIVE_90")
            $str .= " AND ACTIVATED !='D'";
        else
            $str .= " AND ACTIVATED = 'Y'";
        return $str;
    }
    function ShardIdForProfile($profileid)
    {
        $shardId = getProfileDatabaseConnectionName($profileid, 'slave', $this->mysqlObj);
        return $shardId;
    }
    function checkMsgSyntax($message)
    {
        $newMessage = "";
        $msg_piece  = explode(",", $message);
        if (is_Array($msg_piece)) {
            foreach ($msg_piece as $k => $v) {
                if (trim($v))
                    $msg[] = $v;
            }
            $message = implode(",", $msg);
        }
        $msg_piece_dot = explode(".", $message);
        if (is_Array($msg_piece_dot)) {
            foreach ($msg_piece_dot as $k1 => $v1) {
                $msg_Comma_arr = array();
                $msg_comma     = explode(",", $v1);
                if (is_Array($msg_comma)) {
                    foreach ($msg_comma as $k2 => $v2) {
                        if (trim($v2))
                            $msg_Comma_arr[] = $v2;
                    }
                    $msg_dot_arr[] = implode(",", $msg_Comma_arr);
                } elseif (trim($msg_comma))
                    $msg_dot_arr[] = $msg_comma;
            }
            $newMessage = implode(".", $msg_dot_arr);
            return $newMessage;
        } else
            return $message;
    }
    // Trac #1073 Get the matched profile from the match alert mail log for last 30 days
    function matchAlertArray($profileid, $gap)
    {
        $sql = "SELECT DISTINCT USER from matchalerts.LOG WHERE DATE >= " . $gap . " AND RECEIVER = " . $profileid . " AND USER NOT IN (SELECT BEST_MATCH FROM BEST_MATCH_SMS_LOG WHERE PROFILEID =" . $profileid . " AND SENT = 'Y' ) ";
        $res = mysql_query($sql, $this->dbMatch) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception(mysql_error()));
        $count = 0;
        while ($row = mysql_fetch_assoc($res)) {
            $arr[$count++] = $row['USER'];
        }
        return $arr;
    }
    // Trac #1073 Filters the match profile by given criteria
    function filterProfile($profileid, $arr)
    {
        $profile = implode(",", $arr);
        $eoiArr  = $this->matchEoiArray($profileid, $profile);
        if (!empty($eoiArr)) {
            $arr = @array_diff($arr, $eoiArr);
        }
        if (!empty($arr)) {
            $profile = implode(",", $arr);
            $ignArr  = $this->ignoreProfileArray($profileid, $profile, $arr);
            if (!empty($ignArr)) {
                $arr = @array_diff($arr, $ignArr);
            }
            if (!empty($arr)) {
                $profile  = implode(",", $arr);
                $shortArr = $this->shortlistedProfile($profileid, $profile);
                if (!empty($shortArr)) {
                    $arr = @array_diff($arr, $shortArr);
                }
                if (!empty($arr)) {
                    $profile = implode(",", $arr);
                    $viewArr = $this->viewedProfile($profileid, $profile);
                    if (!empty($viewArr)) {
                        $arr = @array_diff($arr, $viewArr);
                    }
                }
            }
        }
        return $arr;
    }
    // Trac #1073 Get the profile for which EOI has been sent or recieved 
    function matchEoiArray($profileid, $profile)
    {
        $mysqlObj = new Mysql;
        $myDbName = $this->ShardIdForProfile($profileid);
        $myDb     = $mysqlObj->connect("$myDbName");
        $sql      = " SELECT RECEIVER FROM newjs.CONTACTS WHERE SENDER = " . $profileid . " AND RECEIVER IN (" . $profile . ")";
        $res = mysql_query($sql, $this->dbShards[$myDbName]) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while running the select query in matchEOIArray() function");
        $count = 0;
        while ($row = mysql_fetch_assoc($res)) {
            $arr[$count++] = $row['RECEIVER'];
        }
        $sql = " SELECT SENDER FROM newjs.CONTACTS WHERE SENDER IN (" . $profile . ") AND RECEIVER = " . $profileid;
        $res = mysql_query($sql, $this->dbShards[$myDbName]) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while running the select query in matchEOIArray() function");
        while ($row = mysql_fetch_assoc($res)) {
            $arr[$count++] = $row['SENDER'];
        }
        return $arr;
    }
    // Trac #1073 get the details of profile which has ignored or ignored by the given profile id 
    function ignoreProfileArray($profileid, $profile, $arr1)
    {
        $arr  = array();
        $arr2 = array();
        $sql  = "SELECT IGNORED_PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID = " . $profileid . " AND IGNORED_PROFILEID IN (" . $profile . ")";
        $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while running the select query in ignoreProfileArray() function");
        $count = 0;
        while ($row = mysql_fetch_assoc($res)) {
            $arr[$count++] = $row['IGNORED_PROFILEID'];
        }
        if (!empty($arr)) {
            $profileArr = @array_diff($arr1, $arr);
        } else {
            $profileArr = $arr1;
        }
        if (!empty($profileArr)) {
            $profile = implode(",", $profileArr);
            $sql     = "SELECT PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID IN (" . $profile . ") AND IGNORED_PROFILEID = " . $profileid;
            $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while running the select query in ignoreProfileArray() function");
            $count = 0;
            while ($row = mysql_fetch_assoc($res)) {
                $arr2[$count++] = $row['PROFILEID'];
            }
        }
        $arr = array_merge($arr, $arr2);
        return $arr;
    }
    // Trac #1073 get the details of the bookmarked profile by the given profileid 
    function shortlistedProfile($profileid, $profile)
    {
        $sql = "SELECT BOOKMARKEE FROM newjs.BOOKMARKS WHERE BOOKMARKER = " . $profileid . " AND BOOKMARKEE IN (" . $profile . ")";
        $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while running the select query in shortlistedProfile() function");
        $count = 0;
        while ($row = mysql_fetch_assoc($res)) {
            $arr[$count++] = $row['BOOKMARKEE'];
        }
        return $arr;
    }
    // Trac #1073 Get the details of which viewed profile 
    function viewedProfile($profileid, $profile)
    {
        $sql = "SELECT VIEWED FROM newjs.VIEW_LOG_TRIGGER WHERE VIEWER = " . $profileid . " AND VIEWED IN (" . $profile . ")";
        $res = mysql_query($sql, $this->dbShards["211Slave"]) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while running the select query in Viewedprofile() function");
        $count = 0;
        while ($row = mysql_fetch_assoc($res)) {
            $arr[$count++] = $row['VIEWED'];
        }
        return $arr;
    }
    // Trac #1073 Return the best match profile which has the maximum view count 
    function bestMatchProfile($arr)
    {
        $profilein = implode(",", $arr);
        $sql       = "SELECT PROFILEID,VIEW_COUNT FROM newjs.SMS_TEMP_TABLE WHERE PROFILEID IN (" . $profilein . ") ORDER BY VIEW_COUNT DESC";
        $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while running the select query in bestMatchProfile() function");
        $row         = mysql_fetch_assoc($res);
        $bestProfile = $row;
        return $bestProfile;
    }
    // Trac #1073 this function get the profile id and the match alert array and return the best match profile id after filtering the profile as per the given criteria. 
    function getBestProfile($profileid, $gap, $arr)
    {
        $filterArr = $this->filterProfile($profileid, $arr);
        if (!empty($filterArr))
            $bestProfile = $this->bestMatchProfile($filterArr);
        return $bestProfile;
    }
    // Trac #1073 Insert the best match Profile in the log 
    function insertInBestSmsLog($profileid, $bestProfile, $score)
    {
        $sql = "INSERT IGNORE INTO newjs.BEST_MATCH_SMS_LOG (PROFILEID,BEST_MATCH,DATE,SCORE,SENT) VALUES(" . $profileid . "," . $bestProfile . ",now()," . $score . ",'N')";
        mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while insert Best profile in BEST_MATCH_SMS_LOG insertInBestSmsLog() function");
    }
    //trac 1073 get the details of subscription
    function getSubscription($key)
    {
        if ($this->subscription[$key] == "P")
            return " AND SUBSCRIPTION !=''";
        else
            return " AND SUBSCRIPTION =''";
    }
    function getBestMatchProfile($profileid)
    {
        $sql = "SELECT BEST_MATCH,SCORE,DATE FROM BEST_MATCH_SMS_LOG WHERE PROFILEID = " . $profileid . " AND SENT = 'N' ORDER BY DATE ASC";
        $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while running the select query in bestMatchProfile() function");
        if (mysql_num_rows($res) > 0) {
            while ($row = mysql_fetch_assoc($res)) {
                //$date1 = new DateTime("now");
                $date2   = $row["DATE"];
                $dayDiff = $this->date_diff($date2);
                if ($dayDiff > 1) {
                    $sql = "DELETE FROM BEST_MATCH_SMS_LOG WHERE PROFILEID = " . $profileid . " AND BEST_MATCH = " . $row["BEST_MATCH"];
                    $res = mysql_query($sql, $this->dbMaster) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while Deleting unset bestprofile from BEST_MATCH_SMS_LOG in function getBestMatchProfile() function");
                    return false;
                } else {
                    $bestProfile["PROFILEID"]  = $row["BEST_MATCH"];
                    $bestProfile["VIEW_COUNT"] = $row["SCORE"];
                    return $bestProfile;
                }
            }
        } else {
            return false;
        }
    }
    // trac 1014 get the details of allotted agent if details is not available send the name as rahul and toll free number
    function allotedToAgent($profileid)
    {
        $sql = "SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID = " . $profileid;
        $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while running the select query in bestMatchProfile() function");
        if (mysql_num_rows($res) > 0) {
            $row       = mysql_fetch_assoc($res);
            $agentname = $row["ALLOTED_TO"];
            $sql       = "SELECT * FROM newjs.SMS_CONTACTS WHERE USERNAME = '" . $agentname . "' AND BRANCH != 'Noida'";
            $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while running the select query in bestMatchProfile() function");
            if (mysql_num_rows($res) > 0) {
                $row          = mysql_fetch_assoc($res);
                $allotedAgent = $row;
            }
            if (empty($allotedAgent) || $allotedAgent["SMS_MOBILE"] == "") {
                $sql = "SELECT CENTER,SUB_CENTER FROM jsadmin.PSWRDS WHERE USERNAME = '" . $agentname . "'";
                $res = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while running the select query in bestMatchProfile() function");
                $row = mysql_fetch_assoc($res);
                if ($row["SUB_CENTER"]) {
                    $branch = $row["SUB_CENTER"];
                } else {
                    $branch = $row["CENTER"];
                }
                if (empty($allotedAgent)) {
                    $allotedAgent["SMS_NAME"] = "Rahul";
                    $allotedAgent["BRANCH"]   = $branch;
                }
                $allotedAgent["SMS_MOBILE"] = "18004196299";
            }
            return $allotedAgent;
        }
        return false;
    }
    function date_diff($end)
    {
		$current  = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
        $start_ts = JSstrToTime(date("Y-m-d", $current));
        $end_ts   = JSstrToTime($end);
        $diff     = $end_ts - $start_ts;
        $diff     = abs(round($diff / 86400));
        return $diff;
    }
    function getTempJPROFILE()
    {
        return "newjs.SMS_TEMP_TABLE";
    }
    function ftoTimeCriteria($profile, $today_date_format)
    {
        if (!empty($profile["FTO_SUB_STATE"]) && $profile["FTO_EXPIRY_DATE"] >= $today_date_format) {
            if ($this->date_diff($profile["FTO_EXPIRY_DATE"]) > 1) {
                if ($this->date_diff($profile["FTO_ENTRY_DATE"]) % 2 == 0)
                    $criteria = 0;
                else if ($this->date_diff($profile["FTO_ENTRY_DATE"]) % 2 == 1)
                    $criteria = 1;
            } else
                $criteria = 2;
        } else
            $criteria = 0;
        return $criteria;
    }


    // other conditions to filter the profile
    function filterProfileForVD($profileid,$number,$db_slave='')
    {
    	$number =$this->vdMobileNumberChecks($number,$db_slave);	
    	if(!$number)
    		return false;

    	$sqlNegative = "SELECT PROFILEID FROM incentive.NEGATIVE_PROFILE_LIST WHERE MOBILE IN('$number','0$number','91$number')";
    	$resNegative = mysql_query($sqlNegative,$db_slave) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("$sqlNegative".mysql_error($db_slave)));
    	$rowCnt =mysql_num_rows($resNegative); 
    	if($rowCnt>0)
    		return false;
    	return $number;	
    }

    // check Flat VD Discount
    function checkFlatVdDiscount($profileid,$durationArr)
    {
                $vdOfferDurationObj =new billing_VARIABLE_DISCOUNT_OFFER_DURATION('newjs_slave');
                $discountDetails =$vdOfferDurationObj->getDiscountDetailsForProfile($profileid);

                foreach($discountDetails as $key=>$val){
                        $discountArr =$val;
                        unset($discountArr['PROFILEID']);
                        unset($discountArr['SERVICE']);
                        foreach($discountArr as $key1=>$val1){
				if(in_array($key1, $durationArr))
	                                $discountNewArr[] =$val1;
			}
                }
                $discountUniqueArr =array_values(array_unique($discountNewArr));
                $totCount =count($discountUniqueArr);
                if($totCount==1)
                        return true;
                return false;
    }

    // Validations added for the mobile numbers 
    function vdMobileNumberChecks($number,$db_slave='')
    {
    	if(!is_numeric($number))
    		return false;

    	$number =substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/","",$number),-10);
    	if(strlen($number)!='10')
    		return false;

    	if($number<7000000000)
    		return false;

    	$sqlJunk ="select count(*) cnt from newjs.PHONE_JUNK WHERE PHONE_NUM='$number'";
    	$resJunk = mysql_query($sqlJunk,$db_slave) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("$sqlJunk".mysql_error($db_slave)));
    	$rowJunk = mysql_fetch_array($resJunk);
    	if($rowJunk['cnt']>0)
    		return false;
    	return $number;
    }
    function updateVDSmsSent($row_pool)
    {	
	$score ='40';
	if(is_array($row_pool))
		$profileStr =implode(",",$row_pool);
	$tempSmsObj =new newjs_TEMP_SMS_DETAIL();
	$vdPoolTechObj =new billing_VARIABLE_DISCOUNT_POOL_TECH();				
	$profilesArr =$vdPoolTechObj->getProfilesForScore($profileStr,$score);
	if(count($profilesArr)>0){
		foreach($profilesArr as $key=>$pid)
			$tempSmsObj->updateSentForVD($pid);    
	}
    }
    function logSentSMS($key, $paramArr=array())
    {
        switch($key)
        {
            case "REQUEST_CALLBACK":
                $SMSCallback = new billing_SMS_REQUEST_CALLBACK();
                $SMSCallback->addSMSDetails($paramArr);
            break;
        }
    }

        public function userLoggedInFromApp($profileid,$date)
        {
                        $sql = "SELECT count(*) as CNT FROM MIS.`LOGIN_TRACKING` WHERE `PROFILEID`=$profileid AND date(DATE)>='$date' AND WEBSITE_VERSION IN ('A','I')";
                        $result = mysql_query($sql, $this->dbSlave) or $this->SMSLib->errormail($sql, mysql_errno() . ":" . mysql_error(), "Error occured while fetching details for SMS Key:  EOI  in processData() function while executing on MIS.LOGIN_TRACKING");
                        if($row = mysql_fetch_assoc($result))
                        {
                           if($row['CNT']==0) return false;
                           else return true;
                        }
                        
                        return false;      
        }
    
    
}
?>
