   <!--Header starts here-->
~include_partial('global/header',[bigBanner=>$bigBanner,showGutterBanner=>1])`
 <!--Header ends here-->
 
 
<!--pink strip starts here-->
<!--Main container starts here-->
<div id="main_cont">	
    
<!--pink strip ends here-->
  <p class="clr_4"></p>
<div id="topSearchBand"></div>
~include_partial('global/sub_header')`
  <p class="clr_4"></p>
 <!--slide-bluetop starts here-->

 <!--slide-bluetop ends here-->
<!--orange strip starts here here-->

<!--orange strip ends here here-->

<!--breadcrumb section starts here-->
~if !$loginProfile->getPROFILEID()`
<div class="sp12"></div>
<p id="breadcrumbs" ><span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="~sfConfig::get('app_site_url')`" itemprop="url"><span itemprop="title">Home</span></a></span> &rsaquo; ~if $profileLinkArr['REL_LINK'] neq ''`<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="~sfConfig::get('app_site_url')`~$profileLinkArr['REL_LINK']`" itemprop="url" title="~$religionSelf` Matrimonial"><span itemprop="title">~$religionSelf` Matrimony</span></a></span> &rsaquo; ~/if`~if $profileLinkArr['MTNG_LINK'] neq ''`<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="~sfConfig::get('app_site_url')`~$profileLinkArr['MTNG_LINK']`" itemprop="url" title="~FieldMap::getFieldLabel("community_small",$profile->getMTONGUE())` Matrimonial"><span itemprop="title">~FieldMap::getFieldLabel("community_small",$profile->getMTONGUE())` Matrimony</span></a></span> &rsaquo; ~/if`~if $profileLinkArr['CASTE_LINK'] neq ''`<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="~sfConfig::get('app_site_url')`~$profileLinkArr['CASTE_LINK']`" itemprop="url" title="~$CASTE` Matrimonial"><span itemprop="title">~$CASTE` Matrimony</span></a></span> &rsaquo; ~/if`~if $profileLinkArr['BRIDE_GROOM_LINK'] neq ''`<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="~sfConfig::get('app_site_url')`~$profileLinkArr['BRIDE_GROOM_LINK']`" itemprop="url" title="~FieldMap::getFieldLabel("community_small",$profile->getMTONGUE())` ~if $PROFILEGENDER eq 'Male'`Grooms~else`Brides~/if`"><span itemprop="title">~FieldMap::getFieldLabel("community_small",$profile->getMTONGUE())`~if $PROFILEGENDER eq 'Male'` Grooms~else` Brides~/if`</span></a></span> &rsaquo; ~/if`~$TopUsername`</p>
~/if`
<!--Breadcrumb ends here-->

<!--top tab  start -->

<div class="lstnxt b">~$BREADCRUMB|decodevar`</div>
~if $OFFLINE_CALL_PROGRESS`
<div class="b fr ">Communication with user in progress</div>
~else`
<div class="~if !$OFFLINE_ASSISTANT_REM`addmem~else` b ~/if` fr">~if $OFFLINE_ASSISTANT_ADD`<img src="~sfConfig::get(app_img_url)`/images/plus-icon.gif" align="absmiddle">&nbsp;<a href="~$SITE_URL`/profile/invoke_contact_engine.php?width=400&height=360&checksum=&profilechecksum=~$PROFILECHECKSUM`&index=0&to_do=add_intro&ajax_error=1" class="thickbox">Add to "Members to be called" list</a>~/if`~if $OFFLINE_ASSISTANT_REM`Added to `members to be called list`~/if` </div>
~/if`
~include_partial("profile_sub_head",[FROM_PROFILEPAGE=>$FROM_PROFILEPAGE,TopUsername=>$TopUsername,total_rec=>$total_rec,actual_offset=>$actual_offset,j=>$j,searchid=>$searchid,other_params=>$other_params,NAVIGATOR=>$NAVIGATOR,PROFILECHECKSUM=>$PROFILECHECKSUM,SHOW_NEXT_PREV=>$SHOW_NEXT_PREV,SHOW_PREV=>$SHOW_PREV,SHOW_NEXT=>$SHOW_NEXT,SHOW_PREV=>$SHOW_PREV,fromPage=>$fromPage,prevLink=>$prevLink,nextLink=>$nextLink,OnlineMes=>$OnlineMes,stopAlbumView=>$stopAlbumView,show_profile=>$show_profile,actual_offset_real=>$actual_offset_real,curLink=>$curLink,responseTracking=>$responseTracking])`
<p class="clr"></p>
<!--profile details  start -->
<!--profile pic start -->
<div class="pro_tupn pro_tup1n">
  <div>
