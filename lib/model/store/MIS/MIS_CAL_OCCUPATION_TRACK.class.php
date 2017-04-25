<?php
class MIS_CAL_OCCUPATION_TRACK extends TABLE {
   
        public function __construct($dbname="")
        {
              parent::__construct($dbname);
        }

        public function insert($profileid,$occText)
        {
                try{
                        $sql = "REPLACE INTO  MIS.CAL_OCCUPATION_TRACK  VALUES(:PROFILEID,:occText)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
                        $prep->bindParam(":occText", $occText, PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
        }

}

?>
