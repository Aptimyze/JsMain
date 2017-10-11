<?php

class MOBILE_API_UPGRADE_APP_NOTIFICATION extends TABLE{
    public function __construct($dbName="") {
        parent::__construct($dbName);
    }
    
    public function insert($androidUpdateVersion, $androidMaxVersion){
        if($androidUpdateVersion && $androidMaxVersion){
            try{
                $entryDt = date("Y:m:d H:i:s");
                $sql = "INSERT INTO MOBILE_API.UPGRADE_APP_NOTIFICATION (ANDROID_UPDATE_VERSION, CURRENT_ANDROID_MAX_VERSION, ENTRY_DT) VALUES(:ANDROID_UPDATE_VERSION, :CURRENT_ANDROID_MAX_VERSION, :ENTRY_DT)";
                $res = $this->db->prepare($sql);
                $res->bindValue(":ANDROID_UPDATE_VERSION",$androidUpdateVersion,PDO::PARAM_INT);
                $res->bindValue(":CURRENT_ANDROID_MAX_VERSION",$androidMaxVersion,PDO::PARAM_INT);
                $res->bindValue(":ENTRY_DT",$entryDt,PDO::PARAM_STR);
                $res->execute();
            } catch (PDOException $ex) {
                throw new jsException($ex);
            }
        }
    }
    
    public function getLastInsertedData(){
        try{
            $sql = "SELECT * from MOBILE_API.UPGRADE_APP_NOTIFICATION ORDER BY ENTRY_DT DESC LIMIT 1";
            $res = $this->db->prepare($sql);
            $res->execute();
            if($row = $res->fetch(PDO::FETCH_ASSOC)){
                $result["ANDROID_UPDATE_VERSION"] = $row["ANDROID_UPDATE_VERSION"];
                $result["CURRENT_ANDROID_MAX_VERSION"] = $row["CURRENT_ANDROID_MAX_VERSION"];
                return $result;
            }
        } catch (PDOException $ex) {
            throw new jsException($ex);
        }
    }
}
?>