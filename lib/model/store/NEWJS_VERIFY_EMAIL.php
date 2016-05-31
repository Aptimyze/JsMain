<?php

/**
 * Created by PhpStorm.
 * User: pankaj
 * Date: 11/5/16
 * Time: 4:48 PM
 */
class NEWJS_VERIFY_EMAIL extends TABLE
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
    public function getVerifyEmail($pid)
    {
        try
        {
            if($pid)
            {
                $sql="select STATUS from newjs.VERIFY_EMAIL WHERE PROFILEID=:PROFILEID";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
                $prep->execute();
                if($result = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    return $result["STATUS"];
                }
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
