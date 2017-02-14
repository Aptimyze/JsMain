<?php
class PHONE_VERIFIED_LOG extends TABLE
{
    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }

    public function getProfilesVerifiedOnADate($dateVal)
    {
        try
        {
            $dateStart = $dateVal . " 00:00:00";
            $dateEnd   = $dateVal . " 23:59:59";

            $sql  = "SELECT COUNT( DISTINCT PROFILEID ) AS COUNT FROM  jsadmin.PHONE_VERIFIED_LOG WHERE ENTRY_DT >=  :DATE_START AND ENTRY_DT <= :DATE_END";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":DATE_START", $dateStart, PDO::PARAM_STR);
            $prep->bindValue(":DATE_END", $dateEnd, PDO::PARAM_STR);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                return $result['COUNT'];
            }

        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getVerificationDate($PROFILEID, $number)
    {
        try
        {
            $res = null;
            $str = '';
            if ($PROFILEID && $number) {
                if (!strstr($number, "-")) {
                    $str = ":mob, :mob0, :mob91, :mob91A";
                } else {
                    $str = ":landl, :landl0, :landlstd,:landlstd0";
                }

            }

            if ($str) {
                $sql  = "SELECT ENTRY_DT FROM jsadmin.PHONE_VERIFIED_LOG WHERE PROFILEID=:PROFILEID AND PHONE_NUM IN (" . $str . ") ORDER BY ENTRY_DT DESC LIMIT 1";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
                if (!strstr($number, "-")) {
                    $prep->bindValue(":mob", $number, PDO::PARAM_STR);
                    $prep->bindValue(":mob0", '0' . $number, PDO::PARAM_STR);
                    $prep->bindValue(":mob91", '91' . $number, PDO::PARAM_STR);
                    $prep->bindValue(":mob91A", '+91' . $number, PDO::PARAM_STR);
                } else {
                    $stdNumberArray = explode("-", $number);
                    $std            = $stdNumberArray[0];
                    $landline       = $stdNumberArray[1];
                    $prep->bindValue(":landl", $landline, PDO::PARAM_STR);
                    $prep->bindValue(":landl0", '0' . $landline, PDO::PARAM_STR);
                    $prep->bindValue(":landlstd", $std . $landline, PDO::PARAM_STR);
                    $prep->bindValue(":landlstd0", '0' . $std . $landline, PDO::PARAM_STR);
                }
                $prep->execute();
                if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                    $res = $result['ENTRY_DT'];
                }

            } else {
                throw new jsException("No phone number as Input paramter");
            }

            return $res;

        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
    public function getProfilesVerified($profiles)
    {
        try
        {
            foreach ($profiles as $k => $p) {
                if ($str != '') {
                    $str .= ",";
                }

                $str .= ":PROFILEID" . $k;
            }
            $sql  = "SELECT DISTINCT(PROFILEID) FROM  jsadmin.PHONE_VERIFIED_LOG WHERE PROFILEID IN (" . $str . ")";
            $prep = $this->db->prepare($sql);
            foreach ($profiles as $k => $p) {
                $prep->bindValue(":PROFILEID" . $k, $p, PDO::PARAM_STR);
            }

            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $return[] = $result['PROFILEID'];
            }

            return $return;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    public function fetchVerifiedProfiles($dateTime)
    {
        try
        {
            $sql  = "SELECT DISTINCT(PROFILEID) FROM  jsadmin.PHONE_VERIFIED_LOG WHERE ENTRY_DT>:ENTRY_DT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":ENTRY_DT", $dateTime, PDO::PARAM_STR);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $return[] = $result['PROFILEID'];
            }

            return $return;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getNoOfTimesVerified($profileid)
    {
        try
        {
            $sql  = "SELECT COUNT(*) AS COUNT FROM jsadmin.PHONE_VERIFIED_LOG WHERE PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                return $result;
            } else {
                $result['COUNT'] = 0;
            }

            return $result;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    public function getLogForOtherNumberVerified($profileId, $number, $startDate, $endDate)
    {
        try
        {

            $sql  = "SELECT * FROM jsadmin.PHONE_VERIFIED_LOG WHERE PROFILEID!=:PROFILEID AND PHONE_NUM=:PHONE_NUM AND ENTRY_DT BETWEEN :STARTDATE AND :ENDDATE ";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PHONE_NUM", $number, PDO::PARAM_STR);
            $prep->bindValue(":STARTDATE", $startDate, PDO::PARAM_STR);
            $prep->bindValue(":ENDDATE", $endDate, PDO::PARAM_STR);
            $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_STR);
            $prep->execute();

            if ($result = $prep->fetchAll(PDO::FETCH_ASSOC)) {
                return $result;
            }

        } catch (PDOException $e) {
            jsCacheWrapperException::logThis($e);
        }
    }

    public function insertEntry($profileid, $phoneType, $phoneNum, $msg, $opUsername)
    {

        if (!$profileid || !$phoneType || !$phoneNum || !$msg) {
            throw new jsException("", "one or more of the arguements IS BLANK IN insert() OF jsadmin_PHONE_VERIFIED_LOG.class.php");
        }

        try
        {

            $sql = "INSERT into jsadmin.PHONE_VERIFIED_LOG (`PROFILEID`,`PHONE_TYPE`,`PHONE_NUM`,`MSG`,`OP_USERNAME`,`ENTRY_DT`) VALUES (:PROFILEID,:PHONETYPE,:PHONE,:MESSAGE,:USERNAME,:TIME)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->bindValue(":PHONE", $phoneNum, PDO::PARAM_STR);
            $res->bindValue(":USERNAME", '', PDO::PARAM_STR);
            $res->bindValue(":MESSAGE", $msg, PDO::PARAM_STR);
            $res->bindValue(":PHONETYPE", $phoneType, PDO::PARAM_STR);
            $res->bindValue(":TIME", (new DateTime)->format('Y-m-j H:i:s'), PDO::PARAM_STR);
            $res->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
    public function getVerifiedPhoneNumbers($profiles)
    {
        try {
            foreach ($profiles as $k => $p) {
                if ($str != '') {
                    $str .= ",";
                }

                $str .= ":PROFILEID" . $k;
            }
            $sql  = "SELECT DISTINCT(PHONE_NUM) FROM  jsadmin.PHONE_VERIFIED_LOG WHERE PROFILEID IN (" . $str . ")";
            $prep = $this->db->prepare($sql);
            foreach ($profiles as $k => $p) {
                $prep->bindValue(":PROFILEID" . $k, $p, PDO::PARAM_STR);
            }

            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $phone = $result['PHONE_NUM'];
                if ($phone) {
                    $dataArr[] = $phone;
                }

            }
            return $dataArr;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    public function getVerifiedProfiles($phoneNumber)
    {
        try {
            foreach ($phoneNumber as $k => $p) {
                if ($str != '') {
                    $str .= ",";
                }

                $str .= ":PHONE_NUM" . $k;
            }
            $sql  = "SELECT DISTINCT(PROFILEID) FROM  jsadmin.PHONE_VERIFIED_LOG WHERE PHONE_NUM IN (" . $str . ")";
            $prep = $this->db->prepare($sql);
            foreach ($phoneNumber as $k => $p) {
                $prep->bindValue(":PHONE_NUM" . $k, $p, PDO::PARAM_STR);
            }

            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $pid = $result['PROFILEID'];
                if ($pid) {
                    $dataArr[] = $pid;
                }

            }
            return $dataArr;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getVerifiedProfilesAndPhone($phoneNumber)
    {
        try {
            foreach ($phoneNumber as $k => $p) {
                if ($str != '') {
                    $str .= ",";
                }

                $str .= ":PHONE_NUM" . $k;
            }
            $sql  = "SELECT PROFILEID, PHONE_NUM FROM  jsadmin.PHONE_VERIFIED_LOG WHERE PHONE_NUM IN (" . $str . ")";
            $prep = $this->db->prepare($sql);
            foreach ($phoneNumber as $k => $p) {
                $prep->bindValue(":PHONE_NUM" . $k, $p, PDO::PARAM_STR);
            }

            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $dataArr[] = $result;
            }
            return $dataArr;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

}
