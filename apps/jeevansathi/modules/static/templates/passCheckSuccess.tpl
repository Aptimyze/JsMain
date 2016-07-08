<div id="mainContent">
  <div class="loader" id="pageloader"></div>
  <div id="deleteProfilePasswordPage"> 
    <!--start:top-->
    <div id="overlayHead" class="bg1 txtc pad15">
      <div class="posrel lh30">
        <div class="fontthin f20 white">Your Password</div>
        ~if $deleteOption neq '1'`
        <a href="/static/deleteReason?delete_option=~$deleteOption`"><i class="mainsp posabs set_arow1 set_pos1"></i></a>
        ~else`
         <a href="/static/deleteOption"><i class="mainsp posabs set_arow1 set_pos1"></i></a>
         ~/if`
       </div>
    </div>
    <!--end:top--> 
    <!--start:option-->
    <div class="bg4 f16 fontlig color13"> 
   		
        <!--start:input field-->
        <div style="padding:20%">
        	<input id="passValueID" type="password" placeholder="Enter Password" class="f20 fontthin color11 fullwid txtc">        
        </div>
        <!--end:input field-->
        <!--start:submit button-->
        <div id="foot" class="posfix fullwid bg7 btmo">
			<input type="submit" id="passCheckID" class="fullwid dispbl lh50 txtc f16 white" value="Delete My Profile">
		</div>
        <!--end:submit button-->
      
    </div>
~if ($deleteOption eq '1') || ($deleteOption eq '2') || ($deleteOption eq '3')`
    <div id="offerCheckBox" class="disp-none" style="padding: 25px 10% 0px 10%;">       
      <div class="fl">
        <li style="list-style: none;"><input id='offerConsentCB' type="checkbox" name="js-offerConsentCheckBox" checked="checked"></li>
      </div>
    <div class="fontlig pl20 f15 grey5  mt20 pr10" style="margin-left: 20px;">I authorize Jeevansathi to send Emails containing attractive offers related to the wedding</div>
    </div>
~/if`    
    <!--end:option--> 
   
  </div>
</div>
<div id="deleteConfirmation-Layer" class ='dn' style="background-color: #09090b;">
  <div  class="posrel " style="padding:5% 0 8% 0;">

	<div class="br50p txtc" style='height:80px;'>
			
		</div>
		 
	</div>
		 
	<div class="txtc">	 
	<div class="fontlig white f18 pb10 color16">Delete Profile Permanently</div>
	<div class="pad1 lh25 fontlig f14" style='color:#cccccc;'>This will completely delete your profile information, contact history and active paid membership(s), if any. Are you sure about deleting your profile?</div>
  </div>
  <!--start:div-->
  <div style='padding: 25px 0 8% 0;'>
	<div id='deleteYesConfirmation' class="bg7 f18 white lh30 fullwid dispbl txtc lh50" onclick="deleteConfirmation('Y');">Yes, Delete Profile Permanently</div>
  </div>
  <!--end:div-->
  <div id='deleteNoConfirmation' onclick="deleteConfirmation('/static/deleteOption');" style='color:#cccccc; padding-top: 12%;' class="pdt15 pb10 txtc white f14" style="padding-top:15%;">Dismiss</div>
  </div>

<script>
    var delete_reason='~$deleteReason`';
    var delete_option='~$deleteOption`';
    var successFlow='~$successFlow`';
    </script>
