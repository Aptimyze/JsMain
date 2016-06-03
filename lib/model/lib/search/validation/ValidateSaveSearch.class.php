<?php

class ValidateSaveSearch extends ValidationHandler
{
	public function savesearch($param)
	{
		$objSaveSearch = new UserSavedSearches($param[loggedInObj]);
		$UserSaveSearch = $objSaveSearch->countRecord();
		$savedSearches = $objSaveSearch->getSavedSearches();
		foreach ($savedSearches as $k => $v) {
			if (in_array($param[name], $v))    //If saveSearchName already exists 
			{
				$errorMsg = SaveSearchMsgEnum::$SameSearchName;
				return $errorMsg;
			}
		}
		if ($UserSaveSearch == SearchConfig::$maxSaveSearchesAllowed) 	//max limit exceeded
		$errorMsg = SaveSearchMsgEnum::$LimitError;
							
		elseif (!$param[name])        //At this stage if save Search Name is not set 
			$errorMsg = SaveSearchMsgEnum::$BlankError;
						   
		elseif ($param[loggedInObj]->getGENDER() == $param[SearchParam]->getGENDER())	//if gender is same
			$errorMsg = SaveSearchMsgEnum::$GenderError;
		else
			$errorMsg = null;
			
		return $errorMsg;
	}
}
?>
