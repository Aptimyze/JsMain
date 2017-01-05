<?php
class billing_DROPOFF_SOURCE_TRACKING extends TABLE
{

    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }

    public function addSourceTracking($profileid, $pgNo, $fromSource, $country = 'IN')
    {
        if (empty($profileid)) {
            $profileid = 0;
        }
        if (empty($country)) {
            $country = 'IN';
        }
        try
        {
            $dt       = date("Y-m-d H:i:s");
            $dt_5mins = date("Y-m-d H:i:s", time() - 300);
            $count    = $this->getPast5MinTracking($profileid, $fromSource);
            if ($count > 0) {
                if ($profileid != 0) {
                    $sqlUpdate = "UPDATE billing.DROPOFF_SOURCE_TRACKING SET PAGE=:PAGE WHERE PROFILEID=:PROFILEID AND SOURCE=:SOURCE AND ENTRY_DT>=:FIVE_MINS_DT";
                } else {
                    $sqlUpdate = "UPDATE billing.DROPOFF_SOURCE_TRACKING SET PAGE=:PAGE WHERE PROFILEID=0 AND SOURCE=:SOURCE AND ENTRY_DT>=:FIVE_MINS_DT";
                }
                $prepUpdate = $this->db->prepare($sqlUpdate);
                if ($profileid) {
                    $prepUpdate->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                }
                $prepUpdate->bindValue(":PAGE", $pgNo, PDO::PARAM_INT);
                $prepUpdate->bindValue(":SOURCE", $fromSource, PDO::PARAM_STR);
                $prepUpdate->bindValue(":FIVE_MINS_DT", $dt_5mins, PDO::PARAM_STR);
                $prepUpdate->execute();
            } else {
                if ($profileid != 0) {
                    $sqlInsert = "INSERT INTO billing.DROPOFF_SOURCE_TRACKING(PROFILEID,PAGE,ENTRY_DT,SOURCE,COUNTRY) VALUES(:PROFILEID,:PAGE,:ENTRY_DT,:SOURCE,:COUNTRY)";
                } else {
                    $sqlInsert = "INSERT INTO billing.DROPOFF_SOURCE_TRACKING(PAGE,ENTRY_DT,SOURCE,COUNTRY) VALUES(:PAGE,:ENTRY_DT,:SOURCE,:COUNTRY)";
                }
                $prepInsert = $this->db->prepare($sqlInsert);
                if ($profileid != 0) {
                    $prepInsert->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                }
                $prepInsert->bindValue(":PAGE", $pgNo, PDO::PARAM_INT);
                $prepInsert->bindValue(":SOURCE", $fromSource, PDO::PARAM_STR);
                $prepInsert->bindValue(":COUNTRY", $country, PDO::PARAM_STR);
                $prepInsert->bindValue(":ENTRY_DT", $dt, PDO::PARAM_STR);
                $prepInsert->execute();
            }
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getPast5MinTracking($profileid, $fromSource)
    {
        try {
            $dt       = date("Y-m-d H:i:s");
            $dt_5mins = date("Y-m-d H:i:s", time() - 300);
            if ($profileid != 0) {
                $sql = "SELECT COUNT(*) AS CNT FROM billing.DROPOFF_SOURCE_TRACKING WHERE PROFILEID=:PROFILEID AND ENTRY_DT>=:FIVE_MINS_DT AND SOURCE=:SOURCE";
            } else {
                $sql = "SELECT COUNT(*) AS CNT FROM billing.DROPOFF_SOURCE_TRACKING WHERE PROFILEID=0 AND ENTRY_DT>=:FIVE_MINS_DT AND SOURCE=:SOURCE";
            }
            $prep = $this->db->prepare($sql);
            if ($profileid != 0) {
                $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            }
            $prep->bindValue(":FIVE_MINS_DT", $dt_5mins, PDO::PARAM_STR);
            $prep->bindValue(":SOURCE", $fromSource, PDO::PARAM_STR);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                return $result['CNT'];
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function deleteSourceTracking($profileid, $fromSource)
    {
        try
        {
            $dt       = date("Y-m-d H:i:s");
            $dt_5mins = date("Y-m-d H:i:s", time() - 300);
            $count    = $this->getPast5MinTracking($profileid, $fromSource);
            if ($count > 0) {
                if ($profileid != 0) {
                    $sqlUpdate = "DELETE FROM billing.DROPOFF_SOURCE_TRACKING WHERE PROFILEID=:PROFILEID AND SOURCE=:SOURCE AND ENTRY_DT>=:FIVE_MINS_DT";
                } else {
                    $sqlUpdate = "DELETE FROM billing.DROPOFF_SOURCE_TRACKING WHERE PROFILEID=0 AND SOURCE=:SOURCE AND ENTRY_DT>=:FIVE_MINS_DT";
                }
                $prepUpdate = $this->db->prepare($sqlUpdate);
                if ($profileid) {
                    $prepUpdate->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                }
                $prepUpdate->bindValue(":SOURCE", $fromSource, PDO::PARAM_STR);
                $prepUpdate->bindValue(":FIVE_MINS_DT", $dt_5mins, PDO::PARAM_STR);
                $prepUpdate->execute();
            }
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }
}
