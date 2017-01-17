<?php
class jsadmin_OPS_PHONE_VERIFIED_LOG extends TABLE
{
    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }


function insertOPSPhoneReport($cid,$profileId,$phoneNum,$phoneType,$phoneStatus)
{

        if (!$profileId) {
            throw new jsException("", "one or more of the arguements IS BLANK IN insert() OF jsadmin_PHONE_VERIFIED_LOG.class.php");
        }

        try
        {

            $sql = "INSERT into jsadmin.OPS_PHONE_VERIFIED_LOG (`PROFILEID`,`PHONE_TYPE`,`PHONE_NUM`,`OPS_USERID`,`ENTRY_DT`,`PHONE_STATUS`) VALUES (:PROFILEID,:PHONETYPE,:PHONE,:OPS_USERID,:TIME,:PHONE_STATUS)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
            $res->bindValue(":PHONE", $phoneNum, PDO::PARAM_STR);
            $res->bindValue(":OPS_USERID", $cid, PDO::PARAM_STR);
            $res->bindValue(":PHONETYPE", $phoneType, PDO::PARAM_STR);
            $res->bindValue(":PHONE_STATUS", $phoneStatus, PDO::PARAM_STR);
            $res->bindValue(":TIME", (new DateTime)->format('Y-m-j H:i:s'), PDO::PARAM_STR);
            $res->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }


    public function getProfilesUnVerifiedCount($profileid,$startDate)
    {
        try
        {

            $sql  = "SELECT COUNT(*) AS COUNT FROM  jsadmin.OPS_PHONE_VERIFIED_LOG WHERE ENTRY_DT >=  :DATE_START AND PHONE_STATUS='N' and PROFILEID =:PROFILEID ";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":DATE_START", $startDate, PDO::PARAM_STR);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                return $result['COUNT'];
            }

        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getOPSUserProcessedCount($startDate,$endDate)
    {
        try
        {

            $sql  = "SELECT COUNT(DISTINCT OPS_USERID,PROFILEID,DATE(ENTRY_DT),PHONE_TYPE) AS CNT,OPS_USERID,DATE(ENTRY_DT) as DT,PHONE_STATUS FROM  jsadmin.OPS_PHONE_VERIFIED_LOG WHERE DATE(ENTRY_DT) BETWEEN  :DATE_START AND :DATE_END AND (PHONE_STATUS='N' || PHONE_STATUS='B') AND PHONE_TYPE != 'A' GROUP BY OPS_USERID,DATE(ENTRY_DT),PHONE_STATUS";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":DATE_START", $startDate, PDO::PARAM_STR);
            $prep->bindValue(":DATE_END", $endDate, PDO::PARAM_STR);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
                $return[]=$result;
            return $return;
            

        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }


}

