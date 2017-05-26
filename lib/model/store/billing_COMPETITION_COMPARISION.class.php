<?php
class billing_COMPETITION_COMPARISION extends TABLE{
       
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
	public function getComparisionID()
	{
		try
                {
			$sql="SELECT MAX(ID) AS ID,SITE from billing.COMPETITION_COMPARISION GROUP BY SITE";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$row[$result["SITE"]]=$result["ID"];
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $row;
	}
	public function getServiceInfo($id)
	{
		try
                {
                        $sql="SELECT PRICE,DURATION,CONTACTS from billing.COMPETITION_COMPARISION WHERE ID=:ID";
                        $prep = $this->db->prepare($sql);
			$prep->bindValue(":ID", $id, PDO::PARAM_INT);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $row["CONTACTS"]=$result["CONTACTS"];
				$row["PRICE"]=$result["PRICE"];
				$row["DURATION"]=$result["DURATION"];
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $row;
	}	
}
?>
