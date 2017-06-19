<?php
class incentive_FTA_DATA extends TABLE 
{
	public function __construct($dbname="")
	{
		  parent::__construct($dbname);
	}
	public function getProfilesCalledAfter($time)
	{
		try
        {
			$sql="SELECT PROFILEID,EXECUTIVE,CALLED_DATE from incentive.FTA_DATA WHERE CALLED_DATE >= :CALLED_DATE";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":CALLED_DATE",$time,PDO::PARAM_STR);
			$prep->execute();
			$i=0;
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$profiles[$i]['PROFILEID'] = $result['PROFILEID'];
				$profiles[$i]['EXECUTIVE'] = $result['EXECUTIVE'];
				$profiles[$i]['CALLED_DATE'] = $result['CALLED_DATE'];
				$i++;
			}

		}
		catch(Exception $e)
		{
				throw new jsException($e);
		}
		return $profiles;
	}
	public function getProfilesCalledBetween($time1,$time2)
        {
                try
        {
                        $sql="SELECT PROFILEID,EXECUTIVE,CALLED_DATE from incentive.FTA_DATA WHERE CALLED_DATE >= :CALLED_DATE1 AND CALLED_DATE <= :CALLED_DATE2";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":CALLED_DATE1",$time1,PDO::PARAM_STR);
                        $prep->bindValue(":CALLED_DATE2",$time2,PDO::PARAM_STR);  
                        $prep->execute();
                        $i=0;
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $profiles[$i]['PROFILEID'] = $result['PROFILEID'];
                                $profiles[$i]['EXECUTIVE'] = $result['EXECUTIVE'];
                                $profiles[$i]['CALLED_DATE'] = $result['CALLED_DATE'];
                                $i++;
                        }

                }
                catch(Exception $e)
                {
                                throw new jsException($e);
                }
                return $profiles;
        }
}
