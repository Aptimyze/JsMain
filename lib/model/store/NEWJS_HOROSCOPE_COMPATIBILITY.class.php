<?php
/**
 * Description of NEWJS_HOROSCOPE_COMPATIBILITY
 * Store Class for CRUD Operation on newjs.HOROSCOPE_COMPATIBILITY
 *
 * @author Kunal Verma
 * @created 24th march 2016
 */
/**
 * Class NEWJS_HOROSCOPE_COMPATIBILITY
 */
class NEWJS_HOROSCOPE_COMPATIBILITY extends TABLE
{
    /**
     * NEWJS_HOROSCOPE_COMPATIBILITY constructor.
     * @param string $dbname
     */
    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }

    /**
     * replaceRecord
     * @param $pid
     * @param array $paramArr
     * @return bool
     */
    public function replaceRecord($iProfileID,$iOtherProfileID)
    {
        try {
            $now = date("Y-m-d");
            $sql = "REPLACE INTO newjs.HOROSCOPE_COMPATIBILITY (PROFILEID,PROFILEID_OTHER,DATE) VALUES (:PID,:OPID,:NOW)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PID", $iProfileID);
            $res->bindValue(":OPID", $iOtherProfileID);
            $res->bindValue(":NOW", $now);
            $res->execute();
            return true;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }
}

?>
