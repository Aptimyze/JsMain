<script>
    var namePrivacy=~if $namePrivacy neq 'N'`'Y'~else`'N'~/if`;
    var suggestions =~if $calObject.LAYERID eq '16'`~$dppSuggestions|decodevar`~else`''~/if`;
    var primaryEmail = '~$primaryEmail`';

    function validateAndSend()
    {
            var altEmailUser = ($("#altEmailInpCAL").val()).trim();
            var validation=validateAlternateEmail(altEmailUser,primaryEmail);
            if(validation.valid!==true)
            {  
                showError(validation.errorMessage);
                CALButtonClicked=0;
                return;
            }

                else
                 {
                 $.ajax({
                    url: '/api/v1/profile/editsubmit?editFieldArr[ALT_EMAIL]='+altEmailUser,
                    type: 'POST',
                    success: function(response) {
                      if(response.responseStatusCode == 1)
                      {
                      showError("Something went wrong");
                      CALButtonClicked=0;
                      return;   
                      }
                 $("#altEmailCAL").hide();
                 msg = "A link has been sent to your email Id "+altEmailUser+', click on the link to verify your email';
                 $("#altEmailMsg").text(msg);
                 $("#confirmationSentAltEmail").show();
                   return; 
                    }
                });              

                }

    }
</script>

~if $calObject.LAYERID eq '13'`
<script>
  

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
    if(email.toLowerCase() == primaryMail.toLowerCase())
    {
            emailVerified.valid = false;
            emailVerified.errorMessage = "Alternate and Primary Emails cannot be same";
            return emailVerified;
    }

            emailVerified.valid = true;
            emailVerified.errorMessage = "A link has been sent to your email id "+altEmail+" click on the link to verify your email.";
            return emailVerified;
     
    }
</script>

~/if`

<input type="hidden" id="CriticalActionlayerId" value="~$calObject.LAYERID`">

~if $calObject.LAYERID eq '13'`
  
        <div class="txtc pad12 white fullwid f13 posabs dispnone" id="validation_error"  style="top: 0px;background-color: rgba(102, 102, 102, 0.5);z-index:104;">Please provide a valid email address.</div>

      <div class="darkBackgrnd" id="altEmailCAL">
  <div class="fontlig">
      <div style="padding: 100px 20px 0px 20px;" class="app_clrw f20 txtc">~$calObject.TEXTNEW`</div> 
    <!--    <div class="pad_new2 app_clrw f14 txtc ">~$calObject.TEXT`</div> -->
    <input id='altEmailInpCAL' value='~$nameOfUser`' type="text" class="bg4 lh60 fontthin mt30 f24 fullwid txtc" placeholder="Your alternate email">
        <div class="pt10 f15 fontlig fullwid txtc colr8A">~$calObject.TEXTUNDERINPUT`</div>
         <div class="pad_new app_clrw f14 txtc">~$calObject.SUBTITLE`</div>

        <div id="CALButtonB2" onclick="criticalLayerButtonsAction('~$calObject.ACTION2`','B2');"  class="f14 fontlig txtc app_clrw colr8A" style="padding-top: 115px">~$calObject.BUTTON2NEW`</div>
        
        <div onclick="validateAndSend();" type="submit" id="submitAltEmail" class="fullwid dispbl lh50 txtc f18 btmo posfix bg7 white">~$calObject.BUTTON1NEW`</div>
    </div>
  
</div>


      <div id="confirmationSentAltEmail" class="darkBackgrnd dispnone">
  <div class="fontlig">
      <div class="pad_new app_clrw f20 txtc" style="padding-top: 170px">Email Verification</div> 
    <!--    <div class="pad_new2 app_clrw f14 txtc ">~$calObject.TEXT`</div> -->
         <div class="pad_new app_clrw f14 txtc" id="altEmailMsg" style="padding-left: 20px;padding-right: 20px"></div>    
         <div id="CALButtonB3" style="padding-top: 200px" onclick="criticalLayerButtonsAction('~$calObject.ACTION1NEW`','B1');"  class="pad_new app_clrw f16 txtc">OK</div>    
    </div>
  
</div>


