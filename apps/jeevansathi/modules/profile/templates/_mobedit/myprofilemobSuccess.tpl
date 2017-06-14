  <!--pixelcode for register page-->
  ~if isset($pixelcode)`
    ~$pixelcode|decodevar`
  ~/if`
<div class="perspective" id="perspective">
	<div class="pcontainer" id="pcontainer">
<!--start:div--> 
		<!--start:div-->
		
            <div id="newLoader" class='otplayer' style="display:none;">
                <img src="/images/jsms/commonImg/loader.gif" class="posabs" style="left: 44%;top: 50%;"/>
            </div>

                <div class="fullwid bg1" id="topbar">
		  <div class="pad1">
			<div class="rem_pad1">
			  <div class="fl wid20p white"><i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i> </div>
			  <div class="fl wid60p txtc white fontthin f19">~$USERNAME`</div>
			  <div class="fl wid20p white txtr fontlig f14 pt7"><a href="/profile/viewprofile.php?username=~$USERNAME`&preview=1" bind-slide=1 class="white">Preview</a></div>
			  <div class="clr"></div>
			</div>
		  </div>
		</div>
		<!--end:div-->
		<!--start:slider-->
		<div id="ed_slider" class="swipe vh">
			<div id ="sw" class="bxslider" >
				<div id="sliderName" class="slidechild" >
					<!--start:div-->
					<div id ="subHeadTab" class="fullwid editAlbumBG prz" style="z-index:105;">
						  <div id ="innerSubHeadTab" class="pad5">
							  <div id="leftTabName"class="fl wid30p color5 fontlig f12 pt2 opa70">LeftTabValue</div>
							  <div id="MainTabName" class="fl wid40p txtc color5 fontlig f14 textTru" maintab="1">MainTabValue
							   <span class="arow4"></span>
							  </div>							 
							  <div id="RightTabName"class="fl wid30p color5 txtr fontlig f12 pt2 opa70"></div>
							  <div class="clr"></div>
						  </div>
					</div>
					<!--end:div--> 
					
				<div id="EditSection" class="fullwid oa">
					<div class="fullwid  brdr1 bwhite" slideOverLayer="OVERLAYID">
					  <div class="pad1">
						<div class="pad2">
						  <div class="fl wid94p wwrap">
							<div id="EditFieldName" class="color3 f14 fontlig">
							</div>
							<div id="EditFieldLabelValue" class="color4 f12 pt10 fontlig">
							</div>
							</div>
							<div class="fr wid4p pt8"> <i id='ARROWID' class="mainsp arow1"></i>
							</div>
							<div class="clr"></div>
						</div>
					  </div>
					</div>
				</div>
					<!--end:div--> 
				</div>
			</div>
		</div>
		<!--end:silder--> 

<div id ="cancelOverLayBackGround" class="dn"> </div>
<div id="cancelOverLayer" class="overlay_1_e page transition top_1 dn">
  ~include_partial("profile/mobedit/cancelOverlay")`
</div>

<div id="confirmOverLayer" class="overlay_1_e page transition top_1 dn">
  <div id="PromptSectionName" class="dp">
	<div style="position:relative" >
    <div class="txtc pad-all10">
    <i id="nonEditablePic" class="mainsp phn1 dn"></i>
        <div id="TEXT1_ID" class="f14 color3 pt7 fontlig">TEXT1</div>
        <div id="TEXT2_ID" class="f14 color3 pt4 fontlig pb20">TEXT2</div>
    </div>
    <div style="border-top:1px solid #dbdbdb">
    	<div class="fullwid">
        	<div id="TAB1_ID" class="fl txtc pad2 wid49p brdr2">
            	<div id="Action1" class="fontthin f17 color2">TAB1_NAME</div>
            </div>
            <div id="TAB2_ID" class="fl txtc pad2 wid49p">
		<div id="Action2" class="fontthin f17 color2">TAB2_NAME</div>           	
            </div>
            <div class="clr"></div>
        </div>
    </div>
    </div>
</div>
</div>
<input type="hidden" id="listShow" value="~$checkalbum`">
	<div id="overLayer" class="page transition right_1 dn">
