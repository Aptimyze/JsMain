<?php
include($_SMARTYPATHS);
/**
* All the smarty related actions/functions are performed through this class.
*/
class UserSmarty extends Smarty
{
	/**
	* This function is used to assign a value from php to htm.
	* @param string $to Variable name to which the value should be assigned.
	* @param string $from Variable/value to be assigned to $to variable.
	*/
	public function assignValue($to,$from)
	{
		return $this->assign($to,$from);
	}

	/**
	* This function is used to display a template.
	* @param string $template The template to be displayed.
	*/
	public function displayTemplate($template)
	{
		return $this->display($template);
	}

	/**
	* This function is used to fetch a template.
	* @param string $template The template to be fetched.
	*/
	public function fetchTemplate($template)
	{
		return $this->fetch($template);
	}
}
?>
