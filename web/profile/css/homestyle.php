<?php
	include("common.php");
?>
html, body{margin:0; padding:0;}
#container{width: 100%; margin: 0px auto;}
#wrapper {float: left; width: 100%;}

/*HEADER*/
#top{ padding:5px 10px 5px 10px}
#tabrow{width:100%; float:left; background-color:#BC001D}
#tabrowy{width:100%; float:left; line-height:17px; margin-top:1px;background-image:url("http://ser4.jeevansathi.com/profile/images/ybg.gif")}

/*TABS*/
#maintab {margin-left: 0px;}
#maintab ul { margin: 0; padding: 0; list-style: none; }
#maintab li { float: left; margin: 0 0px 0 0; white-space: nowrap; background-color:#DFD69D; font: 11px verdana,Arial, Helvetica, sans-serif; font-weight:bold;   border-right:1px solid #B9B69F; }
#maintab a { float: left; padding: 0 0 0 9px; text-decoration: none;color: #515017; border-top:1px solid #B9B69F;}
#maintab a:hover{ background-position: 0 -28px; color: #000000;}
#maintab a.current { background-position: 0 -28px; background: url("http://ser4.jeevansathi.com/profile/images/tabw1.gif"); color: #FFFFFF;border-top:1px solid #850217; }
#maintab a span { float: left;  padding: 5px 9px 4px 0;}
#maintab a:hover span{background-position: 100% -28px; cursor: pointer; }
#maintab a.current span {background-position: 100% -28px; background: url("http://ser4.jeevansathi.com/profile/images/tabw2.gif") no-repeat 100% 0; cursor: pointer;}
#maintab a.current:hover span {cursor: default;} 
/*END TABS*/

/*TABS*/
#htab {margin-left: 0px;}
#htab ul { margin: 0; padding: 0; list-style: none; }
#htab li { float: left; margin: 0 0px 0 0px; white-space: nowrap; background-image:url(<?=$SER6_URL?>/profile/images/inex-tab2.gif); background-position:center; background-repeat:no-repeat; font: 11px verdana,Arial, Helvetica, sans-serif; font-weight:normal;}
#htab a { float: left; padding: 0 0 0 9px; text-decoration: none;color: #6a6d2e;}
#htab a:hover{ background-position: 0 -28px; color: #000000;}
#htab a.current { background-position: 0 -28px; background: url("<?=$SER6_URL?>/profile/images/in_tabr1.gif"); color: #000000;font-weight:bold; margin-left:}
#htab a span { float: left;  padding: 5px 9px 4px 0;}
#htab a:hover span{background-position: 100% -28px; cursor: pointer; }
#htab a.current span {background-position: 100% -28px; background: url("<?=$SER6_URL?>/profile/images/in_tabr2.gif") no-repeat 100% 0; cursor: pointer;}
#htab a.current:hover span {cursor: default;} 
/*END TABS*/

/*END HEADER*/

