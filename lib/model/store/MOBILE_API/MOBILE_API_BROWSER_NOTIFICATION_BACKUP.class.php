<?php
class MOBILE_API_BROWSER_NOTIFICATION_BACKUP extends TABLE 
{ 
	public function __construct($dbName = "") 
	{
        parent::__construct($dbName);
    }

    /*function to get insert row in table
    * @params :$paramsArr
    * @return : none
    */
    public function insertBackupNotification($paramsArr){
        try{
        	$insertValues="";
            if(is_array($paramsArr))
            {
                $sql = "INSERT IGNORE INTO MOBILE_API.BROWSER_NOTIFICATION_BACKUP(ID,REG_ID, NOTIFICATION_KEY, NOTIFICATION_TYPE, MESSAGE, TITLE, ICON, TAG, LANDING_ID, ENTRY_DT,RECEIVED_DATE,PRIORITY,MSG_ID,SENT_TO_QUEUE,SENT_TO_FCM,SENT_TO_CHANNEL,TTL,RESPONSE,STATUS) VALUES ";
                foreach ($paramsArr as $key => $val) 
                {
                    $insertValues = $insertValues."(:ID$key,:REG_ID$key, :NOTIFICATION_KEY$key, :NOTIFICATION_TYPE$key, :MESSAGE$key, :TITLE$key, :ICON$key, :TAG$key, :LANDING_ID$key, :ENTRY_DT$key, :RECEIVED_DATE$key,:PRIORITY$key,:MSG_ID$key, :SENT_TO_QUEUE$key,:SENT_TO_FCM$key,:SENT_TO_CHANNEL$key,:TTL$key,:RESPONSE$key,:STATUS$key),";
                }
                $insertValues = $insertValues.substr($insertValues, 0,-1);
                $sql = $sql.$insertValues;
                $prep = $this->db->prepare($sql);
                
                foreach ($paramsArr as $key => $value) 
                {
                    
                  $prep->bindValue(":ID$key",$value["ID"],PDO::PARAM_INT);
                    $prep->bindValue(":REG_ID$key",$value["REG_ID"],PDO::PARAM_STR);
                    $prep->bindValue(":NOTIFICATION_KEY$key",$value["NOTIFICATION_KEY"],PDO::PARAM_STR);
                    $prep->bindValue(":NOTIFICATION_TYPE$key",$value["NOTIFICATION_TYPE"],PDO::PARAM_STR);
                    $prep->bindValue(":MESSAGE$key",$value["MESSAGE"],PDO::PARAM_STR);
                    $prep->bindValue(":SENT_TO_QUEUE$key",$value["SENT_TO_QUEUE"],PDO::PARAM_STR);
                    $prep->bindValue(":SENT_TO_FCM$key",$value["SENT_TO_FCM"],PDO::PARAM_STR);
                    $prep->bindValue(":SENT_TO_CHANNEL$key",$value["SENT_TO_CHANNEL"],PDO::PARAM_STR);
                    $prep->bindValue(":RESPONSE$key",$value["RESPONSE"],PDO::PARAM_STR);
                    $prep->bindValue(":PRIORITY$key",$value["PRIORITY"],PDO::PARAM_INT);
                    $prep->bindValue(":RECEIVED_DATE$key",$value["RECEIVED_DATE"],PDO::PARAM_STR);
                    $prep->bindValue(":MSG_ID$key",$value["MSG_ID"],PDO::PARAM_INT);
                    $prep->bindValue(":TITLE$key",$value["TITLE"],PDO::PARAM_STR);
                    $prep->bindValue(":ICON$key",$value["ICON"],PDO::PARAM_STR);
                    $prep->bindValue(":TAG$key",$value["TAG"],PDO::PARAM_STR);
                    $prep->bindValue(":LANDING_ID$key",$value["LANDING_ID"],PDO::PARAM_STR);
                    $prep->bindValue(":ENTRY_DT$key",$value["ENTRY_DT"],PDO::PARAM_STR);
                    $prep->bindValue(":TTL$key",$value["TTL"],PDO::PARAM_INT);
                    $prep->bindValue(":STATUS$key",$value["STATUS"],PDO::PARAM_STR);
                 
                }
                $prep->execute();
            }
        } catch (Exception $ex) {
            throw new jsException($ex);
		}
	}

        public function getDataCountForRange($startDate, $endDate)
        {
                try{
                        $sql ="SELECT count(b.REG_ID) count, b.NOTIFICATION_KEY, b.SENT_TO_QUEUE, b.SENT_TO_CHANNEL,b_reg.CHANNEL FROM MOBILE_API.`BROWSER_NOTIFICATION_BACKUP` b, MOBILE_API.BROWSER_NOTIFICATION_REGISTRATION b_reg WHERE b.REG_ID=b_reg.REG_ID AND b.ENTRY_DT>=:START_DATE AND b.ENTRY_DT<=:END_DATE GROUP BY b.NOTIFICATION_KEY, b.SENT_TO_QUEUE, b.SENT_TO_CHANNEL,b_reg.CHANNEL";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":START_DATE",$startDate, PDO::PARAM_STR);
                        $res->bindValue(":END_DATE",$endDate, PDO::PARAM_STR);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC)){
                                $rowArr[$row['NOTIFICATION_KEY']][$row['CHANNEL']][$row['SENT_TO_CHANNEL']][$row['SENT_TO_QUEUE']] =$row['count'];
                        }
                        return $rowArr;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }

}
?>