<div class="fl" style="padding-left:6px; margin-right:12px;">
<div class="lstlgn lsonl f11 " style="margin:0px 0px 0px 0px; color:#4e4e4e"><strong>Last online</strong><br>~$OnlineMes`</div>
~if $PHOTO eq ""`
 <div> 
 ~if $GENDER eq "M"`

	<img src="~sfConfig::get('app_img_url')`/~StaticPhotoUrls::requestPhotoMaleProfilePicUrl`" width="152" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;">
	~if !$crmback`<div style="position:relative">
	<div class="grey-highlight" id = "photo_req_layer~$other_profileid`">
	~if !$PHOTO_REQUESTED`
		<input type="image"  src="~sfConfig::get('app_img_url')`/P/images/request-photo.jpg" class="btn-req-photo" onclick = "photo_ajax_request('~$PROFILECHECKSUM`','photo_request_end',~$other_profileid`,'detail')"/>
	~else`
		<font class="btn-req-photo white  b">Photo request sent</font>
	~/if`	
	</div>
	<div id = "PHOTO_REQ~$other_profileid`" onclick="javascript:check_window('close_photo_mes(~$other_profileid`)')"></div>
	</div>	
	~/if`
~else` 
	<img src="~sfConfig::get('app_img_url')`/~StaticPhotoUrls::requestPhotoFemaleProfilePicUrl`" width="152" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;">
	~if !$crmback`<div style="position:relative">
	<div class="grey-highlight" id = "photo_req_layer~$other_profileid`">
	~if !$PHOTO_REQUESTED`
		<input type="image"  src="~sfConfig::get('app_img_url')`/P/images/request-photo.jpg" class="btn-req-photo" onclick = "photo_ajax_request('~$PROFILECHECKSUM`','photo_request_end',~$other_profileid`,'detail')"/>
	~else`
		<font class="btn-req-photo white  b">Photo request sent</font>
	~/if`
	</div>
	<div id = "PHOTO_REQ~$other_profileid`" onclick="javascript:check_window('close_photo_mes(~$other_profileid`)')"></div>
	</div>~/if`
~/if`
</div>
~else`
<div style="~if !$stopAlbumView`cursor:pointer;~/if`background-image:url(~$PHOTO`);position:relative;" ~if $ALBUM_CNT && !$stopAlbumView` onclick="document.location='~sfConfig::get("app_site_url")`/profile/albumpage?~if $curLink`&curLink=~$curLink`~else`~if $total_rec`total_rec=~$total_rec`~/if`~if $actual_offset_real`&actual_offset=~$actual_offset_real`~/if`~if $show_profile`&show_profile=~$show_profile`~/if`~if $j`&j=~$j`~/if`~if $searchid`&searchid=~$searchid`~/if`~$other_params`&~$NAVIGATOR`&profilechecksum=~$PROFILECHECKSUM`&~if $sf_request->getParameter('stype')`&stype=~$sf_request->getParameter('stype')`~/if`~if $sf_request->getParameter('clicksource')`&clicksource=~$sf_request->getParameter('clicksource')`~/if`~if $sf_request->getParameter('countlogic')`&countlogic=~$sf_request->getParameter('countlogic')`~/if`~if $sf_request->getParameter('matchalert_mis_variable')`&matchalert_mis_variable=~$sf_request->getParameter('matchalert_mis_variable')`~/if`~if $sf_request->getParameter('suggest_profile')`&suggest_profile=~$sf_request->getParameter('suggest_profile')`~/if`~if $sf_request->getParameter('CAME_FROM_CONTACT_MAIL')`&CAME_FROM_CONTACT_MAIL=~$sf_request->getParameter('CAME_FROM_CONTACT_MAIL')`~/if`~/if`'"~/if`><img src="~sfConfig::get('app_img_url')`/profile/ser4_images/transparent_img.gif" width="152" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;">
 ~if $verificationSeal`
 <div id="verficationSeal">
           ~include_partial("search/verifySealLayer",[detailsArr=>$verificationSeal])`
    </div > 
 ~/if` 
</div>
~/if`

            <div class="propicd">
		~if $ALBUM_CNT && !$stopAlbumView` <a href="~sfConfig::get("app_site_url")`/profile/albumpage?~if $curLink`&curLink=~$curLink`~else`~if $total_rec`total_rec=~$total_rec`~/if`~if $actual_offset_real`&actual_offset=~$actual_offset_real`~/if`~if $show_profile`&show_profile=~$show_profile`~/if`~if $j`&j=~$j`~/if`~if $searchid`&searchid=~$searchid`~/if`~$other_params`&~$NAVIGATOR`&profilechecksum=~$PROFILECHECKSUM`&~if $sf_request->getParameter('stype')`&stype=~$sf_request->getParameter('stype')`~/if`~if $sf_request->getParameter('clicksource')`&clicksource=~$sf_request->getParameter('clicksource')`~/if`~if $sf_request->getParameter('countlogic')`&countlogic=~$sf_request->getParameter('countlogic')`~/if`~if $sf_request->getParameter('matchalert_mis_variable')`&matchalert_mis_variable=~$sf_request->getParameter('matchalert_mis_variable')`~/if`~if $sf_request->getParameter('suggest_profile')`&suggest_profile=~$sf_request->getParameter('suggest_profile')`~/if`~if $sf_request->getParameter('CAME_FROM_CONTACT_MAIL')`&CAME_FROM_CONTACT_MAIL=~$sf_request->getParameter('CAME_FROM_CONTACT_MAIL')`~/if`~/if`" class="jshup1 sprte">Photos (~$ALBUM_CNT`)</a>~/if`          
		 <br />
		 <div id="LAGAN_ID_PROFILE_~$other_profileid`" class="lagan_id"></div>
		 <BR>
	</div></div>
<!--profile pic end -->
<!--profile content start -->
<div class="fl" style="width:320px">
<div class="protop b" style="float:left;width:100%">
<div class="fl" style="color:#000;">About ~$HIMHER` &nbsp;</div>

~if $ISONLINE eq "1" and $CANNOTCONTACT neq "1"`
<div class="fl">
&nbsp;&nbsp;&nbsp;<a  href="#" onClick="openChatWindow('~$PROFILECHATID`','~$PROFILECHECKSUM`','~$PROFILECHATID`','~$PROFILENAME`','','~$CHECKSUM`'); return false;" class="jshup6 sprte" title="Profile Online on Jeevansathi.com – Click here to chat">&nbsp;</a>&nbsp;&nbsp;
</div>
~else`
~if $GTALK_ONLINE eq "1" and $CANNOTCONTACT neq "1"`
<div class="fl">

&nbsp;&nbsp;&nbsp;<a onClick="openChatWindow('~$PROFILECHATID`@gmail.com','~$PROFILECHECKSUM`','~$PROFILECHATID`','~$PROFILENAME`','','~$CHECKSUM`'); return false;" class="gtalkhup6 sprte" title="Profile Online on Gtalk – Click here to chat">&nbsp;</a>&nbsp;&nbsp;
</div>
~/if`
~/if`
~if CommonFunction::isEvalueMember($profile->getSUBSCRIPTION())`
<div class="fl e_vlu_icon sprt_cn_ctr f_2 mar3top"></div>
~else if CommonFunction::isJsExclusiveMember($profile->getSUBSCRIPTION())`
<div class="fl jsexlusivebg f_2 mar3top"></div>
~else if CommonFunction::isErishtaMember($profile->getSUBSCRIPTION())`
<div class="fl e_rshta_icon sprt_cn_ctr f_2 mar3top"></div>
~/if`
<p style="height:5px;"></p>

</div> <div style="float:left;padding:2px 3px 5px 0px;clear:both;width:320px">
~include_partial("profile/moreabout",['NameValueArr'=>$moreAboutArr,'InfoLimit'=>$InfoLimit,'PROFILENAME'=>$PROFILENAME])`
<br />

<div class="fl" style="margin-top:7px">
</div>
 </div>
 
</div>

    <div class="clr"></div>
</div>
</div>

<!--profile content end -->
<!--profile widget start -->
<script>
	var onlyContactTab=0;
	~if $tabTemplate`
onlyContactTab=1;
~/if`
var defaultContactTab=~$defaultContactTab`;
var dp_type="";
var dp_login="~$LOGIN`";
var postDataVar={'page_source':'VDP','responseTracking':'~$responseTracking`'
 };

</script>
<div class="prof_wid">
<ul class="tab">
~if $tabName`<li  class="active" id="exp_layer" ><a><i></i><u>&nbsp;~$tabName`&nbsp;&nbsp;</u></a></li>~/if`
<li class="notactive" id="con_layer" ><a ><i></i><u>&nbsp;Contact Details&nbsp;&nbsp;</u></a></li>
</ul>
<div id="exp_tab">
~if $LOGIN && $tabName`	~include_partial("contacts/~$contactEngineObj->getComponent()->layoutTpl`",['contactEngineObj'=>$contactEngineObj])`
~else`
~if !$LOGIN`
~include_partial("contacts/profile_logout")`
~/if`
~/if`
</div>
~if $LOGIN`
~if $contactDetailObj->getComponent()->innerTpl`
<div id="con_tab" style="~if $tabName`display:none~/if`">
	
	
~include_partial("contacts/~$contactDetailObj->getComponent()->layoutTpl`",['contactEngineObj'=>$contactDetailObj])`

</div>
~/if`
~else`

<div id="con_tab" style="~if $tabName`display:none~/if`">
<div class="profile-widget-container">
	~if $showContactDetail`
~include_partial("profile/logoutcontactenginepage",[RANDOMNUMBER=>$RANDOMNUMBER,showRegisterPage=>$showRegisterPage,showTollFree=>$showTollFree,yearArray=>$yearArray,dayArray=>$dayArray,mtongue=>$userMtongue,seo_community_js=>$seo_community_js])`
~else`
~include_partial("contacts/profile_cd_logout")`
~/if`
</div>
</div>
~/if`
</div>

<!--profile widget end -->


<!--top tab  end -->
 <p class=" clr_18"></p>
  <!--mid bottom content start -->
  <!-- start no selected tab -->
<div id="subtab" style="width:950px;">
<ul>
<li style="width:170px"><a  style="cursor:pointer" title="Horoscope" onclick=~if $HOROSCOPE eq 'N' and !$HIDE_HORO`"return horos_ajax_request('~$PROFILECHECKSUM`',0)"~else`"return change_tab(this,'Horoscope',0,1)"~/if`  id="horoscope" ><span><div class="spritem lf hrscpe_icon"></div><strong>&nbsp;~if $HOROSCOPE eq 'N' and !$HIDE_HORO` Request~else` View~/if` Horoscope</strong></span></a></li>

<li style="width:185px">~if $LOGIN`<a  style="cursor:pointer" title="~if $contactObj->getTYPE() eq 'N' && !$tempContact`Contact this Profile~else`Communication History~/if`"  onclick="return change_tab(this,'Contact_History',0,1,1)" id="contact_history" ><span><div class="spritem lf cntc_hstry_icon"  ></div>&nbsp;<strong>~if $contactObj->getTYPE() eq 'N' && !$tempContact`Contact this Profile~else`Communication History~/if`</strong></span></a>~/if`</li>

<li style="width:130px" >~if !$ERROR_MESSAGE`<a href="~sfConfig::get('app_site_url')`/profile/bookmark_add.php?type=show&MODE=S&width=350" class="thickbox" title="Shortlist Profile"  id="bookmark" ~if $BOOKMARKED eq 1` style="display:none" ~/if`><span><div class="spritem lf ad_fvr"></div>&nbsp;Shortlist Profile</span></a> <a href="~sfConfig::get('app_site_url')`/profile/bookmark_remove.php?type=show&MODE=S&width=512&username=~$PROFILENAME`" class="thickbox" title="Remove Shortlist"     id="bookmark_rem" ~if $BOOKMARKED neq 1` style="display:none" ~/if`><span><div class="spritem lf ad_fvr"></div>&nbsp;Remove Shortlist</span></a>
~/if`
</li>

<li style="width:125px">
~if !$ERROR_MESSAGE`
<a href="~sfConfig::get("app_site-url")`/profile/forward_profile.php?profilechecksum=~$PROFILECHECKSUM`&username=~$PROFILENAME`&width=512" class="thickbox" title="Forward Profile"><span><div class="spritem lf frw_frnd_icon"></div>&nbsp;Forward profile</span></a>
~/if`
</li>

<li  style="width:68px">
~if !$ERROR_MESSAGE`
<a  class="blink" onclick="MM_openBrWindow('~sfConfig::get("app_site-url")`/profile/viewprofile.php?profilechecksum=~$PROFILECHECKSUM`&PRINT=1','','width=800,height=600,scrollbars=yes');" title="Print" onclick="cursor:pointer"><span><div class="spritem lf prnt_prof_icon"></div>&nbsp;Print</span></a>
~/if`
</li>

<li style="width:119px">
~if !$ERROR_MESSAGE`
<a href="~sfConfig::get('app_site_url')`/profile/layer_ignore.php?showtemp=1&checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&other_username=~$PROFILENAME`&width=512" class="thickbox  ~if $IGNORED neq '1'` block ~else` dspN ~/if`" title="Block Profile"  id='ignore'><span><div class="spritem lf ignr_prof_icon"></div>&nbsp;Block Profile</span></a>

<a href="~sfConfig::get('app_site_url')`/profile/ignore_profiles_list.php?showtemp=1&checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&username=~$PROFILENAME`&width=512&pid=~$other_profileid`&random=~$RANDOMNUMBER`&reload=1" class="thickbox ~if $IGNORED neq '1'` dspN ~else` block ~/if`" title="Unblock Profile"  id='unblock' onclick="blockUnblockToggle('ignore','unblock')"><span><div class="spritem lf ignr_prof_icon"></div>&nbsp;Unblock Profile</span></a>
~/if`
</li>

<li  style="width:122px">
<a href="~sfConfig::get('app_site_url')`/profile/faq_other.php?width=512&setOption=Abuse" style="cursor:pointer" class="thickbox" ><span><div class="spritem lf js_cht_icon"></div>&nbsp; Report Abuse</span></a>
</li>

</ul>
</div>
<div class="lf" id="show_tab" style="width:935px">
</div>
<div id="contactTab" style="display:none">
	~if $tabTemplate`
	<div class="contactTab t12">
~include_partial("contacts/$tabTemplate",[contactEngineObj=>$contactEngineObj])`
</div>
~/if`
</div>
<!-- End -->
 <p class=" clr"></p>

<div>
<div class="lf t12 b" style="width:635px;padding:5px; border-right: 1px solid #aeaeae; margin-right:3px;" id="profileData">
<div><h2 class="protop1 b" style="color:#000;">Basic Information of ~$PROFILENAME`</h2></div>

<!--left basic information-->
~include_partial("profile/leftbasicinfr",['AGE'=>$AGE,'HEIGHT'=>$HEIGHT,'PROFILEGENDER'=>$PROFILEGENDER,'religionSelf'=>$religionSelf,'MTONGUE'=>$MTONGUE,'CASTE'=>$CASTE,'SUBCASTE'=>$SUBCASTE,casteLabel=>$casteLabel,sectLabel=>$sectLabel,CODEOWN=>$CODEOWN,'PROFILELINK'=>$profileLinkArr])`
<!--end left basic information-->

<!--right basic information-->
~include_partial("profile/rightbasicinfr",['MSTATUS'=>$MSTATUS,'Annulled_Reason'=>$Annulled_Reason,'CHILDREN'=>$CHILDREN,'EDU_LEVEL_NEW'=>$EDU_LEVEL_NEW,'OCCUPATION'=>$OCCUPATION,'CITY_RES'=>$CITY_RES,'COUNTRY_RES'=>$COUNTRY_RES,'INCOME'=>$INCOME,'religionSelf'=>$religionSelf,'GOTHRA'=>$GOTHRA,'GOTHRA_MATERNAL'=>$GOTHRA_MATERNAL,'RELATION'=>$RELATION,casteLabel=>$casteLabel,sectLabel=>$sectLabel,CODEOWN=>$CODEOWN,'PROFILELINK'=>$profileLinkArr])`
<!--end right basic information-->


<p class="clr"></p>
~if $rowTemplate`
~include_partial("contacts/$rowTemplate",[contactEngineObj=>$contactEngineObj])`
~/if`
<div class="sp12"></div>

<div class="sp12"></div>
<div class="rf" style="margin:5px 7px 0px;"><a href="#" class="b blink">Go to top <img src="~sfConfig::get("app_img_url")`/images/icon_blue_up.gif" border="0"></a></div>
<div class="sp12"></div>
<!-- Religion and Ethnicity start here -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$ReligionAndEth,'isEdit'=>$isEdit,'LabelHeading'=>"Religion and Ethnicity",'viewPage'=>"1","CODEOWN"=>$CODEOWN])`
<!-- Religion and Ethnicity end here -->

~if $profile->getSHOW_HOROSCOPE() neq 'D'`
~include_partial("profile/genericProfileSection",['NameValueArr'=>$AstroKundaliArr,'isEdit'=>$isEdit,'LabelHeading'=>"Astro/ Kundali Details",'rightSect'=>1,'viewPage'=>"1","CODEOWN"=>$CODEOWN,'HOROSCOPE'=>$HOROSCOPE,HIDE_HORO=>$HIDE_HORO,PROFILECHECKSUM=>$PROFILECHECKSUM])`
~else`
<div class="lf" style="width:48%;margin-left:20px;_margin-left:10px;">
<div class="lf pd5 subhd">Astro/ Kundali Details&nbsp;~if $isEdit`<a href="/profile/editProfile?flag=~$editFlag`&width=700" style="font-size:14px; color:#0f71ae;" class="thickbox">[Edit]</a>~/if`</div>

