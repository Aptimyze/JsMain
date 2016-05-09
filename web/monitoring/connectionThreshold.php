<?php
include_once(JsConstants::$alertDocRoot."/jsadmin/lock.php");
get_lock("/tmp/connectionThreshold");
while(1)
{
	$php5=JsConstants::$php5path; //live php5
	$cronDocRoot = JsConstants::$cronDocRoot;
	passthru("$php5 $cronDocRoot/symfony monitoring:connectionThreshold");
	sleep(2);
	
}
release_lock("/tmp/connectionThreshold");
?>
