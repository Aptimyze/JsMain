<?php

class jsadmin_CONTACTS_ALLOTED extends TABLE {
    
    public function __construct($dbName="")
    {
        parent::__construct($dbName);
    }
    
    function updateViewedContactCount($profileId)
    {
        try
        {
            $date=date("Y-m-d G:i:s");
            $sql="UPDATE jsadmin.CONTACTS_ALLOTED set VIEWED=VIEWED+1,LAST_VIEWED=:DATE where PROFILEID=:viewerPid";
            $prep=$this->db->prepare($sql);
            
            $prep->bindValue(":viewerPid", $profileId, PDO::PARAM_INT);
            $prep->bindValue(":DATE", $date, PDO::PARAM_STR);
            $prep->execute();
            
            
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
    
    public function getViewedContacts($profileId)
    {
        try
        {
            $sql = "SELECT ALLOTED,VIEWED FROM jsadmin.CONTACTS_ALLOTED WHERE PROFILEID = :PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
            $prep->execute();               
            if($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                return ($result['ALLOTED']-$result['VIEWED']);
            }
            else return 0;
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }
    public function getRemainingContactsForProfile($profileId)
    {
        try
        {
            $sql = "SELECT ALLOTED-VIEWED AS REMAINING FROM jsadmin.CONTACTS_ALLOTED WHERE PROFILEID=:PROFILEID AND ALLOTED>=VIEWED";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                return ($result['REMAINING']);
            }
            else return 0;
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }

    /*function to get contact views log for profiles
    * @param : $profileIdArr---array of profileids
    * @return : $data(profileid based array of alloted,viewed and remaining contacts)
    */
    public function getContactViewsDataForProfiles($profileIdArr)
    {
        try
        {
            if(is_array($profileIdArr) && $profileIdArr)
            {
                $profileIdStr = implode("','", $profileIdArr);
                $sql = "SELECT PROFILEID,ALLOTED,VIEWED,ALLOTED-VIEWED AS REMAINING FROM jsadmin.CONTACTS_ALLOTED WHERE ALLOTED>=VIEWED AND PROFILEID IN ('".$profileIdStr."')";
                $prep = $this->db->prepare($sql);
                $prep->execute(); 
                while($result = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $data[$result['PROFILEID']]['ALLOTED'] = $result['ALLOTED'];
                    $data[$result['PROFILEID']]['VIEWED'] = $result['VIEWED'];
                    $data[$result['PROFILEID']]['REMAINING'] = $result['REMAINING'];
                } 
                return $data; 
            }
            else 
                return null;
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }
    
    //This Function is made only for testing Purpose only.
    public function insertViewedContacts($profileId,$alloted,$viewed)
    {
        try 
        {
            $sql = "SELECT COUNT(*) AS cnt FROM jsadmin.CONTACTS_ALLOTED WHERE PROFILEID = :PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
            $prep->execute();
            $result = $prep->fetch(PDO::FETCH_ASSOC);
            if($result['cnt']>0)            
                $sql = "UPDATE jsadmin.CONTACTS_ALLOTED set ALLOTED=:ALLOTED,VIEWED=:VIEWED,LAST_VIEWED=:DATE WHERE PROFILEID = :PROFILEID"; 
            else            
                $sql = "INSERT INTO jsadmin.CONTACTS_ALLOTED(PROFILEID,ALLOTED,VIEWED,LAST_VIEWED) VALUES (:PROFILEID,:ALLOTED,:VIEWED,:DATE)";
            $prep=$this->db->prepare($sql);
            $date=date("Y-m-d G:i:s");
            
            $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
            $prep->bindValue(":ALLOTED",$alloted,PDO::PARAM_INT);
            $prep->bindValue("VIEWED",$viewed,PDO::PARAM_INT);
            $prep->bindValue(":DATE", $date, PDO::PARAM_STR);
            $prep->execute();           
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getAllotedContacts($profileId)
    {
        try
        {
            $sql = "SELECT ALLOTED FROM jsadmin.CONTACTS_ALLOTED WHERE PROFILEID = :PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
            $prep->execute();               
            if($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                return ($result['ALLOTED']);
            }
            else return 0;
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function updateAllotedContacts($profileId,$count)
    {
        try 
        {
            $sql = "UPDATE jsadmin.CONTACTS_ALLOTED set ALLOTED=ALLOTED+:COUNT WHERE PROFILEID=:PROFILEID"; 
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
            $prep->bindValue(":COUNT", $count, PDO::PARAM_INT);
            $prep->execute();           
            $row_affected = $prep->rowCount();
            if($row_affected == 0)
            {
                return false;
            }
            return true;
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }
    
    public function replaceAllotedContacts($profileId,$count)
    {
        try 
        {
            $sql = "REPLACE INTO jsadmin.CONTACTS_ALLOTED(PROFILEID,ALLOTED,CREATED) VALUES(:PROFILEID,:COUNT,NOW())"; 
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
            $prep->bindValue(":COUNT", $count, PDO::PARAM_INT);
            $prep->execute();           
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }

    /*function to get contact alloted for profiles
    * @param : $profileIdArr---array of profileids
    * @return : $data(profileid based array of alloted,viewed and remaining contacts)
    */
    public function getAllotedContactsForProfiles($profileIdArr)
    {
        try
        {
            if(is_array($profileIdArr) && $profileIdArr)
            {
                $profileIdStr = implode("','", $profileIdArr);
                $sql = "SELECT PROFILEID,ALLOTED FROM jsadmin.CONTACTS_ALLOTED WHERE PROFILEID IN ('".$profileIdStr."')";
                $prep = $this->db->prepare($sql);
                $prep->execute(); 
                while($result = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $data[$result['PROFILEID']] = $result['ALLOTED'];
                } 
                return $data; 
            }
            else 
                return null;
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }
}
