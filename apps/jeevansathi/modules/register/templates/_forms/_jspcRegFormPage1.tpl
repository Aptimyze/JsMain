<!--start:text-->
<div class="fontlig f22 txtc colr1 pt30">Welcome! Let's start your partner search with this Sign up.</div>
<div class="clr pt30"></div>
<!--end:text--> 
<!--start:div.1-->
<!--start:form div-->
<div class="fl wid80p">
  <div class="txtr fontlig f12 required">mandatory</div>
  <!--start:form-->
  <div class="fr clearfix pt5 fontlig f15 fullwid formreg"> 
      <input type="text" name="username" style="display: none">
      <input type="password" name="password" style="display: none">
    <!--start:Your email-->
    <div>
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="email_error"></div>
      </div>
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5 visHid disp-none" id="email_autoC"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none" tabindex="0" id="email_box">
          <label class="lblreg pos_abs js-email js-list pos1_lbl1 required" id="email_label" data-attr="email">Your Email</label>
          <input autocomplete="off" class="js-tBox reg_wid2 fr brdr-0 f15 fontlig toValidate" id="email_value" maxlength="100" data-type="text" type="text" data-toSave ="email" value="" data-required=true data-validate=true tabindex="-1" data-fieldtype="email"/>
        </div>
      </div>
    </div>
    <!--end:Your email--> 
    <!--start:Password-->
    <div class="mt7">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="password_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none" tabindex="0" id="password_box">
          <label class="lblreg pos_abs js-password js-list pos1_lbl1 required" id="password_label" data-attr="password">Password</label>
          <input class="js-tBox reg_wid2 fr brdr-0 f15 fontlig toValidate" id="password_value" maxlength="40" data-type="text" type="password" maxlength="40" tabindex="-1" data-toSave ="password" data-required=true data-validate=true data-fieldtype="password"/>
          <div class="pos-abs disp-none" id="strength-text" style="top: 33px;right: 45px;z-index:10;font-size: 10px;color: rgb(84, 76, 76);">Strength</div>
          <div class="strength-mtr disp-none pos_rel" id="strengthBar">
            <span id="strength-span" class="strength-span" style="width:0px"></span>

          </div>
          <i id="passShow" class="reg-sprtie reg-eye pos_abs reg-pos2 cursp disp-none"></i> </div>
      </div>
    </div>
    <!--end:Password--> 
    <!--start:Create profile for-->
    <div class="mt7">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="cpf_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="cpf_box">
          <label class="lblreg pos_abs js-cpf js-list pos1_lbl1 required" id="cpf_label" data-attr="cpf">Create profile for</label>
          <!--start:option div-->
          <div class="js-tBox reg-mainlist jsdd-cpf" id='cpf_value' data-number="8" data-type="radio" data-max-no-element="6" data-toSave ="relationship" data-required=true data-validate=true data-fieldtype="cpf">
            <div id="cpf-inputBox_set"></div>
            <ul class="rlist cpfopt" id="cpf-list_set">
            </ul>
          </div>
          <!--end:option div--> 
          <!--start:other drop down-->
          <div class="js-other sub-mainlist pos_abs reg-pos4 reg-zi1 regdropbox boxshadow">
            <ul class="rlist cpf-otheropt" id="cpf-other-list_set">
            </ul>
          </div>
          <!--end:other drop down--> 
        </div>
      </div>
    </div>
    <!--endt:Create profile for--> 
    <!--start:gender-->
    <div class="mt7 disp-none" id = "genderBox">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="gender_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="gender_box">
          <label class="lblreg pos_abs js-gender js-list pos1_lbl1 required" id="gender_label" data-attr="gender">Gender</label>
          <!--start:option div-->
          <div class="js-tBox reg-mainlist jsdd-gender showdd" id='gender_value' data-number="2" data-type="radio" data-required=true data-toSave ="gender" data-validate=true data-fieldtype="gender">
            <div id="gender-inputBox_set"></div>
            <ul class="rlist genderopt" id="gender-list_set">
            </ul>
          </div>
          <!--end:option div--> 
        </div>
      </div>
    </div>
    <!--end:gender--> 
    <!--start:Full Name-->
    <div class="mt7">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="name_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="name_box">
          <label class="lblreg pos_abs js-name js-list pos1_lbl1 required" id="name_label" data-attr="name">Full Name</label>
	  <input autocomplete="off" class="js-tBox reg_wid2 fl brdr-0 f15 fontlig wid77p fl" id="name_value" maxlength="40" data-type="text" type="text" data-toSave="name_of_user" data-required=true  data-validate=true tabindex="-1" data-fieldtype="name" />
	    <div id="hoverDiv" class="disp_ib pos-abs r0 mt12 mr5 cursp"><span id="showText" class="colrGrey fontlig f12 showToAll">Show to All</span><i id="settingsIcon"></i>
		<ul id="optionDrop" class="optionDrop pos-abs disp-none" data-toSave="displayName">
		    <li class="selected" id="showYes" data-fieldVal='Y'>Show my name to all
		    </li>
		    <li id="showNo" data-fieldVal="N">Don't show my name<br> ( You will not be able to see names of other members )
		    </li>
		</ul>
	    </div>
        </div>
	<div class="outl-none colrTxt f13 fontreg fr extraTxt bg-white">If you wish to hide your name from others, click on settings icon and choose the setting</div>
      </div>
    </div>
    <!--end:Full Name--> 
    <!--start:Date of Birth-->
    <div class="mt7">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="dob_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix" id="dob_selector">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="dob_box">
          <label class="lblreg pos_abs js-dob js-list pos1_lbl1 required" id="dob_label" data-attr="dob">Date of Birth</label>
          <!--start:option div-->
          <div class="js-tBox reg-mainlist jsdd-dob showdd" id="dob_value" data-type="dobSpecial" data-required=true data-toSave ="dtofbirth" data-validate=true data-fieldtype="dob">
            <div id="dob-inputBox_set"></div>
            <ul class="rlist dobopt disp-none" id="dob-list_set">
              <li id="li_dob1"><span id="date_value">Date</span><i id="dateArrow1" class="reg-sprtie reg-droparrow pos_abs reg-pos12 reg-zi100"></i><i id="dateArrow2" class="icons rarrwdob reg-pos11 pos_abs disp-none"></i></li>
              <li id="li_dob2"><span class="disp_ib fullwid pos-rel" id="month_value">Month</span><i id="monthArrow1" class="reg-sprtie reg-droparrow pos_abs reg-pos12 reg-zi100 disp-none"></i><i id="monthArrow2" class="icons rarrwdob reg-pos11 pos_abs disp-none"></i></li>
              <li id="li_dob3"><span class="disp_ib fullwid pos-rel" id="year_value">Year</span><i id="yearArrow1" class="reg-sprtie reg-droparrow pos_abs reg-pos12 reg-zi100 disp-none"></i><i id="yearArrow2" class="icons rarrwdob reg-pos11 pos_abs disp-none"></i></li>
            </ul>
          </div>
          <!--end:option div--> 
          <!--start:date div-->
          <div class="js-date sub-mainlist pos_abs reg-pos5 reg-zi1 regdropbox boxshadow reg-wid12">
            <ul id="datesub">
            </ul>
          </div>
          <!--end:date div--> 
          <!--start:month div-->
          <div class="js-month sub-mainlist pos_abs reg-pos10 reg-zi1 regdropbox boxshadow reg-wid6">
            <ul id="monthsub">
            </ul>
          </div>
          <!--end:month div--> 
          <!--start:year div-->
          <div class="js-year sub-mainlist pos_abs reg-pos10 reg-zi1 regdropbox boxshadow reg-wid6 scrolla reg-hgt200">
            <ul id="yearsub">
            </ul>
          </div>
          <!--end:month div--> 
        </div>
      </div>
    </div>
    <!--start:Date of Birth--> 
    <!--start:phone number-->
    <div class="mt7">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="phone_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none" tabindex="0" id="phone_box">
          <label class="lblreg pos_abs js-phone js-list pos1_lbl1 required" id="phone_label" data-attr="phone">Mobile No.</label>
          <!--start:option div-->
          <div class="js-tBox reg-mainlist jsdd-phone showdd" id="phone_value" data-type="text" data-required=true data-validate=true data-fieldtype="phone" data-toSave ="phone_mob">
            <div class="fullwid clearfix lh41">
              <div class="fl txtc brdrr-1 disp-none" style="width:60px"><input autocomplete="off" type="text" tabindex="-1" maxlength="6" id="isd_value" class="fullwid txtc toValidate" value="+91"/></div>
              <div class="fl disp-none">
                <input autocomplete="off" class="wid380 fr brdr-0 f15 fontlig toValidate" tabindex="-1" maxlength="10" id="mobile_value" type="text"/>
              </div>
              <div class="fl pos_rel helpicon disp-none" id="phone_help"> <i class="reg-sprtie inforeg mt10 help" data-attr="phone"></i> 
                <!--start:help box-->
                <div class="helpbox">
                  <div class="pos_abs reg-pos7 help">
                    <div class="pos_rel brdr-1 bg-white wid280">
                      <div class="padall-10 txtc">
                        <div class="colr5 f15 lh16">100% Phone verified profiles</div>
                        <div class="f13 pt10 fontlig lh16">To have a secure user experience & genuine members, phone verification is mandatory for all. You can change phone privacy settings to show your phone number to select members.</div>
                        <i class="pos_abs reg-sprtie reg-help-arow reg-pos8"></i> </div>
                    </div>
                  </div>
                </div>
                <!--end:help box--> 
              </div>

            </div>
          </div>
          <!--end:option div--> 
        </div>

      </div>
      <div class="fullwid clearfix disp-none jsdd-phone" id="dropHelp">
        <div class="fullwid bg-white reg-wid3 fr">
          <div class="padall-10 fontreg f14 colr4 lh20">
            <div class="f13">Jeevansathi members who like your profile will contact you on this phone number.</div>
            <div class="f13"><span class="fontrobbold">Verification</span> of this number is compulsory after your registration.</div>
          </div>
        </div>
      </div>
    </div>
    <!--end:phone number--> 
    <!--start:registration button-->
    <div class="fullwid mt30">
      <div class="reg-marl1"> 
        <!--start:button-->
        <div class="reg-btn">
          <div class="wid200 scrollhid pos_rel">
            <button class="padalla fontreg f20 colrw wid200 buttonSub pinkRipple hoverPink" id="regPage1Submit" type="submit"> Register me </button>
          </div>
        </div>
        <!--end:button--> 
        <!--start:text-->
	<div class="mt10 f13 colr4 fontreg lh20"> By clicking on <span class="fontrobbold">'Register me'</span>, you confirm<br/>
          that you accept the <a target="_blank" href="/static/page/disclaimer" class="fontrobbold colr5 cursp">Terms of Use</a> and <a target="_blank" href="/static/page/privacypolicy" class="fontrobbold colr5 cursp">Privacy Policy</a></div>
        <!--end:text--> 
      </div>
    </div>
    <!--end:registration button--> 
  </div>
  <!--end:form--> 
