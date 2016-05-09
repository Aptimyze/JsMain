<?php
/**
* store class related to mailer information lke url,name,subject,stagger....
*/
class mmmjs_MAIL_SENT extends TABLE
{
	public function __construct($dbname="matchalerts_slave_localhost")
        {
                parent::__construct($dbname);
        }

	/**
	* This function will record mailer information .....
	* @param $mail - associative array with key as column name & value as value for the respective column
	*/
        public function insert($mailerId,$cnt)
        {
		try
		{
			$dt = date("Y-m-d");
			$sql="REPLACE INTO mmmjs.MAIL_SENT_NEW(DATE, MAILER_ID, SENT) VALUES(:dt,:mailerId,:sentCnt)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":mailerId", $mailerId, PDO::PARAM_INT);
			$res->bindValue(":sentCnt", $cnt, PDO::PARAM_INT);			
			$res->bindValue(":dt",$dt, PDO::PARAM_STR);
			$res->execute();
		}
		catch(PDOException $e)
          	{	
			throw new jsException($e);
          	}
	}

		public function domainDataInsert($table,$date,$mailerId,$domain,$count)
        {
            $sql = "UPDATE $table SET $domain = $domain + $count WHERE DATE = '".$date."' AND MAILER_ID = $mailerId";
            $res = $this->db->prepare($sql);
            $res->execute();

            $row = $res->rowCount();

            if(empty($row)){
                $sql1 = "INSERT INTO $table (DATE,MAILER_ID,$domain) VALUES('".$date."',$mailerId,$count)";
                $res1 = $this->db->prepare($sql1);
                $res1->execute();
            }

        }
	

}