<div class="row2 no_b">User has hidden all Astro/Kundli Details.Please email/call the person to get horoscope/kundli.
~if $paid eq 'N' and !$SAMEGENDER and $LOGIN`<div style="padding-top:5px">To view contact details <br><br><input type="button" class="b green_btn" value="Buy Membership" style="width:146px;"  onclick="javascript:{document.location= '~sfConfig::get('app_site_url')`/profile/mem_comparison.php?from_source=Horoscope_Request_From_Detailed';}">
</div>
~/if`
</div>
</div>
~/if`

<div class="sp12"></div>
<!-- Family Details start here -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$familyArr,'isEdit'=>$isEdit,'LabelHeading'=>"Family Details",'viewPage'=>"1","CODEOWN"=>$CODEOWN])`
<!-- Family details end here -->
<!-- Education Section starts -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$educationAndOccArr,'isEdit'=>$isEdit,'LabelHeading'=>"Education and Occupation",'rightSect'=>1,'viewPage'=>"1","CODEOWN"=>$CODEOWN])`
<!-- Education Section ends -->

<div class="sp12"></div>
<!-- LifeStyle Section starts -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$lifeAttrArray,'isEdit'=>$isEdit,'LabelHeading'=>"Lifestyle and Attributes","viewPage"=>"1","CODEOWN"=>$CODEOWN])`
<!-- Lifestyle Section ends -->
<!-- Hobbies Section starts -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$Hobbies,'isEdit'=>$isEdit,'LabelHeading'=>"Hobbies and Interest",'rightSect'=>1,"viewPage"=>"1","CODEOWN"=>$CODEOWN])`
<!-- Hobbies Section end -->
<p class=" clr_18"></p>
<div class="gry-brd-bot"></div>
<div style="margin: 5px 7px 0px;" class="rf"><a class="b blink" href="#">Go to top <img border="0" src="~sfConfig::get("app_img_url")`/images/icon_blue_up.gif"></a></div> ~include_partial("profile/dppPart",['loginProfile'=>$profile,'dpartner'=>$profile->getJpartner(),'casteLabel'=>$casteLabel,'religionSelf'=>$religionSelf,"viewPage"=>"1","CODEDPP"=>$CODEDPP,'PROFILENAME'=>$PROFILENAME,'show_nhandicap'=>$show_nhandicap,'PARTNERLINK'=>$partnerLinkArr])`
<p class="clr_18"></p>
~if $lastrowTemplate`
~include_partial("contacts/~$lastrowTemplate`",[contactEngineObj=>$contactEngineObj])`
~/if`
<div class="lbdm">
<div style="font-size:15px;float:left"><strong style="color:#3c3c3c;">Profile Link :</strong><a href="#" onclick="return false;" class="ncpCE"> ~sfConfig::get('app_site_url')`/profiles/~$PROFILENAME`</a></div>

