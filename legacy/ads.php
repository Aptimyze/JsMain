<?php

$generic_ads[0]='Indian travel auctions airlines hotel packages cruise';
$generic_ads[1]='Tours Indian holiday airtickets auctions deals cruise';
$metros[0]='Jewellery Indian wedding planner astrology janam patri';
$metros[1]='Flowers Indian gifts shopping handycam apparels ';
$metros[2]='Indian consumer electronics handycam television DVD';
$nri[0]='Flowers designer clothes money transfer calling cards';
$nri[1]='Cosmetics astrology airtickets holidays';
$numofads['generic']=sizeof($generic_ads)-1;
$numofads['metros']=sizeof($metros)-1;
$numofads['nri']=sizeof($nri)-1;


function ads($CITY,$COUNTRY)
{

global $smarty;

$keyword=google_adsense($CITY,$COUNTRY);
//$keyword=google_adsense('','51');

$num_of_radlinks = 3;
$num_of_ads = 0;
//$keyword="delhi airlines mumbai airlines delhi hotels";
//$path_image_js="http://imageads0.googleadservices.com/pagead/ads?client=ca-jeevansathi_newsletters_kw&format=468x60_abgnc_img&output=png&backfill=1&url=null&kw_type=broad&kw=delhi%20airlines%20mumbai%20airlines%20delhi%20hotels&channel=matchalerts&hl=en&adsafe=high&color_bg=FFFFFF&color_border=999999&color_link=0000FF&color_text=000000&color_url=0066FF&good_ads_only=on&cuid=A86FRMyICojoOI7FvNYP&issue=20050421&r=1kw_type=broad&kw=delhi%20airlines%20mumbai%20airlines%20delhi%20hotels&channel=matchalerts&hl=en&adsafe=high&color_bg=FFFFFF&color_border=999999&color_link=0000FF&color_text=000000&color_url=0066FF&good_ads_only=on&cuid=A86FRMyICojoOI7FvNYP&issue=20050421&r=1";

$path="http://pagead2.googlesyndication.com/pagead/ads?client=ca-jeevansathi_radlinks_xml&adsafe=high&url=".rawurlencode($keyword)."&hl=en&output=xml&num_ads=$num_of_ads&num_radlinks=$num_of_radlinks&max_radlink_len=25&rl_filtering=high&rl_mode=default";
                                                                                                                             
$fp = @fopen($path,"r");
$text = @fread($fp,2048);
@fclose($fp);
                                                                                                                             
                                                                                                                             
$parser = xml_parser_create();
xml_parse_into_struct($parser,$text,$vals,$index);
xml_parser_free($parser);
                                                                                                                             
$norad = 0;

for ($ii=0;$ii<$num_of_radlinks;$ii++)
{
  $term_location = $index[TERM][$ii]; 
  if(trim($vals[$term_location][value]) == '' && $ii==0)
  {
        $norad = 1; //echo " NORAD ";
        break;
  }


	if($_SERVER["SERVER_NAME"]=="devjs.infoedge.com")
		$RADLINKS .= "<td align='left'><a href='http://172.16.3.185/profile/google_rad_links.php?kw=".rawurlencode($vals[$term_location][value])."'><font style='font-family:Arial; font-size:12px; font-color:#0000FF'><b>".$vals[$term_location][value]."</b></a></td>&nbsp;"; 
	else
		$RADLINKS .= "<td align='left'><a href='http://www.jeevansathi.com/profile/google_rad_links.php?kw=".rawurlencode($vals[$term_location][value])."'><font style='font-family:Arial; font-size:12px; font-color:#0000FF'><b>".$vals[$term_location][value]."</b></a></td>&nbsp;";

  //$RADLINKS .= "<td align='left'><a href='http://172.16.3.185/gaurav/tryresults_jscriptCS.php?kw=".rawurlencode($vals[$term_location][value])."'><font style='font-family:Arial; font-size:12px; font-color:#0000FF'><b>".$vals[$term_location][value]."</b></a></td>&nbsp;"; 
}

if($norad != 1)
$pregoogle = "<br><center><table width='600' align='center' cellpadding='0' cellspacing='0' border='0'><tr style='font-size:12px;'><td width='18%' align='center' >Ads by Google</td><td width='7%'></td>".$RADLINKS."</tr></table><br>"; 

$google=$pregoogle."<center><table><tr><td><map name=\"google_ad_map_OjJxQuzROKa6ON3byMQN\"><area shape=\"rect\" href=\"http://pagead2.googlesyndication.com/pagead/userfeedback\" coords=\"5,4,71,15\" target=\"_blank\"><area shape=\"rect\" href=\"http://imageads.googleadservices.com/pagead/imgclick/OjJxQuzROKa6ON3byMQN/20050428?pos=0\" coords=\"1,20,232,58\" target=\"_blank\"><area shape=\"rect\" href=\"http://imageads.googleadservices.com/pagead/imgclick/OjJxQuzROKa6ON3byMQN/20050428?pos=1\" coords=\"232,20,463,58\" target=\"_blank\"></map><img src=\"http://imageads.googleadservices.com/pagead/ads?client=ca-jeevansathi_newsletters_kw&format=468x60_abgnc_img&output=png&backfill=1&url=null&kw_type=broad&kw=".rawurlencode($keyword)."&channel=match_alert&hl=en&adsafe=high&color_bg=FFFFFF&color_border=999999&color_link=0000FF&color_text=000000&color_url=0066FF&good_ads_only=on&cuid=OjJxQuzROKa6ON3byMQN&issue=20050428\"usemap=\"#google_ad_map_OjJxQuzROKa6ON3byMQN\" border=0></td></tr></table></center>"; 

$smarty->assign("RADLINK",$google);

}

