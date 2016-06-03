<?php
/*
 * This Class provide functions for PROFILE_VERIFICATION.DOCUMENTS_TRACKING table
 * @author lavesh
 * @created March 21, 2014
*/
class PROFILE_VERIFICATION_DOCUMENTS_SCREENING_TRACKING extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

        /** 
        * This function is used to insert multiple values in table.
        * @param arr containing tracking info (document id of screend document,screening user,time of screening) 
        **/
        public function insertDocuments($arr)
        {
                try
                {
			if(is_array($arr))
			{
	                        $sql="INSERT IGNORE INTO PROFILE_VERIFICATION.DOCUMENTS_SCREENING_TRACKING(DOCUMENT_ID,SCREENED_BY,SCREENING_TIME) VALUES";
				foreach($arr as $k=>$v)
					$ins[] = "(:docId$k,:screenedBy$k,:time$k)";
				$sql.= implode(",",$ins);
                        	$res=$this->db->prepare($sql);
				foreach($arr as $k=>$v)
				{
                	        	$res->bindParam(":docId$k",$v["DOCUMENT_ID"], PDO::PARAM_INT);
	                	        $res->bindParam(":screenedBy$k",$v["SCREENED_BY"], PDO::PARAM_STR);
	                        	$res->bindParam(":time$k",$v["SCREENED_TIME"], PDO::PARAM_STR);
				}
        	                $res->execute();
			}
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
}