<div style="font-size:11px; color:#3490b6; padding-left:20px;float:right" class="no_b1"><img src="~sfConfig::get("app_img_url")`/images/forward.gif"  align="absmiddle"/>&nbsp; <a href="~sfConfig::get("app_site-url")`/profile/forward_profile.php?profilechecksum=~$PROFILECHECKSUM`&username=~$PROFILENAME`&width=512" class="thickbox" title="Forward Profile">Forward this profile</a></div>

</div>
~assign var = date_mod value = $profile->getMOD_DT()`
<div class="sp5"></div><div  class="t10">This page was last updated on ~date("d/m/Y",JSstrToTime($date_mod))`</div>
 <p class=" clr_18"></p>
 <div class="rf" style="margin:5px 7px 0px;"><a href="#" class="b blink">Go to top <img src="~sfConfig::get("app_img_url")`/images/icon_blue_up.gif" border="0"></a></div>

</div>

  <!--right part strat here-->


<div class="lf" style="width:260px;" id="right_banners" align="center"> 

<!-- similar profiles section added by prinka : start -->
<p class=" clr_4"></p>
<div class="protop1 b" id='similar_1' style='float:left;display:none; color:#000;'>
        <h3>
                &nbsp;&nbsp;Similar Profiles
        </h3>
</div>

        <p class=" clr_12"></p>