function givekeyword_india($city)
{
        global $generic_ads,$metros,$nri,$numofads;

	$generic_ads[0]='Indian travel auctions airlines hotel packages cruise';
	$generic_ads[1]='Tours Indian holiday airtickets auctions deals cruise';
	$metros[0]='Jewellery Indian wedding planner astrology janam patri';
	$metros[1]='Flowers Indian gifts shopping handycam apparels ';
	$metros[2]='Indian consumer electronics handycam television DVD';
	$nri[0]='Flowers designer clothes money transfer calling cards';
	$nri[1]='Cosmetics astrology airtickets holidays';
	$numofads['generic']=sizeof($generic_ads)-1;
	$numofads['metros']=sizeof($metros)-1;
	$numofads['nri']=sizeof($nri)-1;

        switch($city)
        {
                case 'DE00' ://delhi
                case 'MH04' ://mumbai
                case 'KA02' ://bangalore
                case 'WB05' ://kolkata
                case 'TN02' ://chennai
                        $citymetro=rand(0,$numofads['metros']);
                        $keywords_for_adsense=$metros[$citymetro];
                        break;
                default ://india
                        $city_non_metro=rand(0,$numofads['generic']);
                        $keywords_for_adsense=$generic_ads[$city_non_metro];
                        break;
        }
        //echo "keyword=".$keywords_for_adsense;
        return $keywords_for_adsense;
}

function google_adsense($city,$country)
{
	
        //if city is a metro as defined above:
        //echo "city=".$city."\n country= ".$country."<br>";
        global $smarty;
        global $nri,$numofads;
	$generic_ads[0]='Indian travel auctions airlines hotel packages cruise';
	$generic_ads[1]='Tours Indian holiday airtickets auctions deals cruise';
	$metros[0]='Jewellery Indian wedding planner astrology janam patri';
	$metros[1]='Flowers Indian gifts shopping handycam apparels ';
	$metros[2]='Indian consumer electronics handycam television DVD';
	$nri[0]='Flowers designer clothes money transfer calling cards';
	$nri[1]='Cosmetics astrology airtickets holidays';
	$numofads['generic']=sizeof($generic_ads)-1;
	$numofads['metros']=sizeof($metros)-1;
	$numofads['nri']=sizeof($nri)-1;

	/*print_r($nri);*/
        if($country==51)
        {
                $keywords_for_adsense=givekeyword_india($city);
        }
        else
        {
                $nri_ads=rand(0,$numofads['nri']);
                $keywords_for_adsense=$nri[$nri_ads];
        }
        $keywords_for_adsense=str_replace(" ",'%20',$keywords_for_adsense);
        $keywords_for_adsense=str_replace("'",'%27',$keywords_for_adsense);
        return $keywords_for_adsense;
        //$smarty->assign('keywords_for_adsense',$keywords_for_adsense);
}


?>
