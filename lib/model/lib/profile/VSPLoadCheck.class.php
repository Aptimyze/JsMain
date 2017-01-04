<?php

class VSPLoadCheck
{
	//This function fetches dppSuggestion values to be shown and returns it to the calling function
	public function set($viewerProfileId,$viewedProfileId)
	{
		$PROFILE_VIEW_SIMILAR_PROFILE_LOAD = new PROFILE_VIEW_SIMILAR_PROFILE_LOAD;
   		$result = $PROFILE_VIEW_SIMILAR_PROFILE_LOAD->set($viewerProfileId,$viewedProfileId);
   		return $result;	
	}
}
?>