<div id='similar_b' align="left">
        ~section name=i loop=$suggAlgoNoOfResultsToBeShownAtATime`
                <div class="no-grey-box" id='simi~$smarty.section.i.index`' style='display:none;margin-left:10px' >
                        <table cellpadding="0" cellspacing="0" border="0" width="260px" style="margin:6px 5px 6px 9px">
                                <tr>
                                        <td oncontextmenu="return false" width="62">
						<a href="#" id='photoUrl~$smarty.section.i.index`' border="0" >
                                                	<img  id='thumbnail~$smarty.section.i.index`' src='' height="75" width="75" border='0' />
						</a>
                                        </td>
                                        <td width="2">
                                        </td>
                                        <td width="162" valign="top" style="padding-top:5px">
                                                <p>
                                                        <a id='simProfUrl~$smarty.section.i.index`' href="#" ><span id='sim~$smarty.section.i.index`' style="_text-align:left;!important;color: #000;font-size:13px;" ></span>
                                                        </a>
                                                </p>                  
                                        </td>
                                </tr>
                        </table>
                </div>
        ~/section`
        <p class=" clr_4"></p>
        <p class=" clr_12"></p>
        <div class="sp8">
        </div>
</div>

<!-- similar profiles section added by prinka : end -->


  <p class=" clr_4"></p> 

         <p class=" clr_4"></p>   
     <p class=" clr_4"></p>
			
        <p class=" clr_4"></p>
 ~if !$LOGIN and sfConfig::get('mod_profile_lead_banner')`
