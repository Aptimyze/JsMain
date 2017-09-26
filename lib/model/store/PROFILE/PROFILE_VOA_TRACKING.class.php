<?php
class PROFILE_VOA_TRACKING extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

  
        public function insert($profileid)
        {
                if (!$profileid)
                        throw new jsException("", "Profile id not passed");
                try {
	              	$sql ="INSERT IGNORE INTO PROFILE.VOA_TRACKING (`PROFILEID` ,`CHANGE_DATE`) VALUES (:PROFILEID , :DATE)";
                    $res = $this->db->prepare($sql);
                    $res->bindValue(":PROFILEID",$profileid, PDO::PARAM_STR);
                    $res->bindValue(":DATE",date("Y-m-d H:i:s"), PDO::PARAM_STR);
                    $res->execute();
                    return true;
                } catch (PDOException $e) {
                       
                }
        }
        public function getData($profileid){
                $sql ="SELECT CHANGE_DATE FROM PROFILE.VOA_TRACKING WHERE PROFILEID = :PROFILEID";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID",$profileid, PDO::PARAM_STR);
                $res->execute();
                $rowSelectDetail = $res->fetch(PDO::FETCH_ASSOC);
                return $rowSelectDetail;
        }

}
?>
