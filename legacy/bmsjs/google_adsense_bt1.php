<?
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
echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link rel=\"stylesheet\" href=\"http://www.jeevansathi.com/P/I/styles.php\" type=\"text/css\"><link rel=\"stylesheet\" href=\"http://www.jeevansathi.com/P/IN/styles.php\" type=\"text/css\"><style type=\"text/css\">";
echo ".blue a{ color:#2400FF;text-decoration:none;}";
echo ".blue a:visited{ color:#2400FF;text-decoration:none;}";
echo ".black a{ color:#000000;text-decoration:none;}";
echo ".black a:visited{ color:#000000;text-decoration:none;}";
echo ".green a{ color:#737F08;text-decoration:underline;}";
echo ".green a:visited{ color:#737F08;text-decoration:underline;}";
echo "</style></head>";
if (!$keyword)
{
       echo "<script language=\"javascript\" src=\"http://ser4.jeevansathi.com/banner/google/google_keywords.js\"> </script>";
}
$google_bt=1;
include("google_adsense1.php");
echo "</html>";
//$smarty->display("./$_TPLPATH/google_adsense_bt.htm");
?>
