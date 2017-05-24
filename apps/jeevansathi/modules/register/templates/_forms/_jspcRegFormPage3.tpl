~if $pageObj->isIncomplete`
<a href="#" id="clickAtLast"></a>
~/if`      
<!--start:text-->
      <div class="fontlig f22 txtc colr1 pt30">Great! You are about to complete your Jeevansathi profile.</div>
      <!--end:text--> 
      <!--start:div.1-->
      <div class="clearfix pt30"> 
        <!--start:form div-->
        <div class="fl wid80p">
          <div class="txtr fontlig f12 required">mandatory</div>
          <!--start:form-->
          <div class="fr clearfix pt5 fontlig f15 fullwid formreg" ~if $pageObj->isIncomplete`style="visibility: hidden"~/if`> 
            <!--start:Highest degree-->
            <div>
              <!--start:error div-->
              <div class="clearfix f14 fontlig">
                <div class="reg-wid3 fr colr5 visHid" id="hdegree_error"></div>
              </div>
              <!--end:error div-->
              <div class="fullwid pos_rel clearfix">
                  <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="hdegree_box">
                  <label class="lblreg pos_abs js-hdegree js-list pos1_lbl1 required" id="hdegree_label" data-attr="hdegree">Highest Degree</label>
                  <!--start:drop down-->
                  <div class="js-tBox" id="hdegree_value" data-type="gridDropdown" data-alpha="4" data-validate=true data-required=true data-fieldtype="hdegree" data-search="true" data-toSave="edu_level_new">
                    <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="hdegree-inputBox_set"/>
                    <div id="hdegree-gridDropdown_set"></div>
                  </div>
                  <!--end:drop down--> 
                </div>
              </div>
            </div>
            <!--end:Highest degree--> 
            <div class="js-moreUgDegree disp-none cursp pt5 f11 fontlig txtr" id="addMoreUgDegree">Add another UG Degree</div>
            <div class="js-morePgDegree disp-none cursp txtr pt5 f11 fontlig" id="addMorePgDegree">Add another PG Degree</div>
             <!--start:PG degree-->
      <div class="mt7 disp-none" id="degree_pg">
        <!--start:error div-->
        <div class="clearfix f14 fontlig">
          <div class="reg-wid3 fr colr5  visHid" id="pgDegree_error"></div>
        </div>
        <!--end:error div-->
        <div class="fullwid pos_rel clearfix">
          <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="pgDegree" tabindex="0" id="pgDegree_box">
            <label class="lblreg pos_abs js-pgDegree js-list pos1_lbl1" id="pgDegree_label" data-attr="pgDegree">PG Degree (optional)</label>
            <div class="js-tBox" id="pgDegree_value" data-type="gridDropdown" data-columns="4"  data-required=true data-fieldtype="pgDegree" data-toSave ="degree_pg" data-validate=true data-search="true">
                <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="pgDegree-inputBox_set" tabindex="-1"/>
              <div id="pgDegree-gridDropdown_set"></div>
            </div>
          </div>
        </div>
      </div>
      <!--end:PG degree--> 
      <div class="js-morePgDegree disp-none cursp txtr f11 fontlig pt5" id="addPg">Add another PG Degree</div>
      <!--start:PG college-->
      <div id="pg_college" class="disp-none">
      <div class="mt7" id="pgCollege_selector">
        <!--start:error div-->
        <div class="clearfix f14 fontlig">
          <div class="reg-wid3 fr colr5  visHid" id="pgCollege_error"></div>
        </div>
        <!--end:error div-->
        <div class="fullwid pos_rel clearfix">
          <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="pgCollege" tabindex="0" id="pgCollege_box">
            <label class="lblreg pos_abs js-pgCollege js-list pos1_lbl2" id="pgCollege_label" data-attr="pgCollege">PG College (optional)&nbsp;&nbsp;&nbsp;</label>
            <div class="js-tBox reg_wid2 fr brdr-0 f15 fontlig" id="pgCollege_value" data-type="autoSuggest"  data-required=false data-validate=true data-toSave ="pg_college" data-fieldtype="pgCollege" data-search="true" data-hidden-Drop="true"/>
            <input autocomplete="off" maxlength="100" class="reg_wid2 fr brdr-0 f15 fontlig" id="pgCollege-inputBox_set" tabindex="-1"/>
            <div class="disp-none" id="pgCollege-gridDropdown_set"></div>
          </div>
        </div>
      </div>
        </div>
    </div>
    <!--end:PG college--> 
    <!--start:UG degree-->
      <div class="mt7 disp-none" id="degree_ug">
        <!--start:error div-->
        <div class="clearfix f14 fontlig">
          <div class="reg-wid3 fr colr5  visHid" id="ugDegree_error"></div>
        </div>
        <!--end:error div-->
        <div class="fullwid pos_rel clearfix">
          <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="ugDegree" tabindex="0" id="ugDegree_box">
            <label class="lblreg pos_abs js-ugDegree js-list pos1_lbl1" id="ugDegree_label" data-attr="ugDegree">UG Degree (optional)</label>
            <div class="js-tBox" id="ugDegree_value" data-type="gridDropdown" data-columns="4"  data-required=true data-fieldtype="ugDegree" data-toSave ="degree_ug" data-validate=true data-search="true">
                <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="ugDegree-inputBox_set" tabindex="-1"/>
              <div id="ugDegree-gridDropdown_set"></div>
            </div>
          </div>
        </div>
      </div>
      <!--end:UG degree--> 
      <div class="js-moreUgDegree disp-none txtr f11 fontlig pt5 cursp" id="addUg">Add another UG Degree</div>
      <!--start:UG college-->
      <div id="college" class="disp-none">
      <div class="mt7" id="ugCollege_selector">
        <!--start:error div-->
        <div class="clearfix f14 fontlig">
          <div class="reg-wid3 fr colr5  visHid" id="ugCollege_error"></div>
        </div>
        <!--end:error div-->
        <div class="fullwid pos_rel clearfix">
          <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" data-attr="ugCollege" tabindex="0" id="ugCollege_box">
            <label class="lblreg pos_abs js-ugCollege js-list pos1_lbl2" id="ugCollege_label" data-attr="ugCollege">UG College (optional)&nbsp;&nbsp;&nbsp;</label>
            <div class="js-tBox reg_wid2 fr brdr-0 f15 fontlig" id="ugCollege_value" data-type="autoSuggest"  data-required=false data-validate=true data-toSave ="college" data-fieldtype="ugCollege" data-search="true" data-hidden-Drop="true"/>
            <input maxlength="100" autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="ugCollege-inputBox_set" tabindex="-1"/>
            <div class="disp-none" id="ugCollege-gridDropdown_set"></div>
          </div>
        </div>
      </div>
    </div>
    </div>
    <!--end:UG college--> 
    <!--start:Other PG degree-->
    <div class="mt7 disp-none" id="otherPgDegreeInput">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="otherPgDegree_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="otherPgDegree_box">
          <label class="lblreg pos_abs js-otherPgDegree js-list pos1_lbl1" id="otherPgDegree_label" data-attr="otherPgDegree">Other PG Degree (optional)&nbsp;&nbsp;&nbsp;</label>
          <input autocomplete="off" maxlength="40" class="js-tBox reg_wid2 fr brdr-0 f15 fontlig " id="otherPgDegree_value" data-type="text" type="text" data-toSave ="other_pg_degree" data-required=false data-characters-only="1" data-validate=true tabindex="-1" data-fieldtype="otherPgDegree"/>
        </div>
      </div>
    </div>
    <!--end:Other Pg Degree-->
    <!--start:Other Ug degree-->
    <div class="mt7 disp-none" id="otherUgDegreeInput">
      <!--start:error div-->
      <div class="clearfix f14 fontlig">
        <div class="reg-wid3 fr colr5  visHid" id="otherUgDegree_error"></div>
      </div>
      <!--end:error div-->
      <div class="fullwid pos_rel clearfix">
        <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="otherUgDegree_box">
          <label class="lblreg pos_abs js-otherUgDegree js-list pos1_lbl1" id="otherUgDegree_label" data-attr="otherUgDegree">Other UG Degree (optional)&nbsp;&nbsp;&nbsp;</label>
          <input autocomplete="off" maxlength="40" class="js-tBox reg_wid2 fr brdr-0 f15 fontlig " id="otherUgDegree_value" data-type="text" type="text" data-toSave ="other_ug_degree" data-required=false data-characters-only="1" data-validate=true tabindex="-1" data-fieldtype="otherUgDegree"/>
        </div>
      </div>
    </div>
    <!--end:Other Ug Degree-->
            <!--start:Occupation-->
             <div class="mt7">
              <!--start:error div-->
              <div class="clearfix f14 fontlig">
                <div class="reg-wid3 fr colr5 visHid" id="occupation_error"></div>
              </div>
              <!--end:error div-->
              <div class="fullwid pos_rel clearfix">
                  <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id=occupation_box> 
                  <label class="lblreg pos_abs js-occupation js-list pos1_lbl1 required" data-attr="occup" id="occupation_label">Occupation</label>                 
                  <div class="js-tBox" id="occupation_value" data-type="gridDropdown"  data-columns="3" data-validate=true data-required=true data-fieldtype="occupation" data-search="true" data-toSave="occupation">
                    <input autocomplete="off" class="reg_wid2 fr brdr-0 f15 fontlig" id="occupation-inputBox_set"/>
                    <div id="occupation-gridDropdown_set"></div>
                  </div>
                </div>
              </div>
            </div>            
            <!--end:Occupation-->
            <!--start:Occupation-->
             <div class="mt7">
              <!--start:error div-->
              <div class="clearfix f14 fontlig">
                <div class="reg-wid3 fr colr5 visHid" id="income_error"></div>
              </div>
              <!--end:error div-->
              <div class="fullwid pos_rel clearfix">
                  <div class="reg-wid3 fr reg-divselc bg-white outl-none toValidate" tabindex="0" id="income_box">
                  <label class="lblreg pos_abs js-income js-list pos1_lbl1 required" id="income_label" data-attr="income">Annual Income</label>              
                  <div class="js-tBox" id="income_value" data-type="gridDropdown" data-columns="1" data-validate=true data-required=true data-fieldtype="income" data-toSave="income">
                    <div id="income-inputBox_set"></div>
                    <div id="income-gridDropdown_set"></div>
                  </div>
                </div>
              </div>
            </div>   
           </div>
        </div>
      </div>
            <!--end:Occupation-->
    <div class="container reg_wid1 divp3brdr formreg" ~if $pageObj->isIncomplete`style="visibility: hidden"~/if`> 
      <!--start:text-->
      <div class="fontlig f22 txtc colr1 pt30">Here is your chance to make your profile stand out!</div>
      <!--end:text--> 
      <!--start:div.1-->
      <div class="clearfix pt30"> 
        <!--start:form div-->
        <div class="fl wid80p">
          <!--start:form-->
          <div class="fr clearfix pt5 fontlig f15 fullwid formreg"> 
            <!--start:Highest degree-->
            <div>
              <!--start:error div-->
              <div class="clearfix f14 fontlig">
                <div class="reg-wid3 fr colr5 visHid" id="aboutme_error"></div>
              </div>
              <!--end:error div-->
              <div class="fullwid pos_rel clearfix">
                  <div class="reg-wid3 fr bg-white outl-none aboutme-h" tabindex="0" id="aboutme_box">
                  <label class="lblreg pos_abs js-aboutme js-list pos1_lbl1 required" id="aboutme_label" data-attr="aboutme">Express Yourself!</label>
                  <textarea autocomplete="off" class="js-tBox reg_wid3 aboutme-t fr brdr-0 f14 fontlig outl-none toValidate disp-none" style="color: #353e4f;" id="aboutme_value" data-type="text" type="text" data-validate=true data-required=true tabindex="-1" data-fieldtype="aboutme" data-toSave="yourinfo" maxlength="3000"></textarea>
                  <span class="f14 fr fontlig pos_abs disp-none jsdd-aboutme" id="cCount" style="color: #353e4f;top: 110px;right: 3px;">Minimum Character: 100</span>
                </div>
              </div>
            </div>   
            <div class="fullwid clearfix disp-none jsdd-aboutme" id="aboutmeHelp">
               <div class="fullwid bg-white reg-wid3 fr">
                        <div class="padall-10 fontreg f14 colr4 lh20">
                          <div>Introduce yourself (Don't mention your name). Write about your values, beliefs/goals and aspirations.                    How do you describe yourself? Your interests and hobbies.</div>
                          <div><br>This text will be screened by our team.</div>
                        </div>
                      </div>
              </div>
            
            
            <!--start:registration button-->
            <div class="fullwid mt30">
              <div class="reg-marl1"> 
                
                <!--start:button-->
                <div class="reg-btn">
                  <div class="scrollhid pos_rel wid230">
                    <button class="padalla fontreg f20 colrw wid230 buttonSub hoverPink pinkRipple" id="regPage3Submit">Complete Registration</button>
                  </div>
                </div>
                <!--end:button--> 
		</div>
		</div>                
              </div>
            </div>
            <!--end:registration button--> 
          </div>
          <!--end:form--> 
        </div>
        <!--end:form div--> 
<input type="hidden" name="_csrf_token" value="9be2bb7d7764889c2d0bf19d93d1a848" id="registrationData__csrf_token" />
