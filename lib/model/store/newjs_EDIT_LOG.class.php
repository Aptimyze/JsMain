<?php
class NEWJS_EDIT_LOG extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function getDetails($pid,$fields="")
        {
			try 
			{
				if($pid)
				{ 
                                        if($fields == ""){
                                                $fields = "GENDER,RELIGION,CASTE,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COMPANY_NAME,EDU_LEVEL,INCOME,AGE,COUNTRY_RES,CITY_RES,COUNTRY_BIRTH,CITY_BIRTH,PHONE_RES,PHONE_MOB,ALT_MOBILE,EMAIL,HANDICAPPED,FAMILY_BACK,MOTHER_OCC,PROFILE_HANDLER_NAME,CONTACT,PARENTS_CONTACT,FAMILY_INCOME,IPADD";
                                        }
					$sql="SELECT $fields, CONVERT_TZ(MOD_DT,'SYSTEM','right/Asia/Calcutta') as MOD_DT FROM newjs.EDIT_LOG WHERE PROFILEID = :PROFILEID ORDER BY MOD_DT DESC ";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res[]= $result;
					}
					return $res;
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}


		public function isNewEntry($pid)
        {
			try 
			{
				if($pid)
				{ 
					$sql="SELECT count(*) as cnt from newjs.EDIT_LOG where PROFILEID=:PROFILEID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
					$res=$prep->fetch(PDO::FETCH_ASSOC);
					if($res["cnt"])
					{
						return $res["cnt"];
					}
					else
						return 0;
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}

		public function insertEditDetails($params)
        {
			try 
			{
				if($params && is_array($params))
				{ 
					foreach ($params as $key => $value) {
						$insertFields = $insertFields.$key.",";
						$insertValues = $insertValues.":".$key.",";
					}
					$insertFields = substr($insertFields, 0,-1);
					$insertValues = substr($insertValues, 0,-1);
					$sql="INSERT INTO newjs.EDIT_LOG (".$insertFields.") VALUES (".$insertValues.")";
					$prep=$this->db->prepare($sql);
					foreach ($params as $key => $value) {
						$prep->bindValue(":".$key,$value);
					}
					$prep->execute();
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
