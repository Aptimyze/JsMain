<?php
class MOBILE_API_NOTIFICATION_MESSAGE_LOG extends TABLE
{
    public function __construct($dbname = "")
    {
        $dbname ='crm_slave';
        $this->databaseName ='NOTIFICATION_NEW';

        parent::__construct($dbname);
        $this->NOTIFICATION_KEY_BIND_TYPE = "STR";
        $this->MESSAGE_BIND_TYPE          = "STR";
        $this->TITLE_BIND_TYPE            = "STR";
        $this->MESSAGE_ID_BIND_TYPE       = "INT";
        $this->ENTRY_DT_BIND_TYPE         = "STR";
    }

    public function insert($key, $msgId, $message, $title)
    {
        $istTime = date("Y-m-d H:i:s", strtotime('+9 hour 30 minutes'));
        $sqlInsert = "INSERT IGNORE INTO  NOTIFICATION_NEW.NOTIFICATION_MESSAGE_LOG (`NOTIFICATION_KEY` ,`MESSAGE_ID`,`MESSAGE`,`TITLE`,`ENTRY_DT`) VALUES (:NOTIFICATION_KEY,:MESSAGE_ID,:MESSAGE,:TITLE,:ENTRY_DT)";
        $resInsert = $this->db->prepare($sqlInsert);
        $resInsert->bindValue(":NOTIFICATION_KEY", $key, constant('PDO::PARAM_' . $this->{'NOTIFICATION_KEY_BIND_TYPE'}));
        $resInsert->bindValue(":MESSAGE_ID", $msgId, constant('PDO::PARAM_' . $this->{'MESSAGE_ID_BIND_TYPE'}));
        $resInsert->bindValue(":MESSAGE", $message, constant('PDO::PARAM_' . $this->{'MESSAGE_BIND_TYPE'}));
        $resInsert->bindValue(":TITLE", $title, constant('PDO::PARAM_' . $this->{'TITLE_BIND_TYPE'}));
        $resInsert->bindValue(":ENTRY_DT", $istTime, constant('PDO::PARAM_' . $this->{'ENTRY_DT_BIND_TYPE'}));
        $resInsert->execute();
    }

    public function fetchNotificationKeyLatestEntry($key)
    {
        $sql = "SELECT * FROM NOTIFICATION_NEW.NOTIFICATION_MESSAGE_LOG WHERE NOTIFICATION_KEY=:NOTIFICATION_KEY ORDER BY ENTRY_DT DESC LIMIT 1";
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
                        $sql = "delete FROM NOTIFICATION_NEW.NOTIFICATION_MESSAGE_LOG WHERE ENTRY_DT>:ST_DATE AND ENTRY_DT<:END_DATE";
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
