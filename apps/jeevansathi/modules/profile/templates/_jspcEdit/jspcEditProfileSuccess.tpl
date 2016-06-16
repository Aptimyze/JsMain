~include_Partial("search/photoAlbum")`


<input id='hiddenPhoneMain' type='hidden' value='' phonetype='M'>
<input id='hiddenIsd1' type='hidden' value=''>
<input id='hiddenPhoneOther' type='hidden' value='' phonetype='A'>
<input id='hiddenIsd2' type='hidden' value=''>
<input id='hiddenLandline' type='hidden' value='' phonetype='L'>
<input id='hiddenIsd3' type='hidden' value=''>

<div class="pos-rel fullwid"> 
  <!--start:top part-->
  <div id="CPImage" class="prf-cover1" style="height:387px;">
    <div class="container mainwid pt35"> 
       ~include_partial("global/JSPC/_jspcCommonTopNavBar")`
    </div>
  </div>
  <!--end:top part--> 

  <!--start:second part-->
  <div class="bg-4">
    <div class="pos-rel container mainwid settop1"> 
      <div class="overlaywhite disp-none js-loaderShow"></div>
      <div class="overlayload js-loaderShow js-loaderDiv disp-none"><img src="~sfConfig::get('app_img_url')`/images/jspc/commonimg/loader.gif"></div>
      <div id="coverParent" class="ht50">
        <!--start:change cover photo div-->
        <div class="clearfix pb20" id="changeCP">
          <div class="fr color-blockfour lh40" id="changeCPBtn">
            <div class="colrw fontreg f15 vicons edpp1 edpic1 opa70 cursp">Change Cover Photo</div>
          </div>
        </div>
        <!--end:change cover photo div-->

        <!--start:change cover photo menu div-->
        <div class="pb20 disp-none" id="changeCPMenu">
          <div class="color-blockfour fullwid txtl">
              <div class="edpp11 clearfix">
                  <ul id="coverPhotoCatul" class="changecover clearfix hor_list fontlig colrw f15 fl">
                      <!--Dynamically added categories -->
                  </ul>
                  <div class="fr" id="toggleCPBtn">
                      <div class="colrw fontreg f15 vicons edpic10 opa70 cursp edpdim1"></div>
                  </div>
              </div>         
          </div>
        </div>
        <!--end:change cover photo menu div--> 
      </div>
      
        <!--start:overlay-->
    <!--    <div class="hpoverlay z3 disp-none" id="CPoverlay"></div> -->
        <!--end:overlay-->
        
        <!--start:cover layer-->
        <div id="changeCPLayer" class="pos_fix setCover z1001 disp-none">
            <div class="coverWidth edpbg2 pos-rel">
                <i id="closebtnedp" class="sprite2 edpcross1 pos-abs  CPclosepos cursp"></i>
                <div class="edpp12">
                    <div>
                        <ul id="changeCPLayerul" class="coversel hor_list clearfix">
                            <!-- The <li> elements are generated dynamically -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--end:cover layer-->

      <!--start:photo div 1-->
      <div class="prfbg1 clearfix"> 
        <!--start:photo-->
        ~include_partial("global/JSPC/_jspcProfilePhoto",["arrOutDisplay"=>$arrOutDisplay])`
        <!--end:photo--> 
        <!--start:upload div-->
        <div class="fl color11 fontlig edpwid1 f15">
          <p class="txtc pt40">Upload Photos From</p>
          <ul class="hor_list clearfix pl60 pt20">
            <li><a href="/social/addPhotos?uploadType=C" class="sprite2 edpic2 disp_ib"></a></li>
            <li class="ml37"><a href="/social/addPhotos?uploadType=F" class="sprite2 edpic3 disp_ib"></a></li>
          </ul>
          <ul class="hor_list clearfix edpp2 pt15">
            <li class="edpbrd1 pr10"><a href="/social/addPhotos?uploadType=C" class="color11">Computer </a></li>
            <li class="pl10"><a href="/social/addPhotos?uploadType=F" class="color11">Facebook</a></li>
          </ul>
          <p class="f13 edpcolr1 txtc pt20">You can set Photo Privacy</p>
        </div>
        <!--end:upload div--> 
        <!--start:make your profile-->
        ~if $iPCS neq 100`
        <div class="fl" id="PCSBlock">
          <ul class="listnone fontlig f13 edplist1" id="PCSBlockul">
            <li class="fontreg">Add details to your profile</li>
            ~foreach from=$arrMsgDetails key=szKey item=szVal`
            ~if $szKey eq "PHOTO"`
            <li><a href="~$arrLinkDetails[$szKey]`" class="color11 cursp">~$szVal`</a></li>
            ~else`
            <li><a myhref="~$arrLinkDetails[$szKey]`" class="color11 cursp editLink">~$szVal`</a></li>
            ~/if`
            <!--
            <li><a href="#" class="color11">+12%  Add Career Details</a></li>
            <li><a href="#" class="color11">+10%  Add Ethnicity Details</a></li>
            <li><a href="#" class="color11">+7%  Add Astro Details</a></li>
            -->
            ~/foreach`
          </ul>
        </div>
        ~/if`
        <!--end:make your profile--> 
        <!-- start:profile completion-->
        <div class="fr prfwid12 fontlig f13 edpcolr1 edpbrd2 pos-rel z1" style="height:220px;">
          <div class="txtc pt15 pb8 pt35">
            <div id="profileCompletionScore"></div>
            <p class="pt13 color11">Profile completion</p>
            <p id="lastModified" class="pt13">Last Edited on ~$arrOutDisplay.about.last_mod`</p>
            <p id="profileViews" class="pt5">Profile Views ~$arrOutDisplay.about.profileViews`</p>
          </div>
        </div>
        <!--end:profile completion--> 
      </div>
      <!--end:photo div 1--> 

      <!--start:content edit part-->
      <div class="mt13">
        <div class="fullwid clearfix"> 
          <!--start:left div-->
          <div class="fl prfwid3">      
            <div class="bg-white fullwid">       
              <!--start:content section-->
              <div class="f15 fontlig color11">        
                <!--start:Basic Details-->
                <div class="prfbr3">
                  <div class="prfp5" id="section-basic">
                    <div class="clearfix"> <i class="sprite2 fl edpic6"></i>
                      <div class="fl colr5 pl8 f17 pt2" >Basic Details</div>
                        <div class="fr pt4"><a class="cursp color5 fontreg f15 js-editBtn editableSections" data-section-id="basic">Edit</a> </div>
                    </div>
                    <div class="pl30 prflist1 fontlig js-basicView">
                      <p class="f24 pt25 fontlig" id="nameLabelParent"><span class="edpcolr2" >Name</span> - 
                        ~if isset($arrOutDisplay.about.name) and $arrOutDisplay.about.name neq $notFilledInText`
                          <span class="color11" id='nameView'>
                            ~$name`
                          </span>
                          ~else`
                          <span class="color5" id='nameView'>
                            ~$notFilledInText`
                          </span>
                        ~/if`
                        <span class="~if ($editApiResponse.Details.NAME.value|count_characters) eq 0 || $editApiResponse.Details.NAME.screenBit neq 1` disp-none ~/if` js-undSecMsg"> 
                          <span class="disp_ib color5 f13" > Under Screening</span></span>
                      </p>  
                      <ul class="clearfix fontreg">
                        <li>
                          <p class="color12 pt15 fontlig">Age, Height</p>
                          <p class="pt2 fontlig">~$arrOutDisplay.about.age` (~$arrOutDisplay.about.formatted_dob`), <span id='heightView'>~$arrOutDisplay.about.height`</span> </p>
                        </li>
                        <li>
                          <p class="color12 pt15 fontlig">Highest Education</p>
                          <p class="pt2 fontlig" id='educationView'>~$arrOutDisplay.about.education`</p>
                        </li>
                        <li>
                          <p class="color12 pt15 fontlig">Religion</p>
                          <p class="pt2 fontlig" id='religionView'>~$arrOutDisplay.about.religion`</p>
                        </li>
                        <li>
                          <p class="color12 pt15 fontlig">Occupation</p>
                          <p class="pt2 fontlig" id='occupationView'>~$arrOutDisplay.about.occupation`</p>
                        </li>
                        <li>
                          <p class="color12 pt15 fontlig">Mother Tongue</p>
                          <p class="pt2 fontlig" id='mtongueView'>~$arrOutDisplay.about.mtongue`</p>
                        </li>
                        <li>
                          <p class="color12 pt15 fontlig">Annual Income, Location</p>
                          <p class="pt2 fontlig"><span id='incomeView'>~$arrOutDisplay.about.income`</span>, <span id='locationView'> ~$arrOutDisplay.about.location`</span> </p>
                        </li>
                        ~if $arrOutDisplay.about.caste_sect_label && $arrOutDisplay.about.edit_caste`
                        <li>
                          <p class="color12 pt15 fontlig" id='caste_sect_labelView'>~$arrOutDisplay.about.caste_sect_label`</p>
                          <p class="pt2 fontlig" >
                            <span id="edit_casteView">~$arrOutDisplay.about.edit_caste`</span>
                            <span id="edit_sectView" ~if $arrOutDisplay.about.edit_sect eq $notFilledInText`                                class="color5"   ~/if`  > ~$arrOutDisplay.about.edit_sect`</span>
                          </p>
                        </li>
                        ~/if`
                        <li>
                          <p class="color12 pt15 fontlig">Profile managed by</p>
                          ~if isset($arrOutDisplay.about.posted_by)`
                          <p class="pt2 fontlig" id='posted_byView'>~$arrOutDisplay.about.posted_by`</p>
                          ~else`
                          <p class="pt2 color5 fontlig" id='posted_byView'>~$notFilledInText`</p>
                          ~/if`
                        </li>
                        <li>
                          <p class="color12 pt15 fontlig">Marital Status</p>
                          <p class="pt2 fontlig" >
                            <span id="m_statusView">~$arrOutDisplay.about.m_status`</span>
                          </p>
                        </li>
                        ~if $editApiResponse.Details.MSTATUS.value neq N`
                        <li>
                          <p class="color12 pt15 fontlig">Have Children?</p>
                          <p class="pt2 fontlig" >
                            <span id="have_childView" 
                              ~if $arrOutDisplay.about.have_child eq $notFilledInText`
                                class="color5"  
                              ~/if`
                            > 
                            ~$arrOutDisplay.about.have_child`
                            </span>
                          </p>
                        </li>
                        ~/if`
                      </ul>
                    </div>
                    <!--start:Edit Basic Details-->
                    <div class="pl30 ceditform" id="basicEditForm"><!---Edit Form--></div>
                    <!--end:Edit Basic Details-->
                  </div>
                </div>
                <!--end:Basic Details--> 
                <!--start:about us-->
                ~include_Partial("profile/jspcViewProfile/_jspcViewProfileAboutPersonSection",["apiData"=>$arrOutDisplay,"bEditView"=>true,"notFilledInText"=>$notFilledInText,"editApi"=>$editApiResponse])`
                <!--end:about us--> 
                <!--start:Education-->
                ~include_Partial("profile/jspcViewProfile/_jspcViewProfileEducationSection",["apiData"=>$arrOutDisplay,"bEditView"=>true,"notFilledInText"=>$notFilledInText,"editApi"=>$editApiResponse])`
                <!--end:Education--> 
                <!--start:Family Details-->
                ~include_Partial("profile/jspcViewProfile/_jspcViewProfileFamilySection",["apiData"=>$arrOutDisplay,"bEditView"=>true,"notFilledInText"=>$notFilledInText,"editApi"=>$editApiResponse])`
                <!--end:Family Details--> 
                <!--start:Lifestyle-->
                ~include_Partial("profile/jspcViewProfile/_jspcViewProfileLifestyleSection",["apiData"=>$arrOutDisplay,"bEditView"=>true,"notFilledInText"=>$notFilledInText,"editApi"=>$editApiResponse])`
                <!--end:Lifestyle--> 
                <!--start:She Likes-->
                ~include_Partial("profile/jspcViewProfile/_jspcViewProfileHobbiesSection",["apiData"=>$arrOutDisplay,"bEditView"=>true,"notFilledInText"=>$notFilledInText,"editApi"=>$editApiResponse])`
                <!--end:She Likes-->               
              </div>
              <!--end:content section--> 
            </div>
            <div class="prfbg2 fullwid">
              <div class="prfp11">
                <div class="clearfix">
                  <div class="fl fontlig f15 color11 pt5">Profile Page : <a href="#" class="color11">http://www.jeevansathi.com/profiles/~$username`</a></div>
                  <div class="fr btmicon">
                    <ul class="clearfix">
                      <li class="pos-rel">
                        <i class="sprite2 prfic29 cursp share js-action"></i>
                        <!--start:tooltip-->
                        <div class="tooltip1">
                          <div class="boxtip colrw fontlig prfp8 wd68">
                            Share Profile
                          </div>                                    
                        </div>
                        <!--end:tooltip-->
                      </li>
                    </ul>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--end:left div--> 
          <!-- start share profile-->
        ~include_Partial("profile/jspcViewProfile/_jspcShareProfileSection",["apiData"=>$arrOutDisplay])`
        <!--end share profile-->
          <!--start:right div-->
          <div class="fr fontlig prfwid12"> 
            <!--start:contact details-->
            <div class="bg-white fullwid fontlig" id="section-contact">
              <div class="edpp3 prfbr2">
                <ul class="hor_list clearfix  fullwid">
                  <li class="edpwid2 clearfix"> <i class="fl vicons edpic4"></i>
                    <p class="fl color5 f17 pt3 pl5">Contact Details</p>
                  </li>
                  <li class="pt4 "> <a class="color5 fontlig f15 js-editBtn cursp" data-section-id="contact" >Edit</a> </li>
                </ul>
              </div>
              <div class="prfp12 f14 js-contactView">
                <ul class="listn gunna fontlig">
                  <li>
                    <p class="color12" id="emailLabelParent">
                      Email id <span class="ml5 ~if ($editApiResponse.Contact.EMAIL.value|count_characters:true) eq 0 || $editApiResponse.Contact.EMAIL.screenBit neq 1` disp-none ~/if` js-undSecMsg">
                          <span class="disp_ib color5 f13" > Under Screening</span>
                        </span>
                    </p>
                    <p class="color11">
                      <span id='my_emailView' ~if $arrOutDisplay.contact.my_email eq $notFilledInText` class="color5"  ~/if`>
                        ~$arrOutDisplay['contact']['my_email']`
                      </span>
                    </p>  
                  </li>
                  <li>
                    <p class="color12" >
                      Mobile No. 
                    </p>
                    <div class="clearfix pos-rel">
                    	<div class="fl wid74p">
                        	<p class="color11">
                            <span id='mobileView' ~if $arrOutDisplay.contact.mobile eq $notFilledInText` class="color5"  ~/if`>
                              ~$arrOutDisplay['contact']['mobile']`
                            </span>
                          </p>
                          <p class="color11" id="mobile_owner_nameLabelParent">
                            <span id="mobile_descView"> 
                               ~$arrOutDisplay['contact']['mobile_desc']`
                            </span> 
                            <span  class="ml5 ~if ($editApiResponse.Contact.PHONE_MOB.value|count_characters:true) eq 0 ||  ($editApiResponse.Contact.MOBILE_OWNER_NAME.value|count_characters:true) eq 0 || $editApiResponse.Contact.MOBILE_OWNER_NAME.screenBit neq 1` disp-none ~/if` js-undSecMsg">
                              (<span class="color5 f13 wordWrapBreak" >Under Screening</span>)
                            </span>
                          </p>
                        </div>
                        <div class="fr wid25p pos-abs right0">
                        	<div ~if $arrOutDisplay['contact']['phone_mob_status'] eq Verified` class="color12" ~else` class="cursp color5" ~/if` id="phone_mob_statusView" >~$arrOutDisplay['contact']['phone_mob_status']`</div>
                        </div>
                    </div>
                  </li>
                  <li>
                    <p class="color12">
                      Alternate No. 
                    </p>
                    <div class="clearfix pos-rel">
                    	<div class="fl wid74p">
                        	<p class="color11">
                            <span id='alt_mobileView' ~if $arrOutDisplay.contact.alt_mobile eq $notFilledInText` class="color5"  ~/if`>
                              ~$arrOutDisplay['contact']['alt_mobile']`
                            </span>
                          </p>
                          <p class="color11" id="alt_mobile_owner_nameLabelParent">
                            <span id="alt_mobile_descView"> 
                               ~$arrOutDisplay['contact']['alt_mobile_desc']`
                            </span>
                            <span  class="ml5 ~if ($editApiResponse.Contact.ALT_MOBILE.value|count_characters:true) eq 0 || ($editApiResponse.Contact.ALT_MOBILE_OWNER_NAME.value|count_characters:true) eq 0 || $editApiResponse.Contact.ALT_MOBILE_OWNER_NAME.screenBit neq 1` disp-none ~/if` js-undSecMsg">
                              (<span class="color5 f13 wordWrapBreak" >Under Screening</span>)
                            </span>
                          </p>
                        </div>
                        <div class="fr wid25p pos-abs right0">
                        	<div ~if $arrOutDisplay['contact']['alt_mob_status'] eq Verified` class="color12" ~else` class="color5 cursp" ~/if` id="alt_mob_statusView">~$arrOutDisplay['contact']['alt_mob_status']`</div>
                        </div>
                    </div>
                  </li>
                  <li>
                    <p class="color12">
                      Landline No.
                    </p>
                    <div class="clearfix pos-rel">
                    	<div class="fl wid74p">
                        	<p class="color11">
                            <span id='landlineView' ~if $arrOutDisplay.contact.landline eq $notFilledInText` class="color5"  ~/if`>
                              ~$arrOutDisplay['contact']['landline']`
                            </span>
                          </p>
                   			<p class="color11" id="phone_owner_nameLabelParent"> 
                          <span id="landline_descView"> 
                            ~$arrOutDisplay['contact']['landline_desc']`
                          </span> 
                          <span class="ml5 ~if ($editApiResponse.Contact.PHONE_RES.value|count_characters:true) eq 0 || ($editApiResponse.Contact.PHONE_OWNER_NAME.value|count_characters:true) eq 0 || $editApiResponse.Contact.PHONE_OWNER_NAME.screenBit neq 1` disp-none ~/if` js-undSecMsg">
                            (<span class="color5 f13 wordWrapBreak" >Under Screening</span>)
                          </span>
                        </p>
                        </div>
                        <div class="fr wid25p pos-abs right0">
                        	<div ~if $arrOutDisplay['contact']['phone_res_status'] eq Verified` class="color12" ~else` class="color5 cursp" ~/if` id="phone_res_statusView">~$arrOutDisplay['contact']['phone_res_status']`</div>
                        </div>
                    </div>                    
                  </li>
                  <li>
                    <p class="color12">Suitable time to call</p>
                    <p class="color11">
                      <span id='time_to_callView' ~if $arrOutDisplay.contact.time_to_call eq $notFilledInText` class="color5"  ~/if`>
                      ~$arrOutDisplay['contact']['time_to_call']`
                      </span>
                    </p>
                  </li>
                  <li>
                    <p class="color12" id="contactLabelParent" >
                      Contact Address <span class="ml10 ~if ($editApiResponse.Contact.CONTACT.value|count_characters:true) eq 0 || $editApiResponse.Contact.CONTACT.screenBit neq 1` disp-none ~/if` js-undSecMsg">
                          <span class="disp_ib color5 f13" >Under Screening</span>
                        </span>
                    </p>
                    
                    
                    <p class="color11">
                      <span id='addressView' ~if $arrOutDisplay.contact.address eq $notFilledInText` class="color5"  ~/if`>
                        ~$arrOutDisplay['contact']['address']`
                      </span>
                    </p>
                  </li>
                  <li>
                    <p class="color12" id="parents_contactLabelParent" >
                      Parent's Address <span class="ml10 ~if ($editApiResponse.Contact.PARENTS_CONTACT.value|count_characters:true) eq 0 || $editApiResponse.Contact.PARENTS_CONTACT.screenBit neq 1` disp-none ~/if` js-undSecMsg">
                          <span class="disp_ib color5 f13" >Under Screening</span>
                        </span>
                    </p>
                    <p class="color11">
                      <span id='parent_addressView' ~if $arrOutDisplay.contact.parent_address eq $notFilledInText` class="color5"  ~/if`>
                        ~$arrOutDisplay['contact']['parent_address']`
                      </span>
                    </p>
                  </li>
                </ul>
              </div>
              <!--start:Edit Basic Details-->
              <div class="prfp12 f14 fontlig">
                <div class="clearfix cntct" id="contactEditForm"><!---Edit Form--></div>
              </div>
              <!--end:Edit Basic Details-->             
            </div>
            <!--end:contact details-->
            
            <!--start:Horoscope Details--> 
                ~include_Partial("profile/jspcViewProfile/_jspcViewProfileAstroSection",["apiData"=>$arrOutDisplay,"bEditView"=>true,"notFilledInText"=>$notFilledInText,"editApi"=>$editApiResponse,"selfReligion"=>$arrOutDisplay["lifestyle"]["religion_value"]])`
            <!--end:Horoscope Details-->
            <!--start:gunna layer-->
            <div class="pos_fix layerMidset layersZ disp-none" id="horoscopeLayer">
                <div class="edpwid18 upHoroClr pos-rel">
                    <i id="closebtnHL" class="sprite2 edpcross1 pos-abs  CPclosepos cursp"></i>
                    <!--start:layer add your horoscope-->
                    <div>
                        <!--start:heading-->
                        <div class="upperCase f16 fontreg colrw lh61 bg5 pl30">ADD YOUR HOROSCOPE</div>
                        <!--end:heading-->
                        <div id="horoscopeDiv">
                            <div class="txtc pt10">Horoscope match is must?</div>
                            <div class="fontreg  txtc pt10 pb30">
                                <button id="bt_yes" class="lh41 bg_pink txtc colrw brdr-0 wid33p_1 f15 cursp">Yes</button>
                                <button id="bt_no" class="lh41 bg_pink txtc colrw brdr-0 wid33p_1 ml10 f15 cursp">No</button>
                            </div>
                            <!--start:buttons-->
                            <!--<div class="fontreg  txtc pt30 pb30">
                                <button id="createHoroBtn" class="lh41 bg_pink txtc colrw brdr-0 wid33p_1 f15 cursp">Create my Horoscope</button>
                                <button id="uploadHoroBtn" class="lh41 bg_pink txtc colrw brdr-0 wid33p_1 ml10 f15 cursp">Upload a scanned copy</button>
                            </div>    -->        
                            <!--end:buttons-->
                            <!--start:div-->
                            <div class="edpwid19 mauto disp-none" id="uploadHoroDiv">
                                <div class="bg-white edpbrd5  clearfix lh30">
                                    <div class="fl pt8 pl20">
                                        <input id="uploadFileName" readonly class="color12 f15 fontlig w416" value="" >
                                    </div>
                                    <div class="fr edpbrd6 wid150 txtc">
                                        <div class="file-upload">
                                            <span class="fontlig f15 color11">Browse File</span>
                                            <input name="horoscope" id="horoFile" class="upload" type="file">
                                        </div>
                                    </div>
                                </div>
                                <p id="uploadError" class="pt10 f13 fontlig color12">.gif/.jpeg/.jpg files not more than 4MB  </p>
                                <div class="txtc mt20 edpm2">
                                    <button id="uploadSubmit" disabled="true" class="bg_pink lh44 colrw f20 fontreg pl30 pr30 brdr-0 cursp">Upload</button>
                                </div>
                            </div>            
                            <!--end:div-->
                        </div>
                        <!--start:div-->
                        <div id="horoscopeSuccess" class="txtc f17 color11 fontlig edpm3 disp-none">
                            Horoscope uploaded successfully
                        </div>
                        <!--end:div-->
                        <!--start:div-->
                        <div id="createHoroDiv" class="edpwid19 mauto disp-none">
                            <iframe class="brdr-0 fullwid hgt275" src="http://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_DataEntry_Matchstro.dll?BirthPlace?js_UniqueID=~$js_UniqueID`&js_year=~$BIRTH_YR`&js_month=~$BIRTH_MON`&js_day=~$BIRTH_DAY`"></iframe>
                        </div>            
                        <!--end:div--> 
                    </div>
                    <!--end:layer add your horoscope-->
                </div>
            </div>
            <!--end:gunna layer-->
             <!--start:gunna layer-->
            <div class="pos_fix layerMidset layersZ disp-none" id="removeHoroscopeLayer">
                <div class="edpwid18 upHoroClr pos-rel">
                    <i id="removeClosebtnHL" class="sprite2 edpcross1 pos-abs  CPclosepos cursp"></i>
                    <!--start:layer add your horoscope-->
                    <div>
                        <!--start:heading-->
                        <div class="upperCase f16 fontreg colrw lh61 bg5 pl30">REMOVE HOROSCOPE</div>
                        <!--end:heading-->
                        <div id="removeHoroscopeDiv">
                            <div class="txtc pt10">This will delete your Horoscope, Time of Birth and Place of Birth. Would you like to proceeed?</div>
                            <div class="fontreg  txtc pt10 pb30">
                                <button id="Rbt_yes" class="lh41 bg_pink txtc colrw brdr-0 wid33p_1 f15 cursp">Yes</button>
                                <button id="Rbt_no" class="lh41 bg_pink txtc colrw brdr-0 wid33p_1 ml10 f15 cursp">No</button>
                            </div>
                        </div>
                    </div>
                    <!--end:layer add your horoscope-->
                </div>
            </div>
            <!--end:gunna layer-->
            
            <!--end:Horoscope Details--> 
            <!--start:verifcation id-->
            <div class="bg-white fullwid fontlig mt15" id="section-verification">
              <div class="edpp3 prfbr2">
                <ul class="hor_list clearfix  fullwid">
                  <li class="edpwid2 clearfix"> <i class="fl vicons edpic4"></i>
                    <p class="fl color5 f17 pt3 pl5">Verification ID </p>
                  </li>
                  <li class="pt4">
                      <a  class="color5 fontreg f15 js-editBtn js-verificationView cursp" data-section-id="verification">
                          ~if $editApiResponse.Contact.ID_PROOF_TYP.value && $editApiResponse.Contact.ID_PROOF_NO.value`
                            Edit
                          ~else`
                            Add
                          ~/if`
                      </a>
                  </li>
                </ul>
              </div>
              <div class="prfp12 f14 js-verificationView">
                <p class="color2 f11 txtc">* ID will not be visible to any member.</p>
                <p class="txtc color12 f14 pt40 pb48">
                    <span id='my_verification_idView' ~if $arrOutDisplay.contact.my_verification_id eq $notFilledInText` class="color5" ~else` class="color11"  ~/if`>
                        ~$arrOutDisplay['contact']['my_verification_id']`
                      </span>
                    
                </p>
              </div>
                <!--start:Edit Basic Details-->
              <div class="prfp12 f14 fontlig">
                <div class="clearfix cntct" id="verificationEditForm"><!---Edit Form--></div>
              </div>
              <!--end:Edit Basic Details-->   
            </div>
            <!--end:verifcation id-->
          </div>
          <!--end:right div--> 

        </div>
      </div>
      <!--end:content edit part-->
    </div>
    <!--start:footer-->
    ~include_partial('global/JSPC/_jspcCommonFooter')`      
    <!--end:footer--> 
  </div>
  <!--end:second part--> 
</div>
<script type="text/javascript">
  var senderEmail = "~$loggedInEmail`";
  var ProCheckSum = "~$arrOutDisplay["page_info"]["profilechecksum"]`";
  var profileGender = "~$arrOutDisplay["about"]["gender"]`";
  var username = "~$username`";
  var id = "~$js_UniqueID`";
  var dd = "~$BIRTH_DAY`";
  var mm = "~$BIRTH_MON`";
  var yy = "~$BIRTH_YR`";
  var EditWhatNew = "~$EditWhatNew`";
  //var _e_api = ~$jsonEditResp|decodevar|json_encode`;
   var $profileScore = null;
   var profileCompletionValue = "~$iPCS`";
   var coverPhotoUrl = "~$editApiResponse.Details.COVER.value`";
</script>
