 ~include_partial('global/header',[showGutterBanner=>1])`
<link rel="stylesheet" type="text/css" href="~sfConfig::get(app_img_url)`/css/edit_layer_css_1.css" />
<link rel="stylesheet" type="text/css" href="~sfConfig::get(app_site_url)`/css/~$rupeeSymbol_css`">
<!--Main container starts here-->
<!--pink strip starts here-->
<div id="main_cont">	
 <!--Header ends here-->
  <p class="clr_4"></p>
<div id="topSearchBand"></div>
<?php include_partial('global/sub_header') ?>
<!--slide-bluetop ends here-->
   <p class="clr_4"></p>
   <p class="clr_4"></p>
<!--orange strip starts here here-->
<!--orange strip ends here here-->
<!--top tab  start here-->
    <p class="clr"></p>
    <p class=" clr"></p>
    <p class=" clr_4"></p>

~if $showGotItBand`
	~include_partial("global/gotItBand",['GotItBandPage'=>"~$GotItBandPage`",'GotItBandMessage'=>"~$GotItBandMessage`"])`
~/if`
<div class="txt_plus_ul11 fl">
    <ul class="tab">
       <li class="active">
       <a href="#"><i></i><u class="b" style="font-size: 15px; color: #000;">My Profile (~$TopUsername`)&nbsp;</u></a>
       </li>
      <li><a href="~sfConfig::get(app_site_url)`/social/addPhotos"><i></i>
      <u class="b" style="font-size: 15px; color: #000;">Photos & more... </u></a>
      </li>
    </ul>
    <p class="clr"></p>
    <p class="clr_18"></p>
</div>
<!--top tab ends here -->
<!--photo and other top details starts here -->
<div class="fl" style=" background-image:url(~sfConfig::get(app_img_url)`/images/myprobg.gif); background-repeat:repeat-x;padding-left:6px; margin-right:12px; width:930px; ">
~if $INCOMPLETE eq 'Y'`
~include_partial("profile/content1_incomplete",['GENDER'=>$GENDER,'PHOTO'=>$PHOTO,'NO_OF_PHOTOS'=>$NO_OF_PHOTOS])`
~else`
~if $NO_FTO`
 <!--profile pic starts -->       
  <div class="pro_tupn pro_tup1n1" style="width:470px">
      <div>
      <div class="fl" style="padding-left:6px; margin-right:12px;">
          ~if $PHOTO eq ""`
 <div>
 ~if $GENDER eq "M"`

	~if !$crmback`<a  href="/social/addPhotos"~/if`><img src="~sfConfig::get('app_img_url')`/images/upload_photo_male.jpg" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;">~if !$crmback`</a>~/if`
~else`
	~if !$crmback`<a  href="/social/addPhotos">~/if`<img src="~sfConfig::get('app_img_url')`/images/upload_photo_female.jpg" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;">~if !$crmback`</a>~/if`
~/if`
</div>
~else`
<div>~if !$crmback`<a  href="/social/addPhotos">~/if`<img src="~$PHOTO`" width="150" height="200" GALLERYIMG="NO" border="1" oncontextmenu="return false;">~if !$crmback`</a>~/if`</div>
~/if`
      <br />
      <br />
      </div>
   </div>
<!--profile pic end -->
<!--profile content start -->
   <div style="padding-left:12px;margin-top:90px;">
   <div class="propicd fl b">
   <div><a href="/social/addPhotos" class="naukri_btn3 sprte">&nbsp;</a></div>
 	<p class=" clr_18"></p>
    <p class=" clr_18"></p>
    </div>
    ~if $OLDER_WITH_NO_PHOTO and $PHOTO eq ""`
   <div style="width:281px; border-radius:7px 7px 7px 7px" class="fl upclr1">
<div class="fl widthauto"><img src="~sfConfig::get('app_img_url')`/images/sml_img~if $GENDER eq 'F'`_girl~/if`.jpg"></div>
<div class="fl" style="padding-left:15px">
You can keep your
<font color="#c20102;">
<spam>Photos private </spam></font><img src="~sfConfig::get('app_img_url')`/images/lock_red.jpg"><br>
 show photos only to members 
you like
</div>
</div>
</div>
    
   
    ~else`
    <div class="fl upclr"> Uploading Photos gives you<br />
	<font color="#c20102;"><spam>8 times</spam></font> more responses</div> 
    </div>
    ~/if`
