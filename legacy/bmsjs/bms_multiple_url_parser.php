<?php
include ("includes/bms_connect.php");

if ($submit)
{
	$f = fopen($url,"r");
	$fp = file($url);
	if($f)
	{
		$x = 0;
		//$path = "/usr/local/apache/sites/jeevansathi.com/htdocs/bmsjs/banners/";
		$path = JsConstants::$bmsDocRoot."/bmsjs/banners/temp/";
		$Adname = "ieplAds.".$zone."_AdID".$banner.".htm";
		$bannername = $path.$Adname;
		$fp1 = fopen($bannername,"w+");
		foreach ($fp as $line)
		{
			if($domain = strstr($line,"href="))
			{
				$a = explode("href=\"",$domain);

				$url1 = explode("\"",$a[1]);
				$URL = $url1[0];

				$srch ="href=\"".$URL;
				//$repl = urlencode($URL);
				$multiurl = "|".$banner."|".$URL;

				$replace_str = "href=\"".JsConstants::$bmsUrl."/bmsjs/bms_hits.php?multiurl=$multiurl";
				$new_con = str_replace($srch,$replace_str,$line);

				if ($fp1)
					fwrite($fp1,$new_con);
				else
				{
					echo "No file Pointer.Please try again(1)";
					exit (0);
				}
			}
			else
			{
				if ($fp1)
					fwrite($fp1 , $line);
				else
				{
					echo "No file Pointer.Please try again(2)";
					exit (0);
				}
			}
		}
	}
	fclose($f);
	fclose($fp1);

	$siteurl = JsConstants::$bmsUrl."/bmsjs/banners/temp/";
	$bannerurl = $siteurl.$Adname;
	$smarty->assign("flag","1");
	$smarty->assign("bannerurl",$bannerurl);
        $smarty->display("./$_TPLPATH/bms_multiple_url_parser.htm");
}
else
{
	$smarty->assign("zone",$zone);
	$smarty->assign("banner",$banner);
	$smarty->display("./$_TPLPATH/bms_multiple_url_parser.htm");
}
?>
