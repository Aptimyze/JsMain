<?php
$num_of_ads=8;
$num_of_radlinks=1;
$num_of_ads_rt=5;               //Number of Ads requested from Google
if(!$p)
        $p=1;
$path="http://www.google.com/search?client=jeevansathi&q=".rawurlencode($keyword)."&output=xml&hl=en&adsafe=high&num=0&ad=w8&adpage=$p&adtest=off&ip=198.65.112.205&useragent=Mozilla%2F4%2E51+%5Ben%5D+%28Win98%3B+U%29";
$text="";
$fp = @fopen($path,"r");
while($txt = @fread($fp,1024))
        $text .= $txt;
@fclose($fp);
function formatLine($str)
{
        $len=strlen($str);
        $tr_len=28;
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

function formatLine1($str)
{
        $len=strlen($str);
        $tr_len=140;
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
xml_parse_into_struct($parser,$text,$vals,$index);//Parse the result set into array
xml_parser_free($parser);
$GOOGLEPRE="<div style=\"width: 170px; border:1px solid #BBC371\" class=\"blue\"><div style=\"background-color:BBC371; color:#FFFFFF; font: 11px solid verdana, verdana, arial; padding: 2px\"><a href=\"http://services.google.com/feedback/abg?url=http://corp.naukri.com/mynaukri/mn_newsmartsearch.php&hl=en&client=ca-jeevansathi-site_js";
 
//$GOOGLEPRE="<table width=\"125\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" style=\"border:1px solid #F5A77C\"><tr ><td height=\"20\" bgcolor=\"#B02C2C\" class=\"wads\"><a href=\"http://services.google.com/feedback/abg?url=http://corp.naukri.com/mynaukri/mn_newsmartsearch.php&hl=en&client=ca-jeevansathi-site_js";

//Ads by google</td></tr>";
 
//$GOOGLEPRE="<div style=\"margin: 0px auto; padding: 0px; width: 100%; background-color: rgb(255, 255, 255);\">&nbsp;<a href=\"http://services.google.com/feedback/abg?url=http://corp.naukri.com/mynaukri/mn_newsmartsearch.php&hl=en&client=ca-naukri_js";
 
 
/*$GOOGLEPRE="<br><table width=\"120\" height=\"240\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#FFE5CC\"><tr><td><table width=\"120\" height=\"240\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"sblack\" style=\"line-height:16px; padding-left:2px \" bgcolor=\"#FFFFFF\"><tr bgcolor=\"#FFE5CC\"><td><div align=\"center\"><font color=\"#999999\"><b>Ads by google</b></font></div></td></tr>";*/
 
 
//$GOOGLEPRE1="<div class=\"spacer\">&nbsp;</div><table style=\"border: 1px solid rgb(208, 215, 222);\" align=\"left\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\"><tbody><tr><td class=\"wads\" bgcolor=\"#095cc2\" height=\"20\" >&nbsp;Ads by google</td></tr>";
 
/*$GOOGLEPRE1="<table width=\"728\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#FFE5CC\"><tr><td><table width=\"728\" border=\"0\" align=\"center\" cellpadding=\"2\" cellspacing=\"0\" bgcolor=\"#FFFFFF\"><tr><td width=\"10\" bgcolor=\"#FFE5CC\">&nbsp;</td>
<td width=\"718\" bgcolor=\"#FFE5CC\"><span style=\"color: #999999\"><font style=\"font-family:arial; font-size:11px; font-weight:bold; color:#999999\">
<b>Ads By Google</b>
</font></span></td></tr><tr><td ></td><td ></td></tr><tr><td></td><td height=\"8\"></td></tr><tr><td>&nbsp;</td><td><font style=\"font-family:Arial; font-size:12px;\">";*/
 
$GOOGLEPRE1="<div style=\"width:728px; border:1px solid #BBC371\" class=\"blue\"><div style=\"background-color:#BBC371; color:#FFFFFF; font:11px solid verdana,arial; padding:2px\">Ads by Google</div><div style=\"line-height:6px; clear:both \">&nbsp;</div>"; 
//$GOOGLEPRE1="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" style=\"border:1px solid #F5A77C\"><tr ><td height=\"20\" bgcolor=\"#B02C2C\" class=\"wads\">Ads by google</td></tr>"; 
 
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
                $GOOGLEPRE .="&amp;adU=".rawurlencode($adU)."&amp;adT=".rawurlencode($line1);
                //$GOOGLEAD .= "<div class=\"feat1\"><span class=\"feat2\"><a href=\"".$Link."\" onmouseover=\"window.status='$adU';return true;\" onmouseout=\"window.status='';return true;\" style=\"color: rgb(0, 0, 255);\" target=\"_blank\">".formatLine(strip_tags($line1))."</a></span><span class=\"feat4\"><a href=\"$Link\"onmouseover=\"window.status='$adU';return true;\" onmouseout=\"window.status='';return true;\" class=\"feat4\" target=\"_blank\"><br>".formatLine(strip_tags($line2))."</a></span><br><a href=\"$Link\"onmouseover=\"window.status='$adU';return true;\" onmouseout=\"window.status='';return true;\" class=\"linkcolorgreen\" target=\"_blank\"><span class=\"gadr\">".formatLine($adU)."</span></a></div>";
                //$GOOGLEAD .= "<div class=\"feat1\"><span class=\"feat2\"><a href=\"".$Link."\" onmouseover=\"window.status='$adU';return true;\" onmouseout=\"window.status='';return true;\" style=\"color: rgb(0, 0, 255);\" target=\"_blank\">".formatLine(strip_tags($line1))."</a></span><span class=\"feat4\"><a href=\"$Link\"onmouseover=\"window.status='$adU';return true;\" onmouseout=\"window.status='';return true;\" class=\"feat4\" target=\"_blank\"><br>".formatLine(strip_tags($line2))."</a></span><br><a href=\"$Link\"onmouseover=\"window.status='$adU';return true;\" onmouseout=\"window.status='';return true;\" class=\"linkcolorgreen\" target=\"_blank\"><span class=\"gadr\">".formatLine($adU)."</span></a></div>";
 
                //$GOOGLEAD.= "<tr><td><FONT style=\"FONT-SIZE: 11px; FONT-FAMILY: Arial\"><a href=\"".$Link."\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\" style=\"color:#990000\"><b>".formatLine(strip_tags($line1))."</b></a></span><br><span class=blacklink><a href=\"$Link\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\">".formatLine(strip_tags($line2))."</a></span><br><span class=blacklinku><a href=\"$Link\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\" \"color:#cc0000\" >".formatLine($adU)."</a></td></tr>";
                $GOOGLEAD.= "<div style=\"font:normal 11px verdana,arial;color:#737F08;padding:2px;margin-left:3px\" class=\"green\"><a href=\"".$Link."\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\">".formatLine(strip_tags($line1))."</a></div><div style=\"font:normal 11px verdana, arial; padding: 2px; margin-left: 3px\"><span class=\"black\"><a href=\"$Link\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\">".formatLine(strip_tags($line2))."</a></span><br><a href=\"$Link\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\">".formatLine($adU)."</a></div><div style=\"line-height:6px; clear:both \">&nbsp;</div>";
                /*$GOOGLEAD.= "<tr><td height=\"50\" class=\"vads bb\"><span class=\"bads\"><a href=\"".$Link."\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\">".formatLine(strip_tags($line1))."</a></span><br><a href=\"$Link\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\">".formatLine(strip_tags($line2))."</a><br><span class=\"gads\"><a href=\"$Link\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\" \"color:#cc0000\" >".formatLine($adU)."</a></span><br></td></tr>";*/
 
        }
 
        else
        {
                if($line1 && $show_bot=="")
                        $show_bot="Y";
                //$GOOGLEAD1 .="<tr><td class=\"vads bb\" height=\"50\"><span class=\"bads\"><a href=\"$Link\" target=\"_blank\">$line1</span><br>$line2<br><span class=\"gads\">$adU</span></a></td></tr>";
                /*$GOOGLEAD1 .="<a href=\"".$Link."\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\" style=\"color:#990000 \" ><b>".formatLine1(strip_tags($line1))."</b></a></span>&nbsp;&nbsp;<span class=blacklink><a href=\"$Link\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\" >".formatLine1(strip_tags($line2))."</a></span>&nbsp;&nbsp;<span class=blacklinku><a href=\"$Link\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\" \"color:#cc0000\" >".formatLine1($adU)."</a><br><br>";*/
                $GOOGLEAD1.="<div style=\"float:left; font:normal 12px arial; color:#737F08; padding:2px; margin-left:3px\" class=\"green\"><a href=\"".$Link."\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\">".formatLine1(strip_tags($line1))."</a></div><div style=\"float:left; font:normal 12px arial;padding:2px\"><span class=\"black\"><a href=\"$Link\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\" >".formatLine1(strip_tags($line2))."</a></span>&nbsp;<a href=\"$Link\" target=\"_new\" onMouseover=\"window.status='$adU'; return true\" onMouseout=\"window.status=''; return true\" >".formatLine1($adU)."</a></div><div style=\"line-height:6px; clear:both\">&nbsp;</div>";
        }
        }
        $i++;
}
                //To Display the google ad
$GOOGLEPRE .="&amp;done=1\" target=\"_blank\">Ads By Google</a></div><div style=\"line-height:4px; clear: both\">&nbsp;</div>";
if($show_rt == "Y" && $google_rt)
        echo $GOOGLEPRE.$GOOGLEAD."</div>"; //HTML
//else
//        echo "&nbsp;";
//echo "|XX|XX|";
$GOOGLEAD1.="</div>";
 
if($show_bot=="Y" && $google_bt)
        echo $GOOGLEPRE1.$GOOGLEAD1;
//else
//        echo "&nbsp;";
die;
 
 
?>
