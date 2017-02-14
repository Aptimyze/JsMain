<?php
class incentive_NEGATIVE_SUBMISSION_LIST extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
        
    public function insert($type,$typeValue,$comments)
    {
        try
        {
            $sql ="INSERT IGNORE INTO incentive.NEGATIVE_SUBMISSION_LIST(`TYPE`,`TYPE_VALUE`,`COMMENTS`,`ENTRY_DT`) VALUES(:TYPE,:TYPE_VALUE,:COMMENTS,now())";
            $res = $this->db->prepare($sql);
	    $res->bindValue(":TYPE", $type, PDO::PARAM_STR);
	    $res->bindValue(":TYPE_VALUE", $typeValue, PDO::PARAM_STR);
	    $res->bindValue(":COMMENTS", $comments, PDO::PARAM_STR); 			
            $res->execute();
            return $this->db->lastInsertId();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
    public function getData($ID)
    {
        try {
            $sql = "SELECT * FROM incentive.NEGATIVE_SUBMISSION_LIST WHERE ID=:ID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":ID", $ID, PDO::PARAM_INT);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                return $result;
            }
            return;
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
    }



}