</div>
<!--profile content end -->
<!--profile content1 start -->
<div class="fr" style="width:450px;">
       <div class="fl">
                <div style="margin-right:50px;_width:200px;">
                <div class="protop2n b" style="color:#000">Profile Status
                <br />
                </div>
                <div class="pfbar">Your Profile is ~$iPCS`% Complete
                <br />
                <table width="185"  border="0" cellspacing="0" cellpadding="0" class="pgbarborder">
  <tr>
    <td><table width="~$iPCS`%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="pgbar b" align="center" style="color:#fff;">~$iPCS`% </td>
  </tr>
</table></td>
  </tr>
</table><br><br>
                Last Edited on : <strong style="color:#000000;" class="t12">~$profileModDate`</strong>
                <br />
                Profile Views &nbsp;&nbsp;&nbsp;: <strong style="color:#000000;" class="t12">~$profileViews`</strong><p style="height:2px"></p>
                </div>
             </div>
       </div>
	<div>
	<script>
		function show_tp(id){
			document.getElementById(id).style.display='block';
			return false;
		}
		
		function hide_tp(id){
			document.getElementById(id).style.display='none';
			return false;
		}
	</script>
    </div>
    ~if $iPCS neq 100`
	<p style=" height:45px;"></p> 
	<div style="border-top:1px solid #e9e9e9; width:470px;_width:100%;_float:left;" class="fr">&nbsp;</div><br/>
	<div class="clr"></div> 
	<div class="protop2n b" style="white-space:nowrap; color:#000;">Add the following to complete your profile</div>
    <div style="font-size:12px; color:#0f72ad;margin-bottom:10px;" class="no_b">
        <div>
			<p style="padding-bottom:4px;">
				~foreach from=$arrMsgDetails key=szKey item=szVal`
					~if $szKey neq PHOTO`
						<img src="~sfConfig::get(app_img_url)`/images/arrow_right.gif" />&nbsp;&nbsp;<a href="~$arrLinkDetails[$szKey]`" class="thickbox"  style="color:#0f72ad;" >~$szVal`</a><br>
					~else`
						<img src="~sfConfig::get(app_img_url)`/images/arrow_right.gif" />&nbsp;&nbsp;<a href="~$arrLinkDetails[$szKey]`" class="blink" style="color:#0f72ad;" >~$szVal`</a><br>
					~/if`
				~/foreach`
			</p>
		</div>
        ~/if`
~else`
~if $FtoState eq 'C1' or $FtoState eq 'C2'`
~include_partial("profile/content1_upload_photo",['GENDER'=>$GENDER])`
~/if`
~if $FtoState eq 'D1' or $FtoState eq 'D2' or $FtoState eq 'D3' or $FtoState eq 'D4' or $FtoState eq 'E4'`
~include_partial("profile/content1_d1",['GENDER'=>$GENDER,'PHOTO'=>$PHOTO,'LAYER_URL'=>$LAYER_URL,'LAYER_TEXT'=>$LAYER_TEXT,'FtoState'=>$FtoState,'INTR_REC'=>$INTR_REC,'INTR_SENT'=>$INTR_SENT,'ACCPT_REC'=>$ACCPT_REC,'PROFILE_VIEW'=>$profileViews,'NO_OF_PHOTOS'=>$NO_OF_PHOTOS])`
~/if`
~if $FtoState eq 'C3'`
~include_partial("profile/content1_verify_phone",['GENDER'=>$GENDER,'PHOTO'=>$PHOTO,'NO_OF_PHOTOS'=>$NO_OF_PHOTOS])`
~/if`
~/if`
~/if`
</div>
<!--profile content1 end -->
</div>
	<p class="clr_18"></p>

<!--profile details starts here  -->

