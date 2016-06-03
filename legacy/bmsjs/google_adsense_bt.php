<?

$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");

include_once("./includes/bms_connect.php");
$AFS = 1;

include("ggl_keyword.php");
$names=explode(',',$keyword);
if(count($names)==1)
        $keyword=$names[0];
else
{
        $ranNum=rand(0,count($names)-1);
        $keyword=$names[$ranNum];
}
//echo $keyword;
echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><style type=\"text/css\">";
echo ".wads{font:normal 11px Verdana,Arial;color:#fff;text-decoration:none;}";
echo ".vads{font:normal 11px Verdana,Arial;color:#000;text-decoration:none;line-height:16px;}";
echo ".vads a{font:normal 11px Verdana,Arial;color:#000;text-decoration:none;}";
echo ".bb{border-bottom:1px solid #E6E9C5}";
echo" .gads{font:normal 11px Verdana,Arial;text-decoration:underline;color:#003498}";
echo ".gads a{font:normal 11px Verdana,Arial;text-decoration:underline;color:#003498}";
echo ".bads{font:normal 11px Verdana,Arial;color:#7D843A;text-decoration:none;}";
echo ".bads a{font:normal 11px Verdana,Arial;color:#7D843A;text-decoration:none;}";
echo "body { margin-left: 0px; margin-top: 0px; margin-right: 0px;}";
echo "</style></head>";
if (!$keyword)
{
       echo "<script language=\"javascript\" src=\"http://ser4.jeevansathi.com/banner/google/google_keywords.js\"> </script>";
}
$google_bt=1;
include("google_adsense.php");
echo "</html>";
//$smarty->display("./$_TPLPATH/google_adsense_bt.htm");
?>
