<?php
/*
 * This Class provide functions for newjs.CRITICAL_INFO_DOC_ASSIGNED table
 * @author Bhavana Kadwal
 * @created June 12, 2016
*/
class CRITICAL_INFO_DOC_ASSIGNED extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

        /** 
        * This function is used to insert multiple values in PROFILE_VERIFICATION_DOCUMENTS table.
        * @param pid  : profileid of user whose documenets need to be screened
        * @param user : screening user
        **/
        public function insertDocuments($pid,$user)
        {
                try
                {
			$time = date("Y-m-d H:i:s");
                        $sql="REPLACE INTO newjs.CRITICAL_INFO_DOC_ASSIGNED(PROFILEID, ALLOTED_TIME, ASSIGNED_TO) VALUES (:PID,:TIME,:USER)";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":PID", $pid, PDO::PARAM_INT);
                        $res->bindValue(":TIME", $time, PDO::PARAM_STR);
                        $res->bindValue(":USER", $user, PDO::PARAM_STR);
                        $res->execute();
                        return $row;

                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }


        /**
        * This function is used to get the profile which have been allotted to a specific screening user and havent been screened yet.
	* @param time : maximum time for which user need to screen else if can be alloted to anyone.
	* @param case  possible values greater/less i.e ALLOTED_TIME is greater/less or less than argument time passed
	* @param user screening user
	* @return array containing alloted alloted user info
        **/
        public function userAllottedProfiles($time,$case,$user='')
        {
                try
                {
                	$sql="SELECT PROFILEID,ALLOTED_TIME,ASSIGNED_TO FROM newjs.CRITICAL_INFO_DOC_ASSIGNED WHERE 1 ";
			if($user)
				$sql.="AND ASSIGNED_TO=:USER ";
			if($case=='greater')
				$sql.="AND ALLOTED_TIME >= :TIME ";
			elseif($case=='less')
				$sql.="AND ALLOTED_TIME < :TIME ";

                	$res=$this->db->prepare($sql);
			if($user)
        	        	$res->bindValue(":USER", $user, PDO::PARAM_STR);
                	$res->bindValue(":TIME", $time, PDO::PARAM_STR);
	                $res->execute();
	
        	        if($row = $res->fetch(PDO::FETCH_ASSOC))
                	        return $row;
	                else
        	                return NULL;
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }


        /*
	* This function is used to delete records.
        * @param pid  : profileid of user whose documenets need to be screened
        * @param user : screening user
        */
        public function del($pid,$user="")
        {
                try
                {
                        $sql = "DELETE FROM newjs.CRITICAL_INFO_DOC_ASSIGNED WHERE PROFILEID=:PROFILEID";
			if($user)
                                $sql.=" AND ASSIGNED_TO=:NAME";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
			if($user)
                        	$res->bindValue(":NAME", $user, PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
}
?>
