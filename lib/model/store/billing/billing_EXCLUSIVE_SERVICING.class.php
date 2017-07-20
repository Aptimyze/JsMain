<?php

class billing_EXCLUSIVE_SERVICING extends TABLE {

    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    /**
     * Function to get count of pending welcome calls for a particular agent
     *
     * @param   $agent ID
     * @return  array of rows
     */
    public function getWelcomeCallsCount($agent) {
        try {
            $sql = "SELECT COUNT(1) AS CNT FROM billing.EXCLUSIVE_SERVICING";
            $sql = $sql . " WHERE SERVICE_DAY IN ('','NA')";
            $sql = $sql . " AND AGENT_USERNAME =:AGENT";
            $res = $this->db->prepare($sql);
            $res->bindValue(":AGENT", $agent, PDO::PARAM_STR);
            $res->execute();
            if ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                return $result['CNT'];
            }
            return NULL;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    /**
     * Function to get profile details from EXCLUSIVE_SERVICING table
     *
     * @param   $fields,$assigned flag
     * @return  array of rows
     */
    public function getClientsForWelcomeCall($fields = "*", $agent, $orderBy = "") {
        try {
            $sql = "SELECT " . $fields . " FROM billing.EXCLUSIVE_SERVICING";
            $sql = $sql . " WHERE SERVICE_DAY IN ('','NA')";
            $sql = $sql . " AND AGENT_USERNAME ='$agent'";
            if ($orderBy)
                $sql = $sql . " ORDER BY " . $orderBy . " DESC";
            $res = $this->db->prepare($sql);
            $res->execute();
            while ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                $rows[$result["CLIENT_ID"]] = $result;
            }
            return $rows;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    /**
     * Function to get profile details from EXCLUSIVE_SERVICING table
     *
     * @param   $fields,$assigned flag
     * @return  array of rows
     */
    public function checkBioData($profileid) {
        try {
            $sql = "SELECT BIODATA_LOCATION,BIODATA_UPLOAD_DT FROM billing.EXCLUSIVE_SERVICING where CLIENT_ID = :CLIENTID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENTID", $profileid, PDO::PARAM_STR);
            $res->execute();
            if ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                return array($result['BIODATA_LOCATION'], $result['BIODATA_UPLOAD_DT']);
            }
            return false;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

        /**
     * Function to get profile details from EXCLUSIVE_SERVICING table
     *
     * @param   $fields,$assigned flag
     * @return  array of rows
     */
    public function deleteBioData($profileid) {
        try {
            $sql = "update billing.EXCLUSIVE_SERVICING SET BIODATA_LOCATION = '' where CLIENT_ID = :CLIENTID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENTID", $profileid, PDO::PARAM_STR);
            $res->execute();
            return true;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

        /**
     * Function to get profile details from EXCLUSIVE_SERVICING table
     *
     * @param   $fields,$assigned flag
     * @return  array of rows
     */
    public function setBioDataLocation($profileid,$location) {
        try {
            $sql = "update billing.EXCLUSIVE_SERVICING SET BIODATA_LOCATION =:PATH, BIODATA_UPLOAD_DT = now() where CLIENT_ID = :CLIENTID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PATH", $location, PDO::PARAM_STR);
            $res->bindValue(":CLIENTID", $profileid, PDO::PARAM_STR);
            $res->execute();
            return true;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
    
            /**
     * Function to get profile details from EXCLUSIVE_SERVICING table
     *
     * @param   $fields,$assigned flag
     * @return  array of rows
     */
    public function setServiceDay($profileid,$serviceDay) {
        try {
            $sql = "update billing.EXCLUSIVE_SERVICING SET SERVICE_DAY=:SERVICE_DAY, SERVICE_SET_DT = now() where CLIENT_ID = :CLIENTID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENTID", $profileid, PDO::PARAM_STR);
            $res->bindValue(":SERVICE_DAY", $serviceDay, PDO::PARAM_STR);
            $res->execute();
            return true;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
    
        /**
     * Function to get profile details from EXCLUSIVE_SERVICING table
     *
     * @param   $fields,$assigned flag
     * @return  array of rows
     */
    public function getServiceDay($profileid) {
        try {
            $sql = "SELECT SERVICE_DAY,SERVICE_SET_DT FROM billing.EXCLUSIVE_SERVICING where CLIENT_ID = :CLIENTID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENTID", $profileid, PDO::PARAM_STR);
            $res->execute();
            if ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                return array($result['SERVICE_DAY'], $result['SERVICE_SET_DT']);
            }
            return false;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
    
}

?>