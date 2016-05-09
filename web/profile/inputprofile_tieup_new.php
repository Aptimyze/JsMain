<?php
include_once("connect.inc");
unset($get_post);
if(is_array($_GET))
{
        foreach($_GET as $key => $value)
                $get_post[] = "$key=$value";
}
if(is_array($_POST))
{
        foreach($_POST as $key => $value)
                $get_post[] = "$key=$value";
}
if(is_array($get_post))
        $get_post_string = @implode("&",$get_post);

header('Location:'.$SITE_URL.'/profile/registration_pg1.php?'.$get_post_string);
unset($get_post_string);
die;
?>
