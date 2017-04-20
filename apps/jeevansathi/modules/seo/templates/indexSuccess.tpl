<script>
var SITE_URL="~sfConfig::get('app_site_url')`";
var prof_checksum="~$sf_request->getAttribute('checksum')`";
var user_login="~$sf_request->getAttribute('login')`";
var google_plus=0;
var searchId = "~$sf_request->getParameter("searchid")`";
var seoField = "~$field`";
var seoValue = "~$value`";
var seoFlag = "yes";
</script>

~if $GR_LOGGEDIN eq 0 and !$GR_ISEARCH`
<!-- Google Remarketing Starts -->
<script>
var google_conversion_id = 1056682264;
var google_conversion_label = "j5CPCPy1_gIQmOLu9wM";
//  Below custom params may be modified. When no value, use empty string ''
var google_custom_params = {
CurrentDate : '~$GR_DATE`',
              PageType : 'CommunityPages',
              Gender : '~$GR_GENDER`',
              Religion : '~$GR_RELIGION`',
              Residence : '~$GR_RESIDENCE`',
              Edu_Occ : '~$GR_EDU_OCC`',
              MotherTongue : '~$GR_MTONGUE`',
              Caste : '~$GR_CASTE`',
	      MaritalStatus: '~$GR_MSTATUS`'
};

var google_remarketing_only = true;
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="https://googleads.g.doubleclick.net/pagead/viewthroughconversion/1056682264/?value=0&amp;label=j5CPCPy1_gIQmOLu9wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- Google Remarketing Ends -->
<script type="text/javascript">
(function() {
    try {
        var viz = document.createElement('script');
        viz.type = 'text/javascript';
        viz.async = true;
        viz.src = ('https:' == document.location.protocol ?'https://ssl.vizury.com' : 'http://www.vizury.com')+ '/analyze/pixel.php?account_id=VIZVRM782';

        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(viz, s);
        viz.onload = function() {
            pixel.parse();
        };
        viz.onreadystatechange = function() {
            if (viz.readyState == "complete" || viz.readyState == "loaded") {
                pixel.parse();
            }
        };
    } catch (i) {
    }
})();
</script>
~/if`


<div id="main_cont">
<noscript>
<div style="position:fixed;z-index:1000;width:100%">
<div style="text-align:center;padding-bottom:3px;font-family:verdana,Arial;font-size:12px;font-size-adjust:none;font-stretch:normal;font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;background-color:#E5E5E5;">
<b><img src="~$IMG_URL`/profile/images/registration_new/error.gif" width="23" height="20"> Javascript is disabled in your browser.Due  to this certain functionalities will not work. 
<a href="~sfConfig::get('app_site_url')`/P/js_help.htm" target="_blank">Click Here</a> , to know how  to enable it.
</b>
</div>
</div>
</noscript>
<div class="hdr">
<div class="fl" style="width:350px; padding:10px;">
<div class="fl" >
<span class="fl">A </span>
<a href="https://www.naukri.com"><span class="nkri_lgo sprte fl" style="margin:0px;"></span></a>
<span class="fl">&nbsp;Group Company</span>
</div > <br /><br />
<div class="clr"></div>
<div class="lf" style="padding-right:4px;margin-bottom:4px;width:250px"><a href="~sfConfig::get('app_site_url')`"><p style="cursor:pointer" class="lgo sprte"></p></a>
<i class="lf">Indian Matrimonials - We Match Better</i></div>

</div>

<div class="fr tp_plus_call">
<div class="tp_lnks b" style = "width:470px">
<a href="~sfConfig::get('app_site_url')`/profile/login.php?SHOW_LOGIN_WINDOW=1" class="thickbox tp_lnksa">Existing User Login</a>&nbsp; | &nbsp;
<a href="~sfConfig::get('app_site_url')`/profile/registration_new.php?source=~$SOURCE`" class="tp_lnksa">New User Register</a>&nbsp;&nbsp; &nbsp;
<!-- <b class="mron hpl sprte" style="padding-left:17px; font-size:12px;">
<a href="http://server.iad.liveperson.net/hc/13507809/?cmd=file&file=visitorWantsToChat&offlineURL=~sfConfig::get('app_site_url')`/P/faq_redirect.php&site=13507809&byhref=1&imageUrl=http://www.jeevansathi.com/profile/images_try/liveperson" target='chat13507809' onclick="javascript:trackClicks('LH');window.open('http://server.iad.liveperson.net/hc/13507809/?cmd=file&file=visitorWantsToChat&offlineURL=~sfConfig::get('app_site_url')`/P/faq_redirect.php&site=13507809&imageUrl=http://www.jeevansathi.com/images_try/liveperson&referrer='+escape(document.location),'chat13507809','width=472,height=320');return false;" class="mron">Live Help</a> -->
</b>
</div>
<p class="clr"></p>
<div class="call_us gry">Call us On 
<b class="orng">1-800-419-6299 [ Toll Free ]</b>
<div class="orng2_1">Only in India</div>
</div>
<u class="sprte fr"></u> 
</div>

</div>