~include_partial("profile/mobedit/overlay")`
</div>
<div id="filterDpp" class="page transition bottom_1 dn">
    ~include_partial("profile/mobedit/manageFilters")`
</div>
<div id="divOverlay" class="dn">
<div id="json_key" class="json_color_val f17 fontlig" value ="json_value" data=1 onClick=showNonEditableOverLayer(0)>json_label_val</div>
</div>
<div id="checkOverlay" class="pad8 dn">
    <div overlaydiv="1" class="fl wid91p">
        <div id="default_val"><div id="default_key" class="f17 fontthin pad2 brdr3" value ="json_value" data=1 >json_label_val</div></div>
        <div id="OverlayID" name="overlayOption">{{overlayoptions}}</div>
    </div>
</div>
<div id="fileOverlay" class="pad8 dn">
    <div overlaydiv="1" class="fl wid91p">
        <form  id="submitForm" action="" method="post" enctype="multipart/form-data">
                <input type="file" name="default_key" onchange="dcallback_fn" id="file_key" labelKey="default_label_key" style="width:0px;height:0px;position:absolute;" MstatusChange/>
        </form>
        
                <div id="default_key" class="f16 fontthin upload-btn-jsms" value ="json_value" data=1 style="display:inline;">json_label_val</div>
                <div id="default_label_key" class="f17 fontthin pad2" value ="json_value" data=1 style="display:inline;" >jpg/pdf only</div>        
        
    </div>
</div>
<div id="textAreaOverlay" class="dn" >
<textarea id="json_key" class="fullwid f17 fontthin color3o minhgt300" placeholder="json_label_placeholder" name="json_key" onKeyUp="keyfunctionShow"  >json_label_val</textarea>
</div>
<div id="textInputIsdOverlay" class="dn">
<div class="fl color3o f17 fontlig padding03">+</div>
<input id="RES_ISD" class="fl color3o f17 fontlig epwid8p" name ="RES_ISD" value ="phoneArray" data=1 onKeyup="keyfunctionShow" placeholder="{{PLACEHOLDER}}" type="tel" autocomplete="off"></input>
</div>
<div id="textInputStdOverlay" class="dn">
<div class="fl color3o f17 fontlig padding03">-</div>
<input type="tel" id="STD" class="fl f17 fontlig color3o {{displayWidth}}" name ="STD" value ="phoneArray" data=1 onKeyup="keyfunctionShow" placeholder="{{PLACEHOLDER}}" autocomplete="off"></input>
</div>
<div id="textInputOverlay" class="dn">
<input id="json_key" class="color3o f17 fontlig wid80p" name =json_key value ="json_value" data=1 onKeyup="keyfunctionShow" placeholder="Not filled in"></input>
</div>
<div id="under_screening" class="dn">
<span id="underscreening" class="color3 f10">(under screening)</span>
</div>
<div id="overlay_2" class="overlay_2_skip dn">
  </div>
 <div id="overlay_2_temp" class="dn">
<div class="fullwid txtc pad2 brdr4" indexpos="-1">  
	<div indexpos="{{indexpos}} "><i class="mainsp close1 cursp" indexpos="-1"></i></div> 
</div>
<div class="fullwid txtc pad2 brdr4 fontlig" indexpos="{{indexpos}}"> 
	<div class="f14 white cursp {{BOLD}}" indexpos="{{indexpos}}">{{KEYNAME}}</div> 
</div>  
</div>
<div id="filterButton" class ="dn">
	<div class="fullwid bg7">
	<div class="dispbl lh50 txtc white" onClick="showFilterOverlayer()">Manage Strict Filters</div>
	</div>
 </div>
~if isset($horoExist)`
<div id="horoscopeButton" class ="dn">
  <div class="fullwid bg7">
    <div class="dispbl lh50 txtc white js-createHoroscope" >
      ~if $horoExist eq 'N'`
      Create 
      ~else` 
      Update 
      ~/if`
      Horoscope
    </div>
  </div>
