<?php
class NEWJS_SWAP_JPROFILE extends TABLE
{
        public function __construct($dbname="")
        {
		parent::__construct($dbname);
        }

        public function insert($profileid)
        {
                try
                {
			$sql="insert ignore into newjs.SWAP_JPROFILE (PROFILEID) values(:profileid)";
                        $res=$this->db->prepare($sql);
			$res->bindValue(":profileid", $profileid, PDO::PARAM_INT);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
	public function dontUpdateTrigger()
	{
                try
                {
			$sql="SET @DONT_UPDATE_TRIGGER=1";
			$res=$this->db->prepare($sql);
			$res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }

	}

                //Three function for innodb transactions
        public function startTransaction()
        {
                $this->db->beginTransaction();
        }
        public function commitTransaction()
        {
                $this->db->commit();
        }

        public function rollbackTransaction()
        {
                $this->db->rollback();
        }
        //Three function for innodb transactions

}
?>