~elseif $calObject.LAYERID eq '16'`

        <div id="overlayHead" class="bg1">
            <div class="txtc pad15">
                <div class="posrel">
                    <div class="fontthin f19 white">Desired Partner Profile</div>
                    <i id="closeFromDesiredPartnerProfile" class=" posabs mainsp srch_id_cross " style="right:0; top:0px;" onclick="criticalLayerButtonsAction('','B2');"></i>
                </div>
            </div>

        </div>
 
        <div id="overlayMid" class="bg4 pad3 ">
            <div id="mainHeading" class="color8 fontreg f18 txtc pb10">Relax Your Criteria</div>
            <div id="dppDescription" class="txtc color8 fontlig f17"></div>
            <div id="dppSuggestions" class="mb30"></div>
        </div>


        <div id="foot" class="posfix fullwid bg7 btmo">
            <div class="scrollhid posrel">
                <input type="submit" id="upgradeSuggestion" class="fullwid dispbl lh50 txtc f16 pinkRipple white" value="Upgrade Desired Partner Profile">
            </div>
        </div>
    
    
    
  
  ~elseif $calObject.LAYERID !=9`
      <div style="background-color: #09090b;">
  <div  class="posrel pad18Incomplete">

	<div class="br50p txtc" style='height:80px;'>
			~if $showPhoto eq '1'`
			~if $gender eq 'M'` 	
				<img id="profilepic" class="image_incomplete" src="~StaticPhotoUrls::noPhotoMaleJSMS`"> 
				~else`<img id="profilepic" class="image_incomplete" src="~StaticPhotoUrls::noPhotoFemaleJSMS`"> 
				~/if`
			~/if`
		</div>
		 
	</div>
	 
	<div class="txtc">	 
	<div class="fontlig white f18 pb10 color16">~$calObject.TITLE`</div>
	<div class="pad1 lh25 fontlig f14" style='color:#cccccc;'>~$calObject.TEXT`</div>
  </div>
  <!--start:div-->
  ~if $calObject.ACTION1 neq ''`
  <div style='padding: 25px 0 8% 0;'>
	<div id='CALButtonB1' class="bg7 f18 white lh30 fullwid dispbl txtc lh50" onclick="criticalLayerButtonsAction('~$calObject.ACTION1`','B1');">~$calObject.BUTTON1`</div>
  </div>
  <!--end:div-->
  <div id='CALButtonB2' onclick="criticalLayerButtonsAction('~$calObject.ACTION2`','B2');" style='color:#cccccc; padding-top: 12%;' class="pdt15 pb10 txtc white f14">~$calObject.BUTTON2`</div>
  ~else`
  <div style='padding: 25px 0 8% 0;'>
	<div id='CALButtonB2' class="bg7 f18 white lh30 fullwid dispbl txtc lh50" onclick="criticalLayerButtonsAction('~$calObject.ACTION2`','B2');">~$calObject.BUTTON2`</div>
  </div>
  
  ~/if`
  
  </div>
  
  ~else`
      <div class="txtc pad12 white fullwid f13 posabs dispnone" id="validation_error"  style="top: 0px;background-color: rgba(102, 102, 102, 0.5);z-index:104;">Please provide a valid name.</div>

      <div class="darkBackgrnd">
	<div class="fontlig">
    	<div class="pad_new app_clrw f20 txtc">Provide Your Name</div>
        <div class="pad_new2 app_clrw f14 txtc ">~$calObject.TEXT`</div>
		<input id='nameInpCAL' value='~$nameOfUser`' type="text" class="bg4 lh60 fontthin mt30 f24 fullwid txtc" placeholder="Your name here">
        <div class="pt10 f15 fontlig fullwid txtc colr8A">This field will be screened</div>
        <div class="mt30 pad_new2 hgt90">
            <div id='CALPrivacy1' onclick="switchColors('#CALPrivacy1','#CALPrivacy2');$('#hideShowText').hide();namePrivacy='Y';" type="submit" class="dispibl f14 txtc fontlig wid49p brdrRad2 ~if $namePrivacy neq 'N'`bg7~else`bgBtnGrey~/if` lh40 app_clrw">Show my name to all</div>
            <div id='CALPrivacy2' onclick="switchColors('#CALPrivacy2','#CALPrivacy1');$('#hideShowText').show();namePrivacy='N';" type="submit" class="dispibl f14 txtc fontlig wid49p brdrRad2 ~if $namePrivacy neq 'N'`bgBtnGrey~else`bg7~/if` lh40 app_clrw mlNeg2">Don't show my name</div>
            <div id="hideShowText" ~if $namePrivacy neq 'N'`style="display:none"~/if` class="pt10 f15 fontlig fullwid txtc colr8A">You will not be able to see names of other members.</div>
        </div>
        
        <div id="skipBtn" onclick="criticalLayerButtonsAction('~$calObject.ACTION2`','B2');"  class="f14 fontlig txtc app_clrw pt35p">~$calObject.BUTTON2`</div>
        
        <div onclick="criticalLayerButtonsAction('~$calObject.ACTION1`','B1');" type="submit" id="submitName" class="fullwid dispbl lh50 txtc f18 btmo posfix bg7 white">~$calObject.BUTTON1`</div>
    </div>
	
</div>
      
      ~/if`

    