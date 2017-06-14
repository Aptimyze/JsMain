<?php

class NEWJS_CONTACTS_STATUS extends TABLE {

    public function __construct($dbname="") {
        parent::__construct($dbname);
    }
    public function updateSaveSearch($profileid)
    {
        if(!$profileid)
            throw new jsException("","PROFILEID IS BLANK IN updateSaveSearch() OF NEWJS_CONTACTS_STATUS.class.php");

        try
        {
            $sql = "UPDATE CONTACTS_STATUS SET SAVE_SEARCH=:SAVE_SEARCH WHERE PROFILEID=:PROFILEID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":SAVE_SEARCH", 'Y', PDO::PARAM_INT);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->execute();
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function update($contact_status_fields, $profileid) {
        try {

            foreach ($contact_status_fields as $key => $value) {
                $set[] = $key . " = :" . $key;
            }
            $setValues = implode(",", $set);
            $sql = "UPDATE newjs.CONTACTS_STATUS SET $setValues WHERE PROFILEID=:PROFILEID";
            $res = $this->db->prepare($sql);

            foreach ($contact_status_fields as $key => $value) {
                if ($key === "EXPIRY_DT" || $key === "SAVE_SEARCH") {
                    $res->bindValue(":" . $key, $value, PDO::PARAM_STR);
                }
                else {
                    $res->bindValue(":" . $key, $value, PDO::PARAM_INT);
                }
            }

            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);

            $res->execute();
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function replace($contact_status_fields, $profileid) {
        try {
            $count = 0;
            foreach ($contact_status_fields as $key => $value) {
                $set[$count] = $key ."= '$value' ";
                $keyArr[$count] = $key; 
                $vals[$count] = $value;
                $count++;
            }
            $set[$count]= "PROFILEID='$profileid'";
            $keyArr[$count] = "PROFILEID"; 
            $vals[$count] = $profileid;
            $setValues = implode(",", $set);
            $keyStr = implode(",",$keyArr);
            $valsStr = implode(",",$vals);
            $sql = "INSERT INTO newjs.CONTACTS_STATUS  ($keyStr) VALUES ($valsStr) ON DUPLICATE KEY UPDATE $setValues";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->execute();
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function delete($profileid) {
        try {
            $sql = "DELETE FROM newjs.CONTACTS_STATUS WHERE PROFILEID=:PROFILEID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->execute();
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }
}
