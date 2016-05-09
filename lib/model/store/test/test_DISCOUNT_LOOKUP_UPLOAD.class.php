<?php

class test_DISCOUNT_LOOKUP_UPLOAD extends TABLE{
    
    public function __construct($dbname="")
    {
		parent::__construct($dbname);
    }
    
    public function getRecords($fields = "*")
    {
        try{
            $sql = "SELECT ".$fields." FROM test.DISCOUNT_LOOKUP_UPLOAD";
            $res = $this->db->prepare($sql);
            $res->execute();
            while($result = $res->fetch(PDO::FETCH_ASSOC))
            {
                $data[] = $result;
            }
            return $data;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
}

?>
