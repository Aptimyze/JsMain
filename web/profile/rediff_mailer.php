<?php

$path = $_SERVER['DOCUMENT_ROOT'];

include_once($path."/profile/connect.inc");
$db = connect_db();


$smarty->assign("gender",$gender);
$smarty->assign("name_of_user",$name_of_user);
$smarty->assign("email",$email);
$smarty->assign("day",$day);
$smarty->assign("month",$month);
$smarty->assign("year",$year);

$smarty->display("rediff_mailer.htm");

?>