</div>
~/if`
<!-- <div id='dppToolTip' class='dn'>
  <div  class="fullwid  brdr1 bwhite">
  <div class="pad1">
    <div class="pad2">
      <div class="fl wid94p wwrap fontlig f14 color3">
      The criteria you mention here determines the ‘Desired Partner Matches’ you see. So please review this information carefully. Moreover, Filters determine whose Interests/Calls you want to receive.
      </div>
      <div class="clr"></div>
    </div>
  </div>
</div>
</div> -->

<div id="dppMatchalertToggle" class="dn">

	<div class="fullwid dpbg1">
		<div class="dpbg2 fontlig txtc">
			<p class="f14"> The criteria below influences the matches and interests you recieve</p>
			<p class="f16 pt10">No. of Mutual Matches with below criteria - <span id="mutualMatchCountMobile"></span></p>

		</div>
	</div>



<div class="fullwid brdr1 bwhite">
<div class="pad1">
<div class="pad2">
<div class="fl wwrap fontlig f14 color3">
<div class="fl wwrap fontlig color3">
<div class="f13 wid76p dispibl">Also send me matches outside my Desired Partner Profile
<!-- <div class="f12 color4 pt10">If this is 'ON', you may receive recommendations based on your activity which can be outside your Desired Partner Profile</div> -->
</div>
    <div id="toggleButton" class="fr dispibl filter-onoff-new ~if $toggleMatchalerts eq 'dpp'` filter-off ~else` filter-on ~/if`" onclick="toggleDppMatchalerts();"></div>
</div>
</div>
<div class="clr"></div>
</div>
</div>
</div>
</div>    


<script>
	showLoader();	
		var renderPage=new mobEditPage;
		 var DualHamburger=0;
		 var fromCALHoro=~if $fromCALHoro == 1`'1'~else`'0'~/if`;
		 var fromCALphoto=~if $fromCALphoto == 1`'1'~else`'0'~/if`;
</script>
</div>
<div class="hamburger dn fullwid" id="ehamburger">
~include_partial("profile/mobedit/hamb")`
</div>
<div id="hamburger" class="hamburgerCommon dn fullwid">	
	~include_component('static', 'newMobileSiteHamburger')`	
</div>
<div class="hamoverlay ltransform fullwid" id="hamoverlay"></div>
<div id="SAVE_DONE" class="fullwid dn" style="position:absolute;bottom:0px;">
  	<a  class="bg7 white lh30 fullwid dispbl txtc lh50">Done</a>
	</div>
</div>

<div id="albumPage" class="dn">
~include_partial("social/mobile/mobilePhotoUploadProgress",[gender=>~$GENDER`,username=>~$USERNAME`,selectTemplate=>~$selectTemplate`,alreadyPhotoCount=>~$alreadyPhotoCount`,profilepicurl=>~$profilepicurl`,selectFile=>~$selectFile`,privacy=>~$privacy`,selectFileOrNot=>~$selectFileOrNot`,picturecheck=>~$picturecheck`])`
</div>
~if $sourcename && $groupname && $fromPhoneVerify`
~include_partial("global/gtm",['groupname'=>$groupname,'sourcename'=>$sourcename,'age'=>$loginProfile->getAGE(),'mtongue'=>$loginProfile->getMTONGUE(),'city'=>$loginProfile->getCITY_RES()])`
~/if`

<div id='emailSentConfirmLayer' class="otplayer dispnone">
        <input id='altEmailDefaultText' type="hidden" value="A link has been sent to your email id {email}, click on the link to verify email.">
    <div id="altEmailinnerLayer" class="otpcenter cssLayerFix bg4 fontlig f18">
        <div class="txtc pt40">
        </div>
        <p class="color3 txtc pt10">Email Verification</p>
        <p id="emailConfirmationText" style='padding-left: 4px; padding-right: 4px; word-wrap: break-word;' class="color4 txtc pt10 pb30"></p>
        <div class="otpbr2 txtc otplh60">
            <div id='js-okIncorrectOtp'  onclick='$("#emailSentConfirmLayer").hide();return true;' class="f19 otpcolr2 fontthin">OK</div>
        </div>
    </div>
</div>
