<?php
class INVALID_PHONE extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

	public function existInINVALID_PHONE($PROFILEID){
                try
                {
                        $res=null;
                        if($PROFILEID)
                        {
        			$sql="SELECT count(*) AS COUNT FROM incentive.INVALID_PHONE WHERE PROFILEID =:PROFILEID";      //unique is there on prfileid
                                $prep=$this->db->prepare($sql);
                                $prep->bindValue(":PROFILEID",$PROFILEID,PDO::PARAM_INT);
                                $prep->execute();
                                if($result = $prep->fetch(PDO::FETCH_ASSOC))
                                {
                                        if($result["COUNT"]>0)
						$return='Y';
					else
						$return="N";
                                }

                        }
                        else
                                throw new jsException("No phone number as Input paramter");
                        return $return;

                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}


        public function deleteEntry($PROFILEID)
        {
                try
                {

                        if($PROFILEID)
                        {
                                $sql="DELETE FROM incentive.INVALID_PHONE WHERE PROFILEID=:PROFILEID";
                                $prep=$this->db->prepare($sql);
                                $prep->bindValue(":PROFILEID",$PROFILEID,PDO::PARAM_INT);
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
