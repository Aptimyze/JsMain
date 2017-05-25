<?php
/* This script contains all Javascript and CSS files and included in connect.inc to avoide caching problem */
if(class_exists('sfConfig')) include_once (sfConfig::get("sf_web_dir")."/profile/commonfile_functions.php"); //Symfony
else include_once ("commonfile_functions.php");
$JAVASCRIPT = getJavascript();
$CSS = getCss();
if($smarty)
{
        assignVars($JAVASCRIPT,$CSS,$smarty);
}

