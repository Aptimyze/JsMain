<?php
class newjs_OLDEMAIL extends TABLE
{
    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }

    public function duplicateOldEmail($email)
    {
        try {
            // $sql = "SELECT PROFILEID FROM newjs.OLDEMAIL WHERE OLD_EMAIL = :EMAIL";
            // $prep = $this->db->prepare($sql);
            // $prep->bindValue(":EMAIL", $email, PDO::PARAM_STR);
            // $prep->execute();
            // $result = ($prep->fetch(PDO::FETCH_NUM) > 0) ? 1 : 0;
            $result = 0;
            return $result;
        } catch (PDOException $e) {

            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function update($profileid, $email)
    {
        try {
            $sql  = "INSERT IGNORE INTO newjs.OLDEMAIL VALUES(:PROFILEID,:EMAIL)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":EMAIL", $email, PDO::PARAM_STR);
            $prep->execute();
        } catch (PDOException $e) {

            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
    public function getEmailList($profiles)
    {
        try {
            foreach ($profiles as $k => $p) {
                if ($str != '') {
                    $str .= ",";
                }

                $str .= ":PROFILEID" . $k;
            }
            $sql  = "SELECT DISTINCT(OLD_EMAIL) EMAIL FROM newjs.OLDEMAIL WHERE PROFILEID IN (" . $str . ")";
            $prep = $this->db->prepare($sql);
            foreach ($profiles as $k => $p) {
                $prep->bindValue(":PROFILEID" . $k, $p, PDO::PARAM_STR);
            }

            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $email = $result['EMAIL'];
                if ($email) {
                    $dataArr[] = $email;
                }

            }
            return $dataArr;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    public function getEmailProfiles($emailArr)
    {
        try {
            foreach ($emailArr as $k => $p) {
                if ($str != '') {
                    $str .= ",";
                }

                $str .= ":OLD_EMAIL" . $k;
            }
            $sql  = "SELECT DISTINCT(PROFILEID) FROM newjs.OLDEMAIL WHERE OLD_EMAIL IN (" . $str . ")";
            $prep = $this->db->prepare($sql);
            foreach ($emailArr as $k => $p) {
                $prep->bindValue(":OLD_EMAIL" . $k, $p, PDO::PARAM_STR);
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
    public function getEmailProfilesAndEmail($emailArr)
    {
        try {
            foreach ($emailArr as $k => $p) {
                if ($str != '') {
                    $str .= ",";
                }

                $str .= ":OLD_EMAIL" . $k;
            }
            $sql  = "SELECT PROFILEID, OLD_EMAIL FROM newjs.OLDEMAIL WHERE OLD_EMAIL IN (" . $str . ")";
            $prep = $this->db->prepare($sql);
            foreach ($emailArr as $k => $p) {
                $prep->bindValue(":OLD_EMAIL" . $k, $p, PDO::PARAM_STR);
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
