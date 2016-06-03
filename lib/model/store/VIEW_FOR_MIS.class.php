<?php
class VIEW_FOR_MIS extends TABLE{
       

        

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function insert($profileid,$gender,$paid,$havephoto,$mtongue,$caste,$date,$frommatchalert,$contact_matchalert,$visitoralert)
        {
		try 
		{
			$sql="insert ignore into MIS.VIEW_FOR_MIS(PROFILEID,GENDER,PAID,PHOTO,MTONGUE,CASTE,DATE,MATCHALERT,CONTACT_MATCHALERT,VISITORALERT) VALUES('$profileid','$gender','$paid','$havephoto','$mtongue','$caste','$date','$frommatchalert','$contact_matchalert','$visitoralert')";
			$prep=$this->db->prepare($sql);
            $prep->execute();
            
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
		
		
}
?>
