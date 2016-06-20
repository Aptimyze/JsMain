<div id="mainContent">
  <div class="loader" id="pageloader"></div>
  <div> 
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
<script>
    var delete_reason='~$deleteReason`';
    var delete_option='~$deleteOption`';
    var successFlow='~$successFlow`';
    </script>