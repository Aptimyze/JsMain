<?php
class billing_VOUCHER_SUCCESSSTORY extends TABLE{
       

       
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        	
		public function insertSuccessStory($paramArr)
        {
			try 
			{
				$sql="INSERT INTO billing.VOUCHER_SUCCESSSTORY(STORYID,PROFILEID,USERNAME_H,USERNAME_W,NAME_H,NAME_W,CONTACT,PHONE_RES,PHONE_MOB,EMAIL,CITY_RES) VALUES(:STORYID,:PROFILEID,:USERNAME_H,:USERNAME_W,:NAME_H,:NAME_W,:CONTACT,:PHONE_RES,:PHONE_MOB,:EMAIL,:CITY_RES)";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":STORYID",$paramArr['STORYID'],PDO::PARAM_INT);
				$prep->bindValue(":PROFILEID",$paramArr['PROFILEID'],PDO::PARAM_INT);
				$prep->bindValue(":USERNAME_H",$paramArr['USERNAME_H'],PDO::PARAM_STR);
				$prep->bindValue(":USERNAME_W",$paramArr['USERNAME_W'],PDO::PARAM_STR);
				$prep->bindValue(":NAME_H",$paramArr['NAME_H'],PDO::PARAM_STR);
				$prep->bindValue(":NAME_W",$paramArr['NAME_W'],PDO::PARAM_STR);
				$prep->bindValue(":CONTACT",$paramArr['CONTACT'],PDO::PARAM_STR);
				$prep->bindValue(":PHONE_RES",$paramArr['PHONE_RES'],PDO::PARAM_STR);
				$prep->bindValue(":PHONE_MOB",$paramArr['PHONE_MOB'],PDO::PARAM_STR);
				$prep->bindValue(":EMAIL",$paramArr['EMAIL'],PDO::PARAM_STR);
				$prep->bindValue(":CITY_RES",$paramArr['CITY_RES'],PDO::PARAM_STR);
				if($res=$prep->execute())
                {
                	return $res;
                }
                return NULL;
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
		
		public function getID($storyId)
		{
			try {
				$sql = "SELECT COUNT(ID) as CNT FROM billing.VOUCHER_SUCCESSSTORY WHERE STORYID=:STORYID";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":STORYID",$storyId,PDO::PARAM_INT);
				$prep->execute();
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					return $result['CNT'];
				}
				return NULL;
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
		
	}
?>
