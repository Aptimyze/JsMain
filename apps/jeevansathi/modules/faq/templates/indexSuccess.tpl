
<!-- CSS Style -->
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
/* search */
.sreach_pannal {
	background-image:url(~sfConfig::get('app_img_url')`/img_revamp/bg_search.gif);
	background-repeat:repeat-x;
	width:778px;
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
/* end search */

/* setting */
.st_open_tab {
	float:left;
	font:normal 12px arial;
	padding:3px 0 7px 1px;
	color:#117DAA;
	clear:both;
	width:155px;
}
.st_close_tab {
	background-image:url(~sfConfig::get('app_img_url')`/img_revamp/open_tab_setting.gif);
	background-repeat:no-repeat;
	width:163px;
	height:25px;
	font:bold 12px arial;
	padding:2px 0 0 1px;
	color:#117DAA;
	clear:both;
	background-position:right
}
.st_close_bigtab {
        background-image:url(~sfConfig::get('app_img_url')`/img_revamp/open_bigtab_setting.gif);
        background-repeat:no-repeat;
        width:163px;
        height:40px;
        font:bold 12px arial;
        padding:2px 0 0 1px;
        color:#117DAA;
        clear:both;
        background-position:right
}

.sub_st_tab {
	padding:4px 0 0 12px;
}
.success_bg {
	background-image:url(~sfConfig::get('app_img_url')`/img_revamp/bg_success_story.gif);
	background-repeat:no-repeat;
	width:118px;
	height:94px;
}
.hide_delete_bg {
	background-color:#ffed77;
	border:1px #ffe742 solid;
	padding:5px;
}
/* curve */
.y {
background-color:;
}
.y_b_l {
	background: transparent url(~sfConfig::get('app_img_url')`/profile/images/y_b_l.gif) 0 100% no-repeat
}
.y_b_r {
	background: transparent url(~sfConfig::get('app_img_url')`/profile/images/y_b_r.gif) 100% 100% no-repeat
}
.y_t_l {
	background: transparent url(~sfConfig::get('app_img_url')`/profile/images/y_t_l.gif) 0 0 no-repeat
}
.y_t_r {
	background: transparent url(~sfConfig::get('app_img_url')`/profile/images/y_t_r.gif) 100% 0 no-repeat;
	padding:3px
}
.y_top {
	background: url(~sfConfig::get('app_img_url')`/profile/images/y_dot.gif) 0 0 repeat-x;
	width: 100%;
}
.y_bot {
	background: url(~sfConfig::get('app_img_url')`/profile/images/y_dot.gif) 0 100% repeat-x
}
.y_lft {
	background: url(~sfConfig::get('app_img_url')`/profile/images/y_dot.gif) 0 0 repeat-y
}
.y_rgt {
	background: url(~sfConfig::get('app_img_url')`/profile/images/y_dot.gif) 100% 0 repeat-y
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
.search_box .tp_left_cur {
	background:url(~sfConfig::get('app_img_url')`/profile/images/search_tp_left_cur.gif) no-repeat left top;
}
.search_box .tp_right_cur {
	background:url(~sfConfig::get('app_img_url')`/profile/images/search_tp_right_cur.gif) no-repeat right top;
}
.search_box .btm_right_cur {
	background:url(~sfConfig::get('app_img_url')`/profile/images/search_btm_right_cur.gif) no-repeat bottom right;
}
.search_box .btm_left_cur {
	background:url(~sfConfig::get('app_img_url')`/profile/images/search_btm_left_cur.gif) no-repeat bottom left;
}
.search_box .top_line {
	background:url(~sfConfig::get('app_img_url')`/profile/images/search_grey_dot.gif) repeat-x top;
}
.search_box .btm_line {
	background:url(~sfConfig::get('app_img_url')`/profile/images/search_grey_dot.gif) repeat-x bottom;
}
.search_box .right_line {
	background:url(~sfConfig::get('app_img_url')`/profile/images/search_grey_dot.gif) repeat-y right;
}
.search_box .left_line {
	background:url(~sfConfig::get('app_img_url')`/profile/images/search_grey_dot.gif) repeat-y left;
}
.search_box input.search {
	width:360px;
	height:16px;
	border:1px solid #ccc;
}
/*status curves starts  here*/
.lft_bdr {
	background:#f9f9f9 url(~sfConfig::get('app_img_url')`/profile/images/light_grey-dot.gif) repeat-y left;
}
.btm_bdr {
	background:url(~sfConfig::get('app_img_url')`/profile/images/light_grey-dot.gif) repeat-x bottom;
}
.right_bdr {
	background:url(~sfConfig::get('app_img_url')`/profile/images/light_grey-dot.gif) repeat-y right;
}
.right_bot_cur {
	background:url(~sfConfig::get('app_img_url')`/profile/images/light_grey_cur_right.gif) no-repeat bottom right
}
.left_bot_cur {
	background:url(~sfConfig::get('app_img_url')`/profile/images/light_grey_cur_left.gif) no-repeat left bottom;
}
.terms_condition {
	font:11px Arial, Helvetica, sans-serif;
	width:453px;~* initially 460px*`
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
	background:url(~sfConfig::get('app_img_url')`/profile/images/li_square_img.gif) no-repeat 0 5px;
	padding:0 0 0 8px;
}
.terms_condition ul.faq{margin:10px 0 0 5px; padding:0; font-size:12px;}
.terms_condition ul.faq li{list-style:none; background:url(~sfConfig::get('app_img_url')`/profile/images/plus_img.gif) no-repeat 0 2px;margin-bottom:10px;}
.terms_condition ul.faq li.min{background:url(~sfConfig::get('app_img_url')`/profile/images/minus_img.gif) no-repeat 0 2px;}
.terms_condition ul.faq li a{color:#117daa; text-decoration:none; margin-left:-20px; padding-left:40px;cursor:pointer;}
.terms_condition ul.faq li p.content{margin-top:10px;}
.terms_condition ul.faq li p.content a{margin-left:0px; padding-left:0px;}
/*status curves ends  here*/
</style>

<!-- JavaScript -->
<script type="text/javascript" language="JavaScript">
function HideContent(d) 
{
	if(d.length < 1) 
	{ 
		return; 
	}
	document.getElementById(d).style.display = "none";
}
function ShowContent(d) 
{
	if(d.length < 1) 
	{ 
		return; 
	}
	document.getElementById(d).style.display = "block";
}
function ReverseContentDisplay(d) 
{
	if(d.length < 1) 
	{ 
		return; 
	}
	if(document.getElementById(d).style.display == "none") 
	{ 
		document.getElementById(d).style.display = "block"; 
	}
	else 
	{ 
		document.getElementById(d).style.display = "none"; 
	}
}
function showHideItems(myItem)
{
        if(document.getElementById("div"+myItem).style.display == "none")
        {
                document.getElementById("div"+myItem).style.display = "block";
                document.getElementById("minus"+myItem).className ="min";
        }
        else
        {
                document.getElementById("div"+myItem).style.display = "none";
                document.getElementById("minus"+myItem).className= "";
        }
}

</script>
<!-- Main DIV-->
<!--Header starts here-->
 <?php include_partial('global/header') ?>
<!--Header ends here-->

        <div>
               
                <div id="container">
			<div id="topSearchBand"></div>
                        <div class="clear"></div>
 
<!--
 Sub Header Start here                       
-->
  <p class="clr_4"></p>
<div id="topSearchBand"></div>
<?php include_partial('global/sub_header') ?>
  <p class="clr_4"></p>
<br>
<!--
Ends
-->


			<div class="sp3"></div>
			<h1 class="lf" style="margin-bottom:5px;">Frequently Asked Questions</h1>
			<div class="lf" style="padding-top:5px;"> </div>
			<div class="sp3"></div>
			<!-- start middle section -->
			<div class="lf" style="width: 168px;">
				<div class="lf" style="width: 168px;">
					<div><img src="~sfConfig::get('app_img_url')`/img_revamp/top_tab_setting.gif"></div>
					<div style="clear:both;"></div>
					<div style="background-image:url(~sfConfig::get('app_img_url')`/img_revamp/hr_tab_setting.gif);float:left;width:164px;background-repeat:repeat-y;">

						~foreach from=$arrstart key=sec item=szLabel`
						<div ~if $current eq $szLabel['id']`~if strlen($szLabel['name']) gt 30` class="st_close_bigtab"~else` class="st_close_tab"~/if`~else`class="st_open_tab"~/if`>
							<div class="sub_st_tab">
								<a ~if $current neq $szLabel['id']` 
									href="?checksum=~$CHECKSUM`&tracepath=~$szLabel['id']`" 
								~else`
									href="#"
								~/if`
								 class="blink">~$szLabel['name']`</a>
							</div>
						</div>
						~/foreach`

						<div class="st_open_tab">
							<div class="sub_st_tab"><a href="/faq/feedback?checksum=~$CHECKSUM`&tracepath=~$trace`&NO_NAVIGATION=~$NO_NAVIGATION`&id=~$linkarr[$sec+1].id`" class="blink thickbox">Service Related Issues</a></div>
						</div>
					</div>
					<div style="clear:both;"></div>
					<div><img src="~sfConfig::get('app_img_url')`/img_revamp/bottom_tab_setting.gif"></div>
				</div>
			</div>
			<div class="terms_condition lf" style="border-top:1px solid #aaa;">
				<ul class="faq">
				<!--change the background image of the li when it expands-->
					~foreach from=$linkarr key=sec item=szFAQElement`
					<li id='minus~$szFAQElement["id"]`' ~if $flagged eq 1 and $szFAQElement['id'] eq 16`class='min'~/if`>
						<a id='plus~$szFAQElement["id"]`' onClick='showHideItems("~$szFAQElement["id"]`")' >~$szFAQElement["name"]`</a>
						<p class="content" id="div~$szFAQElement['id']`" ~if $flagged eq 1`~if $nonstyle eq '1' and $szFAQElement["id"] neq '16'`style="display:none;margin-left:0 px;padding-left:0 px;"~else`style="display:block;margin-left:0px;padding-left:0px;"~/if`~else`~if $nonstyle eq '1'`style="display:none;margin-left:0 px;padding-left:0 px;"~else`style="display:block;margin-left:0px;padding-left:0px;"~/if`~/if`>
							
							~$szFAQElement["answer"]|decodevar`
							<span class="rf"><img src="~sfConfig::get('app_img_url')`/img_revamp/icon_blue_next.gif" alt="know more" title="know more">
								~if $szFAQElement["chk"] eq 0`
									<a href="/faq/feedback?checksum=~$CHECKSUM`&tracepath=~$trace1`&NO_NAVIGATION=~$NO_NAVIGATION`&id=~$szFAQElement['id']`&width=512" class="blink thickbox">
								~else`
									<a href="/faq/feedback?checksum=~$CHECKSUM`&tracepath=~$trace1`&NO_NAVIGATION=~$NO_NAVIGATION`&id=~$szFAQElement['id']`&width=512" class="blink thickbox">
								~/if`
								Know More</a>
							</span>
						</p>
					</li>
					~/foreach`					
				</ul>
			</div>		
			~include_partial("successStory/rightPanel",[rightPanelStory=>"$rightPanelStory",loginData=>"$profileID",bms_1=>"$bms_1",bms_2=>"$bms_2"])`
		</div>
	</div>
</div>

<!-- Main DIV END-->
~include_partial('global/footer',[NAVIGATOR=>~$NAVIGATOR`,bms_topright=>$bms_topright,bms_bottom=>$bms_bottom,G=>$G,viewed_gender=>$GENDER,data=>''])`


