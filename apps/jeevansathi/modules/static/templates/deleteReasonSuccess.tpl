<div id="mainContent">
  <div class="loader" id="pageloader"></div>
  <div> 
    <!--start:top-->
    <div class="bg1 txtc pad15 ">
      <div class="posrel lh30 newhgt1">
        <div class="fontthin f20 white"></div>
        <a href="/static/deleteOption"><i class="mainsp posabs set_arow1 set_pos1"></i></a> 
        <div class="posabs nset1"><a href="/static/passCheck?delete_option=~$deleteOption`" class="white opa70 f16">Skip</a></div>
        
       </div>
    </div>
    <!--end:top--> 
    <!--start:option-->
    <div class="bg4 f16 fontlig color13"> 
      
        <!--start:input field-->
        <div style="padding:20%">
          <textarea id="DeleteReasonID" name="DeleteReasonID" class="f20 fontthin color11 fullwid txtc" placeholder='~$deleteText`'></textarea>      
        </div>
        <!--end:input field-->
        <!--start:submit button-->
        <div id="foot" class="posfix fullwid bg7 btmo">
     <!-- <input type="submit" id="deleteButtonID" class="fullwid dispbl lh50 txtc f16 white" value="Next">-->
            <div id="deleteButtonID" class="fullwid dispbl lh50 txtc f16 white" style="bottom:0;">Next</div>    </div>

    </div>
        <!--end:submit button-->
      ~if ($deleteOption eq '1') || ($deleteOption eq '2') || ($deleteOption eq '3')`
    <div id="offerCheckBox" class="disp-none" style="padding: 25px 10% 0px 10%;">       
      <div class="fl">
        <li style="list-style: none;"><input type="checkbox" name="js-offerConsentCheckBox" checked="checked"></li>
      </div>
    <div class="fontlig pl20 f15 grey5  mt20 pr10" style="margin-left: 20px;">I authorize Jeevansathi to send Emails containing attractive offers related to the wedding</div>
    </div>
~/if`
    </div>
    <!--end:option--> 
   
  </div>
</div>
<script>
    var delete_option='~$deleteOption`';
    </script>