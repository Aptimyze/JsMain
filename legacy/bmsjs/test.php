<?
/*print_r($_COOKIE);
if(isset($_COOKIE['color']))
{
        echo "here";
        echo $_COOKIE['color'];
}*/
include("includes/bms_connect.php");

$smarty->assign("profileid","156193");
$smarty->assign("zone","20");
$smarty->assign("subzone","1");
$smarty->display("./$_TPLPATH/test.htm");

?>