/*QSEARCH*/
#searchblock {width:100%; float:left; background-color:#FFFFFF; padding:0px 0 3px 0; border-bottom:1px solid #BC7B17; background-image:url(http://ser4.jeevansathi.com/profile/images/sbg.gif)}
div.qsearch0{ float:left; height:100px; width:2%;}
div.qsearch1{float:left; margin:1px 0 2px 5px; width:27%; height:18px; }
div.qsearch2{float:left; margin:1px 0 2px 5px; width:40%; height:18px;}
div.qsearch3{float:left; margin:1px 0 2px 5px; width:29%; height:18px;}
div.stxt1{ float:left; margin:2px 0 0 0px; font:normal 11px verdana,Arial; color:#666666; width:75px;}
div.stxt2{ float:left; margin:2px 0 0 0px; font:normal 11px verdana,Arial; color:#666666; width:105px;}
div.stxt3{ float:left; margin:2px 0 0 0px; font:normal 11px verdana,Arial; color:#666666; width:40px;}
/*QSEARCH ENDS*/

/*CENTER STARTS*/
<?php
if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
        echo "#center{margin-left:260px; margin-right:10px;width:99%}\n";
else
        echo "#center{margin-left:260px; margin-right:10px;}\n";
?>
.pg{margin:10px 0px 10px 8px;}
.bor { font-family:verdana,Arial; color:#ffffff; font-weight:bold ; font-size:16px; cursor:pointer; border:1px solid #874201; background-image:url(http://ser4.jeevansathi.com/profile/images/bor.gif)}
/*END Clusters*/

/*RESULTS*/
div.srhd{float:left; width:98%;}
div.srhd1{font:bold 16px verdana,Arial; color:#616161;}
div.results{ float:left; width:97%; background-color:#ffffff; border:1px solid #feb70d; margin:0px 0 0 0;}
#srf { margin:0 6px 0 6px;}
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
.prf{padding-left:5px; background-image:url(<?=$SER6_URL?>/profile/images/prf_bg.gif); background-position:top; background-repeat:repeat-x; color:#535152;font:normal 10px verdana,Arial; line-height:25px}
.tx{padding:8px 8px 0 8px; word-wrap: break-word;}
.pmsg{padding:4px 8px 4px 8px; background:#FFF5AB;font:normal 10px verdana,Arial;}
.nmsg{padding:4px 8px 4px 8px;}
.dts{padding:8px 8px 0 8px; background:#F2F2F2;font:normal 10px verdana,Arial;word-wrap: break-word;}
.dts-fea{padding:8px 8px 0 8px; background:#FEE6CA;font:normal 10px verdana,Arial;word-wrap: break-word;}
.lks{padding:8px 8px 0 8px;color:#003498;font:normal 10px verdana,Arial; text-decoration:none;}
.lks a{color:#003498;font:normal 10px verdana,Arial; text-decoration:underline;}
.lks a:visited{color:#800080;font:normal 10px verdana,Arial; text-decoration:underline;}
.fr{font:normal 10px verdana,Arial; color:#ff0000;}
#one,#two,#three,#four,#five,#six,#seven,#eight,#nine,#ten,#eleven,#tewelve{color: #000;}
#a1,#a2,#a3,#a4,#a5,#a6,#a7,#a8,#a9,#a10,#a11,#a12{}
#b1,#b2,#b3,#b4,#b5,#b6,#b7,#b8,#b9,#b10,#b11,#b12{}
#c1,#c2,#c3,#c4,#c5,#c6,#c7,#c8,#c9,#c10,#c11,#c12{}
#d1,#d2,#d3,#d4,#d5,#d6,#d7,#d8,#d9,#d10,#d11,#d12{}
/*RESULTS ENDS*/
/*CENTER ENDS*/

/*LEFTPANNEL*/
#leftpannel{float:left; width:240px; margin:0; padding-left:2px;}
/*LEFTPANNEL ENDS*/

/*LINKS FONTS COMMON*/
/*.bluelink { color:#003498;text-decoration:none;}*/
.bluelink a { color:#003498; text-decoration:underline;}
.bluelink a:visited{ color:#003498;text-decoration:underline;} 

.blink a { color:#000000; text-decoration:none;}
.blink a:visited{ color:#000000;text-decoration:none;} 

.wlink a { color:#ffffff; text-decoration:none;}
.wlink a:visited{ color:#ffffff;text-decoration:none;} 

.wten{font:normal 10px verdana,Arial; color:#ffffff}
.wele{font:normal 11px verdana,Arial; color:#000000}
.bten{font:normal 10px verdana,Arial;}
.bele{font:normal 11px verdana,Arial;}
.btwe{font:normal 12px verdana,Arial;}
.greyele{font:normal 11px verdana,Arial; color:#666666}
.greytwe{font:normal 12px verdana,Arial; color:#666666}
.button-membership { font-family:verdana,Arial; color:#ffffff; font-weight:bold ; font-size:12px; cursor:pointer; background-image:url(<?=$SER6_URL?>/profile/images/upgrade-membership.gif); border:0px solid #FF0000; height:26px; }

.textbox {FONT-SIZE: 10px; FONT-FAMILY: verdana,Arial}
div.spacer1 {clear: both; line-height:1px}
div.spacer {clear: both; line-height:5px}
div.spacer15 {clear: both; line-height:15px}
div.spacerg{ background-color:#e7e7e7; border-bottom:1px solid #c2c2c2; clear: both; line-height:5px}

.pad2{ padding:2px 2px 2px 2px}
.pad4{ padding:4px 4px 4px 4px}

div.lftflow0{ float:left;}
div.lftflow{ float:left; margin-left:1px;}
div.leftflow1{ float:left; margin-left:2px;}
div.leftflow{ float:left; margin-left:5px;}
div.rightflow{ float:right; margin-right:5px;}

/*END LINKS FONTS COMMON*/

/*FOOTER*/
#footer{clear: both;margin: 0;padding: 2px;}


h2{font:bold 11px verdana,arial; color:#CC0000; margin:0 0 5px 10px}
h1{font:bold 22px verdana,arial; margin:0;}
.tc{float:right; width:455px;}
.lih{background-image:url(http://ser4.jeevansathi.com/profile/images/livehelp_cont.gif); width:121px; height:26px; color:#FFFFFF}
.g14{font:normal 14px verdana,arial; color:#666666}
.pl{float:left; font:bold 16px verdana,Arial; color:#616161; padding-top:7px}
.joinbg{width:240px; height:244px;background-image:url(<?=$SER6_URL?>/profile/images/home_reg_bg.gif) }
.nu{font:normal 16px verdana,arial; color:#FFFFFF; padding:15px 0 0 25px; float:left}
.jj{font:normal 15px verdana,arial; color:#a55669; padding:40px 0 0 25px; float:left; text-align:right;}
.jco{ float:left;width:210px; border-top:1px solid #ded9df; margin:0 0px 0 5px; padding:3px 0 3px 0}
.lbo{width:235px; padding:0px; border:1px solid #666666 }
.gl{line-height:2px; background-color:#999999; margin:10px 5px 0 5px }
.sb{width:235px; border:1px solid #666666 }
.bm{margin:0 0px 0 5px; width:97%; border:1px solid #c2c2c2}


.blacklink A {
	COLOR: #000000; TEXT-DECORATION: none
}
.blacklink A:visited {
	COLOR: #000000; TEXT-DECORATION: none
}

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