<script type="text/javascript">

function dID(arg){
     return document.getElementById(arg);
	 }
function submit_me(){
var lead_email_val=dID("lead_email").value;
if(!check_email(lead_email_val)){
  dID("lead_email").focus();
  dID('email_err').innerHTML="Please enter valid email address";
}
else{
dID('email_err').innerHTML="&nbsp;";
dID('form_d').style.display="none";
dID('thanks').style.display="block";
$.ajax({
type: 'POST',
url: '/profile/sugarcrm_registration/create_lead.php',
data: { viewed_profileid: dp_otherProfile, email: lead_email_val,gender: '~$GENDER`'~if sfConfig::get('mod_profile_show_age')`, age: dID("lead_age").value~/if` ~if sfConfig::get('mod_profile_show_mobile')`,mobile: dID("lead_mobile").value~/if`~if sfConfig::get('mod_profile_show_mtongue')`, mtongue: dID("lead_mtongue").value ~/if` },
success:function(data){
}
});
}
return false;
}
</script>
 <div style="width:248px; height:598px; border:1px solid #b4c2d4; background:#cdddf2; overflow:hidden;">
<div><img src="~sfConfig::get('app_img_url')`/images/lead_banner.gif" width="248" height="362" border="0" /></div>
<form action="#" method="post" name="form2" id="form2" style="margin: 0px; padding: 0px;" onsubmit="submit_me();return false;">
<div style="width:225px; padding-left:16px; padding-right:8px; font-family:Verdana; color:#4d4d4d;">
<div id="form_d" style="display:block">
        <div style="font-family:Arial; font-size:14px; color:#010101; line-height:17px;">Get similar matches <br><span style="color:#272a2d;">Right in your INBOX!</span></div>
    <table border="0" cellspacing="0" cellpadding="0" style="font-size:14px; font-family:Arial; line-height:18px;">
    <tr>
    <td height="10"></td>
    </tr>
    ~if sfConfig::get('mod_profile_show_age')`
    <tr>
    <td style="padding-left:3px">AGE:</td>
    </tr>
    <tr>
    <td align="center">
    <input type="text" onblur="(this.value=='')? (this.value='Age',this.style.color='#666666'):''" onfocus="(this.value=='Age')? (this.value='',this.style.color='#000000'):''" value="Age"  style="width:207px; height:19px; border:1px solid #4d6185; margin:2px 0; padding:0; font-size:11px;"  name="lead_age" id="lead_age" />
    </td>
    </tr>
    <tr>
    <td height="5"></td>
    </tr>
    ~/if`
    ~if sfConfig::get('mod_profile_show_mobile')`
    <tr>
    <td style="padding-left:3px">MOBILE:</td>
    </tr>
    <tr>
    <td align="center"><input type="text" onblur="(this.value=='')? (this.value='Mobile',this.style.color='#666666'):''" onfocus="(this.value=='Mobile')? (this.value='',this.style.color='#000000'):''" value="Mobile" style="width:207px;; height:19px; border:1px solid #4d6185; margin:2px 0; padding:0; font-size:11px;" name="lead_mobile" id="lead_mobile" /></td>
    </tr>
    <tr>
    <td height="5"></td>
    </tr>
    ~/if`
  
    <td align="center"><input type="text" onblur="(this.value=='')? (this.value='Email Address',this.style.color='#666666'):''" onfocus="(this.value=='Email Address')? (this.value='',this.style.color='#000000'):''" value="Email Address" style="width:207px; height:19px; border:1px solid #4d6185; margin:2px 0; padding:0; font-size:11px;" name="lead_email" id="lead_email" /></td>
    </tr>
    <tr>
    <td style="color:#e93a3e;font-size:11px;height:15px;" id="email_err">&nbsp;</td>
    </tr>
        ~if sfConfig::get('mod_profile_show_mtongue')`
    <tr>
    <td style="padding-left:3px">Mother Tongue:</td>
    </tr>
    <tr>
    <td align="center"><input type="text" onblur="(this.value=='')? (this.value='Mother Tongue',this.style.color='#666666'):''" onfocus="(this.value=='Mother Tongue')? (this.value='',this.style.color='#000000'):''" value="Mother Tongue" style="width:207px;; height:19px; border:1px solid #4d6185; margin:2px 0; padding:0; font-size:11px;" name="lead_mtongue" id="lead_mtongue" /></td>
    </tr>
    <tr>
    <td height="5"></td>
    </tr>
    ~/if`
     <tr>
    <td height="10"></td>
    </tr>
    <tr>
        <td height="30" align="center"><input type="submit" name="Submit" value="Submit" title="Submit"  style="background:url(~sfConfig::get('app_img_url')`/images/lead_banner_btnimg.gif) no-repeat left top; color:#2b2418; height:25px; font-size:14px; font-weight:bold; width:78px; border:0px; text-transform:uppercase;" /></td>
    </tr>
    </table>
