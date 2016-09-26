<?php

/**
 * @author Vibhor Garg
 * @copyright Copyright 2011, Infoedge India Ltd.
 */
include_once JsConstants::$docRoot . "/commonFiles/SymfonyPictureFunctions.class.php";
// including for logging purpose
include_once JsConstants::$docRoot . "/classes/LoggingWrapper.class.php";

class Scoring_ab
{
    //Variables
    public $PROFILEID;
    public $GENDER;
    public $MTONGUE;
    public $CITY_RES;
    public $ENTRY_DT;
    public $SHOW_HOROSCOPE;
    public $AGE;
    public $INCOME;
    public $SOURCE;
    public $CASTE;
    public $MOB_STATUS;
    public $LANDL_STATUS;
    public $OCCUPATION;
    public $EDU_LEVEL;
    public $MSTATUS;
    public $GET_SMS;
    public $RELIGION;
    public $EDU_LEVEL_NEW;
    public $VERIFY_EMAIL;
    public $HEIGHT;
    public $TIME_TO_CALL_START;
    public $TIME_TO_CALL_END;
    public $HAVE_CAR;
    public $OWN_HOUSE;
    public $FAMILY_STATUS;
    public $SHOWADDRESS;
    public $WORK_STATUS;
    public $DTOFBIRTH;
    public $PARTNER_FIELDSFILLED;
    public $globalParamsObj;

    public function __construct($profileid, $myDb, $shDb, $parameter = "*", $ptype, $globalParamsObj)
    {
        $this->globalParamsObj = $globalParamsObj;
        $this->setAllVariables($profileid, $myDb, $shDb, $parameter = "*", $ptype);
    }

    /**
     * This function is used to set all the required variables of a profile depending upon the profile type.
     */
    public function setAllVariables($profileid, $myDb, $shDb, $parameter = "*", $ptype)
    {
        if ($profileid) {
            $this->PROFILEID = $profileid;
        }

        /*Set all common parameters*/
        $sql    = "SELECT $parameter FROM newjs.JPROFILE WHERE PROFILEID=$profileid";
        $result = mysql_query_decide($sql, $myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql . mysql_error($myDb)));
        $myrow  = mysql_fetch_array($result);
        if ($myrow) {
            foreach ($myrow as $key => $value) {
                $this->$key = $value;
            }

        }

        /*Set computational parameters depending upon the profile type*/

        //Profile Data
        $this->newmodel[PROFILEID]          = $this->PROFILEID;
        $this->newmodel[WORK_STATUS]        = $this->WORK_STATUS;
        $this->newmodel[FAMILY_STATUS]      = $this->FAMILY_STATUS;
        $this->newmodel[EDU_LEVEL]          = $this->EDU_LEVEL;
        $this->newmodel[INCOME]             = $this->INCOME;
        $this->newmodel[SHOWADDRESS]        = $this->SHOWADDRESS;
        $this->newmodel[HAVE_CAR]           = $this->HAVE_CAR;
        $this->newmodel[EDU_LEVEL_NEW]      = $this->EDU_LEVEL_NEW;
        $this->newmodel[OCCUPATION]         = $this->OCCUPATION;
        $this->newmodel[OWN_HOUSE]          = $this->OWN_HOUSE;
        $this->newmodel[CASTE]              = $this->CASTE;
        $this->newmodel[CITY_RES]           = $this->CITY_RES;
        $this->newmodel[MOB_STATUS]         = $this->MOB_STATUS;
        $this->newmodel[LANDL_STATUS]       = $this->LANDL_STATUS;
        $this->newmodel[GENDER]             = $this->GENDER;
        $this->newmodel[RELIGION]           = $this->RELIGION;
        $this->newmodel[HEIGHT]             = $this->HEIGHT;
        $this->newmodel[MSTATUS]            = $this->MSTATUS;
        $this->newmodel[VERIFY_EMAIL]       = $this->VERIFY_EMAIL;
        $this->newmodel[GET_SMS]            = $this->GET_SMS;
        $this->newmodel[LANDL_STATUS]       = $this->LANDL_STATUS;
        $this->newmodel[HEIGHT]             = $this->HEIGHT;
        $this->newmodel[TIME_TO_CALL_START] = $this->TIME_TO_CALL_START;
        $this->newmodel[TIME_TO_CALL_END]   = $this->TIME_TO_CALL_END;
        $this->newmodel[SHOW_HOROSCOPE]     = $this->SHOW_HOROSCOPE;
        $this->newmodel[MTONGUE]            = $this->MTONGUE;
        $this->newmodel[DOB]                = $this->DTOFBIRTH;
        $this->newmodel[LAST_LOGIN_DT]      = $this->LAST_LOGIN_DT;
        $this->newmodel[ENTRY_DT]           = $this->ENTRY_DT;

