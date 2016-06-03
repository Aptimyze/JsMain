<?php
class NEWJS_OBSCENE_WORDS extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
		
		
		public function getObsceneWord()
        {
			try 
			{
					$sql="SELECT SQL_CACHE WORD FROM OBSCENE_WORDS";
					$prep=$this->db->prepare($sql);
					$prep->execute();
					while($res = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$result[]= $res['WORD'];
					}
					
					return $result; 
			}	
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		
		
		
}
?>
