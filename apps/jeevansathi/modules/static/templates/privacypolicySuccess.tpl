<style>
body {
margin:0;
padding:0;
font-size:12px;
color:#000000;
font-family:Arial, sans-serif
}
#main {
width:100%;
margin:0px;
float:left;
}
#container {
width: 930px;
margin: 0px auto;
}

.pd2 {
padding:2px 2px 2px 5px;
}
div.row {
clear:both;
padding:3px 0 0px 0;
color:#000;
width:98%;
margin:auto;
}
.child_arr {
margin:5px 5px 0 10px;
}
.mar_top_35 {
margin-top:35px;
}
.search_box {
margin:0;
padding:0;
}
.terms_condition {
font:11px Arial, Helvetica, sans-serif;
width:600px;
margin:0 10px;
}
.terms_condition h2 {
margin:0;
padding:0;
font:bold 11px Arial, Helvetica, sans-serif;
text-indent:0;
}
.terms_condition p.content {
margin:10px 0 15px 0;
padding:2px;
}
.terms_condition ul.terms {
margin:-10px 0 0;
padding:0;
}
.terms_condition ul.terms li {
list-style:none;
background:url(images/li_square_img.gif) no-repeat 0 5px;
padding:0 0 0 8px;
}
.extracss{border-top:1px solid #aaa;}
/*status curves ends  here*/

</style>
<!--Header starts here-->
 <?php include_partial('global/header') ?>
<!--Header ends here-->

<!--pink strip starts here-->
<!--Main container starts here-->

<div id="main_cont">

<div id="container">

<!--pink strip ends here-->
  <p class="clr_4"></p>
<div id="topSearchBand"></div>
<?php include_partial('global/sub_header') ?>
  <p class="clr_4"></p>
<br>
<div class="clear"></div>
~$SUB_HEAD`
<div class="sp16"></div>
<BR>
<h1 style="float:left">Privacy Policy</h1>
<div class="sp3"></div>
<div class="lf" style="width: 168px;">
<div class="lf" style="width: 168px;">
<div><img src="~sfConfig::get("app_img_url")`/img_revamp/top_tab_setting.gif"></div>
<div style="clear:both;"></div>
<div style="background-image:url(~sfConfig::get("app_img_url")`/img_revamp/hr_tab_setting.gif);float:left;width:164px;background-repeat:repeat-y;">
<div class="st_open_tab">
<div class="sub_st_tab"><a href="/static/page/fraudalert" class="blink">Fraud Alert</a></div>
<div class="sub_st_tab"><a href="/static/page/disclaimer" class="blink">Terms &amp; Conditions</a></div>
</div>
<div class="st_close_tab">
<div class="sub_st_tab"><a href="#" class="blink">Privacy Policy</a></div>
</div>
<div class="st_open_tab">
<div class="sub_st_tab"><a href="/static/page/thirdparty" class="blink">Third  Party Terms &amp; Conditions</a></div>

</div>
<div class="st_open_tab">
<div class="sub_st_tab"><a href="/static/page/privacyfeatures" class="blink">Privacy Features</a></div>
</div>
</div>
<div style="clear:both;"></div>
<div><img src="http://static.jeevansathi.com/img_revamp/bottom_tab_setting.gif"></div>
</div>

</div>
~include_partial("successStory/rightPanel",[rightPanelStory=>"$rightPanelStory",loginData=>"$loginData",bms_1=>"$bms_1",bms_2=>"$bms_2"])`
~include_partial($innerTemplate)`

</div>
</div>

~include_partial('global/footer',[NAVIGATOR=>~$NAVIGATOR`,bms_topright=>$bms_topright,bms_bottom=>$bms_bottom,G=>$G,viewed_gender=>$GENDER,data=>''])`
