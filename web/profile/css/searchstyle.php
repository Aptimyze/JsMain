<?php
	include("common.php");
?>
html, body{margin:0; padding:0;}
#container{width: 100%; margin: 0px auto;}
#wrapper {float: left; width: 100%;}

/*HEADER*/
#top{ padding:5px 10px 5px 10px}
#tabrow{width:100%; float:left; background-color:#d3d3d3;}
#tabrowy{width:100%; float:left; height:20px; margin-top:1px;background-image:url("http://ser4.jeevansathi.com/profile/images/ybg.gif")}
/*TABS*/
#maintab {margin-left: 0px;}
#maintab ul { margin: 0; padding: 0; list-style: none; }
#maintab li { float: left; margin: 0 0 0 0; white-space: nowrap; background-image:url(<?=$SER6_URL?>/profile/images/top-nav-bg.gif); background-position:bottom; background-repeat:repeat-x; font: 11px verdana,Arial, Helvetica, sans-serif; font-weight:bold; }
#maintab a { float: left; padding: 0 0 0 9px; text-decoration: none;color: #2673bb;}
#maintab a:hover{ color: #000000;}
#maintab a.current { background-position: 0 -28px; background: url("<?=$SER6_URL?>/profile/images/top-tab-left.gif"); color: #2673bb; }
#maintab a span { float: left;  padding: 5px 9px 4px 0;}
#maintab a:hover span{ cursor: pointer; color:#2673bb; }
#maintab a.current span {background-position: 100% -28px; background: url("<?=$SER6_URL?>/profile/images/top-tab-rite.gif") no-repeat 100% 0; cursor: pointer;}
#maintab a.current:hover span {cursor: default;}
.button-search { font-family:verdana,Arial; color:#ffffff; font-weight:bold; font-size:12px; cursor:pointer; width:62px; height:22px; background-image:url(<?=$SER6_URL?>/profile/images/search-button.gif); background-posttion:bottom; background-repeat:no-repeat; border:0px solid #FF0000; padding-bottom:1px}
/*END TABS*/
/*END HEADER*/

/*QSEARCH*/
#searchblock {width:100%; float:left; background-color:#FFFFFF; padding:0px 0 5px 0; background-image:url("<?=$SER6_URL?>/profile/images/search-bar-bg.gif"); background-position:bottom; background-repeat:repeat-x; height:70px}
div.qsearch0{ float:left; height:100px; width:2%;}
div.qsearch1{float:left; margin:1px 0 2px 5px; width:27%; height:18px; }
div.qsearch2{float:left; margin:1px 0 2px 5px; width:40%; height:18px;}
div.qsearch3{float:left; margin:1px 0 2px 5px; width:29%; height:18px;}
div.stxt1{ float:left; margin:2px 0 0 0px; font:normal 11px verdana,Arial; color:#666666; width:75px;}
div.stxt2{ float:left; margin:2px 0 0 0px; font:normal 11px verdana,Arial; color:#666666; width:105px;}
div.stxt3{ float:left; margin:2px 0 0 0px; font:normal 11px verdana,Arial; color:#666666; width:40px;}
div.s0{ clear:both; line-height:0px}
/*QSEARCH ENDS*/

/*CENTER STARTS*/
<?php
if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
        echo "#center{margin-left:200px; margin-right:10px;width:98%}\n";
else
        echo "#center{margin-left:200px; margin-right:10px;}\n"; 

?>
div.utility{ width:95%; border:1px solid #d7d7d7; margin:5px 0px 0px 5px; padding:4px}
/*Clusters*/
#cstbx{ float:left; width:100%; font:normal 10px verdana,Arial; color:#666666; margin: 0 0 10px 0; padding-bottom:0px;}
/**html #cstbx{ float:left; width:98%; border:1px solid #E7E5E6; font:normal 10px verdana,Arial; color:#666666; margin: 0 0 10px 0; padding-bottom:0px;}*/

#cstb{ float:left; width:98%; font:normal 10px verdana,Arial; color:#666666; margin: 0 0 10px 0; padding-bottom:10px; border:1px solid #E7E5E6;}
*html #cstb{ float:left; width:98%; font:normal 10px verdana,Arial; color:#666666; margin: 0 0 10px 0; padding-bottom:0px;}

div.cstbx1{width:98% ; float:left; padding:0px; font:normal 11px verdana,Arial;color:#000000; margin:0 0 5px 0; }
div.cstcon {margin:5px 0 0px 0px;}
div.cstcol{ float:left; width:21%; padding:0px 0 0 5px; line-height:16px; border-right:1px #CCCCCC dotted;}
div.cstcol1{ float:left; width:18%; padding:0px 0 0 5px; line-height:16px; border-right:1px #CCCCCC dotted;}
div.cstcol2{ float:left; width:34%; padding:0px 0 0 5px;line-height:16px; }
.csthed{font:bold 11px verdana,Arial; color:#FF9801}
.csthed2{ font:normal 10px verdana,Arial; color:#FF9801}
.pg{margin:10px 0px 0px 8px;}
/*END Clusters*/

/*RESULTS*/
div.srhd{height:25px; float:left; width:98%;}
div.srhd1{font:bold 16px verdana,Arial; color:#CC0000; width:350px;}
div.results{ float:left; width:98%; background-color:#ffffff; border:1px solid #ffffff; margin:0px 0 0 0;}
#srf { margin:0 5px 0 5px;}
#srf ul { margin: 0; padding:0; list-style: none;  }
<?php
if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
        echo "#srf li {float: left; margin: 0 11px 0 0;  width:30%;}\n";
else
	echo "#srf li {float: left; margin: 0 11px 0 0;  width:31%;}\n";
?>
#ic-rite { MARGIN: 8px 5px 3px 8px; width:20%; float:right; text-align:right  }
#ic-main { width:100%; clear:both }
#ic { margin: 8px 0 30px 8px; } 
#ic ul { margin: 0; padding:0; list-style: none;  }
#ic li { float: left; margin: 0 5px 0 0; width:18px }
#ic-main1 { width:100%; height:45px; }
#ic1 { width:78%; float:left; margin: 8px 0 30px 5px; }
#ic-rite1 { MARGIN: 8px 0px 3px 1px; width:14%; float:right; text-align:right }
#ic1 ul { margin:0; float:left; padding:0; list-style: none; }
#ic1 li { float:left; margin: 0 5px 0 0; width:18px; height:20px }
.mbx{border:1px solid #B4BD64;font:normal 11px verdana,Arial; color:#000000; background-color:#FFF}
.mbx-fea{border:1px solid #B4BD64;font:normal 11px verdana,Arial; color:#000000; background-color:#FEE6CA}
.prf{background-image:url("<?=$SER6_URL?>/profile/images/prf_bg.gif");background-repeat:repeat-x;padding-left:5px;color:#363636;font:normal 10px verdana,Arial; line-height:25px}
.tx{padding:8px 8px 0 8px; word-wrap: break-word;}
.pmsg{padding:4px 8px 4px 8px; background:#FFF5AB;font:normal 10px verdana,Arial;}
.nmsg{padding:4px 8px 4px 8px;}
.offline{padding:4px 8px 4px 8px; background:#f0ffc8;font:normal 10px verdana,Arial;}
.dts{padding:8px 8px 0 8px; background:#F2F2F2;font:normal 10px verdana,Arial; word-wrap: break-word;}
.dts-fea{padding:8px 8px 0 8px; background:#FEE6CA;font:normal 10px verdana,Arial;  word-wrap: break-word;}
.lks{padding:8px 8px 0 8px;color:#003498;font:normal 10px verdana,Arial; text-decoration:none;}
.lks a{color:#003498;font:normal 10px verdana,Arial; text-decoration:underline;}
.lks a:visited{color:#800080;font:normal 10px verdana,Arial; text-decoration:underline;}
.fr{font:normal 10px verdana,Arial; color:#ff0000;}
#lagan{padding:3px; margin:10px 3px 3px 3px; border-top:1px solid #b4bd64}
#lagan ul { margin: 0; padding:0; list-style: none;  }
#lagan li { float: left; margin: 0 5px 0 0; width:18px }
#one,#two,#three,#four,#five,#six,#seven,#eight,#nine,#ten,#eleven,#tewelve{color: #000;}
#a1,#a2,#a3,#a4,#a5,#a6,#a7,#a8,#a9,#a10,#a11,#a12{}
#b1,#b2,#b3,#b4,#b5,#b6,#b7,#b8,#b9,#b10,#b11,#b12{}
#c1,#c2,#c3,#c4,#c5,#c6,#c7,#c8,#c9,#c10,#c11,#c12{}
#d1,#d2,#d3,#d4,#d5,#d6,#d7,#d8,#d9,#d10,#d11,#d12{}
#f1,#f2,#f3,#f4,#f5,#f6,#f7,#f8,#f9,#f10,#f11,#f12{}
/*RESULTS ENDS*/
/*CENTER ENDS*/

/*LEFTPANNEL*/
#leftpannel{float:left; width:175px; margin:0; border:1px solid #F0D5A6;}
.ss{ background-color:#FEE6CA;font:normal 11px verdana,Arial; padding-bottom:10px}
.bgor{ background-color:#F8941A}
div.iconl{ padding-left:5px; float:left; width:20px}
div.lbg{ width:165px; float:left; background-image:url(http://ser4.jeevansathi.com/profile/images/login_bg.gif)}
div.logbox{ padding:2px 0px 2px 0px; width:104px; float:left}
div.lognum{ padding:2px 0px 2px 2px; width:20px; float:left}
div.logic{ padding:2px 0px 2px 6px; width:20px; float:left}
.linklhd{ background-image:url(<?=$SER6_URL?>/profile/images/leftbar-head-bg.gif); background-position:left; background-repeat:repeat-x; padding:2px 2px 2px 10px; font:bold 11px verdana,Arial; }
.linkl{margin:5px 0 5px 2px; font:normal 11px verdana,Arial;}
.linksub{margin-left:15px;font:normal 11px verdana,Arial; lin}
#lftbt { margin: 0; font:normal 11px verdana,Arial;}
#lftbt ul { margin: 0; padding:5px 0 0 5px;  }
#lftbt li { margin: 5px 0 5px 16px; list-style-image:url(http://ser4.jeevansathi.com/profile/images/l_icon.gif); }
*html #lftbt li { margin: 5px 0 5px 13px; list-style-image:url(http://ser4.jeevansathi.com/profile/images/l_icon.gif); }
/*LEFTPANNEL ENDS*/

/*LINKS FONTS COMMON*/
/*.bluelink { color:#003498;text-decoration:none;}*/
.bluelink a { color:#003498; text-decoration:underline;}
.bluelink a:visited{ color:#003498; text-decoration:underline;}

.blacklink a { color:#000000; text-decoration:none;}
.blacklink a:visited{ color:#000000; text-decoration:none;}

/*.bluel { color:#003498;text-decoration:none;}*/
.bluel a{ color:#003498;text-decoration:none;}
.bluel a:visited{ color:#003498;text-decoration:none;}

.wten{font:normal 10px verdana,Arial; color:#ffffff}
.wele{font:normal 11px verdana,Arial; color:#000000}
.bten{font:normal 10px verdana,Arial;}
.bele{font:normal 11px verdana,Arial;}
.btwe{font:normal 12px verdana,Arial;}
.greyele{font:normal 11px verdana,Arial; color:#666666}
.greytwe{font:normal 12px verdana,Arial; color:#666666}

.textbox {FONT-SIZE: 10px; FONT-FAMILY: verdana,Arial}
div.spacer1 {clear: both; line-height:1px}
div.spacer2 {line-height:1px}
div.spacer {clear: both; line-height:5px}
div.spacer15 {clear: both; line-height:15px}
div.spacer30 {clear: both; line-height:30px}
div.spacerg{ background-color:#e7e7e7; border-bottom:1px solid #c2c2c2; clear: both; line-height:5px}

.bgg1{ background-color:#e5e5e5}

.pad2{ padding:2px 2px 2px 2px}
.pad4{ padding:4px 4px 4px 4px}

div.lftflow0{ float:left;}
div.lftflow{ float:left; margin-left:1px;}
div.leftflow1{ float:left; margin-left:2px;}
div.leftflow{ float:left; margin-left:5px;}
div.lf{ float:left; margin-left:0px;width:40%; }
div.rightflow{ float:right; margin-right:5px;}
div.lh{ line-height:16px}
.check{ vertical-align:middle;}
.buttonor { font-family:verdana,Arial; color:#ffffff; font-weight:bold ; font-size:12px; cursor:pointer; border:1px solid #874201; background-image:url(http://ser4.jeevansathi.com/profile/images/bgor.gif)}
/*END LINKS FONTS COMMON*/

/* SUBFOOTER */
h2{font:bold 11px verdana,arial; color:#CC0000; margin:0 0 5px 10px}
.bm{margin:0 0px 0 5px; width:97%; border:1px solid #c2c2c2}
.blink a { color:#000000; text-decoration:none;}
.blink a:visited{ color:#000000;text-decoration:none;}

/* SUBFOOTER ENDS*/

/*FOOTER*/
#footer{clear: both;margin: 0;padding: 2px;}
.greybox{ border:1px solid #D6CFB5;background:#FFFFFF; padding:5px 0 5px 5px; font:normal 11px verdana,Arial; width:95%; float:left;}
.greybgbox{ background-color:#D6CFB5; border:1px solid #D6CFB5; padding:2px 0px 2px 5px; font:normal 11px verdana,Arial; width:95%; float:left; margin-top:15px}
.g14{font:normal 14px verdana,arial; color:#666666}
div.box{ margin:0px 5px 0 5px;}
div.box1{ margin:3px 5px 0 20px; font:normal 10px verdana,Arial; color:#999999}

#cst ul{ list-style-type:none;margin:0px; padding:0px;}
#cst li{width:32%; list-style-type:none; margin:0px; padding:0px; float:left;}
/*FOOTER ENDS*/

/*ADDED FOR MEMBERS AWAITING FORMY RESPONSE*/
div.srhd2{font:bold 16px verdana,Arial; color:#CC0000; width:330px;}
div.srhd3{font:bold 16px verdana,Arial; color:#CC0000; }
.gr{color:#6c7425;font:bold 11px verdana,Arial;}


/* tooltip */
div.ttip{
width:200px; background:#FDFBE6; border:1px solid #757575; color:#857E33; padding:4px; position:absolute; left:120px; top:330px; Z-INDEX: 1500; VISIBILITY: hidden; font:normal 11px verdana,Arial

}
#leftpannel2{float:left; width:185px; margin:0}

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
.msixb{font:bold 16px verdana,Arial; color:#CC0000;}
