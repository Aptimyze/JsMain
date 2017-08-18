<?php
class incentive_BRANCH_STATE extends TABLE   
{
        public function __construct($dbname="")
        {
              parent::__construct($dbname);
        }
	public function fetchStates()
        {
                try
                {
                        $sql="SELECT VALUE from incentive.BRANCH_STATE";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $states[] = $result['VALUE'];
                        }

                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $states;
        }
}

