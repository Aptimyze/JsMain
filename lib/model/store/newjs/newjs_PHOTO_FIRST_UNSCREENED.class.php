<?php
/**
* function related to 1st unscreened photo of user.
* @author : lavesh
*/
class PHOTO_FIRST_UNSCREENED extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }


	/**
	* add entries.
	*/
	public function add($profileid,$source)
	{
		try
		{
			if(!$profileid)
				throw new jsException("","profileid missing in add function error in newjs_PHOTO_FIRST_UNSCREENED.class.php");
			if(!$source)
				$source='unknown';
			$dt = date("Y-m-d");
			$sql="INSERT IGNORE INTO PHOTO_FIRST_UNSCREENED(PROFILEID,SOURCE,ENTRY_DT) VALUES(:PROFILEID,:SOURCE,:DATE)";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":SOURCE", $source, PDO::PARAM_INT);
			$res->bindValue(":DATE",$dt, PDO::PARAM_INT);
			$res->execute();
		}
                catch(PDOException $e)
                {
			throw new jsException("","add function error in newjs_PHOTO_FIRST_UNSCREENED.class.php : ");
		}
        }


	/**
	* updatev flag
	*/
	public function updateFlag($profileid,$flag)
	{
		try
		{
			if(!$profileid)
				throw new jsException("","profileid missing in updateFlag function error in newjs_PHOTO_FIRST_UNSCREENED.class.php");

			$sql="UPDATE newjs.PHOTO_FIRST_UNSCREENED SET FLAG=:FLAG WHERE PROFILEID=:PROFILEID";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":FLAG", $flag, PDO::PARAM_INT);
			$res->execute();
		}
                catch(PDOException $e)
                {
			throw new jsException("","updateFlag function error in newjs_PHOTO_FIRST_UNSCREENED.class.php : ");
		}
	}


	/**
	* get entries.
	*/
	public function get($select,$profileid,$sourceArr='',$flag='')
	{
		try
		{
			if(!$profileid)
				throw new jsException("","profileid missing in get function error in newjs_PHOTO_FIRST_UNSCREENED.class.php");
			if(is_array($sourceArr))
			{
				foreach($sourceArr as $k=>$v)
					$arr[] = ":SOURCE".$k;
				$str = implode(",",$arr);
			}
			$sql = "SELECT $select FROM  newjs.PHOTO_FIRST_UNSCREENED WHERE PROFILEID=:PROFILEID ";
			if($str)
				$sql.=" AND SOURCE IN ($str)";
			if(isset($flag))
				$sql.=" AND FLAG=:FLAG";

			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			if(isset($flag))
				$res->bindValue(":FLAG", $flag, PDO::PARAM_INT);
			if(is_array($sourceArr))
			{
				foreach($sourceArr as $k=>$v)
					$res->bindValue(":SOURCE".$k, $v, PDO::PARAM_STR);
			}
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
                catch(PDOException $e)
                {
			throw new jsException("","get function error in newjs_PHOTO_FIRST_UNSCREENED.class.php");
                }
	}
}
?>
