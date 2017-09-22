<?php
class MIS_LOGIN_TRACKING_OLDPROFILES extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

  
        public function insert($profileid,$date)
        {
                if (!$profileid)
                        throw new jsException("", "Profile id not passed");
                try {
	              	$sql ="REPLACE INTO MIS.LOGIN_TRACKING_OLDPROFILES (`PROFILEID` ,`LOGIN_DATE`) VALUES (:PROFILEID , :DATE)";
                    $res = $this->db->prepare($sql);
                    $res->bindValue(":PROFILEID",$profileid, PDO::PARAM_STR);
                    $res->bindValue(":DATE",$date, PDO::PARAM_STR);
                    $res->execute();
                    return true;
                } catch (PDOException $e) {
                       
                }
        }
        public function getData($profileid){
                $sql ="SELECT LOGIN_DATE FROM MIS.LOGIN_TRACKING_OLDPROFILES WHERE PROFILEID = :PROFILEID";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID",$profileid, PDO::PARAM_STR);
                $res->execute();
                $rowSelectDetail = $res->fetch(PDO::FETCH_ASSOC);
                return $rowSelectDetail;
        }

}
?>
