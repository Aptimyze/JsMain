<?php
//This class is used to execute queries on newjs.STATE_NEW table

class newjs_STATE_NEW extends TABLE
{
	public function __construct($dbname = "") 
	{
   parent::__construct($dbname);
  }

 public function getStatesIndia()
 {
  try
  {
   $sql = "SELECT SQL_CACHE ID,VALUE,LABEL,SORTBY FROM newjs.STATE_NEW";
   $res = $this->db->prepare($sql);
   $res->execute();
   while($row = $res->fetch(PDO::FETCH_ASSOC))
   {
    $output[] = $row;
  }
}
catch(PDOException $e)
{
 throw new jsException($e);
}
return $output;
}
}