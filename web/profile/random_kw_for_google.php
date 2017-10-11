<?php
include_once("connect.inc");
function random_kw_generator()
{
	$keywords_ads[0]="Astrologers";
	$keywords_ads[1]="Astroindia";
	$keywords_ads[2]="Delhi airfares";
	$keywords_ads[3]="Chennai airfares";
	$keywords_ads[4]="Mumbai airfares";
	$keywords_ads[5]="Mumbai airlines";
	$keywords_ads[6]="Digicams";
	$keywords_ads[7]="Handycams";
	$keywords_ads[8]="Agra vacations";
	$keywords_ads[9]="Jaipur vacations";
	$keywords_ads[10]="Janampatri";
	$keywords_ads[11]="Saree";
	$keywords_ads[12]="Indian recepies";
	$keywords_ads[13]="Indian car";

	$num=sizeof($keywords_ads)-1;
	$random=rand(0,13);
	$keyword=$keywords_ads[$random];
	return("$keyword");
}
?>
