<?php

/**
 * 
 * User: Palash Chordia
 * Date: 11/5/16
 * Time: 4:48 PM
 */
class NEWJS_OFFER_CONSENT extends TABLE
{


    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */

    public function __construct($dbname="newjs_master")
    {
        parent::__construct($dbname);
    }
    public function insertConsent($pid)
    {
        try
        {
            if($pid)
            {
                $date = date('Y-m-d H:i:s');
                $sql="INSERT INTO newjs.OFFER_CONSENT VALUES(:PROFILEID,:TIME)";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
                $prep->bindValue(":TIME",$date,PDO::PARAM_INT);
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
