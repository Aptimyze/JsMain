<!--start:text-->
~if $pageObj->isIncomplete`
<a href="#" id="clickAtLast"></a>
~/if`
<div class="fontlig f22 txtc colr1 pt30">~if $pageObj->isIncomplete`Complete your profile now to contact members you like and to receive interests~else`Hi~if $templateVars['name']` ~$templateVars['name']`~/if`! You are joining the Best Matchmaking Experience.~/if`</div>
<!--end:text--> 
<!--start:div.1-->    
<div class="clearfix pt30"> 
  <!--start:form div-->
  <div class="fl wid80p">
    <div class="txtr fontlig f12 required">mandatory</div>
    <!--start:form-->
    <div class="fr clearfix pt5 fontlig f15 fullwid formreg" ~if $pageObj->isIncomplete`style="visibility: hidden"~/if`> 
      <!--start:Mother tongue-->
      <div>
        <!--start:error div-->
        <div class="clearfix f14 fontlig">
          <div class="reg-wid3 fr colr5  visHid" id="mtongue_error"></div>
        </div>
        <!--end:error div-->
        <div class="fullwid pos_rel clearfix">
          <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="mtongue" tabindex="0" id="mtongue_box">
            <label class="lblreg pos_abs js-mtongue js-list pos1_lbl1 required" id="mtongue_label" data-attr="mtongue">Mother tongue</label>
            <div class="js-tBox" id="mtongue_value" data-type="gridDropdown" data-columns="1" data-alpha="1" data-validate=true data-required=true data-toSave ="mtongue" data-fieldtype="mtongue" data-search="true">
                <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="mtongue-inputBox_set" tabindex="-1"/>
              <div id="mtongue-gridDropdown_set"></div>
            </div>
          </div>
        </div>
      </div>
      <!--end:Mother tongue--> 
      <!--start:Religion-->
      <div class="mt7">
        <!--start:error div-->
        <div class="clearfix f14 fontlig">
          <div class="reg-wid3 fr colr5  visHid" id="religion_error"></div>
        </div>
        <!--end:error div-->
        <div class="fullwid pos_rel clearfix">
          <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="religion" tabindex="0" id="religion_box">
            <label class="lblreg pos_abs js-religion js-list pos1_lbl1 required" id="religion_label" data-attr="religion">Religion</label>
            <div class="js-tBox" id="religion_value" data-type="gridDropdown" data-columns="4"  data-required=true data-fieldtype="religion" data-toSave ="religion" data-validate=true data-has-dependent="caste" data-search="true">
                <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="religion-inputBox_set" tabindex="-1"/>
              <div id="religion-gridDropdown_set"></div>
            </div>
          </div>
        </div>
      </div>
      <!--end:Religion--> 
      <!--start:Caste-->
      <div class="mt7 disp-none" id="caste-selector">
        <!--start:error div-->
        <div class="clearfix f14 fontlig">
          <div class="reg-wid3 fr colr5  visHid" id="caste_error"></div>
        </div>
        <!--end:error div-->
        <div class="fullwid pos_rel clearfix">
          <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="caste" tabindex="0" id="caste_box">
            <label class="lblreg pos_abs js-caste js-list pos1_lbl1 required" id="caste_label" data-attr="caste">Caste</label>
            <div id="caste_value" data-type="gridDropdown" data-required=true  data-validate=true   data-fieldtype="caste" data-toSave ="caste" data-dependent="religion" data-columns="3" data-alpha="3" data-search="true">
              <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="caste-inputBox_set" tabindex="-1"/>
              <div id="caste-gridDropdown_set"></div>
            </div>
          </div>
        </div>
        <!-- Start: Caste No Bar -->
     
      <div class="mt10 clearfix f14 fontlig" id="casteNoBarDiv">
        <div class="reg-wid3 fr">
          <div class="disp_ib">
            <input type="checkbox" id="caste_no_bar"/>
          </div>
          <div class="disp_ib colr4 opa80 fontreg">
            I am open to marry people of all castes(Caste no bar)
          </div>
        </div>
      </div>
      <!-- End: Caste No Bar -->
      </div>
      <!--end:Caste--> 
      <!-- Start: CasteMuslim -->
      <div class="mt7 disp-none" id="casteMuslim-selector">
        <!--start:error div-->
        <div class="clearfix f14 fontlig">
          <div class="reg-wid3 fr colr5  visHid" id="casteMuslim_error"></div>
        </div>
        <!--end:error div-->
        <div class="fullwid pos_rel clearfix">
          <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="casteMuslim" tabindex="0" id="casteMuslim_box">
            <label class="lblreg pos_abs js-casteMuslim js-list pos1_lbl1 required" id="casteMuslim_label" data-attr="casteMuslim">Caste</label>
            <div id="casteMuslim_value" data-type="gridDropdown" data-required=true  data-validate=true   data-fieldtype="casteMuslim" data-toSave ="castemuslim" data-columns="3" data-search="true">
              <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="casteMuslim-inputBox_set" tabindex="-1"/>
              <div id="casteMuslim-gridDropdown_set"></div>
            </div>
          </div>
        </div>
      </div>
        <!-- End: CasteMuslim -->
      
      <!--start:subCaste-->
      <div class="mt7 disp-none" id="subcaste_selector">
        <!--start:error div-->
        <div class="clearfix f14 fontlig">
          <div class="reg-wid3 fr colr5  visHid" id="subcaste_error"></div>
        </div>
        <!--end:error div-->
        <div class="fullwid pos_rel clearfix">
          <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="subcaste" tabindex="0" id="subcaste_box">
            <label class="lblreg pos_abs js-subcaste js-list pos1_lbl2" id="subcaste_label" data-attr="subcaste">Subcaste/Surname&nbsp;&nbsp;&nbsp;</label>
            <div class="js-tBox reg_wid2 fr brdr-0 f15 fontlig" id="subcaste_value" data-type="autoSuggest"  data-required=false data-validate=true data-toSave ="subcaste" data-fieldtype="subcaste" data-search="true" data-hidden-Drop="true"/>
            <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="subcaste-inputBox_set" tabindex="-1"/>
            <div class="disp-none" id="subcaste-gridDropdown_set"></div>
          </div>
        </div>
      </div>
    </div>
    <!--end:subCaste--> 
    <!--start:subCaste-->
    <div class="mt7 disp-none" id="manglik_selector">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="manglik_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="manglik" tabindex="0" id="manglik_box">
          <label class="lblreg pos_abs js-manglik js-list pos1_lbl2" id="manglik_label" data-attr="manglik">Are you manglik?&nbsp;&nbsp;&nbsp;</label>
          <div class="js-tBox reg-mainlist showdd" id='manglik_value' data-number="3" data-type="radio" data-validate=true data-required=false data-toSave ="manglik" data-fieldtype="manglik">
            <div id="manglik-inputBox_set"></div>
            <ul class="rlist manglikopt" id="manglik-list_set">
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!--end:subCaste-->
    <!--start:horoscopeMatch-->
    <div class="mt7 disp-none" id="horoscopeMatch_selector">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="horoscopeMatch_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="horoscopeMatch" tabindex="0" id="horoscopeMatch_box">
          <label class="lblreg pos_abs js-horoscopeMatch js-list pos1_lbl2" id="horoscopeMatch_label" data-attr="horoscopeMatch">Horoscope match is necessary?&nbsp;&nbsp;&nbsp;</label>
          <div class="js-tBox reg-mainlist showdd" id='horoscopeMatch_value' data-number="2" data-type="radio" data-validate=true data-required=false data-toSave ="horoscopeMatch" data-fieldtype="horoscopeMatch">
            <div id="horoscopeMatch-inputBox_set"></div>
            <ul class="rlist horoscopeMatchopt" id="horoscopeMatch-list_set">
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!--end:subCaste-->
 
    <!--start:Marital status-->
    <div class="mt7">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="mstatus_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="mstatus_box">
          <label class="lblreg pos_abs js-mstatus pos1_lbl1 required" id="mstatus_label" data-attr="mstatus">Marital status</label>
          <div class="js-tBox" id="mstatus_value" data-type="gridDropdown" data-toSave = "mstatus" data-columns="3" data-required=true data-validate=true data-fieldtype="mstatus">
            <div id="mstatus-inputBox_set"></div>
            <div id="mstatus-gridDropdown_set"></div>
          </div>
        </div>
      </div>
    </div>
    <!--end:Marital status--> 
    <!--start:Have Children?-->
    <div id="haveChildren_selector" class="mt7 disp-none">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="haveChildren_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="havechild" tabindex="0" id="haveChildren_box">
          <label class="lblreg pos_abs js-haveChildren js-list pos1_lbl1 required" id="haveChildren_label" data-attr="havechild">Have Children?</label>
          <div class="js-tBox reg-mainlist showdd" id='haveChildren_value' data-toSave ="havechild" data-number="3" data-type="radio"  data-validate=true data-required=true data-fieldtype="haveChildren">
            <div id="haveChildren-inputBox_set"></div>
            <ul class="rlist haveChildrenopt" id="haveChildren-list_set">
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!--end:Have Children?--> 
    <!--start:height-->
    <div class="mt7">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="height_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="height" tabindex="0" id="height_box">
          <label class="lblreg pos_abs js-height js-list pos1_lbl1 required" id="height_label" data-attr="height">Height</label>
          <div class="js-tBox" id="height_value" data-type="gridDropdown" data-columns="3" data-toSave ="height" data-validate=true data-required=true data-fieldtype="height">
            <div id="height-inputBox_set"></div>
            <div id="height-gridDropdown_set"></div>
          </div>
        </div>
      </div>
    </div>
    <!--end:height--> 
    <!--start:countryReg-->
    <div class="mt7">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="countryReg_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="countryRegliv" tabindex="0" id="countryReg_box">
          <label class="lblreg pos_abs js-countryReg js-list pos1_lbl1 required" id="countryReg_label" data-attr="countryRegliv">Country</label>
          <div class="js-tBox" id="countryReg_value" data-type="gridDropdown" data-columns="3" data-alpha="4" data-toSave ="country_res" data-validate=true data-required=true data-fieldtype="countryReg"  data-search="true">
            <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="countryReg-inputBox_set" tabindex="-1"/>
            <div id="countryReg-gridDropdown_set"></div>
          </div>
        </div>
      </div>
    </div>
    <!--end:countryReg-->
    <!--start:state-->
    <div class="mt7 disp-none" id="stateReg_selector">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="stateReg_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="stateRegliv" tabindex="0" id="stateReg_box">
          <label class="lblreg pos_abs js-stateReg js-list pos1_lbl1 required" id="stateReg_label" data-attr="stateRegliv">State</label>
          <div class="js-tBox" id="stateReg_value" data-type="gridDropdown" data-columns="3" data-toSave ="state_res" data-validate=true data-required=true data-fieldtype="stateReg"  data-search="true">
            <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="stateReg-inputBox_set" tabindex="-1"/>
            <div id="stateReg-gridDropdown_set"></div>
          </div>
        </div>
      </div>
    </div>
    <!--end:stateReg-->
    <!--start:city-->
    <div class="mt7 disp-none" id="cityReg_selector">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="cityReg_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="cityRegliv" tabindex="0" id="cityReg_box">
          <label class="lblreg pos_abs js-cityReg js-list pos1_lbl1 required" id="cityReg_label" data-attr="cityRegliv">City living in</label>
          <div class="js-tBox" id="cityReg_value" data-type="gridDropdown" data-columns="3"  data-toSave ="city_res" data-validate=true data-required=true data-fieldtype="cityReg"  data-search="true">
            <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="cityReg-inputBox_set" tabindex="-1"/>
            <div id="cityReg-gridDropdown_set"></div>
          </div>
          <!--<div class="pos_abs fontreg f11 color8 reg-pos9 txtu" style="cursor:pointer;" id="NfiLink">Not from India?</div>-->
        </div>
      </div>
    </div>
    <!--end:city--> 
    
    <!--start:Pincode-->
    <div class="mt7 disp-none" id="pincode_selector">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="pincode_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">

        <div class="reg-wid3 fr reg-divselc bg-white" data-attr="pincode" tabindex="0" id="pin_box">
          <label class="lblreg pos_abs js-pin js-list pos1_lbl1 required" id="pin_label" data-attr="pincode">Pincode</label>
          <input autocomplete="off" class="js-tBox reg_wid2 fr brdr-0 f15 fontlig toValidate" tabindex="0" id="pin_value" data-type="text" data-toSave ="pincode" type="text" maxlength="6" data-validate=true data-required=true data-fieldtype="pincode"/>
        </div>
      </div>
    </div>
    <!--end:Pincode--> 
    <!--start:registration button-->
    <div class="fullwid mt30">
      <div class="reg-marl1"> 
        <!--start:button-->
        <div class="reg-btn">
          <div class="wid200 scrollhid pos_rel">
          <button class="padalla fontreg f20 colrw wid200 buttonSub  pinkRipple hoverPink" id="regPage2Submit" tabindex="0">Continue</button>
          </div>
        </div>
        <!--end:button--> 

      </div>
    </div>
    <!--end:registration button--> 
  </div>
  <!--end:form--> 
</div>
<!--end:form div--> 
<input type="hidden" name="_csrf_token" value="9be2bb7d7764889c2d0bf19d93d1a848" id="registrationData__csrf_token" tabindex="-1" />
<!--end:form div--> 
        <!--start:why register div-->
        ~include_partial("register/header/_jspcRegSideBar",[])`
        <!--end:why register div-->
