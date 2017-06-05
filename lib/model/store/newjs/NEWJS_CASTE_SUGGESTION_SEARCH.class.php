<?php
class NEWJS_CASTE_SUGGESTION_SEARCH extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/**
        This function fetches the suggested castes of a particular caste.
        * @param  caste value as int.
        * @return array of suggested castes.
        **/
	public function getSuggestedCastes($caste,$type)
	{
		if(!$caste || !$type)
                        throw new jsException("","CASTE OR TYPE IS BLANK IN getSuggestedCastes() of NEWJS_CASTE_SUGGESTION_SEARCH.class.php");

		if($type == 1)
			$str = " AND AUTO_EXPAND = 1";

                try
                {
                        $sql = "SELECT CASTE_SUGGESTED FROM newjs.CASTE_SUGGESTION_SEARCH WHERE CASTE_SEARCHED = :CASTE".$str;
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":CASTE", $caste, PDO::PARAM_INT);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row["CASTE_SUGGESTED"];
			}
			return $output;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
