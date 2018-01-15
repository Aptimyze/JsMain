<style type="text/css">
.f60{font-size:60px}
</style>
<div class="perspective" id="perspective">
<div class="" id="pcontainer">
<div class="bg7 sreen404">
	<div class="pad19">
    ~if $hideHamb neq '1'`
    	<div class="hamicon1"><i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i> </div>
        ~/if`
       <div class="disptbl">
        	<div class="dispcell vertmid">
            	<div class="posrel">
            		<img src="~sfConfig::get('app_img_url')`/images/jsms/404/js-error-img-1.png">                    
                </div>
            </div>
         </div>
    
    
    </div>	
</div>
</div>
<div id="hamburger" class="hamburgerCommon dn fullwid">
	~include_component('static', 'newMobileSiteHamburger')`	
</div>
</div>
<script>
    var h = $(window).innerHeight();
    $("#pcontainer").addClass("bg7");
    if(h<514)
    $(".sreen404").css("height","514px");
    else
    {
        $(".sreen404").height(h);
        $(".sreen404").css('overflow','none');
    }
  
    </script>