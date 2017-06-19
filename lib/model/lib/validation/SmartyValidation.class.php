<?php
/**
 * @brief This class list the possible validation of smarty
 * @author Lavesh Rawat
 */
class SmartyValidation
{
	/*
	* This function will check for syntax errro in html file
	* @param $smarty object of smarty
	* @param $template complete template path.
	* @return 'E' on error in template
	*/	
	public static function isSyntaxError($smarty,$template)
	{	
		try 
		{
			$smarty->fetch($template);
		}
		catch (Exception $e) 
		{
			return 'E';
		}
		return 'N';
	}
}
?>
