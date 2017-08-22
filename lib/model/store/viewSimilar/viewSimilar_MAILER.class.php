<?php

class viewSimilar_MAILER extends TABLE {

    public function __construct($dbname = "newjs_master") {
        parent::__construct($dbname);
    }

    /** This store function is to insert profiles into MAILER table
     */
    public function insertProfiles($sender, $profileArr, $noOfEois, $typeOfEois) {
        try {
            $i = 1;
            $userStr = "";
            foreach ($profileArr as $kry => $val) {
                $userStr1 .= "USER" . $i . ",";
                $userStr2 .= ":USER" . $i . ",";
                $i++;
            }
            $sql = "INSERT INTO viewSimilar.MAILER(PROFILEID," . $userStr1 . "SENT,INTERESTS_SENT,TYPE) VALUES(:SENDER," . $userStr2 . ":SENT,:INTERESTS,:TYPE)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":SENDER", $sender, PDO::PARAM_INT);
            $prep->bindValue(":SENT", 'N', PDO::PARAM_STR);
            $prep->bindValue(":INTERESTS", $noOfEois, PDO::PARAM_INT);
            $prep->bindValue(":TYPE", $typeOfEois, PDO::PARAM_STR);
            $i = 1;
            foreach ($profileArr as $key => $val) {
                $prep->bindValue(":USER" . $i++, $val, PDO::PARAM_INT);
            }
            $prep->execute();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    /* This function is used to get all the profile which need to recieve vsp mailer ie having SENT<>Y  and atleat one profile in user.
     * @param fields : fields to get if different from default
     * @param totalScript : number of script which can be executed
     * @param script : current script number
     * @param limit : limit if required
     * @return result : details of mailer to be sent 
     */

    public function getMailerProfiles($totalScript = "1", $script = "0", $limit = "") {
        try {
            $defaultFields = "SNO,PROFILEID,USER1,USER2,USER3,USER4,USER5,USER6,USER7,USER8,USER9,USER10,USER11,USER12,USER13,USER14,USER15,USER16,USER17,USER18,USER19,USER20,INTERESTS_SENT,TYPE";

            $sql = "SELECT $defaultFields FROM viewSimilar.MAILER where SENT = 'N' AND  MOD(SNO,:TOTAL_SCRIPT)=:SCRIPT";
            if ($limit)
                $sql.= " limit 0,:LIMIT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":TOTAL_SCRIPT", $totalScript, PDO::PARAM_INT);
            $prep->bindValue(":SCRIPT", $script, PDO::PARAM_INT);
            if ($limit)
                $prep->bindValue(":LIMIT", $limit, PDO::PARAM_INT);
            $prep->execute();

            while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }
            return $result;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    /* This funxtion is used update the sent flag(Y for sent and F for fail) for each mail receiver
     * @param sno : serial number of mail
     * @param flag : sent status of the mail
     */

    public function updateSentForUsers($sno, $flag) {
        try {
            if (!$sno || !$flag)
                throw new jsException("no sno /flag passed in updateSentForUsers function in viewSimilar_MAILER.class.php");

            $sql = "UPDATE viewSimilar.MAILER SET SENT=:FLAG WHERE SNO=:SNO";
            $res = $this->db->prepare($sql);
            $res->bindValue(":SNO", $sno, PDO::PARAM_INT);
            $res->bindValue(":FLAG", $flag, PDO::PARAM_STR);
            $res->execute();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    /**
     * Empty The table
     */
    public function truncateTable() {
        try {
            $sql = "TRUNCATE TABLE viewSimilar.MAILER";
            $res = $this->db->prepare($sql);
            $res->execute();
        } catch (PDOException $e) {
            //add mail/sms
            throw new jsException($e);
        }
    }

}