<div class="lf" style="width:28%; border-left: 1px solid #eaeaea;min-height:145px;">
<div class="protop1 b" style="border-left: none; padding-left:5px; color:#000;">Basic Information&nbsp;<a href="~$SITE_URL`/profile/editProfile?flag=PBI&width=700" class="thickbox" style="font-size:14px; color:#0f71ae;">[Edit]</a></div>
    <div class="row2n1 no_b ">
    <label>Posted by</label><div class="rf" style="width:135px">: ~$loginProfile->getDecoratedRelation()`</div></div>
    <div class="row2n1 no_b ">
    <label>Gender</label><div class="rf" style="width:135px">: ~$loginProfile->getDecoratedGender()`
    </div></div>
    <div class="row2n1 no_b ">
    ~if $loginProfile->getDecoratedGender() eq 'Male'`
    <label>Groom's Name</label><div class="rf" style="width:135px">: ~$Name|decodevar`</div>
    ~/if`
    ~if $loginProfile->getDecoratedGender() eq 'Female'`
    <label>Bride's Name</label><div class="rf" style="width:135px">: ~$Name|decodevar`</div>
    ~/if`
    </div>
    
	~assign var=key value='Date of Birth'`
    <div class="row2n1 no_b ">
    <label>Date of Birth </label><div class="rf" style="width:135px">: ~$AstroKundaliArr.$key`
    </div></div>
    <div class="row2n1 no_b ">
    <label>Height  </label><div class="rf" style="width:135px">: ~$loginProfile->getDecoratedHeight()`
    </div></div>
    <div class="row2n1 no_b ">
    <label>Marital Status</label><div class="rf" style="width:135px">: ~$loginProfile->getDecoratedMaritalStatus()`
    </div></div>
	~if $loginProfile->getMSTATUS() neq 'N'`
    <div class="row2n1 no_b ">
    <label>Have Children  </label><div class="rf" style="width:135px">: ~$loginProfile->getDecoratedHaveChild()`
    </div></div>~/if`
	~if $loginProfile->getCOUNTRY_RES() neq '51'`
    <div class="row2n1 no_b ">
    <label>Citizenship</label><div class="rf" style="width:135px">: ~$loginProfile->getDecoratedCitizenship()`
    </div></div>
	~/if`
        <div class="row2n1 no_b ">
        <label class="greycol">Verification ID<span style="color:#ff0000">**</span></label>
        <div class="rf greycol" style="width:135px;" >: ~$loginProfile->getDecoratedID_PROOF_TYP()`<br />
        &nbsp;&nbsp;~$loginProfile->getID_PROOF_NO()`
        </div></div>
</div>

<div class="lf" style="width:32%; border-left: 1px solid #eaeaea;min-height: 145px;">
<div class="protop1 b" style=" padding-left:5px; color:#000;">Contact Information <a class="thickbox" href="~$SITE_URL`/profile/editProfile?flag=PCI&width=700" style="font-size:14px; color:#0f71ae;">[Edit]</a>&nbsp;<a href="/profile/contact_archive.php?width=700" class="thickbox" style="font-size:14px; color:#0f71ae;">[Archive]</a></div>
    <div class="row2n1 no_b ">
    <label>Email id </label><div class="rf" style="width:155px">: ~$loginProfile->getEMAIL()`
    </div></div>
    <div class="row2n1 no_b ">
    <label ~if $loginProfile->getCOUNTRY_RES() eq ''`style="color:red;"~/if`>Country living in</label><div class="rf" style="width:155px">: ~$loginProfile->getDecoratedCountry()` 
    </div></div>
	~if $loginProfile->getCOUNTRY_RES() eq '51' or $loginProfile->getCOUNTRY_RES() eq '128'`
    <div class="row2n1 no_b ">
    <label ~if $loginProfile->getCITY_RES() eq ''`style="color:red;"~/if`>City living in</label><div class="rf" style="width:155px">: ~$loginProfile->getDecoratedCity()`
    </div></div>
	~/if`
    <div class="row2n1 no_b ">
    <label>Residency Status</label><div class="rf" style="width:155px">: ~$loginProfile->getDecoratedRstatus()`
    </div></div>
    <div class="row2n1 no_b ">
    <label ~if $loginProfile->getTIME_TO_CALL_START() eq '' or $loginProfile->getTIME_TO_CALL_END() eq ''`style="color:red"~/if`>Suitable time to call</label><div class="rf" style="width:155px">: ~$loginProfile->getTIME_TO_CALL_START()` ~if $loginProfile->getTIME_TO_CALL_START() neq '' and $loginProfile->getTIME_TO_CALL_END() neq ''`to~/if` ~$loginProfile->getTIME_TO_CALL_END()`
    </div></div>
<div class="row2n1 no_b" style="text-align:left;">
</div>
</div>

<div><br /><br /></div>

