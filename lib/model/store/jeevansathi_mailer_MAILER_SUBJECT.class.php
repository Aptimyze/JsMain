<?php 

class jeevansathi_mailer_MAILER_SUBJECT extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        
/**
 * @fn getSubjectCode
 * @brief fetches results from ContactPrivilege
 * @param $mailId EMAIL_ID 
 * @return SubjectArray according to the MailId
 * @exception jsException for blank criteria
 * @exception PDOException for database level error handling
 */  
 public function getSubjectCode($MAIL_ID)
        {
			try 
			{
					$sql="SELECT SQL_CACHE SUBJECT_TYPE , SUBJECT_CODE FROM jeevansathi_mailer.MAILER_SUBJECT	 WHERE MAIL_ID=:MAIL_ID";
					
					$prep=$this->db->prepare($sql);

					$prep->bindValue(":MAIL_ID",$MAIL_ID,PDO::PARAM_INT);
					
					$prep->execute();
					$i=0;
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$subjectCodeArr[$i]['SUBJECT_TYPE']=$result['SUBJECT_TYPE'];
						$subjectCodeArr[$i]['SUBJECT_CODE']=$result['SUBJECT_CODE'];
						$i++;
					}
						return $subjectCodeArr;
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
}