        //Purchase Data
        $this->newmodel[SUBSCRIPTION_START_DATE] = "";
        $this->newmodel[SUBSCRIPTION_END_DATE]   = "";
        $this->newmodel[NET_AMOUNT]              = "";
        $this->newmodel[DISCOUNT]                = "";
        $this->newmodel[START_DATE]              = "";
        $this->newmodel[SERVICEID]               = "";
        $this->newmodel[CUR_TYPE]                = "";
        if ($ptype == 'R' || $ptype == 'C') {
            $sqlpd = "SELECT START_DATE,END_DATE,SERVICEID,CUR_TYPE,NET_AMOUNT,DISCOUNT FROM billing.PURCHASE_DETAIL WHERE PROFILEID='$this->PROFILEID'";
            $respd = mysql_query_decide($sqlpd, $myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlpd . mysql_error($myDb)));
            while ($rowpd = mysql_fetch_array($respd)) {
                $this->newmodel[SUBSCRIPTION_START_DATE] = $rowpd['START_DATE'];
                $this->newmodel[SUBSCRIPTION_END_DATE]   = $rowpd['END_DATE'];
                $this->newmodel[NET_AMOUNT]              = $rowpd['NET_AMOUNT'];
                $this->newmodel[DISCOUNT]                = $rowpd['DISCOUNT'];
                $this->newmodel[START_DATE]              = $rowpd['START_DATE'];
                $this->newmodel[SERVICEID]               = $rowpd['SERVICEID'];
                $this->newmodel[CUR_TYPE]                = $rowpd['CUR_TYPE'];
            }
        }

        //$pidShard=JsDbSharding::getShardNo($profileid,'slave');
        $shard           = ($profileid % 3) + 1;
        $dbName          = "shard" . $shard . "Slave112";
        $dbMessageLogObj = new NEWJS_MESSAGE_LOG($dbName);

        //Activity Data
        $this->newmodel[Search_In14Days] = "";
        $this->newmodel[Search_In14Days] = $this->globalParamsObj->getSearchParameters($pid);

        $this->newmodel[lOGIN_hist_In_week]          = "";
        $this->newmodel[Viewed_In_HalfMonth]         = "";
        $this->newmodel[Viewer_In_HalfMonth]         = "";
        $this->newmodel[Message_Req_I_Week]          = "";
        $this->newmodel[Message_In_week]             = "";
        $this->newmodel[Message_Req_R_Week]          = "";
        $this->newmodel[ChatReciever_HalfMOnth]      = "";
        $this->newmodel[Photo_Receive_In_HalfMonth]  = "";
        $this->newmodel[Horoscope_Send_In_HalfMonth] = "";
        $this->newmodel[Photo_Send_In_week]          = "";
        if ($ptype == 'F' || $ptype == 'R') {
            $lim_7_dt = date("Y-m-d", time() - 7 * 86400);
            $sqll     = "SELECT COUNT(*) as cnt FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$this->PROFILEID' AND LOGIN_DT >= '$lim_7_dt'";
            $resl     = mysql_query_decide($sqll, $shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqll . mysql_error($shDb)));
            if ($rowl = mysql_fetch_array($resl)) {
                $this->newmodel[lOGIN_hist_In_week] = $rowl["cnt"];
            }

            $lim_15_dt = date("Y-m-d", time() - 15 * 86400);
            $sqle      = "SELECT COUNT(*) as cnt FROM newjs.EOI_VIEWED_LOG WHERE VIEWED = '$this->PROFILEID' AND DATE >= '$lim_15_dt'";
            $rese      = mysql_query_decide($sqle, $shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqle . mysql_error($shDb)));
            if ($rowe = mysql_fetch_array($rese)) {
                $this->newmodel[Viewed_In_HalfMonth] = $rowe["cnt"];
            }

            $sqle = "SELECT COUNT(*) as cnt FROM newjs.EOI_VIEWED_LOG WHERE VIEWER = '$this->PROFILEID' AND DATE >= '$lim_15_dt'";
            $rese = mysql_query_decide($sqle, $shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqle . mysql_error($shDb)));
            if ($rowe = mysql_fetch_array($rese)) {
                $this->newmodel[Viewer_In_HalfMonth] = $rowe["cnt"];
            }

            $total = 0;
            $res   = $dbMessageLogObj->getMessageLogScoringAb100($this->PROFILEID, 'SENDER', $lim_7_dt);
            foreach ($res as $key => $rowl) {
                if ($rowl["TYPE"] == 'I') {
                    $this->newmodel[Message_Req_I_Week] = $rowl["cnt"];
                }

                $total += $rowl["cnt"];
            }
            $this->newmodel[Message_In_week] = $total;
            $rowl                            = $dbMessageLogObj->getMessageLogCountScoringAb100($this->PROFILEID, 'RECEIVER', $lim_7_dt);
            if ($rowl) {
                $this->newmodel[Message_Req_R_Week] = $rowl;
            }

            $sqll = "SELECT COUNT(*) as cnt FROM userplane.CHAT_REQUESTS WHERE RECEIVER = '$this->PROFILEID' AND TIMEOFINSERTION >= '$lim_15_dt'";
            $resl = mysql_query_decide($sqll, $myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqll . mysql_error($myDb)));
            if ($rowl = mysql_fetch_array($resl)) {
                $this->newmodel[ChatReciever_HalfMOnth] = $rowl["cnt"];
            }

            $sqll = "SELECT COUNT(*) as cnt FROM newjs.PHOTO_REQUEST WHERE PROFILEID = '$this->PROFILEID' AND DATE >= '$lim_15_dt'";
            $resl = mysql_query_decide($sqll, $shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqll . mysql_error($shDb)));
            if ($rowl = mysql_fetch_array($resl)) {
                $this->newmodel[Photo_Receive_In_HalfMonth] = $rowl["cnt"];
            }

            $sqll = "SELECT COUNT(*) as cnt FROM newjs.HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY = '$this->PROFILEID' AND DATE >= '$lim_15_dt'";
            $resl = mysql_query_decide($sqll, $shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqll . mysql_error($shDb)));
            if ($rowl = mysql_fetch_array($resl)) {
                $this->newmodel[Horoscope_Send_In_HalfMonth] = $rowl["cnt"];
            }

            $sqll = "SELECT COUNT(*) as cnt FROM newjs.PHOTO_REQUEST WHERE PROFILEID_REQ_BY = '$this->PROFILEID' AND DATE >= '$lim_15_dt'";
            $resl = mysql_query_decide($sqll, $shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqll . mysql_error($shDb)));
            if ($rowl = mysql_fetch_array($resl)) {
                $this->newmodel[Photo_Send_In_week] = $rowl["cnt"];
            }
        }

        $this->newmodel[MESSAGES_COUNT] = "";
        $this->newmodel[LOGINS_LAST30]  = "";
        $this->newmodel[VIEWS_LAST60]   = "";
        $this->newmodel[VIEWER_LAST60]  = "";
        if ($ptype == "C") {
            $lim_30_dt = date("Y-m-d", time() - 30 * 86400);
            $lim_60_dt = date("Y-m-d", time() - 60 * 86400);
            //$fdate = date("Y-m-d", strtotime($this->newmodel[$this->PROFILEID]['SUBSCRIPTION_END_DATE'])-60*86400);
            //$ldate = date("Y-m-d", strtotime($this->newmodel[$this->PROFILEID]['SUBSCRIPTION_END_DATE']));
            $fdate  = $lim_30_dt;
            $ldate  = date("Y-m-d");
            $rowml2 = $dbMessageLogObj->getMessageLogCountEOIScoringAb100($this->PROFILEID, 'RECEIVER', $fdate, $ldate);
            if ($rowml2) {
                $this->newmodel[MESSAGES_COUNT] = $rowml2;
            }

            $sqll = "SELECT COUNT(*) as cnt FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$this->PROFILEID' AND LOGIN_DT >= '$lim_30_dt'";
            $resl = mysql_query_decide($sqll, $shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqll . mysql_error($shDb)));
            if ($rowl = mysql_fetch_array($resl)) {
                $this->newmodel[LOGINS_LAST30] = $rowl["cnt"];
            }

            $sqle = "SELECT COUNT(*) as cnt FROM newjs.EOI_VIEWED_LOG WHERE VIEWED = '$this->PROFILEID' AND DATE >= '$lim_60_dt'";
            $rese = mysql_query_decide($sqle, $shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqle . mysql_error($shDb)));
            if ($rowe = mysql_fetch_array($rese)) {
                $this->newmodel[VIEWS_LAST60] = $rowe["cnt"];
            }

            $sqle = "SELECT COUNT(*) as cnt FROM newjs.EOI_VIEWED_LOG WHERE VIEWER = '$this->PROFILEID' AND DATE >= '$lim_60_dt'";
            $rese = mysql_query_decide($sqle, $shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqle . mysql_error($shDb)));
            if ($rowe = mysql_fetch_array($rese)) {
                $this->newmodel[VIEWER_LAST60] = $rowe["cnt"];
            }
        }

        //Defining source for score api
        if ($ptype == "R") {
            $this->newmodel[SOURCE] = 1;
        } elseif ($ptype == "F") {
            $this->newmodel[SOURCE] = 2;
        } elseif ($ptype == "C") {
            $this->newmodel[SOURCE] = 3;
        }

    }
}
