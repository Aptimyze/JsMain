<link rel="stylesheet" type="text/css"  href="~$IMG_URL`/min/?f=/profile/css/show_app_promo_css.css"> 
<div id="floatMenu" style="display:none;z-index:100;">
	<div class="appdwn_bg1 appdwn_pos1 appdwn_wid1">
    	<a href="javascript:close_help();"><div class="appdwn_bg1 appdwn_close"></div></a>
	<a href="~$SITE_URL`/common/appPromotionDesktop" target="_blank" style="outline:0;">
        <div class="appdwn_clr"></div>
        <div class="appdwn_bg1 appdwn_icon"></div>
        <div class="appdwn_c1 appdwn_f24 appdwn_clr1">
        	Download<br/>Android App of<br/>Jeevansathi for <br/>FREE        
        </div>
        </a>
        </div>
           
    </div>

</div>
<script>
function close_help()
{
	var d = new Date();
	d.setTime(d.getTime()+(60*60*1000));
	var expires = "expires="+d.toGMTString();
	$('#floatMenu').animate({
            height: 'toggle'
            }, 290, function() {
        });
        document.cookie="show_app_prom=1;"+expires+"; path=/";
        
}
var height_help=0;
	
show_help('floatMenu');
~if $is_ie`
jquery_user(document).ready(function(){
			
			
			jquery_user(window).scroll(function () { 
				offset = jquery_user(document).scrollTop()+height_help+"px";
				jquery_user("#floatMenu").animate({top:offset},{duration:500,queue:false});
			});
		});
~/if`
function show_help(id_of_help)
{
	var de = top.document.documentElement;
        var idofhelp=dID(id_of_help);
        setTimeout(function(){
   $('#floatMenu').animate({
            height: 'toggle'
            }, 500, function() {
        });
}, 2000);
        
        idofhelp.style.position='fixed';
		idofhelp.style.bottom="0px";
        idofhelp.style.right="10px";
        
}
</script>
