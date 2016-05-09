<?php
class userplane_CHAT_REQUEST extends TABLE{
       

        
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }

	public function getChatRequestCount($profileid)
	{
		try{
			if(!$profileid)
				throw new jsException("","PROFILEID IS BLANK IN getChatRequestCount() OF userplane_CHAT_REQUEST.class.php");
			$sql = "(SELECT count(DISTINCT(SENDER)) COUNT FROM userplane.CHAT_REQUESTS WHERE RECEIVER=:PROFILEID) UNION (SELECT count(DISTINCT(RECEIVER)) COUNT FROM userplane.CHAT_REQUESTS WHERE SENDER=:PROFILEID)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$chatCount = $chatCount + $row["COUNT"];
			}
			
		}
		catch(PDOException $e)
        {
           throw new jsException($e);
        }
		return $chatCount; 
	}
		
		
}
?>
