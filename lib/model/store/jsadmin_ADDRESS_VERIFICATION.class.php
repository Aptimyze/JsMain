<?php

/**
 * Created by PhpStorm.
 * User: pankaj
 * Date: 11/5/16
 * Time: 5:07 PM
 */
class jsadmin_ADDRESS_VERIFICATION extends TABLE
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
    public function getAddressVerificionStatus($pid)
    {
        try
        {
            if($pid)
            {
                $sql="select SCREENED from jsadmin.ADDRESS_VERIFICATION where PROFILEID=:PROFILEID";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
                $prep->execute();
                if($result = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    return $result["SCREENED"];
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