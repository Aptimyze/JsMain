<?php
class NEWJS_LANGUAGES extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
        This function fetches the LABEL,VALUE, MTNGUE_VAL for all visible languages in alphabatical order.
        * @param  
        * @return array[LABEL][VALUE][MTNGUE_VAL]
        **/
	

	public function getAllLanguages()
	{
		
		try
		{
			$sql = "SELECT LABEL, VALUE, MTONGUE_VAL FROM newjs.LANGUAGES WHERE VISIBLE='Y' ORDER BY ALPHA_SORT";
			$res = $this->db->prepare($sql);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$mtongueArr[] = $row;
			}
			return $mtongueArr;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
