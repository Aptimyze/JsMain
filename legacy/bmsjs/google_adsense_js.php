<?php
$num_of_ads=5;
$num_of_radlinks=1;
$num_of_ads_rt=2;               //Number of Ads requested from Google
/*$keyword="java";
if(!$keyword)
{
        $fp=fopen("google_keyword.txt","r");
        while($txt=fgets($fp,1024))
        {
                $txtARR[]=$txt;
        }
        fclose($fp);
        $cnt=0;
        $cnt=count($txtARR);
        $pick=rand(0,$cnt);
        $keyword=$txtARR[$pick];
 
}*/
if(!$p)
        $p=1;
 
$path="http://www.google.com/search?client=jeevansathi&q=".rawurlencode($keyword)."&output=xml&hl=en&adsafe=high&num=0&ad=w8&adpage=$p&adtest=on&ip=198.65.112.205&useragent=Mozilla%2F4%2E51+%5Ben%5D+%28Win98%3B+U%29";
$text="";
$fp = @fopen($path,"r");
while($txt = @fread($fp,1024))
        $text .= $txt;
@fclose($fp);
function formatLine($str)
{
        $len=strlen($str);
        $tr_len=22;
        $iter=$len/$tr_len;
        $i=0;
        while($i<$iter)
        {
                $finalstr.=substr($str,$i*$tr_len,$tr_len)."<br>";
                $i++;
        }
        $finalstr=substr($finalstr,0,strlen($finalstr)-4);
        return $finalstr;
}
 
$parser = xml_parser_create();// Create an XML parser
xml_parse_into_struct($parser,$text,$vals,$index);//Parse the result set into arrays
xml_parser_free($parser);
$GOOGLEPRE="<div style=\"margin: 0px auto; padding: 0px; width: 100%;
background-color: rgb(255, 255, 255);\">&nbsp;<a
href=\"http://services.google.com/feedback/abg?url=http://corp.naukri.com/mynaukri/mn_newsmartsearch.php&hl=en&client=ca-naukri_js";
$GOOGLEPRE1="<div class=\"spacer\">&nbsp;</div><table style=\"border:
1px solid rgb(208, 215, 222);\" align=\"left\" border=\"0\"
cellpadding=\"2\" cellspacing=\"0\" width=\"100%\"><tbody><tr><td
class=\"wads\" bgcolor=\"#095cc2\" height=\"20\" >&nbsp;Sponsored
Links</td></tr>";
 
$i=0;
while($index['AD'][$i])         //Store the start and end index of an ad
{
        if($vals[$index['AD'][$i]]['type'] == "open")
                $OpenArr[]=$index['AD'][$i];
        elseif($vals[$index['AD'][$i]]['type'] == "close")
                $CloseArr[]=$index['AD'][$i];
        $i++;
}
$i=0;
$show_rt="";
$show_bot="";
while($i<$num_of_ads)           //Parse and store the attributes of the advertisements in various variables
{
        $curr_iter=$OpenArr[$i];
        $adU=$vals[$curr_iter]['attributes']['VISIBLE_URL'];
        $Link=$vals[$curr_iter]['attributes']['URL'];
        if($curr_iter)
        {
        while($curr_iter < $CloseArr[$i])
        {
                if($vals[$curr_iter]['tag'] == "LINE1")
                        $line1=$vals[$curr_iter]['value'];
                if($vals[$curr_iter]['tag'] == "LINE2")
                        $line2=$vals[$curr_iter]['value'];
                if($vals[$curr_iter]['tag'] == "LINE3")
                        $line3=$vals[$curr_iter]['value'];
                $curr_iter++;
        }
        if($i<$num_of_ads_rt)
        {
                if($line1 && $show_rt=="")
                        $show_rt="Y";
                $GOOGLEPRE .=
"&amp;adU=".rawurlencode($adU)."&amp;adT=".rawurlencode($line1);
//              $GOOGLEAD .= "<div class=\"feat1\"><span class=\"feat2\"><a href=\"".$Link."\" onmouseover=\"window.status='$adU';return true;\" onmouseout=\"window.status='';return true;\" style=\"color: rgb(0, 0, 255);\" target=\"_blank\">".$line1."</a></span><span class=\"feat4\"><a href=\"$Link\"onmouseover=\"window.status='$adU';return true;\" onmouseout=\"window.status='';return true;\" class=\"feat4\" target=\"_blank\"><br>".$line2."</a></span><br><a href=\"$Link\"onmouseover=\"window.status='$adU';return true;\" onmouseout=\"window.status='';return true;\" class=\"linkcolorgreen\" target=\"_blank\">".formatLine($adU)."</a></div>";
                $GOOGLEAD .= "<div class=\"feat1\"><span
class=\"feat2\"><a href=\"".$Link."\"
onmouseover=\"window.status='$adU';return true;\"
onmouseout=\"window.status='';return true;\" style=\"color: rgb(0, 0,
255);\"
target=\"_blank\">".formatLine(strip_tags($line1))."</a></span><span
class=\"feat4\"><a
href=\"$Link\"onmouseover=\"window.status='$adU';return true;\"
onmouseout=\"window.status='';return true;\" class=\"feat4\"
target=\"_blank\"><br>".formatLine(strip_tags($line2))."</a></span><br><a
href=\"$Link\"onmouseover=\"window.status='$adU';return true;\"
onmouseout=\"window.status='';return true;\" class=\"linkcolorgreen\"
target=\"_blank\"><span
class=\"gadr\">".formatLine($adU)."</span></a></div>";
        }
 
        else
        {
                if($line1 && $show_bot=="")
                        $show_bot="Y";
                $GOOGLEAD1 .="<tr><td class=\"vads bb\"
height=\"50\"><span class=\"bads\"><a href=\"$Link\"
target=\"_blank\">$line1</span><br>$line2<br><span
class=\"gads\">$adU</span></a></td></tr>";
        }
        }
        $i++;
}
                //To Display the google ad
$GOOGLEPRE .="&amp;done=1\" class=\"headingsnew1\"
target=\"_blank\">Sponsored Links</a>";
if($show_rt == "Y")
        echo $GOOGLEPRE.$GOOGLEAD."</div><hr>"; //HTML
else
        echo "&nbsp;";
echo "|XX|XX|";
 
$GOOGLEAD1 .="</tbody></table>";
 
//echo $GOOGLEAD1;
if($show_bot=="Y")
{
	echo "herer";
        echo $GOOGLEPRE1.$GOOGLEAD1."</div>";
}
else
        echo "&nbsp;";
 
die;
 
 
?>

