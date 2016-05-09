<?php
include_once(JsConstants::$smartyDir);
$smarty=new Smarty;
$smarty->setTemplateDir(JsConstants::$docRoot."/smarty/templates/jeevansathi");
$smarty->setCompileDir(JsConstants::$docRoot."/smarty/templates_c");
$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;

$SITE_URL=JsConstants::$siteUrl;
$smarty->assign("SITE_URL",$SITE_URL);
$SSL_SITE_URL=JsConstants::$ssl_siteUrl;
$smarty->assign("SSL_SITE_URL",$SSL_SITE_URL);

$IMG_URL=JsConstants::$imgUrl;
$IMG_URL2=JsConstants::$imgUrl2;
$smarty->assign("bmsVariable",JsConstants::$bmsUrl);
$smarty->assign("IMG_URL",$IMG_URL);
$smarty->assign("IMG_URL2",$IMG_URL2);

$CHAT_URL=JsConstants::$chatIp;
$smarty->assign("CHAT_URL",$CHAT_URL);

$REG_IMG="$SITE_URL/profile/images/reg";
$smarty->assign("REG_IMG",$REG_IMG);

$smarty->assign("JQUERY_JS",JsConstants::$jquery);
?>
