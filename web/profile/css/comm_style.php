<?php
	include("common.php");
?>
html, body{margin:0; padding:0;}
#container{width: 100%; margin: 0px auto;}
#wrapper {float: left; width: 100%;}

<?php
/* This is for advance search only*/
if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
        echo ".wrap_ad{padding:10px;width: 100%;}\n";
else
        echo ".wrap_ad{padding:10px}\n";
?>


/*HEADER*/
#top{ padding:5px 10px 5px 10px}
#tabrow{width:100%; float:left; background-color:#BC001D}
#tabrowy{width:100%; float:left; height:20px; margin-top:1px;background-image:url("http://ser4.jeevansathi.com/profile/images/ybg.gif")}
/*TABS*/
#maintab {margin-left: 0px;}
#maintab ul { margin: 0; padding: 0; list-style: none; }
#maintab li { float: left; margin: 0 0px 0 0; white-space: nowrap; background-color:#DFD69D; font: 11px verdana,Arial, Helvetica, sans-serif; font-weight:bold;   border-right:1px solid #B9B69F; }
#maintab a { float: left; padding: 0 0 0 9px; text-decoration: none;color: #515017; border-top:1px solid #B9B69F;}
#maintab a:hover{ background-position: 0 -28px; color: #000000;}
#maintab a.current { background-position: 0 -28px; background: url("http://ser4.jeevansathi.com/profile/images/tabw1.gif"); color: #FFFFFF;border-top:1px solid #850217; }
#maintab a span { float: left;  padding: 5px 9px 4px 0;}
#maintab a:hover span{background-position: 100% -28px; cursor: hand; }
#maintab a.current span {background-position: 100% -28px; background: url("http://ser4.jeevansathi.com/profile/images/tabw2.gif") no-repeat 100% 0; cursor: hand;}
#maintab a.current:hover span {cursor: default;} 
/*END TABS*/
/*END HEADER*/
/*QSEARCH*/
#searchblock {width:100%; float:left; background-color:#FFFFFF; padding:5px 0 5px 0; border-bottom:1px solid #BC7B17; background-image:url(http://ser4.jeevansathi.com/profile/images/sbg.gif)}
div.qsearch0{ float:left; height:100px; width:2%;}
div.qsearch1{float:left; margin:1px 0 2px 10px; width:28%; height:18px; }
div.qsearch2{float:left; margin:1px 0 2px 10px; width:40%; height:18px;}
div.qsearch3{float:left; margin:1px 0 2px 10px; width:26%; height:18px;}
div.stxt1{ float:left; margin:2px 0 0 0px; font:normal 11px verdana,Arial; color:#666666; width:75px;}
div.stxt2{ float:left; margin:2px 0 0 0px; font:normal 11px verdana,Arial; color:#666666; width:95px;}
div.stxt3{ float:left; margin:2px 0 0 0px; font:normal 11px verdana,Arial; color:#666666; width:85px;}
/*QSEARCH ENDS*/

/*CENTER STARTS*/
<?php
if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
        echo "#center{margin-left:200px; margin-right:10px;width:99%}\n";
else
        echo "#center{margin-left:200px; margin-right:10px;}\n";
?>
div.utility{ width:95%; background-color:#B4BD62; border:1px solid #CA8451; margin:10px 0px 0px 10px; padding:4px}

/*RESULTS*/
div.srhd{height:25px; float:left; width:98%;}
div.srhd1{font:bold 16px verdana,Arial; color:#CC0000; width:200px;}
div.results{ float:left; width:98%; background-color:#F6F4E7; border:1px solid #F7DFC5; margin:10px 0 0 0;}
#srf { margin:0 10px 0 10px;}
#srf ul { margin: 0; padding:0; list-style: none;  }
<?php
if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
        echo "#srf li {float: left; margin: 0 11px 0 0;  width:30%;}\n";
else
        echo "#srf li {float: left; margin: 0 11px 0 0;  width:31%;}\n";
?>

#ic { margin: 8px 0 30px 8px; }
#ic ul { margin: 0; padding:0; list-style: none;  }
#ic li { float: left; margin: 0 5px 0 0; width:18px }
.mbx{border:1px solid #B4BD64;font:normal 11px verdana,Arial; color:#000000; background-color:#FFF}
.mbx-fea{border:1px solid #B4BD64;font:normal 11px verdana,Arial; color:#000000; background-color:#FEE6CA}
.prf{padding-left:5px;background:#DEDDAD; color:#535152;font:normal 10px verdana,Arial; line-height:25px}
.tx{padding:8px 8px 0 8px}
.dts{padding:8px 8px 0 8px; background:#F2F2F2;font:normal 10px verdana,Arial;}
.dts-fea{padding:8px 8px 0 8px; background:#FEE6CA;font:normal 10px verdana,Arial;}
.lks{padding:8px 8px 0 8px;color:#003498;font:normal 10px verdana,Arial; text-decoration:none;}
.lks a{color:#117daa;font:normal 10px verdana,Arial; text-decoration:underline;}
.lks a:visited{color:#117daa;font:normal 10px verdana,Arial; text-decoration:underline;}
.fr{font:normal 10px verdana,Arial; color:#ff0000;}
#one,#two,#three,#four,#five,#six,#seven,#eight,#nine,#ten,#eleven,#tewelve{color: #000;}
#a1,#a2,#a3,#a4,#a5,#a6,#a7,#a8,#a9,#a10,#a11,#a12{}
#b1,#b2,#b3,#b4,#b5,#b6,#b7,#b8,#b9,#b10,#b11,#b12{}
#c1,#c2,#c3,#c4,#c5,#c6,#c7,#c8,#c9,#c10,#c11,#c12{}
#d1,#d2,#d3,#d4,#d5,#d6,#d7,#d8,#d9,#d10,#d11,#d12{}
/*RESULTS ENDS*/
.pg{margin:20px 10px 20px 10px;}
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
.linklhd{ background-color:#DEDDAD; padding:2px 2px 2px 10px; font:bold 11px verdana,Arial; }
.linkl{margin:5px 0 5px 2px; font:normal 11px verdana,Arial;}
.linksub{margin-left:15px;font:normal 11px verdana,Arial; lin}
#lftbt { margin: 0; font:normal 11px verdana,Arial;}
#lftbt ul { margin: 0; padding:5px 0 0 5px;  }
#lftbt li { margin: 5px 0 5px 16px; list-style-image:url(http://ser4.jeevansathi.com/profile/images/l_icon.gif); }
*html #lftbt li { margin: 5px 0 5px 13px; list-style-image:url(http://ser4.jeevansathi.com/profile/images/l_icon.gif); }
/*LEFTPANNEL ENDS*/

/*LINKS FONTS COMMON*/
/*.bluelink { color:#003498;text-decoration:none;}*/
.bluelink a { color:#117daa; text-decoration:underline;}
.bluelink a:visited{ color:#117daa;text-decoration:underline;} 

/*.bluel { color:#003498;text-decoration:none;}*/
.bluel a{ color:#003498;text-decoration:none;}
.bluel a:visited{ color:#003498;text-decoration:none;}

.wten{font:normal 10px verdana,Arial; color:#ffffff}
.wele{font:normal 11px verdana,Arial; color:#000000}
.bten{font:normal 10px verdana,Arial;}
.bele{font:normal 11px verdana,Arial;}
.bele1{font:normal 11px verdana,Arial;display:none}
.btwe{font:normal 12px verdana,Arial;}
.greyele{font:normal 11px verdana,Arial; color:#000000}
.greytwe{font:normal 12px verdana,Arial; color:#666666}
.msixb{font:bold 16px verdana,Arial; color:#CC0000;}

.textbox {FONT-SIZE: 10px; FONT-FAMILY: verdana,Arial}
div.spacer1 {clear: both; line-height:1px}
div.spacer2 {line-height:1px}
div.spacer {clear: both; line-height:5px}
div.spacer15 {clear: both; line-height:15px}
div.spacerg{ background-color:#e7e7e7; border-bottom:1px solid #c2c2c2; clear: both; line-height:5px}
div.space15 {line-height:15px}


.bgg1{ background-color:#e5e5e5}

.pad2{ padding:5px 2px 5px 2px}
.pad4{ padding:4px 4px 4px 4px}
.blink a { color:#000000; text-decoration:none;}
.blink a:visited{ color:#000000;text-decoration:none;}

div.lftflow0{ float:left;}
div.lftflow{ float:left; margin-left:1px;}
div.leftflow1{ float:left; margin-left:2px;}
div.leftflow{ float:left; margin-left:5px;}
div.rightflow{ float:right; margin-right:5px;}
div.lh{ line-height:16px}
.check{ vertical-align:middle;}
.buttonor { font-family:verdana,Arial; color:#ffffff;border:0px solid #FF0000; font-weight:bold; height:26px; font-size:12px; cursor:pointer; width:254px; background-image:url(<?=$SER6_URL?>/profile/images/button_cheque_pickup.gif)}
.button-login { font-family:verdana,Arial; color:#ffffff; font-weight:bold ; font-size:12px; cursor:pointer; height:25px; background-image:url(<?=$SER6_URL?>/profile/images/log-in.gif); border:0px solid #FF0000;}
.button-create { font-family:verdana,Arial; color:#ffffff; font-weight:bold ; font-size:12px; cursor:pointer; background-image:url(<?=$SER6_URL?>/profile/images/button-create.gif); border:0px solid #FF0000; height:25px; }
.button-membership { font-family:verdana,Arial; color:#ffffff; font-weight:bold ; font-size:12px; cursor:pointer; background-image:url(<?=$SER6_URL?>/profile/images/upgrade-membership.gif); border:0px solid #FF0000; height:26px; }
.button-erishta { font-family:verdana,Arial; color:#ffffff; font-weight:bold ; font-size:12px; cursor:pointer; background-image:url(<?=$SER6_URL?>/profile/images/button-erishta.gif); width:102px; height:25px; border:0px solid #FF0000;}
.button-evalue { font-family:verdana,Arial; color:#ffffff; font-weight:bold ; font-size:12px; cursor:pointer; background-image:url(<?=$SER6_URL?>/profile/images/evalue.gif); width:118px; height:25px; border:0px solid #FF0000;}
.button-vcd { font-family:verdana,Arial; color:#ffffff; font-weight:bold ; font-size:12px; cursor:pointer; background-image:url(<?=$SER6_URL?>/profile/images/view-cont-details.gif); width:241px; height:26px; border:0px solid #FF0000;}
/*END LINKS FONTS COMMON*/

/* SUBFOOTER */
h2{font:bold 11px verdana,arial; color:#CC0000; margin:0 0 5px 10px}
.bm{margin:0 0px 0 5px; width:97%; border:1px solid #c2c2c2}
.blink a { color:#000000; text-decoration:none;}
.blink a:visited{ color:#000000;text-decoration:none;}
                                                                                                                             
/* SUBFOOTER ENDS*/


/*FOOTER*/
#footer{clear: both;margin: 0;padding: 2px;}
.greybox{ border:1px solid #D6CFB5; padding:5px 0 5px 5px; font:normal 11px verdana,Arial; width:97%; float:left;}
.greybgbox{ background-color:#D6CFB5; border:1px solid #D6CFB5; padding:2px 0px 2px 5px; font:normal 11px verdana,Arial; width:97%; float:left; margin-top:30px}
div.box{ margin:0px 5px 0 5px;}
div.box1{ margin:3px 5px 0 20px; font:normal 10px verdana,Arial; color:#999999}
#cst ul{ list-style-type:none;margin:0px; padding:0px;}
#cst li{width:32%; list-style-type:none; margin:0px; padding:0px; float:left;}
/*FOOTER ENDS*/

/*ADDED FOR PROFILE&SEARCH CONFIRMATION*/
div.confmsg{margin:20px 0 20px 0; font:normal 12px verdana,Arial;}
.mb{float:left; width:280px; margin-right:10px; line-height:18px; font:normal 11px verdana,Arial; height:150px; padding:5px 5px 5px 5px}
/*ADDED FOR PROFILE&SEARCH CONFIRMATION ENDS*/


/*ADDED FOR mutiselect message*/
.pms{ float:left;padding:6px 4px 6px 4px;font:normal 11px verdana,Arial; border-top:1px solid #C3B788; color:#4D4D4D; width:95%; margin-left:3px}
.spb{float:left; border-top:1px solid #C3B788; width:95%; margin:0 0 0 3px; line-height:1px; padding-left:8px;}
/*ADDED FOR mutiselect message ENDS*/


/*ROUNDED CORNERS WITH BORDERS*/
.holder1{ float:left; width:45%;line-height:18px; padding-bottom:20px}
div.b1{background: url(http://ser4.jeevansathi.com/profile/images/box.gif) top left no-repeat; width: 400px;}
div.b2{background: url(http://ser4.jeevansathi.com/profile/images/box.gif) top right no-repeat; margin-left: 7px; padding-top: 7px;}
div.b3{background: url(http://ser4.jeevansathi.com/profile/images/box.gif) bottom right no-repeat;}
div.b4{background: url(http://ser4.jeevansathi.com/profile/images/box.gif) bottom left no-repeat; margin: 0 7px 0 -7px; padding: 0 0 7px 7px;}

/* tooltip */
div.ttip{
width:200px; background:#FDFBE6; border:1px solid #757575; color:#857E33; padding:4px; position:absolute; left:120px; top:330px; Z-INDEX: 1500; VISIBILITY: hidden; font:normal 11px verdana,Arial
                                                                                                                             
}

#leftpannel2{float:left; width:175px; margin:0}

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


