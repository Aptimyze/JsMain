<?php
/*This class is used to execute queries on MIS.VIEW_ALBUM_LOG table
 * @author Lavesh Rawat
*/
class LOG_DELETED_HIDDEN_IDS_FOR_SEARCH extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

        /**
        * This function is to add entry.
        * @param  profileIdArr whose record need to be added
        **/
        public function insertRecord($profileIdArr)
        {
                if(!is_array($profileIdArr))
                        throw new jsException("","PROFILEID IS BLANK IN insertRecord() OF LOG_DELETED_HIDDEN_IDS_FOR_SEARCH");
                try
                {
			for ($i=0;$i<count($profileIdArr);$i++)
                                $param[] = "(:PID".$i.")";
			$str = implode(",",$param);
                        $sql = "REPLACE INTO MIS.LOG_DELETED_HIDDEN_IDS_FOR_SEARCH(PROFILEID) VALUES $str";
                        $res = $this->db->prepare($sql);
			for ($i=0;$i<count($profileIdArr);$i++)
	                        $res->bindValue(":PID".$i, $profileIdArr[$i], PDO::PARAM_INT);

                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

	/**
	* This function will fetch all the reocrds from the table
	**/
	public function get()
	{
                $sql="SELECT PROFILEID FROM MIS.LOG_DELETED_HIDDEN_IDS_FOR_SEARCH";
                $res = $this->db->prepare($sql);
                $res->execute();
                while($row = $res->fetch(PDO::FETCH_ASSOC))
                        $arr[]=$row['PROFILEID'];
		return $arr;
	}

	/**
	* This function will fetch all the reocrds from the table
	**/
	public function del($dt)
	{
                $sql="DELETE FROM MIS.LOG_DELETED_HIDDEN_IDS_FOR_SEARCH WHERE UPDATED_TIME<:DT";
                $res = $this->db->prepare($sql);
	        $res->bindValue(":DT",$dt,PDO::PARAM_STR);
                $res->execute();
	}
}
?>
