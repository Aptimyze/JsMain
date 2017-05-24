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
* Name:     readmore<br>
* Purpose:  Placing read more less

* @author nikhil
* @param string $ 
* @return string 
*/
function smarty_modifier_readmore($string,$limit,$divname)
{
	$string=htmlspecialchars_decode($string,ENT_QUOTES);
	$len=strlen($string);
	if($len>$limit)
	{
		$first=substr($string,0,$limit);
		$second=substr($string,$limit,$len);
		$str="<span>".$first."</span><span id='dot_$divname'> ...</span><span id='hide_$divname' style='display:none'>$second </span><a onclick=\"return show_hideinfo('$divname',1)\" title=\"read more\" href='#' class=\" b\" id='readmore_$divname'> Read more </a><a onclick=\"return show_hideinfo('$divname',0)\" title=\" less \" href='#' class=\" b\" id='readless_$divname' style='display:none'> Less</a>";
		return $str;
	}
	else 
		return $string;
	
}
