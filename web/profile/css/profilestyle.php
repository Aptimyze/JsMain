<?php
	include("common.php");
?>
html, body{margin:0; padding:0;}
#container{
width: 100%;
margin: 0px auto;
}
<?php
/*
if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
        echo "#wrapper{margin:20px;width:99%}\n";
else
        echo "#wrapper{margin:20px;}\n";
*/
?>
#wrapper{width:99%}
<?php
if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
	echo "div.pad20{padding:20px;width:100%;}\n";
else
	echo "div.pad20{padding:20px;}\n";
?>

#footer{
clear: both;
margin: 0;
padding: 2px;
}
#top{padding:10px 0px 0px 20px; margin-bottom:10px}
div.msg{ float:left; width:100%; background-image:url(<?=$SER6_URL?>/profile/images/profile-preview-bg.gif); background-position:bottom; background-repeat:repeat-x;}
div.msg1{ float:left; width:69%; padding:5px 5px 5px 20px;border-right:1px dotted #8C8C8C;}
div.msg2{ float:left; width:24%; padding:5px 0px 5px 10px;}
div.msgot{ float:left; width:100%; background-color:#FFF5AB;}
div.msg3{ padding:10px 0px 0px 20px;}

div.rrow{ float:left; width:100%;background-image:url(http://ser4.jeevansathi.com/profile/images/bg_pro_row.gif);  clear:both; line-height:13px}
div.rrow1{ float:left; width:100%;background-image:url(http://ser4.jeevansathi.com/profile/images/bg_pro_row1.gif);  clear:both; line-height:13px}
div.potr{ float:left; width:100%;}
div.psts{ float:left; padding:5px; width:240px; background-color:#F6F4E7; font:normal 11px verdana,Arial; height:100%; }
div.psts1{ margin:50px 60px 0px 300px; font:normal 11px verdana,Arial;}
div.pstslft{ float:left; margin:3px 2px 0 2px; width:90px;}
div.pstsrgt{ float:left; margin:3px 2px 0 2px; width:135px;}

<?php
if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
        echo "div.detail{padding:0px; font:normal 11px verdana,Arial; margin:0px 130px 0px 0px;width:99%}\n";
else
        echo "div.detail{padding:0px;font:normal 11px verdana,Arial; margin:0px 130px 0px 0px;}\n";
?>
div.detail_off{padding:0px; font:normal 11px verdana,Arial; margin:0px 0px 0px 0px;}
div.wrapl{ float:left; width:48%;}
div.wrapr{ float:left; width:52%;}
div.deconl{ float:left; width:35%; padding:0 0 0 2px;}
div.deconr{ float:left; width:60%; padding:0 0 0 2px;}
div.sep{float:left; width:1%;}
div.deconl2{ float:left; width:23%; padding:0px 0px 0px 3px; }
div.deconr2{ float:left; width:74%; padding:0px 0px 0px 3px;}


div.ban{ float:right;font:normal 11px verdana,Arial; width:120px;}
div.decontainer{float:left; width:99%; margin-top:10px; border:1px solid #E6C4AC; padding-bottom:6px}
div.decontainer1{float:left; width:99%; margin-top:10px;padding-bottom:6px}
/* for print profile */
div.decontainerp{float:left; width:99%; border:1px solid #E6C4AC;}
div.dewrap{ float:left; width:50%;}
div.dewrap2{ float:left; width:100%;}
div.dehead{width:100%; background-color:#E8E8E8; padding:3px 0 3px 0}

/*LINKS FONTS*/
.bluelink a { color:#003498; text-decoration:underline;}
.bluelink a:visited{ color:#003498;text-decoration:underline;} 

.wten{font:normal 10px verdana,Arial; color:#ffffff}
.wele{font:normal 11px verdana,Arial; color:#ffffff}
.mtenb{font:bold 10px verdana,Arial; color:#CC0000;}
.meleb{font:bold 11px verdana,Arial; color:#CC0000;}
.msixb{font:bold 16px verdana,Arial; color:#CC0000;}
.bten{font:normal 10px verdana,Arial;}
textarea { font:normal 10px verdana,Arial; color:#999999;}

input { font:normal 10px verdana,Arial; color:#999999;}

div.spacer4 {clear: both; line-height:4px}
.bten2{font:normal 10px verdana,Arial; color:#999999}
.bele{font:normal 11px verdana,Arial;}
.btwe{font:normal 12px verdana,Arial;}
.greyele{font:normal 11px verdana,Arial; color:#666666}
.greytwe{font:normal 12px verdana,Arial; color:#666666}
.bgg3{ background-color:#e5e5e5}

.buttonor{ font:bold 12px verdana,Arial; color:#ffffff; cursor:pointer; border:1px solid #874201; background-image:url(http://ser4.jeevansathi.com/profile/images/bgor.gif);}
/*END LINKS FONTS*/

div.leftflow0{ float:left; margin-left:0px;}
div.leftflow{ float:left; margin-left:5px;}
div.rightflow{ float:right; margin-right:5px;}
.pad2{ padding:2px 2px 2px 2px}
.pad4{ padding:4px 4px 4px 4px}
div.spacer10 {clear: both; line-height:10px}
div.spacer15 {clear: both; line-height:10px}
div.spacer3 {clear: both; line-height:3px}
div.spacer1 {clear: both; line-height:1px}
div.spacerg{ background-color:#e7e7e7; border-bottom:1px solid #c2c2c2; clear: both; line-height:5px}

.holder{margin-top:5px; float:left; width:100%}
.holder1{ float:left; width:45%;line-height:18px; padding-bottom:20px}
.check{ vertical-align:middle;}
.textbox {FONT-SIZE: 10px; FONT-FAMILY: verdana,Arial;color:#000000;}

/*CORNERS BAR*/
.roundcont {background: url(<?=$SER6_URL?>/profile/images/pp-bg.gif); background-position:center; background-repeat:repeat-x; color: #53541A; font:normal 11px verdana,Arial;}
.roundcont p {margin: 0px; padding-left:10px;}
.roundtop { background: url(<?=$SER6_URL?>/profile/images/pp-tr.gif) no-repeat top right; }
.roundbottom {background: url(<?=$SER6_URL?>/profile/images/pp-br.gif) no-repeat bottom right; }
img.corner {width: 4px;height: 4px;border: none;display: block !important;}

.roundcont1 {width: 99%;background-color: #DAD094;color: #53541A; font:normal 11px verdana,Arial;}
.roundcont1 p {margin: 0 5px;}
.roundtop1 { background: url(http://ser4.jeevansathi.com/profile/images/tr1.gif) no-repeat top right; }
.roundbottom1 {background: url(http://ser4.jeevansathi.com/profile/images/br1.gif) no-repeat top right; }
img.corner1 {width: 4px;height: 4px;border: none;display: block !important;}
/*END CORNERS*/

/*URL BUTTON*/
.hlinkbutton {font-family: Verdana, Arial;font-size: 10px; cursor:pointer; color: #0000FF;text-decoration: underline;background-color: #F6F4E7;border: 0px none #F6F4E7;}

/*ROUNDED CORNERS WITH BORDERS*/
div.b1{background: url(http://ser4.jeevansathi.com/profile/images/box.gif) top left no-repeat; width: 400px;}
div.b2{background: url(http://ser4.jeevansathi.com/profile/images/box.gif) top right no-repeat; margin-left: 7px; padding-top: 7px;}
div.b3{background: url(http://ser4.jeevansathi.com/profile/images/box.gif) bottom right no-repeat;}
div.b4{background: url(http://ser4.jeevansathi.com/profile/images/box.gif) bottom left no-repeat; margin: 0 7px 0 -7px; padding: 0 0 7px 7px;}


/* tooltip */
div.ttip{
width:200px; background:#FDFBE6; border:1px solid #757575; color:#857E33; padding:4px; position:absolute; left:120px; top:330px; Z-INDEX: 1500; VISIBILITY: hidden; font:normal 11px verdana,Arial
                                                                                                                             
}

/* photo trail css*/
#trailimageid
{
font-size: 0.75em;
position: absolute;
display: none;
left: 0px;
top: 0px;
width: 170px;
height: 220px;
z-index: 200;
}





