<!--start:hide/ delete profile-->
      <div class="HideDelProfile">
        <div class="clearfix"> 
          <!--start:left-->
          <div id="hidePartID" class="fl setwid9">
            <div class="bg-white setp3 color11" style="height:429px;">
		~if $UNHIDE eq 0`
              <p id="headingID" class="f16 fontreg txtc">Hide your Profile</p>
		
              <p id="hideParaID" class="f15 txtc fontlig pt20 color2">Use this feature when you have decided to stop looking temporarily since you are busy, moving, in the middle of some big lifestyle changes and cannot spare the time to look seriously.</p>
              <p id="hideTextID" class="pt35 f15 txtc fontreg color2">Hide my Profile for</p>
		~else`
		 <p id="headingID" class="f16 fontreg txtc">Show your Profile</p>
		<p id="showParaID" class="f15 txtc fontlig pt20 color2">You have chosen to hide your profile till ~$UNHIDE_DATE`, after which it will be visible to other users again. Use this feature to unhide your profile now.</p>
    <p id="hideTextID" class="pt35 f15 txtc fontreg color2 disp-none">Hide my Profile for</p>
		~/if`
              <div class="clearfix setwid10 mauto pt20">
		~if $UNHIDE eq 0`
                <div id="hideDaysID" class="">
                  <button id="sevenDayHide" class="setactive cursp">7 Days</button>
                  <button id="tenDayHide" class="setbtn1 setp4 cursp">10 Days</button>
                  <button id="thirtyDayHide" class="setbtn1 cursp">30 Days</button>
                </div>
		~/if`
    <div id="hideDaysID" class="disp-none">
                  <button id="sevenDayHide" class="setactive">7 Days</button>
                  <button id="tenDayHide" class="setbtn1 setp4">10 Days</button>
                  <button id="thirtyDayHide" class="setbtn1">30 Days</button>
                </div>
                <div  class="pt10">
                  <p id="passID1" class="color5 f12 txtc sethgt1 fontlig vishid">Incorrect password</p>
                  <div id="passBorderID1" class="setbdr1">
                    <input id="HidePassID" type="password" class="color12 fullwid brdr-0 outwhi lh40 pl13 wid90p f15 fontlig hgt30IE" placeholder="Your Password">
                  </div>
                </div>
              </div>
            </div>
		~if $UNHIDE eq 0`
            <div id="HideID" class="bg_pink lh51 colrw txtc cursp"><div class="colrw f15 fontlig">Hide my Profile</div></div>
		~else`
		 <div id="HideID" class="bg_pink lh51 colrw txtc cursp"><div class="colrw f15 fontlig">Show my Profile</div></div>
         
          ~/if`
           </div>
          <!--end:left--> 
          <!--start:right-->
          <div id="deletePartID" class="fr setwid9">
            <div class="bg-white setp3 color11" style="height:429px;overflow: auto;">
              <p class="f16 fontreg txtc">Delete your Profile</p>
              <p class="f15 txtc fontlig pt20 color2">Please use this feature when you are engaged or have found your life partner. This feature deletes your profile 
                permanently from the site. We would appreciate your feedback on Jeevansathi.com.</p>
              <p class="pt35 f15 txtc fontreg color2">Reason to Delete Profile</p>
              <div class="setwid10 mauto pt20"> 

                <!--start:field 1-->
                <p id="deleteReasonPrompt" class="color5 f12 txtc sethgt1 fontlig disp-none">Please select a reason</p>
                <div id="deleteReasonBox" class="setbdr1">
                  <div  class="setp5 pos-rel">
                    <div id="delOptionID" class="color12 f15 fontlig pos-rel"> <span id="delOptionSetID">Please select a reason for deleting profile</span>
                      <div class="pos-abs vicons setdrop1 setpos3"></div>
                    </div>
                    <!--start:drop down-->
                    <div id="deleteOptionListID" class="pos-abs setwid10 setpod5 z3 list disp-none">
                      <div class="pos-rel fullwid"> 
                        <div class="setbdr1 bg-white">
                          <ul class="listnone color12 f15 fontlig delprof">
                            <li class='sltOption sltOption1'>I found my match on Jeevansathi.com</li>
                            <li class='sltOption sltOption2'>I found my match from other website</li>
                            <li class='sltOption sltOption3'>I found my match elsewhere</li>
                            <li class='sltOption sltOption4'>I am unhappy about services</li>
                            <li class='sltOption sltOption5'>Other reasons</li>
                          </ul>
                        </div>
                      </div>
                    </div>

                    <!--end:drop down--> 
                  </div>
                </div>
                <!--end:field 1--> 
                <!--start:field 2-->
                <div id="specifiedID" class="setbdr1 mt30 disp-none">
    <input id="specifyReasonID" type="text" class="reasonDivCommon specifyReason color12 fullwid brdr-0 outwhi lh40 pl13 hgt30IE wid90p f15 fontlig disp-none" placeholder="Kindly specify the source"></input>
		<textarea id="specifyOtherReasonID" type="text" class="reasonDivCommon specifyReason color12 fullwid brdr-0 outwhi setp7 wid90p f15 brnone fontlig disp-none" placeholder="Kindly specify reason for your dissatisfaction"></textarea>
    <textarea id="specifyOtherReason2ID" type="text" class="reasonDivCommon specifyReason color12 fullwid brdr-0 outwhi setp7 wid90p f15 brnone fontlig disp-none" placeholder="Kindly specify your reason"></textarea>
    <input id="specifyLinkID" class="reasonDivCommon color12 fullwid brdr-0 outwhi lh40 pl13 wid90p f15 hgt30IE fontlig disp-none" placeholder="Please write name of website" type="text">
                </div>
                <!--end:field 2--> 
                <!--start:field 3-->
                


                <div class="pt10">
                  <p id="passID" class="color5 f12 txtc sethgt1 fontlig vishid">Incorrect password</p>
                  <div id="passBorderID" class="setbdr1">
                    <input id="DeletePassID" type="password" class="color12 fullwid brdr-0 outwhi lh40 pl13 wid90p f15 fontlig hgt30IE" placeholder="Your Password">
                  </div>
                </div>
                
                <!--end:field 3--> 
          ~if $showOTP eq 'Y'`
   <a  id ="otpProfileDeletion"  class="setwid10 mauto pt20 pos-rel disp_b txtc color5 cursp">Delete Using OTP</a>
          ~/if`

              </div>
    <div id="offerCheckBox" class="disp-none" style="margin-left: 36px;">              
    <div class="fl">

    <li>    <input type="checkbox"  name="js-offerConsentCheckBox" checked="checked"></li>
    </div>
    <div class="fontlig pl20 f13 grey5  mt20 pr10">I authorize Jeevansathi to send Emails containing attractive offers related to the wedding</div>
    </div>          
            </div>
            <div id="DeleteID" class="bg_pink lh51 colrw txtc cursp">
            <div class="pos-rel scrollhid"><div id="DeleteTextID" class="pinkRipple hoverPink colrw f15 fontlig">Submit</div></div></div>
          </div>
          <!--end:right--> 
        </div>
      </div>
      <div id='deleteConfirmation-layer' class="layerMidset setshare layersZ pos_fix calwid1 disp-none">
        <div class="calhgt1 calbg1 fullwid disp-tbl txtc">
            <div class="disp-cell vmid fontlig color11">
                <div class="wid470 mauto">
                    <p class="f28">Delete Profile Permanently</p>
                    <p class="f14 pt25 lh22">This will completely delete your profile information, contact history and active paid membership(s), if any. Are you sure about deleting your profile?</p>
                </div>            
            </div>
        </div>
        <div class="clearfix">
            <button id='deleteYesConfirmation'  onclick="deleteConfirmation('Y');" class="cursp bg_pink f18 colrw txtc fontreg lh61 brdr-0 calwid2 fl">Yes, Delete Profile Permanently</button>
            <button id='deleteYesConfirmation' onclick="deleteConfirmation('N');" class="cursp bg6 f18 colrw txtc fontreg lh61 brdr-0 calwid2 fl">Dismiss</button>
        </div>
    </div>
<script type="text/javascript">
var hideUnhide = '~$UNHIDE`';
</script>
