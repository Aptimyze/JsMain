<?php

class jeevansathi_mailer_EMAIL_TYPE extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        
	 public function getEMAIL_ID($conditionalArray)
        {
			try 
			{
					$sql="SELECT SQL_CACHE MAIL_ID FROM jeevansathi_mailer.EMAIL_TYPE WHERE MAIL_GROUP=:MAIL_GROUP AND CUSTOM_CRITERIA=:CUSTOM_CRITERIA AND GENDER IN(:GENDER,'') AND PHOTO_PROFILE IN(:PHOTO_PROFILE,'') AND
					(FTO_FLAG LIKE :FTO_FLAG OR FTO_FLAG='')";
					
					$prep=$this->db->prepare($sql);

					$prep->bindValue(":MAIL_GROUP",$conditionalArray['MAIL_GROUP'],PDO::PARAM_INT);
					$prep->bindValue(":CUSTOM_CRITERIA",$conditionalArray['CUSTOM_CRITERIA'],PDO::PARAM_INT);
					$prep->bindValue(":GENDER",$conditionalArray['GENDER'],PDO::PARAM_STR);
					$prep->bindValue(":PHOTO_PROFILE",$conditionalArray['PHOTO_PROFILE'],PDO::PARAM_STR);
					$prep->bindValue(":FTO_FLAG","%".$conditionalArray['FTO_FLAG']."%",PDO::PARAM_STR);
					
					$prep->execute();

					if($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						return $result['MAIL_ID'];
					}
						
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
	public function getEmailType($mail_id){
		try 
			{
				if($mail_id)
				{ 
					$sql="SELECT * FROM jeevansathi_mailer.EMAIL_TYPE WHERE MAIL_ID =:mail_id";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":mail_id",$mail_id,PDO::PARAM_INT);
					$prep->execute();
					if($result = $prep->fetch(PDO::FETCH_ASSOC)){
						$records[] = $result;
					}

						return $records[0];
					
					//return array();
				}	
			}
			catch(PDOException $e)
			{
				echo "error message";
				throw new jsException($e);
			}
	}
}
