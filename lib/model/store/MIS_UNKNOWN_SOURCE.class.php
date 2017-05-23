<?php
class MIS_UNKNOWN_SOURCE extends TABLE {
   
        public function __construct($dbname="")
        {
              parent::__construct($dbname);
        }
        public function insertUnknownSource($source)
        {
                try{
						$date=date("Y-m-d G:i:s");
                        $sql = "insert into MIS.UNKNOWN_SOURCE(SOURCE,`DATE`) values(:source,:date)";
                        $prep = $this->db->prepare($sql);
                        
                        $prep->bindParam(":source", $source, PDO::PARAM_STR);
						$prep->bindParam(":date", $date, PDO::PARAM_STR);						
                        $prep->execute();
                        //$result=$prep->fetch(PDO::FETCH_ASSOC);
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
        }
        
}
?>
