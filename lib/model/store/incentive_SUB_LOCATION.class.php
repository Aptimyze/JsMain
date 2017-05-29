<?php
class incentive_SUB_LOCATION extends TABLE   
{
        public function __construct($dbname="")
        {
              parent::__construct($dbname);
        }
        public function fetchSubLocations($location)
        {
                try
                {
                        $sql="SELECT LABEL from incentive.SUB_LOCATION WHERE PRIORITY =:PRIORITY";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PRIORITY",$location,PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $subLocations[] = trim($result['LABEL']);

                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $subLocations;
        }
        public function fetchSubLocationLabel($value)
        {
                try
                {
                        $sql="SELECT LABEL from incentive.SUB_LOCATION WHERE VALUE=:VALUE";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":VALUE",$value,PDO::PARAM_STR);
                        $prep->execute();
                        if($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $subLocation = trim($result['LABEL']);

                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $subLocation;
        }
}
?>