<div id="hiddenParams" style="display: none">
<input type="hidden" name="tieup_source" value="~$pageObj->sourceVar['tieup_source']`">
<input type="hidden" name="hit_source" value="~$pageObj->sourceVar['hit_source']`">
<input type="hidden" name="adnetwork" value="~$pageObj->tieupParams['JS_ADNETWORK']`">
<input type="hidden" name="adnetwork1" value="~$sf_request->getParameter('adnetwork1')`" >
<input type="hidden" name="fname" value="~$NAME`" >
<input type="hidden" name="newip" value="~$NEWIP`" >
<input type="hidden" name="account" value="~$pageObj->tieupParams['JS_ACCOUNT']`" >
<input type="hidden" name="campaign" value="~$pageObj->tieupParams['JS_CAMPAIGN']`" >
<input type="hidden" name="adgroup" value="~$pageObj->tieupParams['JS_ADGROUP']`" >
<input type="hidden" name="keyword" value="~$pageObj->tieupParams['JS_KEYWORD']`">
<input type="hidden" name="match" value="~$pageObj->tieupParams['JS_MATCH']`">
<input type="hidden" name="lmd" value="~$pageObj->tieupParams['JS_LMD']`">
<input type="hidden" name="showlogin" value="~$sf_request->getParameter('showlogin')`" >
<input type="hidden" name="frommarriagebureau" value="~$FROMMARRIAGEBUREAU`" >
<input type="hidden" name="groupname" value="~$pageObj->pageVar[groupNameParams][GROUPNAME]`" >
<input type="hidden" name="affiliateid" value="~$sf_request->getParameter('affiliateid')`"/>
<input type="hidden" name="id" value="~$ID_AFF`" >
<input type="hidden" name="leadid" id ="leadid" value="~$leadid`"  >
<input type="hidden" name="secondary_source" value="~$sf_request->getParameter('secondary_source')`" >
~if $pageObj->sourceVar['TIEUP_SOURCE'] eq 'ofl_prof'`
	<input type="hidden" name="email_is_ok" id="email_is_ok" value="1" >
~else`
	<input type="hidden" name="email_is_ok" id="email_is_ok" value="" >
~/if`
<input type="hidden" name="source" value="~$pageObj->sourceVar['source']`" id="reg_source">
<input type="hidden" name="record_id" id="reg_record_id" />
<input type="hidden" name="_csrf_token" value="9be2bb7d7764889c2d0bf19d93d1a848" id="registrationData__csrf_token" />
<input type="hidden" name="submitPage" value="1" />
</div>
</div>
<!--end:form div--> 
        <!--start:why register div-->
        ~include_partial("register/header/_jspcRegSideBar",[])`
        <!--end:why register div-->
