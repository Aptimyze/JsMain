<?php
/*
This class is used to send queries to Dummy_DPP_KEYWORDS table in Assisted_Product database
*/
class Dummy_DPP_KEYWORDS extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	This function insert the Dummy_DPP_KEYWORDS
	*/
	public function insert($params)
	{
		try{
			
			foreach($params as $key=>$val)
         ${$key} = $val;
      if(!$PROFILEID)
         jsException::nonCriticalError("PROFILEID IS BLANK IN insert() of Dummy_DPP_KEYWORDS.class.php");
                
			$sql="REPLACE INTO Assisted_Product.Dummy_DPP_KEYWORDS (PROFILEID,KEYWORD_TYPE,KEYWORD) VALUES(:PROFILEID,:KEYWORD_TYPE,:KEYWORD)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
			$prep->bindValue(":KEYWORD_TYPE", $KEYWORD_TYPE, PDO::PARAM_STR);
			$prep->bindValue(":KEYWORD", $KEYWORD, PDO::PARAM_STR);
      $prep->execute();
		}
		 catch(PDOException $e)
	   {
				jsException::nonCriticalError($e);
     }
	}
	/*
	This function select the Dummy_DPP_KEYWORDS
	@param 
	@return an Dummy_DPP_KEYWORDS of profileid 
	*/
	public function select($profileId)
	{
		try{
			if(!$profileId)
         jsException::nonCriticalError("PROFILEID IS BLANK IN select() of Dummy_DPP_KEYWORDS.class.php");
			$sql="SELECT * FROM Assisted_Product.Dummy_DPP_KEYWORDS WHERE PROFILEID = :PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
      $prep->execute();
      $row = $prep->fetch(PDO::FETCH_ASSOC);
      return $row;
		}
		 catch(PDOException $e)
	   {
         jsException::nonCriticalError($e);
     }
	}
}
