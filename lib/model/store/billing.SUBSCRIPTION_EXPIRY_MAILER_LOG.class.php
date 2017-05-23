<?php

class SUBSCRIPTION_EXPIRY_MAILER_LOG extends TABLE {
  
  	public function __construct($dbname = "") {
    		parent::__construct($dbname);
  	}

	public function getMailCountForRange($entryDate)
        {
                try{
                        $sql = "SELECT count(1) as cnt,SENT FROM billing.SUBSCRIPTION_EXPIRY_MAILER_LOG WHERE ENTRY_DT=:ENTRY_DT group by SENT";
                        $res=$this->db->prepare($sql);
                        $res->bindValue("ENTRY_DT",$entryDate,PDO::PARAM_STR);
                        $res->execute();
                        $total = 0;
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                if($row['SENT']=='Y')
                                        $output['SENT'] = $row['cnt'];
                                if($row['SENT']=='B')
                                        $output['BOUNCED'] = $row['cnt'];
                                if($row['SENT']=='I')
                                        $output['INCOMPLETE'] = $row['cnt'];
                                if($row['SENT']=='U')
                                        $output['UNSUBSCRIBE'] = $row['cnt'];
                                $total = $total+$row['cnt'];
                        }
                        $output['TOTAL'] = $total;
                }
                catch(PDOException $e)
                {
                   throw new jsException($e);
                }
                return $output;

        }
}
