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
    <!--end:option--> 
   
  </div>
</div>
<script>
    var delete_reason='~$deleteReason`';
    var delete_option='~$deleteOption`';
    var successFlow='~$successFlow`';
    </script>