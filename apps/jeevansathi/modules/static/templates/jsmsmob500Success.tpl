<style type="text/css">
.f60{font-size:60px}
</style>
<div class="perspective ~if $hideHamb eq '1'`bg7~/if`" id="perspective">
<div class="" id="pcontainer">
<div class="sreen404 bg7">
	<div class="pad19">
    	~if $hideHamb neq '1'`<div class="hamicon1"><i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i> </div> ~/if`
       <div class="disptbl">
        	<div class="dispcell vertmid">
            	<div class="posrel">
            		<img src="~sfConfig::get('app_img_url')`/images/jsms/500/js-500-img.png">                    
                </div>
            </div>
         </div>
    <div>
         
         </div>
    
        </div>
    <div style="border-top:1px solid #df6576" id='retryBtn' class="fullwid txtc">
    	<a class="fullwid  white f16 " style="display:block;padding:15px 0" onclick="javascript:document.location.reload();return false">Retry</a>
    
    </div>
</div>
</div>
<div id="hamburger" class="hamburgerCommon dn fullwid">
	~include_component('static', 'newMobileSiteHamburger')`	
</div>
</div>
<script>
	if(~$hideHamb`){
		var AndroidPromotion=0;
        $("#retryBtn").addClass("bg7");
    }
    var h = $(window).innerHeight();
    $("#pcontainer").addClass("bg7");
    if(h<514)
    $(".sreen404").css("height","514px");
    else
    {
        $(".sreen404").height(h);
        $("#retryBtn").addClass('btmo').addClass('posfix ');
    }

$(document).ready(function() {
    if(typeof trackJsEventGA != 'undefined')
    trackJsEventGA('500-error','JSMS','-');
});
   </script>
