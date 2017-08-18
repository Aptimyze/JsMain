<?php

/**
 * This table is used to store the number of photo profiles screened through a particular source between 2 dates.
**/
class SCREEN_EFFICIENCY extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/**
	 * This table is used to update the number of photo profiles screened through a $source between 2 dates.
	**/
	public function updateScreenedProfilesCount($source1,$source2="",$rec_date)
	{
		$rec_date=explode(" ",$rec_date);
		$rec_date = $rec_date[0];
		$source1 = strtoupper($source1);
		if($source2)
			$source2 = strtoupper($source2);
		$sql="UPDATE MIS.SCREEN_EFFICIENCY SET $source1=$source1+1 ";
		if($source2)
		{
			$source2 = strtoupper($source2);
			$sql.=",$source2=$source2+1 ";
		}
		$sql.=" WHERE RECEIVE_DATE=:REC_DATE AND SUBMITED_DATE=CURDATE()";
                $res=$this->db->prepare($sql);
		$res->bindValue(":REC_DATE", $rec_date, PDO::PARAM_STR);
		if($rec_date)
			$res->execute();

		if($res->rowCount()==0)
		{
			$columns = 'RECEIVE_DATE,SUBMITED_DATE,';
			$values = ":REC_DATE,CURDATE(),";
			if($source2)
			{
				$columns.= "$source1,$source2";
				$values.="'1','1'";
			}
			else
			{
				$columns.= "$source1";
				$values.="'1'";
			}
			$sql = "INSERT INTO MIS.SCREEN_EFFICIENCY ($columns) VALUES ($values)";
			$res=$this->db->prepare($sql);
			$res->bindValue(":REC_DATE", $rec_date, PDO::PARAM_STR);
			if($rec_date)
				$res->execute();
		}
        }

}
?>
