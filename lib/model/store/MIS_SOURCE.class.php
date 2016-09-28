<?php
class MIS_SOURCE extends TABLE {
   
        public function __construct($dbname="")
        {
              parent::__construct($dbname);
        }

        public function getSourceGroup($source)
        {
                try{
                        $sql = "select GROUPNAME from MIS.SOURCE where SourceID=:source";
                        $prep = $this->db->prepare($sql);
                        $prep->bindParam(":source", $source, PDO::PARAM_STR);
			
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $result;
        }
        public function existSource($sourceID)
        {
                try{
                        $sql = "select SourceID from MIS.SOURCE where SourceID=:sourceID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindParam(":sourceID", $sourceID, PDO::PARAM_STR);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                if($result["SourceID"])
                return $result["SourceID"];
                else
                return false;
		}
		
		public function getSourceFields($fieldStr="*",$sourceID)
        {
                try{
                       $sql = "select ".$fieldStr." from MIS.SOURCE where SourceID=:sourceID";
                        $prep = $this->db->prepare($sql);
                         $prep->bindParam(":sourceID", $sourceID, PDO::PARAM_STR);
			
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $result;
        }
        
        public function isPresent($szSource)
        {
			 try{
                       $sql = "select ID  from MIS.SOURCE where SourceID=:sourceID";
                        $prep = $this->db->prepare($sql);
                         $prep->bindParam(":sourceID", $szSource, PDO::PARAM_STR);
			
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                        if($result)
							return true;
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return false;
		}
    public function getSourceList() {
    try {
      $sql = "SELECT `GROUPNAME` FROM MIS.`SOURCE` GROUP BY `GROUPNAME` ORDER BY `GROUPNAME` ASC";
      $prep = $this->db->prepare($sql);
      $prep->execute();
      $groups = array();
      while($res=$prep->fetch(PDO::FETCH_ASSOC)){
        $groups[] = $res;
      }
      return $groups;
    } catch (Exception $e) {
      throw new jsException($e);
    }
    return false;
  }

   /**
     * @param $sourceGroup - array consisting of source group names.
     * @return results Array consisting of source ids 
     * @exception jsException 
     */
        public function getSourceID($sourceGroup)
        {
            try 
            {
              
              $sql = "SELECT `SourceID` FROM MIS.`SOURCE` where `GROUPNAME` =:sourceGroup";
              $prep = $this->db->prepare($sql);
              $prep->bindParam(":sourceGroup", $sourceGroup, PDO::PARAM_STR);
              $prep->execute();

              $result=$prep->fetchAll(PDO::FETCH_COLUMN, 0);
          }
          catch(Exception $e){
            throw new jsException($e);
        }
        return $result;
    }
}
?>
