
<style type="text/css">
.dnd_bg{background-color:#2d2e2e}
.dnd_brdr1{border:2px solid #3b3b3b}
.dnd_pad1{padding:190px 32px}
.f14{font-size: 14px;}

</style>

<script type="text/javascript">
var user_login=true;
var google_plus=false;
var SITE_URL="~$SITE_URL`";


function redirectToMyjs() {
        window.location=SITE_URL+"/";
}
$(function(){
   var dnd_vwid = $( window ).width();
   var dnd_vhgt = $( window ).height();
   $('#ConsentID').css( "height", dnd_vhgt );
   var dnd_hgh1 = $('#dnd_header').height();
   var dnd_hgh2 = $('#footer_btn').height();
   var Tdnd_hght = dnd_vhgt - ( dnd_hgh1 + dnd_hgh2 + (2*36));
   var Tdnd_wid = dnd_vwid - (2* 32);
   $('.dnd_brdr1').css( "height", Tdnd_hght );
   
    
});

</script>

<div id="mainContent">
  
  
   
      <div class="fullwid bg4 fontlig dnd_bg" id="ConsentID"> 
        <!--start:div header-->
        <div class="bg1" id="dnd_header">
          <div class="pad1">
            <div class="rem_pad1 posrel fullwid ">
              <div class="white fontthin f19 txtc">Email Verification</div>             
            </div>
          </div>
        </div>
        <!--end:div header--> 
        
        <!--start:content box-->
        <div class="dnd_pad1" id="dnd_cnt">
          <div class="disptbl">
              <div class="pad16 txtc white f16 fontlig dispcell vertmid">
                  <div>
Your email has been verified.</div>
                </div>
            </div>
        
        </div>
        <!--end:content box-->
       
        
        <!--start:Next-->
        <div class="btmo posabs fullwid" id="footer_btn" onclick="redirectToMyjs();">
          <div id="search_submit" class="bg7 f16 white lh30 fullwid dispbl txtc lh50" >Ok</div>
        </div>
        <!--end:Next--> 
        
      </div>
    </div>