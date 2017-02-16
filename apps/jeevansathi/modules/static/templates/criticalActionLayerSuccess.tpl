
~if $layerId == '13'`<script>
    var primaryEmail = primaryEmail;
     function validateAlternateEmail(altEmail,primaryMail){        
    var email_regex = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
    var email = altEmail.trim();
    var invalidDomainArr = new Array("jeevansathi", "dontreg","mailinator","mailinator2","sogetthis","mailin8r","spamherelots","thisisnotmyrealemail","jsxyz","jndhnd");
    var start = email.indexOf('@');
    var end = email.lastIndexOf('.');
    var diff = end-start-1;
    var user = email.substr(0,start);
    var len = user.length;
    var domain = email.substr(start+1,diff).toLowerCase();
    var emailVerified ={};
    if(jQuery.inArray(domain.toLowerCase(),invalidDomainArr) !=  -1)
        return false;
    else if(domain == 'gmail')
    {
        if(!(len >= 6 && len <=30))
        {
            emailVerified.valid = false;
            emailVerified.errorMessage = "Please provide a valid Alternate Email Id";
            return emailVerified;
        }
    }
    else if(domain == 'yahoo' || domain == 'ymail' || domain == 'rocketmail' )
    {
        if(!(len >= 4 && len <=32))
        {   

            emailVerified.valid = false;
            emailVerified.errorMessage = "Please provide a valid Alternate Email Id";
            return emailVerified;
        }
    }
    else if(domain == 'rediff')
    {
        if(!(len >= 4 && len <=30))
        {
            emailVerified.valid = false;
            emailVerified.errorMessage = "Please provide a valid Alternate Email Id";
            return emailVerified;
        }
    }
    else if(domain == 'sify')
    {
        if(!(len >= 3 && len <=16))
        {
            emailVerified.valid = false;
            emailVerified.errorMessage = "Please provide a valid Alternate Email Id";
            return emailVerified;
        }
    }
    if(email=="")
    {
            emailVerified.valid = false;
            emailVerified.errorMessage = "Please provide a valid Alternate Email Id";
            return emailVerified;
    }

    if(!email_regex.test(email))
    {
            emailVerified.valid = false;
            emailVerified.errorMessage = "Please provide a valid Alternate Email Id";
            return emailVerified;
    }
    //return true;
    if(email == primaryMail)
    {
            emailVerified.valid = false;
            emailVerified.errorMessage = "Alternate and Primary Emails cannot be same";
            return emailVerified;
    }

            emailVerified.valid = true;
            emailVerified.errorMessage = "A link has been sent to your email id "+altEmail+" click on the link to verify your email.";
            return emailVerified;
     
    }

    function closeAltEmailCALLayer()
    {
        $("#criticalAction-layer").hide();
        $(".js-overlay").hide();
    }


</script>
<div id='criticalAction-layer' class="modal3 fontreg">
                <div class="fontlig" id="altEmailDiv">
                    <div class="f16 color11 fontreg bordrBtmGrey" style="padding: 22px 31px;">~$titleText`  <span id="skipBtnAltEmail" class="fr dispibl f15 fontlig" style="cursor: pointer;" onclick="closeAltEmailCALLayer()">Skip</span></div>
                    <div class="padWidget bordrBtmGrey">
                         <div class="txtc fontreg colrGrey f13" style="margin-bottom: -4%">~$contentTextNEW`</div>
                        <div style='margin-top:25px; margin-left: 2%;margin-right: 5%'>
                         <div class="wid500 txtl color5 f12 disp-none" style="position: absolute;top: 86px;" id="CALNameErr">Please provide a valid email address.</div>
                        <input type="text" id="altEmailInpCAL" class="f15 wid90p pa2 txtc" value='~$nameOfUser`' placeholder="Your alternate email" style="">
                        </div>
                        <div class="f11 colrGrey mt5 txtc">This field will be screened</div>
                        <div class="f15 pt20 colrGrey mt5 txtc wid80p" style="margin: 0px auto;">~$subtitle`</div>
                        <button id='CALButtonB3'  onclick="criticalLayerButtonsAction('~$action1`','B1');" class="lh63 f17 fontreg mt20 hlpcl1 cursp fullwid txtc hoverPink">~$button1TextNEW`</button>
                    </div>
            </div> 
        <div id="alternateEmailCnfLayer" class="phnvwid4 mauto layersZ pos_fix setshare disp-none fullwid bg-white modal3" style="padding-top: 13%;margin-top: 40px">
        <div class="bordrBtmGrey">
        <div class="phnvp4 f17 fontreg color11 phnvbdr4" style="margin-top: -38px;margin-left: 5%">Email Verification</div>
        </div>
        <div class="color11">
        <!--start:div-->
        <div class="phnvwid3 mauto pt40 pb27 fontlig">
        <p id='altEmailConfirmTextfixed' class="txtc lh26 f15">A link has been sent to your email Id</p>
        <p id='altEmailConfirmText' class="lh26 textc"></p>
        </div>
        <button id='CALButtonB3'  onclick="closeAltEmailCALLayer()" class="lh63 f17 fontreg mt20 hlpcl1 cursp fullwid txtc hoverPink" style="margin-left: 10%;margin-right: 10%;margin-bottom: 30px;width: 80%">OK</button>
        </div>
        <!--end:layer 1-->
        </div> 
