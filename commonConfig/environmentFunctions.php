<?php

if(!strstr($_SERVER["PHP_SELF"],"symfony_index.php") && !strstr($_SERVER["PHP_SELF"],"operations.php") && !strstr($_SERVER["PHP_SELF"],"operations_dev.php") )
{
	foreach (array('_GET', '_POST') as $_SG) {
		foreach ($$_SG as $_SGK => $_SGV) {
			$$_SGK = quote_smart($_SGV);
		}
	}
}

function quote_smart($value)
{
	if( is_array($value) ) {
		return array_map("quote_smart", $value);
	} else {
			return addslashes($value);
	}
}

function JSstrToTime($timeString,$var="")
{
	if( strtotime($timeString)!=strtotime("0000-00-00") )
	{
		if($var)
			return strtotime($timeString,$var);
		else
			return strtotime($timeString);
	}
	else
		return false;
}

?>
