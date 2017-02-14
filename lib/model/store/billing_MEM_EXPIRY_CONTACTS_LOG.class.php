<?php
class billing_MEM_EXPIRY_CONTACTS_LOG extends TABLE{
       
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
    public function add($profileid)
    {
        try
        {
                $sql="INSERT INTO billing.MEM_EXPIRY_CONTACTS_LOG(`PROFILEID`,`ENTRY_DT`) VALUES(:PROFILEID,now())";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $prep->execute();
        }
        catch(Exception $e)
        {
                throw new jsException($e);
        }
    }
    public function getProfileList($entryDt)
    {
        try
        {
                $sql="SELECT PROFILEID FROM billing.MEM_EXPIRY_CONTACTS_LOG WHERE ENTRY_DT>=:ENTRY_DT";
                $prep = $this->db->prepare($sql);
                $prep->bindValue("ENTRY_DT",$entryDt,PDO::PARAM_STR);
                $prep->execute();
                while($res = $prep->fetch(PDO::FETCH_ASSOC))
			$data[] =$res['PROFILEID'];
                return $data;
        }
        catch(Exception $e)
        {
                throw new jsException($e);
        }
    }

}
?>
