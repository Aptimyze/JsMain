<?php
class incentive_SALES_REGISTRATION_CSV_DATA extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	public function insertProfile($profileid,$username,$entryDt,$gender='',$relation='',$cityRes='',$locality='',$mobile1='',$landline='',$mobile2='')
        {
                try
                {
                        $sql = "INSERT IGNORE INTO incentive.SALES_REGISTRATION_CSV_DATA (PROFILEID,USERNAME,ENTRY_DT,GENDER,RELATION,CITY_RES,LOCALITY,MOBILE1,LANDLINE,MOBILE2,CSV_ENTRY_DATE) VALUES(:PROFILEID,:USERNAME,:ENTRY_DT,:GENDER,:RELATION,:CITY_RES,:LOCALITY,:MOBILE1,:LANDLINE,:MOBILE2,now())";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DT",$entryDt,PDO::PARAM_STR);
			$prep->bindValue(":GENDER",$gender,PDO::PARAM_STR);
			$prep->bindValue(":RELATION",$relation,PDO::PARAM_STR);
			$prep->bindValue(":CITY_RES",$cityRes,PDO::PARAM_STR);
			$prep->bindValue(":LOCALITY",$locality,PDO::PARAM_STR);			
			$prep->bindValue(":MOBILE1",$mobile1,PDO::PARAM_STR);
			$prep->bindValue(":LANDLINE",$landline,PDO::PARAM_STR);
			$prep->bindValue(":MOBILE2",$mobile2,PDO::PARAM_STR);

                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function removeProfiles($csvEntryDate)
        {
                try
                {
                        $sql="DELETE FROM incentive.SALES_REGISTRATION_CSV_DATA WHERE CSV_ENTRY_DATE<:CSV_ENTRY_DATE";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":CSV_ENTRY_DATE",$csvEntryDate,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function getData($date)
        {
                try
                {
                        $sql="SELECT USERNAME,ENTRY_DT,GENDER,RELATION,CITY_RES,LOCALITY,MOBILE1,LANDLINE,MOBILE2 FROM incentive.SALES_REGISTRATION_CSV_DATA WHERE CSV_ENTRY_DATE = :CSV_ENTRY_DATE ORDER BY PROFILEID DESC";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":CSV_ENTRY_DATE",$date,PDO::PARAM_STR);
                        $prep->execute();
                        $i=0;
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $data[$i]=$res;
                                $i++;
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $data;
        }

}
?>
