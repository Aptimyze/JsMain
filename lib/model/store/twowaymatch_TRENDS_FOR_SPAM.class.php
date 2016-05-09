<?php
class TWOWAYMATCH_TRENDS_FOR_SPAM extends TABLE{
       

        

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function getSpamTrends($RECEIVERID)
        {
			try 
			{
				if($RECEIVERID)
				{
					$sql="SELECT W_CASTE,CASTE_VALUE_PERCENTILE,W_MTONGUE,MTONGUE_VALUE_PERCENTILE,W_AGE,AGE_BUCKET,W_HEIGHT,HEIGHT_VALUE_PERCENTILE,W_EDUCATION,EDUCATION_VALUE_PERCENTILE,W_OCCUPATION,OCCUPATION_VALUE_PERCENTILE,W_CITY,CITY_VALUE_PERCENTILE,W_NRI,NRI_N_P,NRI_M_P,W_MSTATUS,MSTATUS_M_P,MSTATUS_N_P,W_MANGLIK,MANGLIK_M_P,MANGLIK_N_P,MANGLIK_A_P,W_INCOME,INCOME_VALUE_PERCENTILE,I_VAL FROM twowaymatch.TRENDS_FOR_SPAM  WHERE PROFILEID=:RECEIVERID";
					
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":RECEIVERID", $RECEIVERID, PDO::PARAM_INT);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$result[]=$row;
					}
					return $result;
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		
}
?>
