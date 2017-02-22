<?php
class MOBILE_API_NOTIFICATION_MESSAGE_LOG extends TABLE
{
    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
        parent::__construct($dbname);
        $this->NOTIFICATION_KEY_BIND_TYPE = "STR";
        $this->MESSAGE_BIND_TYPE          = "STR";
        $this->TITLE_BIND_TYPE            = "STR";
        $this->MESSAGE_ID_BIND_TYPE       = "INT";
    }

    public function insert($key, $msgId, $message, $title)
    {
        $sqlInsert = "INSERT IGNORE INTO  MOBILE_API.NOTIFICATION_MESSAGE_LOG (`NOTIFICATION_KEY` ,`MESSAGE_ID`,`MESSAGE`,`TITLE`,`ENTRY_DT`) VALUES (:NOTIFICATION_KEY,:MESSAGE_ID,:MESSAGE,:TITLE,now())";
        $resInsert = $this->db->prepare($sqlInsert);
        $resInsert->bindValue(":NOTIFICATION_KEY", $key, constant('PDO::PARAM_' . $this->{'NOTIFICATION_KEY_BIND_TYPE'}));
        $resInsert->bindValue(":MESSAGE_ID", $msgId, constant('PDO::PARAM_' . $this->{'MESSAGE_ID_BIND_TYPE'}));
        $resInsert->bindValue(":MESSAGE", $message, constant('PDO::PARAM_' . $this->{'MESSAGE_BIND_TYPE'}));
        $resInsert->bindValue(":TITLE", $title, constant('PDO::PARAM_' . $this->{'TITLE_BIND_TYPE'}));
        $resInsert->execute();
    }

    public function fetchNotificationKeyLatestEntry($key)
    {
        $sql = "SELECT * FROM MOBILE_API.NOTIFICATION_MESSAGE_LOG WHERE NOTIFICATION_KEY=:NOTIFICATION_KEY ORDER BY ENTRY_DT DESC LIMIT 1";
        $res = $this->db->prepare($sql);
        $res->bindValue(":NOTIFICATION_KEY", $key, constant('PDO::PARAM_' . $this->{'NOTIFICATION_KEY_BIND_TYPE'}));
        $res->execute();
        if ($row = $res->fetch(PDO::FETCH_ASSOC)) {
			return $row;
        } else {
			return NULL;
		}
    }

    public function deleteRecordDateWise($sdate,$edate)
        {
                try{
                        $sql = "delete FROM MOBILE_API.NOTIFICATION_MESSAGE_LOG WHERE ENTRY_DT>:ST_DATE AND ENTRY_DT<:END_DATE";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":ST_DATE",$sdate,PDO::PARAM_STR);
                        $res->bindValue(":END_DATE",$edate,PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
                return NULL;
        }
}
