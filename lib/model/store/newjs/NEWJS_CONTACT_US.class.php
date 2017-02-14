<?php

class NEWJS_CONTACT_US extends TABLE
{
    public function __construct($szDBName = "")
    {
        parent::__construct($szDBName);
    }

    public function fetch_All_Contact(&$arrRef_Result)
    {
        try {
            $sql = "SELECT * from newjs.CONTACT_US";
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->execute();
            $arrRef_Result = $pdoStatement->fetchAll();
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function fetchPrintBillData($center)
    {
        try {
            $sql  = "SELECT ADDRESS, PHONE, MOBILE FROM newjs.CONTACT_US WHERE NAME LIKE '%$center%'";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            $result = $prep->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function fetchBranches($city_value, $change = false) // Membership Query

    {
        try {
            if ($change) {
                $var_name = 'STATE';
            } else {
                $var_name = 'CITY_ID';
            }
            if (is_array($city_value) && !empty($city_value)) {
                $cityStr = implode("','", $city_value);
                $sql     = "SELECT CONTACT_PERSON,ADDRESS,PHONE,MOBILE,NAME,STATE FROM newjs.CONTACT_US WHERE {$var_name} IN ('$cityStr')";
            } else {
                $sql = "SELECT CONTACT_PERSON,ADDRESS,PHONE,MOBILE,NAME,STATE FROM newjs.CONTACT_US WHERE {$var_name}=:CITY_ID";
            }
            $prep = $this->db->prepare($sql);
            if (!is_array($city_value)) {
                $prep->bindValue(":CITY_ID", $city_value, PDO::PARAM_STR);
            }
            $prep->execute();
            $count = $prep->rowCount();
            $i     = 0;
            if ($count == 0 && !$change) {
                $sql2  = "SELECT CONTACT_PERSON,ADDRESS,PHONE,MOBILE,NAME,STATE FROM newjs.CONTACT_US WHERE CITY_ID='UP25'";
                $prep2 = $this->db->prepare($sql2);
                $prep2->execute();
                while ($row_address = $prep2->fetch(PDO::FETCH_ASSOC)) {
                    $near_branches[$i]['CONTACT_PERSON'] = $row_address['CONTACT_PERSON'];
                    $near_branches[$i]['ADDRESS']        = nl2br($row_address['ADDRESS']);
                    $near_branches[$i]['PHONE']          = $row_address['PHONE'];
                    $near_branches[$i]['MOBILE']         = $row_address['MOBILE'];
                    $near_branches[$i]['NAME']           = $row_address['NAME'];
                    $near_branches[$i]['STATE']          = $row_address['STATE'];
                    $i++;
                }
            } else {
                while ($row_address = $prep->fetch(PDO::FETCH_ASSOC)) {
                    $near_branches[$i]['CONTACT_PERSON'] = $row_address['CONTACT_PERSON'];
                    $near_branches[$i]['ADDRESS']        = nl2br($row_address['ADDRESS']);
                    $near_branches[$i]['PHONE']          = $row_address['PHONE'];
                    $near_branches[$i]['MOBILE']         = $row_address['MOBILE'];
                    $near_branches[$i]['NAME']           = $row_address['NAME'];
                    $near_branches[$i]['STATE']          = $row_address['STATE'];
                    $i++;
                }
            }
            return $near_branches;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function fetchStates()
    {
        try {
            $sql  = "SELECT DISTINCT STATE ,STATE_VAL FROM newjs.CONTACT_US ORDER BY STATE";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            $i = 0;
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $STATES[$i]['STATE']     = $result['STATE'];
                $STATES[$i]['STATE_VAL'] = $result['STATE_VAL'];
                $i++;
            }
            return $STATES;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
}
