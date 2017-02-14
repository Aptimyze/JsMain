	<!--start:text-->
      <div class="fontlig f22 txtc colr1 pt30">We would love to know about your family.</div>
	<!--end:text-->
	<!--start:div.1-->
	<div class="clearfix pt30">
		<div class="fl wid80p">
		<!--start:form-->
			<div class="fr clearfix pt5 fontlig f15 fullwid formreg">
            <!--start:family type-->
            <div class="mt7">
              <!--start:error div-->
              <div class="clearfix f14 fontlig">
                <div class="reg-wid3 fr colr5 visHid" id="familyType_error"></div>
              </div>
              <!--end:error div-->
              <div class="fullwid pos_rel clearfix">
                <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="familyType_box">
                  <label class="lblreg pos_abs js-familyType js-list pos1_lbl2" id="familyType_label" data-attr="familyType">Family type</label>
                  <div class="js-tBox reg-mainlist showdd" id="familyType_value" data-validate=false data-required=false data-fieldtype="familyType" data-number="4" data-type="radio" data-toSave="family_type">
                    <div id="familyType-inputBox_set"></div>
                    <ul id="familyType-list_set" class="rlist familyTypeopt">
		    </ul>
                  </div>
                </div>
              </div>
            </div>
            <!--end:family type--> 
            <!--start:Father's occupation-->
            <div class="mt7">
              <!--start:error div-->
              <div class="clearfix f14 fontlig">
                <div class="reg-wid3 fr colr5 visHid" id="fatherOccupation_error"></div>
              </div>
              <!--end:error div-->
              <div class="fullwid pos_rel clearfix">

                <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="fatherOccupation_box">
                  <label class="lblreg pos_abs js-fatherOccupation pos1_lbl2" id="fatherOccupation_label" data-attr="fatherOccupation">Father's Occupation</label>
                  <div class="js-tBox" id="fatherOccupation_value" data-type="gridDropdown" data-columns="3" data-validate=false data-required=false data-fieldtype="fatherOccupation" data-toSave="family_back">
                    <div id="fatherOccupation-inputBox_set"></div>
                    <div id="fatherOccupation-gridDropdown_set"></div>
                  </div>
                </div>
              </div>
            </div>
            <!--end:father's occupation--> 



            <!--start:Mother's occupation-->
            <div class="mt7">
              <!--start:error div-->
              <div class="clearfix f14 fontlig">
                <div class="reg-wid3 fr colr5 visHid" id="motherOccupation_error"></div>
              </div>
              <!--end:error div-->
              <div class="fullwid pos_rel clearfix">

                <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="motherOccupation_box">
                  <label class="lblreg pos_abs js-motherOccupation pos1_lbl2" id="motherOccupation_label" data-attr="motherOccupation">Mother's Occupation</label>
                  <div class="js-tBox" id="motherOccupation_value" data-type="gridDropdown" data-columns="3" data-validate=false data-required=false data-fieldtype="motherOccupation" data-toSave="mother_occ">
                    <div id="motherOccupation-inputBox_set"></div>
                    <div id="motherOccupation-gridDropdown_set"></div>
                  </div>
                </div>
              </div>
            </div>
            <!--end:mother's occupation--> 

            <!--start:brother-->
            <div class="mt7">
              <!--start:error div-->
              <div class="clearfix f14 fontlig">
                <div class="reg-wid3 fr colr5 visHid" id="brother_error"></div>
              </div>
              <!--end:error div-->
              <div class="fullwid pos_rel clearfix">
                <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="brother_box">
                  <label class="lblreg pos_abs js-brother js-list pos1_lbl2" id="brother_label" data-attr="brother">Brother</label>
                  <div class="js-tBox reg-mainlist showdd" id="brother_value" data-validate=false data-required=false data-fieldtype="brother" data-number="5" data-type="radio" data-sublist=true data-show-sections=true data-no-of-sections="2" data-toSave="t_brother">
                    <div id="brother-inputBox_set">
			</div>
                    <ul id="brother-list_set" class="rlist brotheropt">
                    </ul>
                  </div>
                  <!--start:other drop down-->
                  <div class="js-other sub-mainlist pos_abs reg-pos4 reg-zi1 regdropbox boxshadow">
                    <ul class="rlist brother-otheropt" id="brother-other-list_set">
                    </ul>
                  </div>
                  <!--end:other drop down-->
                </div>
              </div>
            </div>
            <!--end:brother-->
            <!--start:sister-->
            <div class="mt7">
              <!--start:error div-->
              <div class="clearfix f14 fontlig">
                <div class="reg-wid3 fr colr5 visHid" id="sister_error"></div>
              </div>
              <!--end:error div-->
              <div class="fullwid pos_rel clearfix">
                <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="sister_box">
                  <label class="lblreg pos_abs js-sister js-list pos1_lbl2" id="sister_label" data-attr="sister">Sister</label>
                  <div class="js-tBox reg-mainlist showdd" id="sister_value" data-validate=false data-required=false data-fieldtype="sister" data-number="5" data-type="radio" data-sublist=true data-show-sections=true data-no-of-sections="2" data-toSave="t_sister">
                    <div id="sister-inputBox_set">
			</div>
                    <ul id="sister-list_set" class="rlist sisteropt">
                    </ul>
                  </div>
                  <!--start:other drop down-->
                  <div class="js-other sub-mainlist pos_abs reg-pos4 reg-zi1 regdropbox boxshadow">
                    <ul class="rlist sister-otheropt" id="sister-other-list_set">
                    </ul>
                  </div>
                  <!--end:other drop down-->
                </div>
              </div>
            </div>
            <!--end:sister-->

            <!--start:familyState-->
            <div class="mt7">
              <!--start:error div-->
              <div class="clearfix f14 fontlig">
                <div class="reg-wid3 fr colr5 visHid" id="familyState_error"></div>
              </div>
              <!--end:error div-->
              <div class="fullwid pos_rel clearfix">
                <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="cityliv" tabindex="0" id="familyState_box">
                  <label class="lblreg pos_abs js-familyState js-list pos1_lbl2" id="familyState_label" data-attr="cityliv">Family living in</label>
                  <div class="js-tBox" id="familyState_value" data-type="gridDropdown" data-columns="3" data-validate=false data-required=false data-fieldtype="state" data-search="true" data-toSave="native_state">
                    <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="familyState-inputBox_set"/>
                    <div id="familyState-gridDropdown_set"></div>
                  </div>
                  <div class="pos_abs fontreg f11 color8 reg-pos9 txtu" style="cursor:pointer;" id="NfiLink">Not from India?</div>
                </div>
              </div>
            </div>
            <!--end:familyState-->
    <!--start:familyCity-->
    <div class="mt7 disp-none" id="familyCity_selector">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="familyCity_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white  outl-none toValidate" data-attr="cityliv" tabindex="0" id="familyCity_box">
          <label class="lblreg pos_abs js-familyCity js-list pos1_lbl2" id="familyCity_label" data-attr="cityliv">Native City</label>
                  <div class="js-tBox" id="familyCity_value" data-type="gridDropdown" data-columns="3" data-validate=false data-required=false data-fieldtype="familyCity" data-toSave="native_city" data-search="true">
			  <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="familyCity-inputBox_set"/>
                    <div id="familyCity-gridDropdown_set"></div>
                  </div>
        </div>
      </div>
    </div>
    <!--end:familyCity-->
    <!--start:city other-->
    <div class="mt7 disp-none" id="familyCityOther_selector">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="familyCityOther_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none" tabindex="0" id="familyCityOther_box">
          <label class="lblreg pos_abs js-familyCityOther js-list pos1_lbl2" id="familyCityOther_label" data-attr="familyCityOther">Specify City/Town</label>
          <input autocomplete="off" class="js-tBox reg_wid2 fr brdr-0 f15 fontlig toValidate" id="familyCityOther" data-type="text" type="familyCityOther" tabindex="-1" data-toSave ="ancestral_origin" data-required=false data-validate=false data-fieldtype="familyCityOther" maxlength=40/>
      </div>
    </div>
    </div>
    <!--end:Password-->
            <!--start:address-->
            <div class="mt7">
              <!--start:error div-->
              <div class="clearfix f14 fontlig">
                <div class="reg-wid3 fr colr5 visHid" id="address_error"></div>
              </div>
              <!--end:error div-->
              <div class="fullwid pos_rel clearfix">
                  <div class="reg-wid3 fr reg-divselc bg-white outl-none" tabindex="0" id="address_box">
                  <label class="lblreg pos_abs js-address js-list pos1_lbl2" id="address_label" data-attr="address">Contact address</label>
                  <input autocomplete="off" class="js-tBox reg_wid2 fr brdr-0 f15 fontlig toValidate" id="address_value" maxlength="500" data-type="text" type="address" maxlength="500" tabindex="-1" data-required=false data-validate=false data-fieldtype="address" data-toSave="contact" maxlength=500/>

                  </div>
              </div>
            </div>
            <!--end:address-->
            <!--start:about family-->
            <div class="mt7">
              <!--start:error div-->
              <div class="clearfix f14 fontlig">
                <div class="reg-wid3 fr colr5 visHid" id="aboutfamily_error"></div>
              </div>
              <!--end:error div-->
              <div class="fullwid pos_rel clearfix">
                  <div class="reg-wid3 fr bg-white outl-none aboutme-h" tabindex="0" id="aboutfamily_box">
                  <label class="lblreg pos_abs js-aboutfamily js-list pos1_lbl1" id="aboutfamily_label" data-attr="aboutfamily">About My Family</label>
                  <textarea autocomplete="off" class="js-tBox reg_wid3 aboutme-t fr brdr-0 f14 fontlig outl-none toValidate disp-none" style="color: #353e4f;" id="aboutfamily_value" data-type="text" type="text" data-validate=true tabindex="-1" data-fieldtype="aboutfamily" data-toSave="familyinfo" maxlength="1000"></textarea>
                </div>
              </div>
            </div>   
            <!--end:about family-->
            <!--start:registration button-->
            <div class="fullwid mt30">
              <div class="reg-marl1"> 
                <!--start:button-->
                <div class="reg-btn">
		  <div class="scrollhid pos_rel wid200 disp_ib">
                  <button class="padalla fontreg f20 colrw wid200 buttonSub pinkRipple hoverPink" id="regPage1Submit">Add to my profile</button>
                  </div>
&nbsp;&nbsp;&nbsp;
                <div class="disp_ib vtop pt20">  
		  <a href="/register/page5?fromReg=1&groupname=~$groupname`" id="skipPage4" class="colrb">I will add this later</a>
                </div>
                <!--end:button--> 
                </div>
              </div>
            </div>
            <!--end:registration button--> 
          </div>
          <!--end:form--> 
        </div>
        <!--end:form div--> 
<input type="hidden" name="_csrf_token" value="9be2bb7d7764889c2d0bf19d93d1a848" id="registrationData__csrf_token" />