<div class="lf" style="width:35%;margin-left:20px;">
<input type="hidden" id="profile_layer"/>
    <div class="row2n1 no_b">
    <label ~if ($loginProfile->getPHONE_RES() eq '' or $loginProfile->getPHONE_OWNER_NAME() eq '' or $loginProfile->getPHONE_NUMBER_OWNER() eq '') and $loginProfile->getPHONE_MOB() eq ''`style="color:red"~elseif $post_login eq 1`style="color:red"~/if`>Phone no</label><div class="rf" style="width:175px">: ~$loginProfile->getSTD()`-~$loginProfile->getPHONE_RES()` ~if $loginProfile->getPHONE_RES()`~if $loginProfile->getLANDL_STATUS() eq 'Y'`<img src="/profile/images/registration_new/grtick.gif" title="This number is verified"/>~else`<a href="/profile/myjs_verify_phoneno.php?phonetype=L&width=700" class="thickbox">Verify now</a>~/if`~/if`
    </div></div>
    <div class="row2n1 no_b ">
    <label>--Phone no of </label><div class="rf" style="width:175px">: ~$loginProfile->getPHONE_OWNER_NAME()`~if $loginProfile->getPHONE_OWNER_NAME() neq ''` (~$loginProfile->getDecoratedLandlineNumberOwner()`) ~/if`
    </div></div>
    <div class="row2n1 no_b ">
    <label ~if ($loginProfile->getPHONE_MOB() eq '' or $loginProfile->getMOBILE_OWNER_NAME() eq '' or $loginProfile->getMOBILE_NUMBER_OWNER() eq '') and $loginProfile->getPHONE_RES() eq ''`style="color:red"~elseif $post_login eq 1`style="color:red"~/if`>Mobile no</label><div class="rf" style="width:175px">: ~$loginProfile->getPHONE_MOB()` ~if $loginProfile->getPHONE_MOB()`~if $loginProfile->getMOB_STATUS() eq 'Y'`<img src="/profile/images/registration_new/grtick.gif" title="This number is verified"/>~else`<a href="/profile/myjs_verify_phoneno.php?phonetype=M&width=700" class="thickbox">Verify now</a>~/if`~/if`
    </div></div>
    <div class="row2n1 no_b ">
    <label>--Mobile no of </label><div class="rf" style="width:175px">:  ~$loginProfile->getMOBILE_OWNER_NAME()` ~if $loginProfile->getMOBILE_OWNER_NAME()`(~$loginProfile->getDecoratedMobileNumberOwner()`)~/if`
    </div></div>
~if $FtoState eq 'C1'`	<div class="row2n1 no_b ">
	<div style="background:#ffef92; border: 2px solid #ffd940; padding:6px">
	<div class="b" style="color:#bc001d;">Get Jeevansathi Paid Membership for <span style="font-family:WebRupee; color:#bc001d">R</span><span style=" text-decoration: line-through; color:#000 "><span style="color:#bc001d">1100</span></span> FREE </div>
	<div class="sp5"></div>
	<div>
	<a style="text-decoration:underline" class="fs14 b thickbox" href="~$SITE_URL`/profile/myjs_verify_phoneno.php?width=700">Verify Your Phone Number</a>
	</div>
	</div>
	</div>
	~/if`
    <div class="row2n1 no_b ">
    <label>Messenger ID </label><div class="rf" style="width:175px">: ~$loginProfile->getMESSENGER_ID()`&nbsp;~if $MSGR_CHANNEL neq "" and $SHOW_MSGR_CHANNEL eq 1`(~if $MSGR_CHANNEL eq '1'`Yahoo~elseif $MSGR_CHANNEL eq '2'`MSN~elseif $MSGR_CHANNEL eq '3'`Skype~elseif $MSGR_CHANNEL eq '4'`Others~elseif $MSGR_CHANNEL eq '5'`ICQ~elseif $MSGR_CHANNEL eq '6'`Google Talk~elseif $MSGR_CHANNEL eq '7'`Rediff Bol~/if`)~/if`
    </div></div>
    <div class="row2n1 no_b ">
    <label>Alternate Mobile no~if sfConfig::get('app_myprofile_new_on')`~/if`</label><div class="rf" style="width:175px">: ~$loginProfile->getExtendedContacts()->ALT_MOBILE` ~if $loginProfile->getExtendedContacts()->ALT_MOBILE and $loginProfile->getExtendedContacts()->ALT_MOBILE neq '-'`~if $loginProfile->getExtendedContacts()->ALT_MOB_STATUS eq 'Y'`<img src="/profile/images/registration_new/grtick.gif" title="This number is verified"/>~else`<a href="/profile/myjs_verify_phoneno.php?phonetype=A&width=700" class="thickbox">Verify now</a>~/if`~/if`
    </div></div>
