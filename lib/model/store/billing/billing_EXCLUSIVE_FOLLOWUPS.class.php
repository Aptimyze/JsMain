<?php
class billing_EXCLUSIVE_FOLLOWUPS extends TABLE {

    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    /**
     * Function to get followups' details from billing.EXCLUSIVE_FOLLOWUPS table
     *
     * @param   
     * @return  array of rows
     */ 
	public function getFollowUpEntries($orderBy="")
	{
		try{
		    $sql = "SELECT * FROM billing.EXCLUSIVE_FOLLOWUPS WHERE STATUS NOT IN ('N','Y','F4')";
		    
		    if($orderBy){
		      $sql = $sql." ORDER BY ".$orderBy;
		    }
		    $res = $this->db->prepare($sql);
		    $res->execute();
		    while($result=$res->fetch(PDO::FETCH_ASSOC))
		    {
		        $rows[] = $result;
		    }
		    return $rows;
		}
		catch(Exception $e){
		  throw new jsException($e);
		}
	}
}
?>