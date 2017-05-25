<?php

/*This classes is added to handle functions for MATCHALERT_TRACKING.NEW_MATCHES_EMAILS_TRACKING table
 *This has non symfony code in web/newMatches/TrackingFunctions.class.php
 *Created : Jun 19, 2014
 *Author : Reshu Rajput
*/
class MATCHALERT_TRACKING_NEW_MATCHES_EMAILS_TRACKING extends TABLE
{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    /*This function is used to update the values in the table on the basis of date,
     * if no update is done, then it insert the row
     * @param param: array of fields and count to increase
     * @param date : date
    */
    public function updateNewMatchesTracking($params, $date = '') {
        if (!is_array($params)) throw new jsException("", "params not send in updateNewMatchesTracking() in MATCHALERT_TRACKING_NEW_MATCHES_EMAILS_TRACKING.class.php");
        try {
            if (!$date) $date = date("Y-m-d");
            $i = 0;
            foreach ($params as $key => $value) {
                $keys[$i] = $key;
                $values[$i] = $value;
                $updateStr[] = "$key=$key+:v$i";
                $insertStr[] = ":v$i";
                $i++;
            }
            $sql = "UPDATE MATCHALERT_TRACKING.NEW_MATCHES_EMAILS_TRACKING SET " . implode(",", $updateStr) . " WHERE DATE=:DATE";
            $res = $this->db->prepare($sql);
            $res->bindValue(":DATE", $date, PDO::PARAM_STR);
            for ($i = 0; $i < sizeof($params); $i++) $res->bindValue(":v$i", $values[$i], PDO::PARAM_INT);
            $res->execute();
            if ($res->rowCount() == 0) {
                $sql1 = "INSERT INTO MATCHALERT_TRACKING.NEW_MATCHES_EMAILS_TRACKING (DATE," . implode(',', $keys) . ") VALUES(:DATE," . implode(",", $insertStr) . ")";
                $res1 = $this->db->prepare($sql1);
                $res1->bindValue(":DATE", $date, PDO::PARAM_STR);
                for ($i = 0; $i < sizeof($params); $i++) $res1->bindValue(":v$i", $values[$i], PDO::PARAM_INT);
                $res1->execute();
            }
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function insertDateAndSub($entryDt) {
        try {
            $sql = "INSERT INTO MIS.NEW_MATCHES_EMAILS_TRACKING (DATE,UNSUBSCRIPTION) VALUES (:ENTRY_DT,1) ON DUPLICATE KEY UPDATE UNSUBSCRIPTION = UNSUBSCRIPTION+1";
            $res = $this->db->prepare($sql);
            $res->bindValue(":ENTRY_DT", $entryDt, PDO::PARAM_STR);
            $res->execute();
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>
