<?php
/**
* Smarty plugin
* 
* @package Smarty
* @subpackage PluginsModifier
*/

/**
* Smarty html entity decode modifier plugin
* 
* Type:     modifier<br>
* Name:     decodevar<br>
* Purpose:  simple html entity decode

* @author nikhil
* @param string $ 
* @return string 
*/
function smarty_modifier_decodevar($string)
{
	return htmlspecialchars_decode($string,ENT_QUOTES);
}