<div class="row2n1 no_b " style="text-align:left;">
</div></div>

<div class="sp8" style="height:0px!important;"></div>
<div style="padding-left:0px;">
	<div style="width:260px; float:left; height:104px;border-left: 1px solid #eaeaea;"><div class="fl upclr" style="margin-left:10px; margin-top:10px; width:230px; display:inline"><span style="color:#ff0000">**</span>Verification ID will not be visible to any other Jeevansathi member.</div></div>
    <div class="lf" style="width:32%; border-left: 1px solid #eaeaea;">
    <div class="row2n2 no_b" style="border-top:1px solid #eaeaea;padding-top:4px;">
    <label>Address of parents~if sfConfig::get('app_myprofile_new_on')`~/if`</label><div class="rf" style="width:510px">: ~if $loginProfile->getPARENTS_CONTACT()`~$loginProfile->getPARENTS_CONTACT()|decodevar`~else`-~/if` 
    </div></div>
    <div class="row2n2 no_b ">
    <label>Your contact address</label><div class="rf" style="width:510px">: ~if $loginProfile->getCONTACT()`~$loginProfile->getCONTACT()|decodevar`~else`-~/if`
    </div></div>
    <div class="row2n2 no_b ">
    <label>Blackberry Pin~if sfConfig::get('app_myprofile_new_on')`~/if`  </label><div class="rf" style="width:510px">: ~$alternateContacts->BLACKBERRY`
    </div></div>
    <div class="row2n2 no_b ">
    <label>Linkedin profile ID/URL~if sfConfig::get('app_myprofile_new_on')`~/if` </label><div class="rf" style="width:510px">: ~$alternateContacts->LINKEDIN_URL`
    </div></div>
    <div class="row2n2 no_b ">
    <label>Facebook profile ID/URL~if sfConfig::get('app_myprofile_new_on')`~/if`</label><div class="rf" style="width:510px">: ~$alternateContacts->FB_URL`
    </div></div>
    <div class="row2n1 no_b" style="text-align:left;">
    </div>
    </div>
</div>
<div class="lf t12 b" style="width:780px;_width:768px;padding:0px 5px 5px 0px; margin-right:6px;_padding-right:0px;_margin-right:0px;">
	<div style="border-bottom:1px solid #eaeaea;"></div>
	<p class="clr_4"></p>
		

	<p class="clr"></p>
	<div class="lf"></div>
	<div class="sp6"></div>

<div class="lf"~if $INFOLEN lt 100`style="color:red"~/if`>About me <a ~if $post_login eq 1`href="/profile/editProfile?width=700&flag=PMF&post_login=1"~else`href="/profile/editProfile?width=700&flag=PMF"~/if` class="thickbox" style="font-size:14px; color:#0f71ae;">[Edit]</a>
    <br />
    <span class="no_b">  ~$loginProfile->getDecoratedYourInfo()|decodevar`</span>
</div>

<div class="sp8"></div>
<div class="sp8"></div>

<div class="lf">About my Family <a href="~$SITE_URL`/profile/editProfile?flag=PMF&width=700&for_fam=1" class="thickbox" style="font-size:14px; color:#0f71ae;">[Edit]</a>
    <br />
    <span class="no_b">  ~$loginProfile->getDecoratedFamilyInfo()|decodevar`</span>
</div>
<div class="sp8"></div>
<div class="sp8"></div>

<div class="lf">About my Education <a href="~$SITE_URL`/profile/editProfile?flag=PEO&width=700&from_edu_link=1" class="thickbox" style="font-size:14px; color:#0f71ae;">[Edit]</a>
<br />
<span class="no_b">  ~$loginProfile->getDecoratedEducationInfo()|decodevar`</span>
</div>
<div class="sp8"></div>
<div class="sp8"></div>
<div class="lf">About my Work  <a href="~$SITE_URL`/profile/editProfile?flag=PEO&width=700&from_work_link=1" class="thickbox" style="font-size:14px; color:#0f71ae;">[Edit]</a>
<br />
<span class="no_b">  ~$loginProfile->getDecoratedJobInfo()|decodevar`</span>
</div>
<div class="sp8"></div>
<div class="rf" style="margin:2px 7px 0px;"><a href="#" class="b blink">Go to top <img src="~sfConfig::get(app_img_url)`/images/icon_blue_up.gif" border="0"></a></div>
<div class="sp8"></div>
<div class="sp8"></div>
~include_partial("profile/genericProfileSection",['NameValueArr'=>$ReligionAndEth,'isEdit'=>1,'LabelHeading'=>"Religion and Ethnicity",'editFlag'=>"PRE"])`
<!--<div class="lf" style="width:48%">
<div class="lf pd5 subhd1">Religion and Ethnicity&nbsp;<a href="/profile/editProfile?width=700&flag=PRE" class="thickbox" style="font-size:14px; color:#0f71ae;">[Edit]</a> </div>
    <div class="row2 no_b ">
    <label>Religion</label><div class="rf" style="width:175px">: ~$loginProfile->getDecoratedReligion()`
    </div></div>
    <div class="row2 no_b ">
    <label>Mother Tongue</label><div class="rf" style="width:175px">: ~$loginProfile->getDecoratedCommunity()`
    </div></div>
    <div class="row2 no_b ">
    <label>Caste</label><div class="rf" style="width:175px">: ~$loginProfile->getDecoratedCaste()`
    </div></div>
	~if $religion_partial`
	~include_partial("profile/$religion_partial",['religionInfo'=>$ReligionInfo,'loginProfile'=>$loginProfile])`
	~/if`
    <div class="row2 no_b ">
    <label>Sect</label><div class="rf" style="width:175px">: Yes
    </div></div>
</div> -->
<!-- Astro Details start here -->
<div>
~include_partial("profile/genericProfileSection",['NameValueArr'=>$AstroKundaliArr,'isEdit'=>$isEdit,'LabelHeading'=>"Astro/ Kundali Details",'rightSect'=>1,'isAstro'=>1,'editFlag'=>'CUH'])`
~if $HOROSCOPE eq 'Y'`
<div class="row2 no_b " style="text-align:left;">
&nbsp;&nbsp;<a href="/profile/horoscope_astro.php?width=700&profilechecksum=~$profilechecksum`&from_edit=1" class="thickbox" style="color:#117DAA;"> View your horoscope</a>
</div>
~/if`
<div class="row2 no_b " style="text-align:left;">

<div class="hrcpe_prvcy">
    <strong>Astro-Details Privacy Setting</strong><br />
    <div class="sp8"></div>
   <label> <input type="radio" name="horo_display" class="chbx vam" Value="Y" ~if $loginProfile->getSHOW_HOROSCOPE() eq 'Y'` checked ~/if` onclick="horo_visibility('Y');" /> Show to others</label> <label id="im3_1" style="display:none;">&nbsp;<img src="~$IMG_URL`/profile/images/loader_extra_small.gif" align="top" /></label><label id="im3_2" class="green" style="margin:1px 0 0 5px;display:none;" align="top" ><img src="~$IMG_URL`/profile/images/sml_grn_tick.gif" align="absmiddle" /> Saved</label>
    <div class="sp8"></div>
   <label> <input type="radio" name="horo_display" class="chbx vam" value="N" ~if $loginProfile->getSHOW_HOROSCOPE() eq 'N'` checked ~/if` onclick="horo_visibility('N');" /> Hide Horoscope from others </label> <label id="im4_1" style="display:none;">&nbsp;<img src="~$IMG_URL`/profile/images/loader_extra_small.gif" align="top" /></label><label id="im4_2" class="green" style="margin:1px 0 0 5px;display:none;" align="top" ><img src="~$IMG_URL`/profile/images/sml_grn_tick.gif" align="absmiddle" /> Saved</label>
    <div class="sp8"></div>
   <label> <input type="radio" name="horo_display" class="chbx vam" value="D" ~if $loginProfile->getSHOW_HOROSCOPE() eq 'D'` checked ~/if` onclick="horo_visibility('D');" /> Hide all Astro-Details from others</label> <label id="im5_1" style="display:none;">&nbsp;<img src="~$IMG_URL`/profile/images/loader_extra_small.gif" align="top" /></label><label id="im5_2" class="green" style="margin:1px 0 0 5px;display:none;" align="top" ><img src="~$IMG_URL`/profile/images/sml_grn_tick.gif" align="absmiddle" /> Saved</label>
    <div class="sp8"></div>
    </div>

</div>
</div>
</div>
<!-- Astro details end here -->

<div class="sp12"></div>
<!-- Family Details start here -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$familyArr,'isEdit'=>$isEdit,'LabelHeading'=>"Family Details",'editFlag'=>"PFD"])`
<!-- Family details end here -->
<!-- Education Section starts -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$educationAndOccArr,'isEdit'=>$isEdit,'LabelHeading'=>"Education and Occupation",'rightSect'=>1,'editFlag'=>"PEO"])`
<!-- Education Section ends -->

	<div class="sp12"></div>

<!-- LifeStyle Section starts -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$lifeAttrArray,'isEdit'=>$isEdit,'LabelHeading'=>"Lifestyle and Attributes",'editFlag'=>"PLA"])`
<!-- Lifestyle Section ends -->
<!-- Hobbies Section starts -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$Hobbies,'isEdit'=>$isEdit,'LabelHeading'=>"Hobbies and Interests",'rightSect'=>1,'editFlag'=>"PHI"])`

<!-- Hobbies Section end -->

    <p class="clr_18"></p>
<div class="rf" style="margin:2px 7px 0px;"><a href="#" class="b blink">Go to top <img src="~sfConfig::get(app_img_url)`/images/icon_blue_up.gif" border="0"></a></div>
    <p class="clr_18"></p>
	<div class="lf fs18"><a href="/profile/dpp">Edit Desired Partner Profile</a></div>
    <p class="clr_18"></p>
    <p class="clr_18"></p>
    <p class="clr_18"></p>
