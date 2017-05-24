<?php


class MOBILE_API_MATCH_OF_DAY extends TABLE{
    
    public function __construct($dbName = "") {
        parent::__construct($dbName);
    }
    
    public function getMatchForProfileTillDays($paramsArr){
        if($paramsArr){
            try{
                $sql = "SELECT MATCH_PROFILEID FROM MOBILE_API.MATCH_OF_DAY_LOG where PROFILEID = :PROFILEID AND ENTRY_DT > :ENTRY_DT";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$paramsArr["PROFILEID"],PDO::PARAM_INT);
                $prep->bindValue(":ENTRY_DT",$paramsArr["ENTRY_DT"],PDO::PARAM_STR);
                $prep->execute();
                while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                    $result[] = $row["MATCH_PROFILEID"];
                }
                return $result;
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }
    
    public function getCountForMatchProfile(){
        try{
            $sql = "SELECT MATCH_PROFILEID, COUNT( * ) AS COUNT FROM MOBILE_API.MATCH_OF_DAY_LOG GROUP BY MATCH_PROFILEID ORDER BY COUNT";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result[$row["MATCH_PROFILEID"]] = $row["COUNT"];
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function insert($profileid,$matchProfileid){
        try{
            $sql = "INSERT INTO MOBILE_API.MATCH_OF_DAY_LOG(ID,PROFILEID,MATCH_PROFILEID,ENTRY_DT) VALUES (NULL,:PROFILEID,:MATCH_PROFILEID,:ENTRY_DT)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $prep->bindValue(":MATCH_PROFILEID",$matchProfileid,PDO::PARAM_INT);
            $prep->bindValue(":ENTRY_DT",date("Y-m-d"),PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function deleteLessthanDays($date){
        try{
            $sql = "DELETE FROM MOBILE_API.MATCH_OF_DAY_LOG WHERE ENTRY_DT < :DATE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":DATE",$date,PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    
    public function getMatchForProfileForListing($paramsArr,$ignoreArray = array()){
        if($paramsArr){
            try{
                $sql = "SELECT MATCH_PROFILEID FROM MOBILE_API.MATCH_OF_DAY_LOG where PROFILEID = :PROFILEID AND ENTRY_DT >= :ENTRY_DT ";
                if(isset($paramsArr["IGNORED"])){
                        $sql .= " AND IGNORED = :IGNORED";
                }
                $ignoreArray = array_filter($ignoreArray);
                if(!empty($ignoreArray) && $ignoreArray[0] != ''){
                        $sql .= " AND MATCH_PROFILEID NOT IN (".implode(',',$ignoreArray).")";
                }
		$sql.="  ORDER BY ENTRY_DT DESC";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$paramsArr["PROFILEID"],PDO::PARAM_INT);
                $prep->bindValue(":ENTRY_DT",$paramsArr["ENTRY_DT"],PDO::PARAM_STR);
                if(isset($paramsArr["IGNORED"])){
                        $prep->bindValue(":IGNORED",$paramsArr["IGNORED"],PDO::PARAM_STR);
                }
                $prep->execute();
                while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                    $result[] = $row["MATCH_PROFILEID"];
                }
                return $result;
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }

    public function updateMatchProfile($profileid, $matchProfileid)
    {
        try
        {
            $sql = "UPDATE MOBILE_API.MATCH_OF_DAY_LOG SET IGNORED = 'Y' WHERE PROFILEID = :PROFILEID AND MATCH_PROFILEID = :MATCH_PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":MATCH_PROFILEID", $matchProfileid, PDO::PARAM_INT);
            $prep->execute();
        }
        catch(Exception $ex)
        {
            throw new jsException($ex);
        }
    }
}
