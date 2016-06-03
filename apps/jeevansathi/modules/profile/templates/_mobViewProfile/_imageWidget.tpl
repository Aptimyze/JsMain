<!--start:image-->
<div class="posrel"> 
	<div id="picContent" class="vpro_oh">

        <img id="profilePic" class="classimg3 vpro_w100Per"  />
<!--
		<canvas id='profilePic'></canvas>
-->
	</div>
    ~if isset($arrData.url) && $arrData.pic_count neq 0`
	<div class="posabs vpro_pos4">
        <a href="javascript:openAlbumView();">
            <div class="posabs outerAlbumIcon">
					<div class="bg4 txtc disptbl crBoxCount">
						<div class="f14 color6 dispcell vertmid">~$arrData.pic_count`</div>
					</div>
				</div>
				<div class="bg13 opa50 txtc white opa70 fontreg crBoxIcon">
				    <div class="pt13">
					<i class="mainsp camera"></i>
				    </div>
				 </div>
        </a> 
    </div>

    ~else if !$myPreview`
    <div class="posabs fullwid vpro_40PerTop">
		<div class="disptbl">
            <div class="dispcell txtc">
                <a id="label1" 
                   ~if isset($arrData.action)`
                    href="javascript:requestphototag(getProfileCheckSum(),1);" 
                   ~/if`
                   class="white fontthin f18 lh30 dispbl txtc trans1 srp_pad1">
                    ~$arrData.label`
                </a>
            </div>
        </div>
    </div>
    ~/if`

    <!--start:verificationIcon -->
    ~if $verificationValue neq '0'`
    <div class="posabs srp_pos3 searchNavigation showDetails" data-doc="" id="id1" tupleno="idd1">
        <a id="album1" href="javascript:void(0);">

            <div class="bg13 opa50 txtc white opa70 fontreg crBoxIcon">
                <div class="pt8"> <i class="mainsp verified"></i> </div>
            </div>
        </a>
    </div>
    ~include_partial("profile/mobViewProfile/_documentVerification")`
    ~/if`
     <!--end:verificationIcon-->
	<div id="tab" class="fullwid tabBckImage posabs">
	<div id="tabContent" class="fullwid bg2 vpro_pad5 fontlig posrel">
	  <div id="tabAbout" class=" wid29p fl f12">About ~if $gender eq Female` her ~else` him ~/if`</div>
	  <div id="tabDpp" class="wid30p fr txtr f12">Looking for</div>
	  <div id="tabFamily" class="wid40p txtc f12">Family</div>
	  <div class="clr"></div>
	</div>
	</div>
</div>
<!--end:image--> 
