<?php
/**
This class is required to give suggested castes corresponding a particular caste if search results are less. It also gives the text message to be displayed on the browser.
**/
class CasteSuggest
{
	public function __construct()
        {
        }

	/**
        This function returns suggested castes corresponding to a given caste.
        * @param  caste value, type = 1 for auto relaxation and type = 2 for broadening.
        * @return array of suggested castes.
        **/
	public function getSuggestedCastes($caste,$type)
	{
		$caste_suggest_obj = new NEWJS_CASTE_SUGGESTION_SEARCH;
		$suggestedCastes = $caste_suggest_obj->getSuggestedCastes($caste,$type);
		unset($caste_suggest_obj);
		return $suggestedCastes;
	}

	/**
        This function returns the messgae to be displayed at front end when caste suggestion is implemented.
        * @param  1) selected caste, 2) array of suggested castes
        * @return text message.
        **/
	public function getMessage($caste,$suggestedCaste)
	{
		$CASTE_DROP = FieldMap::getFieldLabel("caste",1,1);
		$searched_caste_religion_arr = explode(":",$CASTE_DROP[$caste]);
		$searched_caste_religion = trim($searched_caste_religion_arr[0]);

		foreach($suggestedCaste as $k=>$v)
		{
			$suggested_caste_religion_arr = explode(":",$CASTE_DROP[$v]);
                	$suggested_caste_religion = trim($suggested_caste_religion_arr[0]);
			if($searched_caste_religion == $suggested_caste_religion)
				$suggestedCaste[$k] = trim($suggested_caste_religion_arr[1]);
			else
				$suggestedCaste[$k] = $CASTE_DROP[$v];
		}	

		$msg = implode(", ",$suggestedCaste);
		return $msg;
	}
}
?>