</div>
<div id="thanks" style="display:none;font-size:16px;color:green">
<p class="clr_18"></p>
<p class="clr_18"></p>
<p class="clr_18"></p>
 "Thanks for your info. You will be receiving matches in a few days!"
</div>
</div>
</form>
</div>
 ~else`
  <div>
~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
~assign var=zedo value= $zedoValue["zedo"]`
~assign var=zedoTag value= $zedo["tag"]`
~if $zedoTag.right_banner1`
  		<p style="display:inline; margin-right:1px; float:left;" id="zt_~$zedo['masterTag']`_right_banner1">
  		
  		</p>
  		~/if`


  </div>
  <div class="sp8"></div>
  ~/if`
  <div class="sp8"></div>
<!--
  <div>
  	
  		<p style="display:inline; margin-right:1px; float:left;" id="right_banner2">
  		
  		<span id = "zedo_right_banner2"></span>
  		</p>
  		
  </div>
-->
</div>
</div>
  <!--right part ends here-->

 <p class=" clr_2"></p>

<p class="clr_18"></p>
<!-- photo request layer -->
~if $PHOTO eq ""`
	<div style = "display:none" id = "req_mes">
		~if $loginProfile->getHAVEPHOTO() eq 'N' or $loginProfile->getHAVEPHOTO() eq ''`
			<div class="div_interactions fl  fs12" id="success_mesPROFILEID" style="position:absolute;left:160px;top:-30px;" >    
				<div class="divlinks fl w240 pos_rltv1" style="padding:0px 0px 10px 10px"  >
					<div class="fr ico_close_green mar_top_4" id="closeIconPROFILEID" onclick = "close_photo_mes(PROFILEID)"></div>
					<p class="width100 fl"><i class="ico_right_sml fl">&nbsp;</i>Your photo request has been sent.
					<br />
    			
					<font class="b"><a href="~sfConfig::get('app_site_url')`/social/addPhotos">Upload your photo now >></a></font></p>
    			
    			</div>
			</div>
		~/if`
	</div>
	<div style = "display:none" id = "err_mes">
		<div class="div_interactions fl  fs12" id="success_mesPROFILEID" style="position:absolute;left:160px;top:-30px;" >   
			<div class="divlinks fl w240 pos_rltv1" style="padding:0px 0px 10px 10px"  >
				<div class="fr ico_close_green mar_top_4" id="closeIconPROFILEID" onclick = "close_photo_mes(PROFILEID)"></div>
				<p class="width100 fl"><i class="ico_cross fl">&nbsp;</i>ERROR MESSAGE
    			<br />
    		</div>
		</div>
	</div>
~/if`
<!--  photo request layer ends here -->


