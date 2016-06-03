<?php
class incentive_INVOICE_TRACK extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

        public function addRecord($idArr,$entryBy)
        {
                try
                {
			$idStr =implode(",",$idArr);
			$sql ="INSERT into incentive.INVOICE_TRACK(SENT_TO,TIME,SENT_BY) VALUES (:SENT_TO,now(),:SENT_BY)";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":SENT_TO", $idStr, PDO::PARAM_STR);
                        $res->bindValue(":SENT_BY", $entryBy, PDO::PARAM_STR);
			$res->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
