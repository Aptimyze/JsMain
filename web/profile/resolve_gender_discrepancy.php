<?php
require_once(JsConstants::$docRoot."/ClickTale/ClickTaleTop.php");
include("connect.inc");
$smarty->display('resolve_discrepancy.htm');
require_once(JsConstants::$docRoot."/ClickTale/ClickTaleBottom.php");
?>
