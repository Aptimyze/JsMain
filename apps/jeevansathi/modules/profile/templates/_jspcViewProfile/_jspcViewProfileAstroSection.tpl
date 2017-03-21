~assign var="rel_val" value=$apiData['lifestyle']['religion_value']`
~if $rel_val eq '1' || $rel_val eq '4' || $rel_val eq '7' || $rel_val eq '9'`
<div class="bg-white noMultiSelect ~if $bEditView` fullwid fontlig mt15~else` mb15 prfwid12 fontlig ~/if`" id="section-horoscope">
                	~if $bEditView`
                    <div class="edpp3 prfbr2">
                        <ul class="hor_list clearfix  fullwid">
                            <li class="edpwid2 clearfix"> <i class="fl vicons edpic5"></i>
                                <p class="fl color5 f17 pt3 pl5">Horoscope Details</p>
                            </li>
                            <li class="pt4">
                                <div class="color5 fontreg f15 js-editBtn editableSections cursp" data-section-id="horoscope" id='horoscopeEdit'>Edit</div> 
                            </li>
                        </ul>
                    </div>
                    ~else`
                    <div class="disp-tbl prfbr2 color11 fontlig fullwid pb20 pt49">
                        <div class="f17 disp-cell vbtm pl20 pos-rel"><span class="js-changeText">Horoscope</span> <div class="f26 pos-abs HoroHeadingSection js-hideGuna disp-none"><span class="js-showGuna"></span>/36</div></div>
                        
                    </div>
                    ~/if`
                    <div class="prfp12 f14 fullHoroData">
                        ~if !$bEditView && $apiData['about']['toShowHoroscope'] eq 'D'`
                        <p class="color11">This user has chosen to hide horoscope details.</p>
                        ~else`
                            ~if $bEditView && (!$apiData['about']['city_country'] !='' || !$apiData['about']['astro_time_check'])`
                                <div class="">
                                    <button id="crUpHoroBtn" class="fullwid bg_pink lh44 f14 colrw txtc brdr-0 cursp editableSections" data-section-id="uploadhoroscope">Create Horoscope</button>
                                </div>
                            ~/if`
                    	<ul class="listn gunna">
                            <li>
                                <p class="color12">Place of Birth</p>
                                <p class="~if $bEditView && !$apiData['about']['city_country']` color5 ~else if $apiData['about']['city_country']` color11 ~else` notFilledInColor ~/if` pt6">~if $apiData['about']['city_country']`~$apiData['about']['city_country']`~else`Not filled in~/if`</p>
                            </li>
                        	<li>
                            	<p class="color12">Date of Birth</p>
                                <p class="~if $bEditView && !$apiData['about']['astro_date']` color5 ~else if $apiData['about']['astro_date']` color11 ~else` notFilledInColor ~/if` pt6">~if $apiData['about']['astro_date']`~$apiData['about']['astro_date']`~else`Not filled in~/if`</p>
                            </li>
                            <li>
                            	<p class="color12">Time of Birth</p>
                                <p class="~if $bEditView && !$apiData['about']['astro_time_check']` color5 ~else if $apiData['about']['astro_time_check']` color11 ~else` notFilledInColor ~/if` pt6">~if $apiData['about']['astro_time_check']`~$apiData['about']['astro_time']`~else`Not filled in~/if`</p>
                            </li>
                        </ul>
                        <div class="js-horoscopeView">
                        <ul class="listn gunna">
                            ~if ($apiData['about']['horo_match'] neq "" && $apiData['about']['horo_match'] neq "-") || $bEditView`
                            <li>
                                <p ~if $bEditView && $apiData["about"]["horo_match"] eq $notFilledInText` class="color11 pt6" ~else` class="mt25 color11 f14" ~/if`>
                                    <span id="horo_matchView" ~if $bEditView && $apiData["about"]["horo_match"] eq $notFilledInText`  class="color5" ~/if` >
                                            ~$apiData['about']['horo_match']`
                                    </span>
                                </p>
                            </li>
                            ~/if`
                            <li>
                            	<p class="color12">Sun sign</p>
                                <p class="color11 pt6">
                                    <span id="astro_sunsignView" ~if $bEditView && $apiData["about"]["astro_sunsign"] eq $notFilledInText`  class="color5" ~else if ($apiData['about']['astro_sunsign'] eq "" || $apiData['about']['astro_sunsign'] eq "-")` class="notFilledInColor" ~/if` >
                                    ~if ($apiData['about']['astro_sunsign'] eq "" || $apiData['about']['astro_sunsign'] eq "-") && !$bEditView`
                                       Not filled in
                                    ~else`
                                       ~$apiData['about']['astro_sunsign']`
                                     ~/if`
                                    </span>
                                </p>
                            </li>
                            <li>
                            	<p class="color12">Rashi/Moon Sign</p>
                                <p class="color11 pt6">
                                    <span id="rashiView" ~if $bEditView && $apiData["about"]["rashi"] eq $notFilledInText`  class="color5" ~else if ($apiData['about']['rashi'] eq "" || $apiData['about']['rashi'] eq "-")` class="notFilledInColor" ~/if` >
                                        ~if ($apiData['about']['rashi'] eq "" || $apiData['about']['rashi'] eq "-") && !$bEditView`
                                            Not filled in
                                        ~else`
                                            ~$apiData['about']['rashi']`
                                        ~/if`
                                    </span>
                                </p>
                            </li>
                            <li>
                            	<p class="color12">Nakshatra</p>
                                <p class="color11 pt6">
                                    <span id="nakshatraView" ~if $bEditView && $apiData["about"]["nakshatra"] eq $notFilledInText`  class="color5" ~else if ($apiData['about']['nakshatra'] eq "" || $apiData['about']['nakshatra'] eq "-")` class="notFilledInColor" ~/if` >
                                        ~if ($apiData['about']['nakshatra'] eq "" || $apiData['about']['nakshatra'] eq "-") && !$bEditView`
                                            Not filled in
                                        ~else`
                                            ~$apiData['about']['nakshatra']`
                                        ~/if`
                                    </span>
                                </p>
                            </li>
                            <li>
                            	<p class="color12">Manglik</p>
                                <p class="color11 pt6">
                                    <span id="astro_manglikView" ~if $bEditView && $apiData["about"]["astro_manglik"] eq $notFilledInText`  class="color5" ~else if ($apiData['about']['astro_manglik'] eq "" || $apiData['about']['astro_manglik'] eq "-")` class="notFilledInColor" ~/if` >
                                        ~if ($apiData['about']['astro_manglik'] eq "" || $apiData['about']['astro_manglik'] eq "-")&& !$bEditView`
                                            Not filled in
                                        ~else`
                                            ~$apiData['about']['astro_manglik']`
                                    ~/if`
                                    </span>
                                </p>
                            </li>
                            ~if $bEditView`
                            <li>
                                <p class="color12">Horoscope Privacy</p>
                                <p class="color11 pt6">
                                    <span id="astro_privacyView" ~if $apiData["about"]["astro_privacy"] eq $notFilledInText`  class="color5" ~else if ($apiData['about']['astro_privacy'] eq "" || $apiData['about']['astro_privacy'] eq "-")` class="notFilledInColor" ~/if` >
                                        ~if ($apiData['about']['astro_privacy'] eq "" || $apiData['about']['astro_privacy'] eq "-")&& !$bEditView`
                                            Not filled in
                                        ~else`
                                            ~$apiData['about']['astro_privacy']`
                                    ~/if`
                                    </span>
                                </p>
                            </li>
                            ~/if`
                        </ul>
                        </div>
                            ~if $bEditView`
                                <div class="ceditform" id="horoscopeEditForm"><!---Edit Form--></div>
                            ~/if`
                        ~/if`
                            <div id="viewHoroBlockParent" class="mt25">
                                ~if ($apiData['about']['othersHoroscope'] eq 'Y' && ($apiData['about']['toShowHoroscope'] eq 'Y' || $apiData['about']['toShowHoroscope'] eq '') ) || ($bEditView && $apiData['about']['horo_available'] eq 'Y')`
                                    <button id="viewHoroBlock" class="bg5 colrw f14 fontlig brdr-0 lh40 txtc fullwid outl1 cursp js-viewHoro">View horoscope</button>
                                    ~if $bEditView && ($apiData['about']['city_country'] !='' || $apiData['about']['astro_time_check'])`
                                    <button id="deleteHoroBlock" class="bg5 mt25 colrw f14 fontlig brdr-0 lh40 txtc fullwid outl1 cursp js-deleteHoro">Remove horoscope</button>
                                    ~/if`
                                ~elseif $apiData['about']['othersHoroscope'] eq 'N' && !$bEditView`
                                   ~if $apiData['about']['requestedHoroscope'] eq '1'`
                                   <button class="bgDisButton color2 f14 fontlig brdr-0 lh40 txtc fullwid outl1">Horoscope request sent</button>
                                   ~else`
                                    ~if !$loginProfileId`
                                    <button class="bg5 colrw f14 fontlig brdr-0 lh40 txtc fullwid outl1 cursp loginLayerJspc">Request horoscope</button>
                                    ~else`
                                    <div style="position:relative;overflow:hidden;">
                                        <button id="vpReqHoro" class="bg5 colrw f14 fontlig brdr-0 lh40 txtc fullwid outl1 cursp blueRipple hoverBlue js-reqHoro">Request horoscope</button>
                                    </div>
                                    ~/if`
                                    ~/if`
                                ~elseif $apiData['about']['toShowHoroscope'] eq 'N' && !$bEditView`
                                    <button class="bgDisButton colr2 f14 fontlig brdr-0 lh40 txtc fullwid outl1">Horoscope hidden</button>
                                ~/if`
                                
                                <!--start:overlay-->
                                <div>
                                    <div id="kundli-layer" class="disp-none">
                                        <i id="cls-view-horo" class="sprite2 close pos_fix closepos cursp layersZ"></i>
                                        <div class="pos_fix layersZ setshare" style="height:400px">
                                        <div class="">
                                        <div>
                                            
                                                <div id="putHoroscope">
                                                </div>  
                                            
                                        </div>
                                        </div>
                                    </div> 
                                </div>
                                <!--end:overlay-->
                                
                                <!--start:horoscope sample layer-->
                                <div id ="astroComp" class="disp-none">
                                <div class="setshare pos_fix smplHp20" id="smplH">
                                  <div class="pos-rel">	
                                        <i id="cls-astroComp" class="sprite2 layersZ close pos-abs closeHoroSam cursp"></i>
                                      <!--start:top row-->
                                      <div class="clearfix p50">
                                        <!--start:left-->
                                        <div class="fl color11 fontlig wid64p">
                                            <p class="f28">Get Astro compatibility</p>        
                                            <p id="textMem" class="f15 pt15 lh22">You can view a detailed report of your compatibility with XYZ1234 with Astro compatibility add-on along with your membership.</p>
                                        </div>    
                                        <!--end:left-->
                                        <!--start:right-->
                                        <div class="fr">
                                            <div class="lh63 wid280 txtc bg_pink mt15">
                                                <a id="buttonMem" class="fontreg f18 colrw">Upgrade Membership</a>
                                            </div>
                                        </div>    
                                        <!--end:right-->
                                      </div>  
                                      <!--end:top row-->
                                      <!--start:sample report-->
                                      <div class="bg-white content" id="sampleHrep" style="height:295px">
                                        <img src="~sfConfig::get('app_img_url')`/images/jspc/viewProfileImg/horoscope-sample_2.jpg"/>
                                      </div>    
                                      <!--end:sample report-->
                                   </div>
                                </div>
                                </div>
                                <!--end:horoscope sample layer-->
                                
                                
                            </div>
                        <div id="astroCompatibility" class="mt25">
                            ~if !$apiData['about']['NO_ASTRO'] AND !$bEditView AND $apiData['about']['sameGender'] neq 1`
                                    <button id="astroCompatibilityButton" class="bg5 colrw f14 fontlig brdr-0 lh40 txtc fullwid outl1 cursp ~if $apiData['about']['COMPATIBILITY_SUBSCRIPTION'] eq 'N' AND $apiData['about']['paidMem'] eq 'Y'` js-astroCompMem ~elseif $apiData['about']['COMPATIBILITY_SUBSCRIPTION'] eq 'N'` js-freeAstroComp ~elseif $apiData['about']['COMPATIBILITY_SUBSCRIPTION'] neq 'N'` js-astroMem ~/if`" ~if $apiData['about']['COMPATIBILITY_SUBSCRIPTION'] neq 'N'` onclick="window.open('~$SITE_URL`/profile/check_horoscope_compatibility.php?profilechecksum=~$apiData["page_info"]["profilechecksum"]`','_blank','width=1100, height=650')"~/if`>Check Astro Compatibility</button>
                            ~/if`
                        </div>
                            ~if $apiData['about']['myHoroscope'] eq 'N' && $apiData['about']['othersHoroscope'] eq 'Y' && $apiData['about']['toShowHoroscope'] eq 'Y' && !$bEditView`
                            <p class="mt25 color11 f14">Check your Astro compatability</p>
                                <div class="mt10" style="position:relative;overflow:hidden;">
                                    <a href="~$SITE_URL`/profile/viewprofile.php?ownview=1&EditWhatNew=uploadhoroscope">
                                    <button id="vpUploadHoro" class="bg5 colrw f14 fontlig brdr-0 lh40 txtc fullwid outl1 cursp blueRipple hoverBlue">Upload your Horoscope</button>
    </a>

                            </div>  
                            ~/if`
                    </div> 
                    <div class="prfp12 f14 noHoroData disp-none pdTop150 height300 txtAlignCenter">
                    <i class="sprite2 puic13"></i>
                    <p class="color11 f14 horoErrorCondition pdTop20"></p>
                    <div class="colr5 okayClick cursp pdTop30">
                    <span><u>OK</u></span>
                    </div>             
                   </div>
                </div>            
~/if`
