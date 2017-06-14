<?php

/**
 *
 */
class newjs_SMS_DETAIL extends TABLE
{
    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }

    public function getCount($key, $ProfileID)
    {
        try
        {
            $todayDate = date('Y-m-d');
            $sql       = "select count(*) as SmsCount from newjs.SMS_DETAIL WHERE DATE(`ADD_DATE`) = '$todayDate' and SMS_KEY = :key and PROFILEID = :ProfileID";
            $prep      = $this->db->prepare($sql);
            $prep->bindValue(":key", $key, PDO::PARAM_STR);
            $prep->bindValue(":ProfileID", $ProfileID, PDO::PARAM_INT);
            $prep->execute();
            $result = $prep->fetch(PDO::FETCH_ASSOC);
            return $result['SmsCount'];
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function insert($profileid, $smsType, $smsKey, $message, $phoneMob, $date, $sentStatus)
    {
        try
        {
            $todayDate = date('Y-m-d');
            $sql       = "INSERT INTO newjs.SMS_DETAIL(PROFILEID, SMS_TYPE, SMS_KEY, MESSAGE, PHONE_MOB, ADD_DATE, SENT) VALUES (:PROFILEID, :SMS_TYPE, :SMS_KEY, :MESSAGE, :PHONE_MOB, :ADD_DATE, :SENT)";
            $prep      = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":SMS_TYPE", $smsType, PDO::PARAM_STR);
            $prep->bindValue(":SMS_KEY", $smsKey, PDO::PARAM_STR);
            $prep->bindValue(":MESSAGE", $message, PDO::PARAM_STR);
            $prep->bindValue(":PHONE_MOB", $phoneMob, PDO::PARAM_STR);
            $prep->bindValue(":ADD_DATE", $date, PDO::PARAM_STR);
            $prep->bindValue(":SENT", $sentStatus, PDO::PARAM_STR);
            $prep->execute();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }
}