<p class="clr_4"></p>
<p class="clr_4"></p>
<p class="clr_4"></p>
~include_partial("seo/levelOneBreadCrumb",[breadCrumbObj=>$breadCrumbObj,LESS_WIDTH=>$LESS_WIDTH,MORE_WIDTH=>$MORE_WIDTH,levelObj=>$levelObj,NOMORE=>$NOMORE])`

<p class="clr_4"></p>
<p class="clr_4"></p>
<p class="clr_4"></p>

~include_partial("seo/levelTwoBreadCrumb",[breadCrumbObj=>$breadCrumbObj])`

<!--Quick searck starts here-->
<div id="topSearchBand"></div>
<!--Quick searck ends here-->
<p class="clr"></p>
<!--Links below search bar starts here-->
~include_partial('sub_head',[USERNAME=>""])`
<!--Links below search bar ends here-->

<!--breadcrumb section starts here-->

<div class="sp5"></div>
<p id="breadcrumbs">
	<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
		<a href="~sfConfig::get('app_site_url')`" itemprop="url">
			<span itemprop="title">Home</span>
		</a>
	</span> 
	&rsaquo; 
	~if $urlLevel2 neq ''`
	<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
		<a href="~sfConfig::get('app_site_url')`~$urlLevel1`" itemprop="url" title="~$level1` Matrimonial">
			<span itemprop="title">~$level1` Matrimony</span>
		</a>
	</span> 
	&rsaquo; 
	<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
		<a href="~sfConfig::get('app_site_url')`~$urlLevel2`" itemprop="url" title="~$level2` Matrimonial">
			<span itemprop="title">~$level2` Matrimony</span>
		</a>
	</span> 
	&rsaquo;
	<span itemprop="title"> ~$levelObj->getH1Tag()` ~if $levelObj->getPageSource() eq 'N'`Matrimony~/if`
	</span>
	~else if  $urlLevel2 eq '' && $level2`
	<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
		<a href="~sfConfig::get('app_site_url')`~$urlLevel1`" itemprop="url" title="~$level1` Matrimonial"><span itemprop="title">~$level1` Matrimony</span>
		</a>
	</span> 
	&rsaquo; 
	<span itemprop="title">~$level2`</span> 
	&rsaquo;<span itemprop="title"> ~$levelObj->getH1Tag()` ~if $levelObj->getPageSource() eq 'N'`Matrimony~/if`</span>
	~else` 
	~if $levelObj->getPageSource() neq 'N'`
        <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                <a href="~sfConfig::get('app_site_url')`~$urlLevel1`" itemprop="url" title="~$level1` Matrimonial"><span itemprop="title">~$level1` Matrimony</span>
                </a>
        </span>&rsaquo;
        ~/if`
	<span itemprop="title"> ~$levelObj->getH1Tag()` ~if $levelObj->getPageSource() eq 'N'`Matrimony~/if`</span>
~/if`
</p>

<!--Breadcrumb ends here-->

<p class="clr"></p>
<!-- Slide Bar Starts Here -->
<div id="slideshow">
<ul id="menu">

<li onmousedown="javascript:trackClicks('SSS');"><a style="color:#00000;" href="#"><b>~$levelObj->getH1Tag()` 
~if $levelObj->getGroomURL()` Matrimony ~/if`</b><u></u></a></li>
<li onmousedown="javascript:trackClicks('SMP');"><a href="~sfConfig::get('app_img_url')`/profile/images/comm_pages/millions_prof.png"><b>Millions of Profiles</b><u></u></a></li>
<li onmousedown="javascript:trackClicks('SSAS');"><a href="~sfConfig::get('app_img_url')`/profile/images/comm_pages/safe_secure_brn.png"><b>Safe &amp; Secure</b><u></u></a></li>
<li onmousedown="javascript:trackClicks('SPM');"><a href="~sfConfig::get('app_img_url')`/profile/images/comm_pages/benefit_bnr.png" ><b>Paid Membership</b><u></u></a></li>
</ul>
<ul id="pictures">
<li onmousedown="javascript:trackClicks('DSS');">

<div style="width:447px;height:255px;background-color:white;cursor:default;">
<span class="fl wd pleft" id="dy_content">~if $levelObj->getContent() neq ''`<h1 class="b_head">~$levelObj->getH1Tag()`~if $levelObj->getGroomURL()` Matrimony ~/if`</h1>~/if`
<p class="clr_hp"></p>
<p class="m_col" style="font-size:11px;padding-left:2px;~if $levelObj->getContent() eq ''`margin-top:-12px;~/if`">
~if $levelObj->getContent() eq ''`<a href= "/success/success_stories.php?parentvalue=~$levelObj->getParentValue()`&mappedvalue=~$levelObj->getMappedValue()`&parenttype=~$levelObj->getParentType()`&mappedtype=~$levelObj->getMappedType()`"><img width="411" height="255" alt="Marriage"        src="~sfConfig::get("app_img_url")`/profile/images/homepagenew/home_ss48.jpg" style="display: block;" border="0"></a> ~else`~$levelObj->getContent()|decodevar`~/if`
<br />

~if $levelObj->getParentType() eq 'SPECIAL_CASES' && $levelObj->getParentValue() eq 'Cancer Survivor'`
<div  style=" display:block;width:229px; height:72px; background:#ff8a00; float:left; clear:both; padding:1px; ">

