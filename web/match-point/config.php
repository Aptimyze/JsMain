<?php
$whichMachine='local';
$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot.'/match-point';
include(JsConstants::$smartyDir);
$smarty=new Smarty;
$smarty->template_dir    =  JsConstants::$docRoot.'/smarty/templates/match-point/';
$smarty->compile_dir    =  JsConstants::$docRoot.'/smarty/templates_c';

//$smarty->relative_dir="match-point/";
$ip=$_SERVER["HTTP_HOST"];
$SITE_URL="http://$ip";

$smarty->assign("SITE_URL",$SITE_URL);

$IMG_URL2="http://$ip";
$IMG_URL="http://$ip";

$smarty->assign("IMG_URL",$IMG_URL);
$smarty->assign("IMG_URL2",$IMG_URL2);
$smarty->assign("SER6_URL",$IMG_URL2);
//$memcache=new Memcache;

?>
