<?php
class CONTACT_ARCHIVE_INFO extends TABLE
{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function checkPhone($numberArray = '', $isd = '') {
        try {
            $res = null;
            $str = '';
            if ($isd == '') $isd = "91";
            if ($numberArray) {
                foreach ($numberArray as $k => $num) {
                    if ($k != 0) $str.= ", ";
                    $str.= ":mob" . $k . ", :mob0" . $k . ", :mobIsd" . $k . ", :mobIsdA" . $k . ", :mobIsdB" . $k . ", :mobIsd0" . $k . ", :mobIsdC" . $k;
                }
            }
            
            if ($str) {
                $sql = "SELECT CA.PROFILEID AS PROFILEID,CAI.OLD_VAL AS OLD_VAL FROM newjs.CONTACT_ARCHIVE_INFO CAI LEFT JOIN newjs.CONTACT_ARCHIVE CA ON CAI.CHANGEID=CA.CHANGEID WHERE CAI.OLD_VAL IN (" . $str . ")";
                $prep = $this->db->prepare($sql);
                if ($numberArray) {
                    foreach ($numberArray as $k => $num) {
                        
                        $prep->bindValue(":mob" . $k, $num, PDO::PARAM_STR);
                        $prep->bindValue(":mob0" . $k, '0' . $num, PDO::PARAM_STR);
                        $prep->bindValue(":mobIsd" . $k, $isd . $num, PDO::PARAM_STR);
                        $prep->bindValue(":mobIsdA" . $k, '+' . $isd . $num, PDO::PARAM_STR);
                        $prep->bindValue(":mobIsdB" . $k, $isd . '-' . $num, PDO::PARAM_STR);
                        $prep->bindValue(":mobIsd0" . $k, '0' . $isd . '-' . $num, PDO::PARAM_STR);
                        $prep->bindValue(":mobIsdC" . $k, '0' . $isd . '-0' . $num, PDO::PARAM_STR);
                    }
                }
                $prep->execute();
                $i = 0;
                while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                    $res[$i]['PROFILEID'] = $result['PROFILEID'];
                    $res[$i]['NUMBER'] = $result['OLD_VAL'];
                    $res[$i]['TYPE'] = "ARCHIVED";
                    $i++;
                }
            } 
            else throw new jsException("No phone number as Input paramter");
            
            return $res;
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
    
    /** Function insert added by Nitesh
     This function is used to insert record in the NEWJS_CONTACT_ARCHIVE_INFO table.
     * @param  $cid Int
     * @param  $ip String
     * @param  $email String
     *
     */
    public function insert($cid, $ip, $email, $oldEmail = '') {
        
        if (!$cid) throw new jsException("", "PROFILEID IS BLANK IN insertRecord() OF NEWJS_CONTACT_ARCHIVE.class.php");
        
        try {
            $now = date("Y-m-d G:i:s");
            if(empty($oldEmail)){
            	$sql = "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES(:changeid,:now,:ip,:email)";
            } else {
            	$sql = "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL,OLD_VAL) VALUES(:changeid,:now,:ip,:email,:oldEmail)";
            }
            $res = $this->db->prepare($sql);
            $res->bindValue(":changeid", $cid, PDO::PARAM_INT);
            $res->bindValue(":ip", $ip, PDO::PARAM_INT);
            $res->bindValue(":email", $email, PDO::PARAM_STR);
            $res->bindValue(":now", $now, PDO::PARAM_STR);
            if(!empty($oldEmail)){
            	$res->bindValue(":oldEmail", $oldEmail, PDO::PARAM_STR);
            }
            $res->execute();
            return $this->db->lastInsertId();
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>