<div style="background-image:url(~sfConfig::get('app_img_url')`/seo_pg_img/hands.jpg);width:124px;height:71px;float:left"></div>
<div style=""><font style="font:12px arial; color:#FFF">Jeevansathi.com 
partners with
VCare<br>
</font></a><font style="font:12px arial; color:#FFF"><a href="~sfConfig::get('app_site_url')`/cancer/vcare" target="_blank" style="color:#fff; float:right">Know More</a></font>
</div>

</div>
~/if`</p>




</span>
<div style="float:right;background-color: #000000;">

<img src="~sfConfig::get('app_img_url')`/seo_pg_img/~$SLIDER_IMAGE`"  HEIGHT="255" border="0" alt="~$levelObj->getAltTag()`" style = "display: block;"/> 
</div>
</div>

</li>
<li onmousedown="javascript:trackClicks('DMP');"><img src="~sfConfig::get('app_img_url')`/profile/images/comm_pages/millions_prof.png" WIDTH="447" HEIGHT="255" alt="online indian matrimonial"/></li>
<li onmousedown="javascript:trackClicks('DSAS');"><img src="~sfConfig::get('app_img_url')`/profile/images/comm_pages/safe_secure_brn.png"  WIDTH="447" HEIGHT="255" alt="online indian matrimonial"/></li>
<li onmousedown="javascript:trackClicks('DPM');"><a href="~sfConfig::get('app_site_url')`/profile/mem_comparison.php"><img border="0" src="~sfConfig::get('app_img_url')`/profile/images/comm_pages/benefit_bnr.png" WIDTH="447" HEIGHT="255" alt="online indian matrimonial"/></a></li>
</ul>
<script type="text/javascript">
window.addEvent('domready', function(){new Slider('menu', 'pictures', { transition: 'fade', auto: true },document.getElementById("randomImages").value);});
</script>
<input type="hidden" id="newHomePage" name="newHomePage" value="1" />
<input type="hidden" id="randomImages" name="randomImages" value="~if $levelObj->getContent() eq ''`~sfConfig::get("app_img_url")`/profile/images/homepagenew/home_ss50.jpg~else`~sfConfig::get('app_img_url')`/seo_pg_img/~$SLIDER_IMAGE`~/if`" />
</div>


<!-- Reg Satrts Here -->
~include_partial("seo/minireg",[minireg=>$minireg,SOURCE=>$levelObj->getSource(),MtongueDropdown=>$MtongueDropdownForTemplate])`

<p class="clr"></p><div class=" sprte mid_line"></div>
<p class="clr_4"></p>
<div>

<!--heading Start -->

<div style="width:930px; margin:auto;">
<div>
~if $LOOP_M neq '0'`
<div class="pro_mn_tp fr">~if $levelObj->getGroomURL()`<a class="pro_url" href="~sfConfig::get('app_site_url')`~$levelObj->getGroomURL()`" title="~$levelObj->getH1Tag()` Matrimonial Grooms">~$levelObj->getH1Tag()` Matrimonial Grooms </a>~else`~$levelObj->getH1Tag()` ~/if` </div>
~/if`
~if $LOOP_F neq '0'`
<div class="pro_mn_tp fl">~if $levelObj->getBrideURL()`<a class="pro_url" href="~sfConfig::get('app_site_url')`~$levelObj->getBrideURL()`" title = "~$levelObj->getH1Tag()` Matrimonial Brides">~$levelObj->getH1Tag()` Matrimonial Brides</a>~else`~$levelObj->getH1Tag()`~/if`</div>
~/if`
</div>
</div>
<p class="clr_4"></p>
<!--heading end -->

<!--listing  left start -->

~include_partial('seo_profiles_list',[profileArr=>$leftArr,Cnt=>$leftCnt,title=>$titleL,levelObj=>$levelObj,textWidth=>212,left=>''])`

<!--listing left end-->

<!--listing  right start -->

~include_partial('seo_profiles_list',[profileArr=>$rightArr,Cnt=>$rightCnt,title=>$titleR,levelObj=>$levelObj,textWidth=>200,left=>1])` 

<!--listing right end-->

<p class=" clr_2"></p>
<div class=" sprte mid_line"></div>

<!--tabbing  start -->

~include_partial('seo/tabbing',[SEO_FOOTER=>$SEO_FOOTER])`
<!--tabbing  end -->

<!-- New Footer -->

<p class=" clr_18"></p>

<!--Main container ends here-->	
<script>
~if $crazyEgg`
setTimeout(function(){var a=document.createElement("script");
    var b=document.getElementsByTagName("script")[0];
    a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0011/8626.js?"+Math.floor(new Date().getTime()/3600000);
    a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
~/if`
</script>
</div>
</div>
~include_partial('global/footer',[NAVIGATOR=>~$NAVIGATOR`,bms_topright=>$bms_topright,bms_bottom=>$bms_bottom,G=>$G,viewed_gender=>$GENDER,data=>''])`
