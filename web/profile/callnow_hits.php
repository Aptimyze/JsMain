<?php

require_once("connect.inc");
$db=connect_db();
$data=authenticated();
if($data)
	recordCallnowHits('CALLNOW_CLICK');

?>
