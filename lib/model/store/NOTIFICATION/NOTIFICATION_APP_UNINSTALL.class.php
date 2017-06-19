<?php

class NOTIFICATION_APP_UNINSTALL extends TABLE {

    public function __construct($dbname = "") {
        parent::__construct($dbname);
        $this->PROFILEID_BIND_TYPE = "INT";
        $this->REGISTRATION_ID_BIND_TYPE = "STR";
        $this->ENTRY_DT_BIND_TYPE = "STR";
        $this->MAILER_SENT_BIND_TYPE = "STR";
    }

    public function getProfileIdsForMailer($orderby = "", $limit = "") {
        try {
            $sql = "SELECT PROFILEID FROM NOTIFICATION.APP_UNINSTALL where MAILER_SENT != 'Y' and ENTRY_DT >= CURDATE() - INTERVAL 1 DAY; ";
            if ($orderby)
                $sql .= " order by $orderby ";
            if ($limit)
                $sql .= " limit $limit ";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row["PROFILEID"];
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    public function updateEntryForSent($profileIdArr) {
        try {
            if (is_array($profileIdArr))
                $str = "(" . implode(",", $profileIdArr) . ")";
            else
                $str = $profileIdArr;
            $sql = "UPDATE NOTIFICATION.APP_UNINSTALL SET  `MAILER_SENT` = 'Y'WHERE PROFILEID";
            if (is_array($profileIdArr))
                $sql = $sql . " IN " . $str;
            else
                $sql = $sql . " = " . $str;
            $prep = $this->db->prepare($sql);
            $prep->execute();
            return true;
        } catch (Exception $ex) {
            throw new jsException($e);

        }
    }

    public function insertUninstalledProfiles($profileid, $oldRegistrationId) {
        try {
            $sql = "INSERT INTO NOTIFICATION.APP_UNINSTALL (PROFILEID,REGISTRATION_ID,ENTRY_DT,MAILER_SENT) VALUES ('$profileid','$oldRegistrationId', now(),'N')";
            $res = $this->db->prepare($sql);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

}

?>