<!--profile details ends here  -->

<!--forward link starts here  -->
<div class="fl">
<div class="lbdm">
	<ul>
    <li style="font-size:16px;"><strong style="color:#3c3c3c;">Profile Page :</strong><a href="#"> http://www.jeevansathi.com/profiles/~$USERNAME`</a></li>
    <li style="font-size:11px; color:#3490b6; padding-left:20px;margin-top:5px;" class="no_b1 fr"><img src="~sfConfig::get(app_img_url)`/images/forward.gif"  align="absmiddle"/>&nbsp;<a class="thickbox" href="~sfConfig::get(app_site_url)`/profile/forward_profile.php?profilechecksum=~$profilechecksum`&username=~$USERNAME`&width=512">Forward this link</a></li>
    <br />
	</ul>
</div>
</div>
</div>
<!--forward link ends here  -->


	 <p class=" clr_2"></p>
    
<!--mid bottom content start -->
		<p class="clr_18"></p>
<!--mid bottom content end -->

     	
~if $post_login neq 1`
<div style="clear:both:float:none">
~$FOOT`
</div>
~else`
<script>
imgLoader = new Image();// preload image
imgLoader.src = tb_pathToImage;
$('.thickbox').colorbox();
</script>
~/if`
<script>
function horo_visibility(option)
{
         if(option == 'Y')
         {
                document.getElementById("im3_1").style.display = "block";
                document.getElementById("im4_1").style.display = "none";
		document.getElementById("im5_1").style.display = "none";
         }
         else if(option == 'N')
         {
                document.getElementById("im3_1").style.display = "none";
                document.getElementById("im4_1").style.display = "block";
		document.getElementById("im5_1").style.display = "none";
         }
	 else if(option == 'D')
         {
                document.getElementById("im3_1").style.display = "none";
                document.getElementById("im4_1").style.display = "none";
		document.getElementById("im5_1").style.display = "block";
         }
        var randomnumber=Math.floor(Math.random()*11111)
        var request_url = "~$SITE_URL`/profile/change_horoscope_visibility.php?horo_display="+option+"&rnumber="+randomnumber;
        sendRequest('GET',request_url);
}
function pop_layer()
{
	var url;
	~if $EditWhatNew eq 'EduOcc'`
		url="~$SITE_URL`/profile/editProfile?width=700&flag=PEO";
	~/if`

	~if $EditWhatNew eq 'FamilyDetails'`
		 url="~$SITE_URL`/profile/editProfile?width=700&flag=PFD";
	~/if`

	~if $EditWhatNew eq 'VerContact'`
		url="~$SITE_URL`/profile/myjs_verify_phoneno.php?width=700";
	~/if`

	~if $EditWhatNew eq 'ContactDetails'`
		url="~$SITE_URL`/profile/editProfile?callTime=~$callTime`&width=700&flag=PCI&gender_logged_in=~$GENDER_LOGGED_IN`";
	~/if`
	
	~if $EditWhatNew eq 'AstroData'`
		url="~$SITE_URL`/profile/editProfile?width=700&flag=CUH&nextLayer=~$nextLayer`&subheader=~$sf_request->getParameter('subheader')`";
	~/if`

	~if $EditWhatNew eq 'LifeStyle'`
		url="~$SITE_URL`/profile/editProfile?width=700&flag=PLA";
	~/if`

	~if $EditWhatNew eq 'Interests'`
		url="~$SITE_URL`/profile/editProfile?width=700&flag=PHI";
	~/if`

	~if $EditWhatNew eq 'RelEthnic'`
		url="~$SITE_URL`/profile/editProfile?width=700&flag=PRE&gender_logged_in=~$GENDER_LOGGED_IN`";
	~/if`
	~if $EditWhatNew eq 'incompletProfile'`
		url="~$SITE_URL`/profile/editProfile?width=700&flag=INCOMP&channel=~$sf_request->getParameter('channel')`&for_about_us=~$for_about_us`&IncompleteMail=~$sf_request->getParameter('mailer')`";
	~/if`
	~if $EditWhatNew eq 'JST'`
                url ="~$SITE_URL`/profile/faqs_layer.php?width=500&questiontext="+escape( 'Edit Basic information' );
        ~/if`
	~if $EditWhatNew eq 'JST2'`
                url ="~$SITE_URL`/profile/faqs_layer2.php?width=700&checksum=~$CHECKSUM`&questiontext="+escape( 'Edit country living in' );
        ~/if`
	~if $EditWhatNew eq 'aboutDPP'`
                url ="~$SITE_URL`/profile/edit_dpp.php?width=700&flag=PPA&FLAG=partner&RELATION=~$RELATION`";
	~/if`
	~if $after_login eq 1 or $after_login eq 3`
		url="~$SITE_URL`/profile/editProfile?width=700&flag=INCOMP&mark=~$after_login`";
	~/if`
	~if $EditWhatNew eq 'PMF'`
		url="~$SITE_URL`/profile/editProfile?width=700&flag=PMF";
	~/if`
	~if $after_login eq 2`
                url="~$SITE_URL`/profile/editProfile?width=700&flag=PEO&mark=~$after_login`&IncompleteMail=~$sf_request->getParameter('IncompleteMail')`";
        ~/if`
	~if $EditWhatNew eq 'EmailDup'`
                url="~$SITE_URL`/profile/editProfile?width=700&flag=PCI&EmailDup=1&DupEmail=~$DupEmail`&invalid_email=~$invalid_email`";
        ~/if`
	~if $EditWhatNew eq 'INTM' and $oldFlag`
		url="~$SITE_URL`/profile/editProfile?width=700&flag=INTM&oldFlag=~$oldFlag`";
	~/if`
	if(url)
	{
		$.colorbox({href:url});
	}
	~if $EditWhatNew eq 'FocusPhoto'`
		var v="'" + window.location + "'"
		if(v.indexOf("photohere")==-1)
			window.location = window.location + "#photohere";
		else
			window.location = window.location;
	~/if`
	~if $EditWhatNew eq 'FocusDpp'`
                var v="'" + window.location + "'"
                if(v.indexOf("dpphere")==-1)
                        window.location = window.location + "#dpphere";
                else
                        window.location = window.location;
        ~/if`
	return;
}
addLoadEvent(pop_layer);
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}
</script>
~if $INCOMPLETE eq 'N'`
~$REGISTRATION|decodevar`
~/if`
~if $pixelcode`
	~$pixelcode|decodevar`
	~/if`
</div>
<!--Main container ends here-->	
~include_partial('global/footer',[NAVIGATOR=>~$NAVIGATOR`,data=>$loginProfile->getPROFILEID()])`
