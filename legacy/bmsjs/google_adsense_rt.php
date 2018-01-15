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
echo ".blue a{ color:#2400FF;text-decoration:none;word-wrap: break-word;}";
echo ".blue a:visited{ color:#2400FF;text-decoration:none;word-wrap: break-word;}";
echo ".black a{ color:#000000;text-decoration:none;word-wrap: break-word;}";
echo ".black a:visited{ color:#000000;text-decoration:none;word-wrap: break-word;}";
echo ".green a{ color:#737F08;text-decoration:underline;word-wrap: break-word;}";
echo ".green a:visited{ color:#737F08;text-decoration:underline;word-wrap: break-word;}";
echo "body { margin-left: 0px; margin-top: 0px; margin-right: 0px;}";
echo "</style></head><div style=\"margin: 0px auto; padding: 0px; width: 100%; background-color:#FFFFFF\">";
if (!$keyword)
{
       echo "<script language=\"javascript\" src=\"http://ser4.jeevansathi.com/banner/google/google_keywords.js\"> </script>";
}
$google_rt=1;
include("google_adsense.php");
echo "</div></html>";

//$smarty->display("./$_TPLPATH/google_adsense_rt.htm");
?>