<!--mid bottom content end -->
 <p class=" clr_18"></p>
<!--footer tabbing  start -->
<!--Main container ends here-->	


<script type="text/javascript">

//code added for similar profiles section by prinka - start

var NAVIGATOR='~$NAVIGATOR`';
var numRand = Math.floor(Math.random()*101)
var DATA = "viewed=~$PROFILECHECKSUM`&viewedGender=~$GENDER`&searchid=~$searchid`&"+numRand;
var NO_OF_RESULTS = '~$suggAlgoNoOfResultsToBeShownAtATime`';

var updateEvalueTracking = '~sfContext::getInstance()->getRequest()->getAttribute('updateEvalueTracking')`';

//corresp js code in viewSimilar_js.js
//code added for similar profiles section by prinka - end

var dp_otherProfile='~$other_profileid`';
var gender='~$GENDER`';
google_plus=0;
dp_contactStatus="~$contact_status`";
dp_checksum="~$CHECKSUM`";
dp_profileChecksum="~$PROFILECHECKSUM`";
dp_profilechecksum="~$PROFILECHECKSUM`";
dp_horoScope="~$HOROSCOPE`";
dp_profileChecksumNew="~$PROFILECHECKSUM_NEW`";
dp_errorMessage="~$ERROR_MESSAGE`";
dp_sameGender="~$SAMEGENDER`";
dp_filter="~$FILTER`";
dp_profileName="~$PROFILENAME`";
dp_navig="~$NAVIGATOR`";
dp_contactHistoryTab="~$LOAD_CONTACT_HISTORY_TAB`";

dp_callAccess="~$CALL_ACCESS`";
dp_callTabSel="~$CALL_TAB_SEL`";
dp_calledOnce="~$CALLED_ONCE`";
dp_errorReceiverUnVerified="~$ERROR_RECEIVER_UNVERIFIED`";
dp_success="~$SUCCESS`";
dp_recProfileid="~$REC_PROFILEID`";
dp_showContactTabEv="~$SHOW_CONTACT_TAB_EV`";
//Added by Anand
kundli_type="~$KUNDLI_TYPE|encodehtml`";
//Added by Anand ends
PH_LAYER_STATUS_DP='~$PH_UNVERIFIED_STATUS`';
~if $contactLimitMessage eq "Not Valid" && $PH_UNVERIFIED_STATUS`
noExpressInterest=1;
~/if`

</script>
<script type="text/javascript">
var head_tab="~$head_tab`";

</script>
~if $SEO_FOOTER`
<p class="clr_8"></p><p class="clr_8"></p>
~include_partial('seo/tabbing',[SEO_FOOTER=>$SEO_FOOTER])`
~/if`
</div>
~BrijjTrackingHelper::setJsLoadFlag(1)`
~include_partial('global/footer',[NAVIGATOR=>~$NAVIGATOR`,G=>$G,viewed_gender=>$GENDER,data=>$loginProfile->getPROFILEID(),'bigBanner'=>$bigBanner])`
<style>
    div.row2 label{width:115px !important}
</style>