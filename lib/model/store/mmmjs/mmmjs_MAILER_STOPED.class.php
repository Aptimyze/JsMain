<?php
/**
* store class for 
*/
class mmmjs_MAILER_STOPED extends TABLE
{
	public function  __construct($dbname="matchalerts_slave_localhost")
	{
		parent::__construct($dbname);
	}


        /**
        * insert entries in the table
        * @param $mailerid - id to be stoped
        * @throws PDO Exception 
        */
        public function add($mailerid)
        {
                try
                {
			$dt = date("Y-m-d");
                        $sql = "INSERT IGNORE INTO mmmjs.MAILER_STOPED (MAILER_ID,ENTRY_DT) VALUES (:mailerid,:dt)";
                        $res = $this->db->prepare($sql);
			$res->bindValue(":mailerid",$mailerid,PDO::PARAM_INT);
			$res->bindValue(":dt",$dt,PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

	/**
	* Retreive function
	*/
        public function get($whereParamArray,$fields='*')
        {
                $arr = NULL;
                $sql = "SELECT $fields from mmmjs.MAILER_STOPED";

                if($whereParamArray["STATUS"])
                        $sqlWhere[] = " STATUS=:STATUS ";

                if($sqlWhere)
                {
                        $sql.=" WHERE ";
                        $sql.= implode('AND',$sqlWhere);
                }
                $res = $this->db->prepare($sql);

                if($sqlWhere)
                {
                        if($whereParamArray["STATUS"])
                                $res->bindValue(":STATUS",$whereParamArray["STATUS"],PDO::PARAM_STR);
                }
                $res->execute();
                while($row = $res->fetch(PDO::FETCH_ASSOC))
                        $arr[] = $row;
                return $arr;
        }

	public function updateStatus($mailerid)
	{
                if($mailerid)
                {
                        try
                        {
                                $sql = "Update mmmjs.MAILER_STOPED set STATUS='Y' where MAILER_ID IN (:mailerid)";
                                $res = $this->db->prepare($sql);
                                $res->bindValue(":mailerid", $mailerid, PDO::PARAM_STR);
                                $res->execute();
                        }
                        catch(PDOException $e)
                        {
                                throw new jsException($e);
                        }
                }

	}
}
?>