</div>

~elseif $layerId != '9'`
<div id='criticalAction-layer' class="layerMidset setshare layersZ pos_fix calwid1 disp-none">
        <div class="calhgt1 calbg1 fullwid disp-tbl txtc">
            <div class="disp-cell vmid fontlig color11">
                <div class="wid470 mauto">
                    <p class="f28">~$titleText`</p>
                    <p class="f14 pt25 lh22">~$contentText`</p>
                </div>            
            </div>
        </div>
        <div class="clearfix">
            ~if $button1Text neq ''`<button id='CALButtonB1'  onclick="criticalLayerButtonsAction('~$action1`','B1');" class="cursp bg_pink f18 colrw txtc fontreg lh61 brdr-0 calwid2 fl">~$button1Text`</button>~/if`
            <button id='CALButtonB2'  id='closeButtonCALayer' onclick="criticalLayerButtonsAction('~$action2`','B2');" class="cursp ~if $button1Text eq ''`bg_pink calwid1~else` bg6 calwid2 ~/if` f18 colrw txtc fontreg lh61 brdr-0 fl">~$button2Text`</button>
        </div>
    </div>
~else`
    
<div id='criticalAction-layer' class="modal2 fontreg">
                <div class="fontlig" id="changeNameDiv">
                    <div class="f17 color11 fontreg bordrBtmGrey padWidget">Provide Your Name</div>
                    <div class="padWidget bordrBtmGrey">
                         <div class="txtc fontreg colrGrey f17">~$contentText`</div>
                        <div style='margin-top:25px;'>
                         <div class="wid500 txtl color5 f12 disp-none" style="position: absolute;top: 114px;" id="CALNameErr">Please provide  a valid name</div>
                        <input type="text" id="nameInpCAL" class="f15 wid90p pa2" value='~$nameOfUser`' placeholder="Your name here" style="">
                        </div>
                        <div class="f13 colrGrey mt5 txtc">This field will be screened</div>
                        <div class="radOption f15 color11 mt20">
                            <div class="disp_ib ml30">
                                <input type="radio" id='CALPrivacyShow' name="optionSelect" value="showAll" ~if $namePrivacy neq 'N'`checked=""~/if`><i></i> Show my name to all
                            </div>
                            <div class="disp_ib ml30">
                                <input type="radio" id='CALPrivacyShow2' name="optionSelect" value="dontShow" ~if $namePrivacy eq 'N'`checked=""~/if`><i></i> Don’t show my name
                            </div>
                        </div>
                        <div id='CALPrivacyInfo' class="~if $namePrivacy neq 'N'`disp-none~/if` f12 mt15 color11 txtc">You will also not be able to see names of other members.</div>
                        <button id='CALButtonB3'  onclick="criticalLayerButtonsAction('~$action1`','B1');" class="lh63 f17 fontreg mt20 hlpcl1 cursp fullwid txtc hoverPink">~$button1Text`</button>
                    </div>
                    <div class="padWidget f13 colrGrey txtc">We will NEVER show your name to other users without your explicit consent </div>
                </div>
            </div>    
                    <script type="text/javascript">
                                    $("#CALPrivacyShow").change(function(){if($(this).is(':checked'))$("#CALPrivacyInfo").hide();});
                                    $("#CALPrivacyShow2").change(function(){if($(this).is(':checked'))$("#CALPrivacyInfo").show();});
                        
                    </script>
                        
~/if`
<input type="hidden" id="CriticalActionlayerId" value="~$layerId`">
