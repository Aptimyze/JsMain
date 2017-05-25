<?php
/*This class is used to handle the Caste Relaxation logic in matchalerts*/
class CasteRelaxation
{
	public function __construct($dbname='')
	{
		$this->dbname = $dbname;
	}

	/*
	This function is used to get all the relaxed castes corresponding a given caste
	@param - caste value
	@return - array of relaxed castes or blank
	*/
	public function getRelaxedCasteList($caste)
	{
		if($caste)
		{
			if(is_array($caste))
				$caste_values = implode(",",$caste);
			else
				$caste_values = $caste;

			$caste_values = str_replace("\"","",$caste_values);
			$caste_values = str_replace("'","",$caste_values);
		
			$mcrObj = new NEWJS_CASTE_RELAXATION_COMMUNITY_MODEL($this->dbname);
			$output = $mcrObj->getRelaxedCasteList($caste_values);
			unset($mcrObj);
		}
		return $output;
	}
}
?>
