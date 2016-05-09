<?php
/**
 * 
 * The Profile Completion Factory 
 * Returns Instance of ApiProfileCompletionScore or Instance of ProfileCompletionScore
 * 
 * Example how to call
 * <code>
 * $cScoreObject = ProfileCompletionFactory::getInstance("API",$objProfile,null); <br>
 * $cScoreObject = ProfileCompletionFactory::getInstance(null,null,$ProfileID);  <br>
 * </code>
 * 
 * @package jeevansathi
 * @subpackage ProfileCompletion
 * @author Kunal Verma
 * @created 30th Dec 2013
 */

/**
 * Factory Class For ProfileCompletion Instances
 * @package ProfileCompletion
 * @author  Kunal Verma
 */
class ProfileCompletionFactory
{
	 /**
	 * Static Function to Get Instance of ApiProfileCompletionScore OR ProfileCompletionScore
	 * @param $szType 		: String
	 * @param $objProfile 	: Profile object
	 * @param $iProfileID 	: Integer
	 * @return Object of ApiProfileCompletionScore OR ProfileCompletionScore
	 * @access public static function
	 * @throws jsException
	 */
	public static function getInstance($szType,Profile $objProfile=null,$iProfileID=null)
	{
		if($szType == "API")
		{
			if($objProfile && $objProfile instanceof Profile)
				return new ApiProfileCompletionScore($objProfile);
			else if($iProfileID && is_numeric($iProfileID))
				return new ApiProfileCompletionScore($iProfileID);
			else
			{
				throw new jsException("","Either Specify ProfileObject or ProfileID. Both cannot be null.");
			}
		}
		else
		{
			if($objProfile && $objProfile instanceof Profile)
				return new ProfileCompletionScore($objProfile);
			else if($iProfileID && is_numeric($iProfileID))
				return new ProfileCompletionScore($iProfileID);
			else
			{
				throw new jsException("","Either Specify ProfileObject or ProfileID. Both cannot be null.");
			}
		}
		
	}
}
?>
